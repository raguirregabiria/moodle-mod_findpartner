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
require_once('locallib.php');




// Course_module ID, or.
$id = optional_param('id', 0, PARAM_INT);

// ... module instance id.
$f  = optional_param('f', 0, PARAM_INT);

// Groupid.
$groupid = optional_param('groupid', 0, PARAM_INT);


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

$PAGE->set_url('/mod/findpartner/viewcontracts.php', array('id' => $cm->id, 'groupid' => $groupid));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);


echo $OUTPUT->header();

echo "<style>table,td{border: 1px solid black;}td{padding: 10px;}</style>";

// Show workblocks.
echo '<table><tr><td>'. get_string('workblock', 'mod_findpartner').'</td><td>'.
get_string('memberstable', 'mod_findpartner').'</td><td>' .
    get_string('workblockstatus', 'mod_findpartner').'</td><td>' .
            get_string('complains', 'mod_findpartner') . '</td></tr>';

$workblocks = $DB->get_records('findpartner_workblock', ['groupid' => $groupid]);
foreach ($workblocks as $workblock) {
    echo '<tr><td>' . $workblock->task . '</td><td>';
    $studentsname = $DB->get_records('findpartner_incharge', ['workblockid' => $workblock->id]);
    foreach ($studentsname as $studentname) {
        $studentinfo = $DB->get_record('user', ['id' => $studentname->studentid]);
        echo $studentinfo->firstname . ' ' . $studentinfo->lastname .'<br>';
    }
    echo "</td>";

    if ($workblock->status == 'A') {
        echo "<td>" . get_string('accepted', 'mod_findpartner') ."</td>";
    } else if ($workblock->status == 'D') {
        echo "<td>" . get_string('dennied', 'mod_findpartner') ."</td>";
    } else if ($workblock->status == 'P') {
        echo "<td>" . get_string('pending', 'mod_findpartner') ."</td>";
    } else if ($workblock->status == 'V') {
        echo "<td>" . get_string('verified', 'mod_findpartner') ."</td>";
    } else if ($workblock->status == 'C') {
        echo "<td>" . get_string('complete', 'mod_findpartner') ."</td>";
    } else if ($workblock->status == 'E') {
        echo "<td>" . get_string('edited', 'mod_findpartner') ."</td>";
    }

    $complains = $DB->get_records('findpartner_complain', ['workblockid' => $workblock->id]);
    if ($complains != null) {
        echo '<td>';
        foreach ($complains as $complain) {
            echo $complain->complain . '<br>';
        }
        echo '</td>';
    } else {
        echo '<td></td>';
    }



}
echo "</table>";

echo $OUTPUT->footer();