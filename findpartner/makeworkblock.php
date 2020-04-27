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
require_once('workblock_form.php');

// Course_module ID, or.
$id = optional_param('id', 0, PARAM_INT);

// ... module instance id.
$f  = optional_param('f', 0, PARAM_INT);

// Groupid.
$groupid = optional_param('groupid', 0, PARAM_INT);

// If it is an edition. 0 if not, else workblock id to edit.
$editworkblock = optional_param('editworkblock', 0, PARAM_INT);

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

$PAGE->set_url('/mod/findpartner/makeworkblock.php', array('id' => $cm->id, 'groupid' => $groupid));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);

echo $OUTPUT->header();

$data = array (
    'id' => $id,
    'select' => $select,
    'groupid' => $groupid,
    'editworkblock' => $editworkblock
);
    // Esto queremos tirarlo en el futuro.
$findpartner = $DB->get_record( 'findpartner', array (
'id' => 1
), '*', MUST_EXIST );

$mform = new workblock_form( null, array (
    $data,
    $findpartner
) );

// If the admin is editing the task is set by default.
if ($editworkblock != 0) {
    $query = $DB->get_record('findpartner_workblock', ['id' => $editworkblock]);
    $toform = array('task' => $query->task);
    $mform->set_data($toform);
}

$mform->display();


if ($mform->is_cancelled()) {
    // Handle form cancel operation, if cancel button is present on form.
    redirect(new moodle_url ('/mod/findpartner/view.php', array('id' => $cm->id)));
} else if ($fromform = $mform->get_data()) {
    // TODO handle cases when a student opens multiple tabsor press the return button.
    // Insert the work block.
    $ins = (object)array('groupid' => $fromform->groupid, 'task' => $fromform->task);
    $workblockid = $DB->insert_record('findpartner_workblock', $ins, $returnid = true. $bulk = false);

    // Insert the students in charge of that block.
    foreach ($fromform->members as $memberid) {
        $ins = (object)array('studentid' => $memberid,'workblockid'=>$workblockid);
        $DB->insert_record('findpartner_incharge', $ins, $returnid = true. $bulk = false);

    }
    
    if ($fromform->editworkblock != 0) {
        $record = $DB->get_record('findpartner_workblock', ['id' => $editworkblock]);
        $record->status = 'E';
        $DB->update_record('findpartner_workblock', $record);
    }

    redirect(new moodle_url ('/mod/findpartner/view.php', array('id' => $cm->id)));
}


echo $OUTPUT->footer();