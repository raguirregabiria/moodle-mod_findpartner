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

/*if (has_capability('mod/findpartner:update', $modulecontext)) {
    echo "<h1>Vista profesor</h1>";
    
}else{
    echo "<h1>Vista alumno</h1>";
}

$event = \mod_findpartner\event\course_module_viewed::create(array(
    'objectid' => $moduleinstance->id,
    'context' => $modulecontext
));
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('findpartner', $moduleinstance);
$event->trigger();*/

global $USER;
global $DB;

$PAGE->set_url('/mod/findpartner/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);

echo $OUTPUT->header();
if (has_capability('mod/findpartner:update', $modulecontext)) {
    echo "<center>Alguna chorrada con palomas $USER->id</center>";
    
}else{
    echo "<center>Este es el id del usuario: $USER->id<br>Este es el id de la actividad: $moduleinstance->id</center>";

    $record = $DB->get_record('findpartner_student', ['studentid' => $USER->id,'findpartnerid'=>$moduleinstance->id]);
    if ($record==null){
        $ins = (object)array('id'=>$USER->id,'studentgroup'=>null,'studentid'=>$USER->id,'findpartnerid'=>$moduleinstance->id);
        $DB->insert_record('findpartner_student', $ins, $returnid=true. $bulk=false);
    }

    echo '<table>';
    $newrecords = $DB->get_records('findpartner_projectgroup', ['findpartner'=>$moduleinstance->id]);
    foreach ($newrecords as $newrecord){
        echo "<tr><td>$newrecord->name: </td><td>$newrecord->description</td></tr>";
    }
    echo '</table>';




    

    
        $groupselect = $DB->get_record( 'groupselect', array (
                'id' => 1
        ), '*', MUST_EXIST );
        $cm = get_coursemodule_from_instance( 'groupselect', $groupselect->id, $groupselect->course, false, MUST_EXIST );
    
    require_once('select_form.php');
    $grpname = format_string( $groups[$select]->name, true, array (
        'context' => $context
) );
$usercount = isset( $counts[$select] ) ? $counts[$select]->usercount : 0;

$data = array (
        'id' => $id,
        'select' => $select,
        'group_password' => $password
);
$mform = new select_form( null, array (
        $data,
        $groupselect,
        $grpname
) );
$mform->display();
    //$mform->new group_form(1,2);
    /*
    if ($mform->is_cancelled()) {
        //Handle form cancel operation, if cancel button is present on form
    } else if ($fromform = $mform->get_data()) {
      //In this case you process validated data. $mform->get_data() returns data posted in form.
    } else {
      // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
      // or on the first display of the form.
     
      //Set default data (if any)
    $mform->set_data($toform)
    $mform->display()
        */




}
echo $OUTPUT->footer();
