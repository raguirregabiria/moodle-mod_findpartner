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

// Header of the page.



echo $OUTPUT->header();
if (has_capability('mod/findpartner:update', $modulecontext)) {

    // Teacher view.

    echo "<center>Alguna chorrada con palomas $USER->id</center>";

} else {

    // Student view.

    echo "<center>Este es el id del usuario: $USER->id<br>Este es el id de la actividad: $moduleinstance->id</center>";

    $record = $DB->get_record('findpartner_student', ['studentid' => $USER->id, 'findpartnerid' => $moduleinstance->id]);
    if ($record == null) {
        $ins = (object)array('id' => $USER->id, 'studentgroup' => null, 'studentid' => $USER->id,
            'findpartnerid' => $moduleinstance->id);
        $DB->insert_record('findpartner_student', $ins, $returnid = true. $bulk = false);
    }

    echo '<table><tr><td>'. get_string('group_name', 'mod_findpartner').'</td><td>'.
        get_string('description', 'mod_findpartner').'</td><td>'.
        get_string('members', 'mod_findpartner').'</td></tr>';

    $newrecords = $DB->get_records('findpartner_projectgroup', ['findpartner' => $moduleinstance->id]);
    $student = $DB->get_record('findpartner_student', array('studentid' => $USER->id, 'findpartnerid' => $moduleinstance->id));
    foreach ($newrecords as $newrecord) {
        $maxmembers = $DB->get_record('findpartner', ['id' => $newrecord->findpartner]);
        $nummembers = $DB->count_records('findpartner_student', array('studentgroup' => $newrecord->id));

        // Group name.

        echo "<tr><td>".$newrecord->name.": </td>";

        // Group description.

        echo "<td>$newrecord->description</td><td>";

        // Group number of members.

        echo $nummembers . "/" . $maxmembers->maxmembers . "</td>";

        // This makes the button of send request if the student has no group and the group is not full.
        if (($nummembers < $maxmembers->maxmembers) && $student->studentgroup == null) {
            echo "<td>" . $OUTPUT->single_button(new moodle_url('/mod/findpartner/makerequest.php',
                array('id' => $cm->id, 'groupid' => $newrecord->id)), get_string('send_request', 'mod_findpartner')) . "</td>";
        }
        echo "</tr>";
    }

    echo '</table>';

    $admin = $DB->get_record('findpartner_projectgroup', array('groupadmin' => $USER->id, 'findpartner' => $moduleinstance->id));

    // If a student is admin of a group.

    if ($admin != null) {

        echo $OUTPUT->single_button(new moodle_url('/mod/findpartner/requests.php',
            array('id' => $cm->id)), get_string('viewrequest', 'mod_findpartner'));
    }

    // If a student has no group, can create one.

    $newrecords = $DB->get_record('findpartner_student', array('studentid' => $USER->id, 'findpartnerid' => $moduleinstance->id));

    if ($newrecords->studentgroup == null) {
        echo $OUTPUT->single_button(new moodle_url('/mod/findpartner/creategroup.php',
            array('id' => $cm->id)), get_string('creategroup', 'mod_findpartner'));
    }
}
echo $OUTPUT->footer();
