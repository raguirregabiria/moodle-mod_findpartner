<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Prints an instance of mod_findpartner.
 *
 * @package     mod_findpartner
 * @copyright   2020 Rodrigo Aguirregabiria Herrero, Manuel Alfredo Collado Centeno, GIETA UPM
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

// Event types.
define('FINDPARTNER_EVENT_TYPE_DUE', 'due');

require_once("$CFG->dirroot/group/lib.php");
require_once("$CFG->dirroot/mod/findpartner/lib.php");


// Pops up an alert message.
function alertmessage($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}

function nummembers($groupid) {
    global $DB;
    return $DB->count_records('findpartner_student', array('studentgroup' => $groupid));
}

function maxmembers($findpartnerid) {
    global $DB;
    $activity = $DB->get_record('findpartner', array('id' => $findpartnerid));
    return $activity->maxmembers;
}

function denyrequests($findpartnerid, $studentid) {
    global $DB;
    $ins = $DB->get_records('findpartner_request', array('student' => $studentid,
    'status' => 'P'));
    foreach ($ins as $row) {
        $group = $DB->get_record('findpartner_projectgroup', array('id' => $row->groupid));
        if ($group->findpartner == $findpartnerid) {
            $row->status = "D";
            $DB->update_record('findpartner_request', $row);
        }
    }
}

function isadmin($groupid, $studentid) {
    global $DB;
    $admin = $DB->get_record('findpartner_projectgroup', array('id' => $groupid));
    if ($studentid == $admin->groupadmin) {
        return true;
    } else {
        return false;
    }
}

// This function match all the students with no group.
function matchall($findpartner) {
    global $DB;
    $groups = $DB->get_records('findpartner_projectgroup', array('findpartner' => $findpartner->id));
}
// Returns True if the contract will be done, False if not.
// It only counts the students that have voted.
function contractapproved($groupid) {
    global $DB;
    $yes = $DB->count_records('findpartner_votes', array('groupid' => $groupid, 'vote' => 'Y'));
    $no = $DB->count_records('findpartner_votes', array('groupid' => $groupid, 'vote' => 'N'));
    return $yes >= $no;
}

// Returns True if the block will be done, False if not.
function workblockapproved($workblockid) {
    global $DB;
    $yes = $DB->count_records('findpartner_workblockvotes', array('workblockid' => $workblockid, 'vote' => 'A'));
    $no = $DB->count_records('findpartner_workblockvotes', array('workblockid' => $workblockid, 'vote' => 'D'));
    return $yes >= $no;
}

// Returns True if the block will be done, False if not.
function workblockverified($workblockid) {
    global $DB;
    $yes = $DB->count_records('findpartner_donevotes', array('workblockid' => $workblockid, 'vote' => 'A'));
    $no = $DB->count_records('findpartner_donevotes', array('workblockid' => $workblockid, 'vote' => 'D'));
    return $yes >= $no;
}

// Update projectgroup table.
function updatestatus($group) {
    global $DB;
    if (contractapproved($group->id)) {
        $group->contractstatus = 'Y';
    } else {
        $group->contractstatus = 'N';
    }
    $DB->update_record('findpartner_projectgroup', $group);
}

function matchgroups($notmaxmembers, $notminmembers, $nogroups, $findpartnerid) {
    $findpartner = $DB->get_record('findpartner', ['id' => $findpartnerid]);
    $autogroupnumber = 1;
    // If there are students without groups.
    while (count($nogroups) > 0) {
        // If there are groups without enough members.
        if (count($notminmembers) > 0) {
            $group = array_pop($notminmembers);
            $student = array_pop($nogroups);
            $student->studentgroup = $group->id;
            $DB->update_record('findpartner_student', $student);
            $nummembers = nummembers($group->id);
            // If has enough members.
            if ($nummembers == $findpartner->minmembers) {
                // If can be more students in the group.
                if($nummembers < $findpartner->maxmembers) {
                    array_push($notmaxmembers, $group);
                }
                // Else do nothing, group is full.
            } else {
                // Group doesn't have enough members yet.
                array_push($notminmembers, $group);
            }
        } else if (count($nogroups) >= $findpartner->minmembers) {
            // Create a group that has enough members.
            $newgroupid = 0;
            for ($i = 0; $i < $findpartner->minmembers; $i++) {
                $student = array_pop($nogroups);
                // The first student will be the admin.
                if ($i == 0) {
                    $ins = (object)array('findpartner' => $finpartnerid,
                        'description' => get_string('autogenerated', 'mod_findpartner'),
                            'name' => get_string('autogenerated', 'mod_findpartner') . " " . $autogroupnumber
                                ,'groupadmin' => $student->studentid);
                    $autogroupnumber++;
                    $newgroupid = $DB->insert_record('findpartner_projectgroup', $ins, $returnid = true. $bulk = false);
                }                
                $student->studentgroup = $newgroupid;
                $DB->update_record('findpartner_student', $student);
                
            }
            if (nummembers($newgroupid) < $findpartner->maxmembers) {
                // If the group doesn't have maxmembers.
                $group = new stdclass;
                $group->id = $newgroupid;
                array_push($notmaxmembers, $group);
            }
        }  else if (count($notmaxmembers) > 0){
            $group = array_pop($notmaxmembers);
            $student = array_pop($nogroups);
            $student->studentgroup = $group->id;
            $DB->update_record('findpartner_student', $student);
            $nummembers = nummembers($group->id);
            // If there is still space.
            if ($nummembers < $findpartner->mazxmembers) {
                array_push($notmaxmembers, $group);
            }
        } else {
            // There are still students without a group, but all of them can't create a group with enough members.
            // All the other groups are full.
            // if ((minmembers - nogroups) < nogroups) nogroups es un grupo
            //else nogroups se distribuye en varios grupos 
        }
        

    }
}