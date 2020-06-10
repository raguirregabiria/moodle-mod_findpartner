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

// 1 to complete and let stundents move, 2 to complete and close groups. 
$completegroups = optional_param('completegroups', 0, PARAM_INT);

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

$PAGE->set_url('/mod/findpartner/completegroups.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);


echo $OUTPUT->header();
if ($completegroups > 0) {
    $findpartner = $DB->get_record('findpartner',
        array('id' => $moduleinstance->id));
    if ($completegroups == 2) {        
        $findpartner->autogroupstatus = 'F';
        $findpartner->dateclosuregroups = time();
        $DB->update_record('findpartner', $findpartner);   
        
    }        
    matchgroups ($moduleinstance->id);
    redirect(new moodle_url('/mod/findpartner/view.php',
                        array('id' => $cm->id)));
}


echo $OUTPUT->single_button(new moodle_url('/mod/findpartner/completegroups.php',
        array('id' => $cm->id, 'completegroups' => 1)),
            get_string('completegroups', 'mod_findpartner'));
echo $OUTPUT->single_button(new moodle_url('/mod/findpartner/completegroups.php',
    array('id' => $cm->id, 'completegroups' => 2)),
        get_string('autocompleteclose', 'mod_findpartner'));

            

echo $OUTPUT->footer();