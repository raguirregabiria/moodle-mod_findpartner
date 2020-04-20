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
require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');
require_once('group_form.php');
require_once('locallib.php');
require_once('group_form_request.php');

// Course_module ID, or.
$id = optional_param('id', 0, PARAM_INT);

// ... module instance id.
$f  = optional_param('f', 0, PARAM_INT);

// Value equals to 1 if student joins the activity, 0 if he/seh refuses.
$join = optional_param('join', 0, PARAM_INT);

// Value equals to 1 if the student wants to exit the activity.
$exitactivity = optional_param('exitactivity', 0, PARAM_INT);

// Value equals to 1 if the student wants to exit the group.
$exitgroup = optional_param('exitgroup', 0, PARAM_INT);

// Value equals to 1 if the student wants to make contract, 2 if not.
$contract = optional_param('contract', 0, PARAM_INT);

if ($id) {
    $cm             = get_coursemodule_from_id('findpartner', $id, 0, false, MUST_EXIST);
    $course         = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleinstance = $DB->get_record('findpartner', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($f) {
    $moduleinstance = $DB->get_record('findpartner', array('id' => $n), '*', MUST_EXIST);
    $course         = $DB->get_record('course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
    $cm             = get_coursemodule_from_instance('findpartner', $moduleinstance->id, $course->id, false, MUST_EXIST);
} else {
    print_error(get_string('missingidandcmid', 'mod_findpartner'));
}

require_login($course, true, $cm);


$modulecontext = context_module::instance($cm->id);

global $USER;
global $DB;

$PAGE->set_url('/mod/findpartner/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);



echo $OUTPUT->header();
if (has_capability('mod/findpartner:update', $modulecontext)) {

    // Teacher view.

    echo $OUTPUT->single_button(new moodle_url('/mod/findpartner/enrolstudents.php',
            array('id' => $cm->id, 'studenttoenrol' => 0)),
                    get_string('enrolstudents', 'mod_findpartner'));

    echo $OUTPUT->single_button(new moodle_url('/mod/findpartner/deenrolstudents.php',
        array('id' => $cm->id, 'studenttoenrol' => 0)),
            get_string('deenrolstudents', 'mod_findpartner'));

    echo "<center>Alguna chorrada con palomas $USER->id</center>";
    $time = getdate(time());

    echo '<table><tr><td>'. get_string('group_name', 'mod_findpartner').'</td><td>'.
        get_string('description', 'mod_findpartner').'</td><td>'.
            get_string('members', 'mod_findpartner').'</td></tr>';
    $newrecords = $DB->get_records('findpartner_projectgroup', ['findpartner' => $moduleinstance->id]);
    $student = $DB->get_record('findpartner_student',
        array('studentid' => $USER->id, 'findpartnerid' => $moduleinstance->id));
    foreach ($newrecords as $newrecord) {
        $maxmembers = $DB->get_record('findpartner', ['id' => $newrecord->findpartner]);
        $nummembers = $DB->count_records('findpartner_student', array('studentgroup' => $newrecord->id));

        // Group name.

        echo "<tr><td>".$newrecord->name.": </td>";

        // Group description.

        echo "<td>$newrecord->description</td><td>";

        // Group number of members.

        echo $nummembers . "/" . $maxmembers->maxmembers . "</td>";

        // Button to view the group.

        echo "<td>" . $OUTPUT->single_button(new moodle_url('/mod/findpartner/viewgroup.php',
            array('id' => $cm->id, 'groupid' => $newrecord->id)),
                    get_string('viewgroup', 'mod_findpartner')) . "</td>";
        echo "</tr>";
    }

    echo '</table>';

} else {

    // Student view.

    echo "<center>Este es el id del usuario: $USER->id<br>Este es el id de la actividad: $moduleinstance->id</center>";
    // If the student decided to exit this activity.
    $time = $DB->get_record('findpartner', ['id' => $moduleinstance->id]);

    // If the date of closure groups has not come, the students can create and join groups.

    if (time() < $time->dateclosuregroups) {
        if ($exitactivity == 1) {
            $DB->delete_records('findpartner_student', array('studentid' => $USER->id, 'findpartnerid' => $moduleinstance->id));
            denyrequests($moduleinstance->id, $USER->id);
        }

        $record = $DB->get_record('findpartner_student', ['studentid' => $USER->id, 'findpartnerid' => $moduleinstance->id]);

        // If the student isn't in the activity.
        if ($record == null && $join == 0) {
            echo "<center>" . get_string('joinmessage', 'mod_findpartner') . "</center>";
            // A button ask the student if them want to join it.
            echo "<center>" . $OUTPUT->single_button(new moodle_url('/mod/findpartner/view.php',
                array('id' => $cm->id, 'join' => 1)),
                get_string('accept', 'mod_findpartner')) . "</center>";
        } else {
            // If the student wants to join gets in the database.
            if ($record == null && $join == 1) {
                $ins = (object)array('studentgroup' => null, 'studentid' => $USER->id,
                    'findpartnerid' => $moduleinstance->id);
                $DB->insert_record('findpartner_student', $ins, $returnid = true. $bulk = false);
            }
            // If the student wants to exit a group.
            if ($exitgroup == 1) {
                if (isadmin($record->studentgroup, $USER->id)) {
                    // We need to check again if the student is alone in the group.
                    // This could happend in some scerarios.
                    $nummembers = $DB->count_records('findpartner_student', array('studentgroup' => $record->studentgroup));
                    if ($nummembers == 1) {
                        // Delete group.
                        $DB->delete_records('findpartner_projectgroup', array('id' => $record->studentgroup));
                        // Update the group of the student.
                        $record->studentgroup = null;
                        $DB->update_record('findpartner_student', $record);
                    }
                } else {
                    $record->studentgroup = null;
                    $DB->update_record('findpartner_student', $record);
                }
            }

            // This prints the table with the groups.

            echo '<table><tr><td>'. get_string('group_name', 'mod_findpartner').'</td><td>'.
                get_string('description', 'mod_findpartner').'</td><td>'.
                    get_string('members', 'mod_findpartner').'</td></tr>';

            $newrecords = $DB->get_records('findpartner_projectgroup', ['findpartner' => $moduleinstance->id]);
            $student = $DB->get_record('findpartner_student',
                array('studentid' => $USER->id, 'findpartnerid' => $moduleinstance->id));
            foreach ($newrecords as $newrecord) {
                $maxmembers = $DB->get_record('findpartner', ['id' => $newrecord->findpartner]);
                $nummembers = $DB->count_records('findpartner_student', array('studentgroup' => $newrecord->id));

                // Group name.

                echo "<tr><td>".$newrecord->name.": </td>";

                // Group description.

                echo "<td>$newrecord->description</td><td>";

                // Group number of members.

                echo $nummembers . "/" . $maxmembers->maxmembers . "</td>";

                // If the student has no group.

                if ($student->studentgroup == null) {
                    // If there is enough space in the group.
                    if ($nummembers < $maxmembers->maxmembers) {

                        // If the student hasn't got more request pending for this group.

                        $requestmade = $DB->count_records('findpartner_request', array('student' => $student->studentid,
                            'groupid' => $newrecord->id, 'status' => 'P'));
                        if ($requestmade == 0) {
                            // The student can make a request to the group.
                            echo "<td>" . $OUTPUT->single_button(new moodle_url('/mod/findpartner/makerequest.php',
                                array('id' => $cm->id, 'groupid' => $newrecord->id)),
                                    get_string('send_request', 'mod_findpartner')) . "</td>";
                        } else {
                            echo "<td><center>Already sent</center></td>";
                        }
                    }
                    // If this is the group of the student.
                } else if ($newrecord->id == $student->studentgroup) {
                    // If the student is the admin.
                    if ($USER->id == $newrecord->groupadmin) {
                        // If the admin is the only member.
                        if ($nummembers == 1) {
                            // Then can leave.
                            echo "<td>" . $OUTPUT->single_button(new moodle_url('/mod/findpartner/view.php',
                                array('id' => $cm->id, 'exitgroup' => 1)),
                                    get_string('exitgroup', 'mod_findpartner')) . "</td>";
                        }
                    } else {
                        // If its not the admin, can leave.
                        echo "<td>" . $OUTPUT->single_button(new moodle_url('/mod/findpartner/view.php',
                                array('id' => $cm->id, 'exitgroup' => 1)),
                                    get_string('exitgroup', 'mod_findpartner')) . "</td>";
                    }
                }
                echo "</tr>";
            }

            echo '</table>';

            $admin = $DB->get_record('findpartner_projectgroup',
                array('groupadmin' => $USER->id, 'findpartner' => $moduleinstance->id));

            // If a student is admin of a group.

            if ($admin != null) {

                // If there are pending.
                $request = $DB->get_record('findpartner_request', array('groupid' => $admin->id, 'status' => 'P'));
                if ($request != null) {
                    // If the group is not full.
                    if (maxmembers($moduleinstance->id) > nummembers($admin->id)) {
                        // Show the button of request.
                        echo $OUTPUT->single_button(new moodle_url('/mod/findpartner/requests.php',
                            array('id' => $cm->id, 'requestid' => -1)), get_string('viewrequest', 'mod_findpartner'));
                    }
                }
            }

            // If a student has no group, can create one.

            $newrecords = $DB->get_record('findpartner_student',
                array('studentid' => $USER->id, 'findpartnerid' => $moduleinstance->id));

            if ($newrecords->studentgroup == null) {
                echo $OUTPUT->single_button(new moodle_url('/mod/findpartner/creategroup.php',
                    array('id' => $cm->id)), get_string('creategroup', 'mod_findpartner'));

                echo $OUTPUT->single_button(new moodle_url('/mod/findpartner/view.php',
                    array('id' => $cm->id, 'exitactivity' => 1)), get_string('exitactivity', 'mod_findpartner'));
            }
        }
        // The groups are close.
    } else {

        $student = $DB->get_record('findpartner_student', array('studentid' => $USER->id, 'findpartnerid' => $moduleinstance->id));
        $group = $DB->get_record('findpartner_projectgroup', array('id' => $student->studentgroup));
        // If the student has vote to make the contract.
        if ($contract == 1) {
            $ins = (object)array('groupid' => $group->id, 'studentid' => $USER->id, 'vote' => 'Y');
            $DB->insert_record('findpartner_votes', $ins, $returnid = true. $bulk = false);
        } else if ($contract == 2) {
            $ins = (object)array('groupid' => $group->id, 'studentid' => $USER->id, 'vote' => 'N');
            $DB->insert_record('findpartner_votes', $ins, $returnid = true. $bulk = false);
        }
        if ($group->contractstatus == 'P') {
            // The student have 24 hours (86000 seconds) to decide if they want to make contracts.
            if (time() < ($time->dateclosuregroups + 86400)) {
                // TODO Put button that says what is a contract.
                // TODO if everyone has voted, change conractstatus.
                echo "<center>" . get_string('alertcontract', 'mod_findpartner') . "</center>";

                $vote = $DB->get_record('findpartner_votes', array('studentid' => $USER->id, 'groupid' => $group->id));

                // If the student hasn't voted.

                if ($vote == null) {
                    echo "<center>" . $OUTPUT->single_button(new moodle_url('/mod/findpartner/view.php',
                        array('id' => $cm->id, 'contract' => 1)), get_string('contractyes', 'mod_findpartner'));
                    echo $OUTPUT->single_button(new moodle_url('/mod/findpartner/view.php',
                        array('id' => $cm->id, 'contract' => 2)), get_string('contractno', 'mod_findpartner')) . "<center>";
                }
            } else {
                if (contractapproved($group->id)) {
                    $group->contractstatus = 'Y';
                } else {
                    $group->contractstatus = 'N';
                }
                $DB->update_record('findpartner_projectgroup', $group);
                redirect(new moodle_url('/mod/findpartner/view.php',
                    array('id' => $cm->id)));
            }
        }
    }
}
echo $OUTPUT->footer();
