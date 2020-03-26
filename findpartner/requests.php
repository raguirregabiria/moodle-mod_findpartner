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

// Id of the request that is accepted or denied.
$requestid  = optional_param('requestid', 0, PARAM_INT);

// Identifier of the button type (accept or deny). Accept = 1|Deny = 0.
$buttonvalue  = optional_param('buttonvalue', 0, PARAM_INT);


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

$PAGE->set_url('/mod/findpartner/requests.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);


echo $OUTPUT->header();

$project = $DB->get_record('findpartner_projectgroup', array('groupadmin' => $USER->id, 'findpartner' => $moduleinstance->id));

// This is used to know if somebody has pressed a button of accept or deny.

if ($requestid > 0) {
    $updaterecord = $DB->get_record('findpartner_request', array('id' => $requestid));
    if ($buttonvalue == 1) {
        $updaterecord->status = 'A';

        // If a student is accepted, the groupid has to be updated in the student table.

        $ins = $DB->get_record('findpartner_student', array('studentid' => $updaterecord->student,
            'findpartnerid' => $moduleinstance->id));
        $ins->studentgroup = $updaterecord->groupid;
        $DB->update_record('findpartner_student', $ins);

        // If a student is accepted in one group, all his request mush be denied.

        $ins = $DB->get_records('findpartner_request', array('student' => $updaterecord->student,
            'status' => 'P'));
        foreach ($ins as $row) {
            $group = $DB->get_record('findpartner_projectgroup', array('id' => $row->groupid));
            if ($group->findpartner == $moduleinstance->id) {
                $row->status = "D";
                $DB->update_record('findpartner_request', $row);

            }
        }
    } else {
        $updaterecord->status = 'D';
    }
    $DB->update_record('findpartner_request', $updaterecord);

    $morerequestsleft = $DB->get_records('findpartner_request', array('groupid' => $project->id, 'status' => 'P'));

    if ($morerequestsleft == null) {

        // If the are no more requests, you go to view.php.

        redirect(new moodle_url('/mod/findpartner/view.php',
            array('id' => $cm->id)));
    } else {

        // If there are more, you stay in the request page.
        // But it has to be refreshed so the accepted or denied request doesn't appear.

        redirect(new moodle_url('/mod/findpartner/requests.php',
            array('id' => $cm->id, 'requestid' => -1)));
    }
}

// Show the requests.

$requests = $DB->get_records('findpartner_request', array('groupid' => $project->id, 'status' => 'P'));

if ($requests != null) {
    echo '<table>';
    echo "<tr><td>". get_string('requestmessage', 'mod_findpartner') .
    "</td><td>". get_string('accept', 'mod_findpartner') .
    "</td><td>". get_string('deny', 'mod_findpartner') ."</td></tr>";
    foreach ($requests as $request) {

        echo "<tr><td>" . $request->message . "</td>";
        echo "<td>" . $OUTPUT->single_button(new moodle_url('/mod/findpartner/requests.php',
            array('id' => $cm->id, 'requestid' => $request->id, 'buttonvalue' => 1)),
                get_string('accept', 'mod_findpartner')) . "</td>";

        echo "<td>" . $OUTPUT->single_button(new moodle_url('/mod/findpartner/requests.php',
            array('id' => $cm->id, 'requestid' => $request->id, 'buttonvalue' => 0)),
                get_string('deny', 'mod_findpartner')) . "</td>";

        echo '</tr>';

    }
}
echo '</table>';

echo $OUTPUT->footer();