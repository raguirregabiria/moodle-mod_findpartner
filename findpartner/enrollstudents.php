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
$studenttoenroll  = optional_param('studenttoenroll', 0, PARAM_INT);

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

$PAGE->set_url('/mod/findpartner/enrollstudents.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);


echo $OUTPUT->header();

if ($studenttoenroll > 0) {
    $ins = (object)array('studentgroup' => null, 'studentid' => $studenttoenroll,
        'findpartnerid' => $moduleinstance->id);
    $DB->insert_record('findpartner_student', $ins, $returnid = true. $bulk = false);
}
$enrolments = $DB->get_records('enrol', ['courseid' => $course->id]);

echo '<table><tr><td>'. get_string('userid', 'mod_findpartner').'</td><td>'.
        get_string('firstname', 'mod_findpartner').'</td><td>'.
            get_string('lastname', 'mod_findpartner').'</td><td>'.
                get_string('email', 'mod_findpartner').'</td></tr>';

$studentsid = array();
foreach ($enrolments as $enrolment) {
    $usersid = $DB->get_records('user_enrolments', ['enrolid' => $enrolment->id]);
    foreach ($usersid as $userid) {
        array_push($studentsid, $userid->userid);        
    }
}
$studentsout = array();
foreach ($studentsid as $studentid) {
    $query = $DB->get_record('findpartner_student', ['findpartnerid' => $moduleinstance->id, 'studentid' => $studentid]);
    if ($query == null) {
        //array_push($studentsout, $studentid);
        $studentinfo = $DB->get_record('user', ['id' => $studentid]);
        echo "<tr><td>" . "$studentinfo->username" .
        "</td><td>" . "$studentinfo->firstname" . "</td><td>" .
            "$studentinfo->lastname" . "</td><td>" .
                "$studentinfo->email" . "</td><td>". 
                $OUTPUT->single_button(new moodle_url('/mod/findpartner/enrollstudents.php',
                array('id' => $cm->id, 'studenttoenroll' => $studentid)),
                    get_string('enrol', 'mod_findpartner')) . "</td></tr>";
    }
}



// $studentsin = $DB->get_records('findpartner_student', ['findpartner' => $moduleinstance->id]);



echo "</table>";
echo "id actividad: $moduleinstance->id";
echo $OUTPUT->footer();

