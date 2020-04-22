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

require_once($CFG->dirroot.'/lib/formslib.php');

class workblock_form extends moodleform {

    /**
     * Maximum length of the group request.
     */
    const TASK_MAXLEN = 1024;


    /**
     * Definition of the form
     */
    public function definition() {
        global $DB;
        $mform = $this->_form;
        list($data) = $this->_customdata;

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        // This is necessary to save the groupid in makerequest.
        $mform->addElement('hidden', 'groupid');
        $mform->setType('groupid', PARAM_INT);

        $mform->addElement('textarea', 'task', get_string('task', 'mod_findpartner'),
                array('wrap' => 'virtual', 'maxlength' => self::TASK_MAXLEN - 1, 'rows' => '3', 'cols' => '102', ''));

        $mform->setType('task', PARAM_NOTAGS);

        $mform->addRule('task', null, 'required', null, 'client');

        
        $students = $DB->get_records('findpartner_student', ['studentgroup' => $data['groupid']]);
        
        $arraystudents = [];

        // We need this in order to save the member id.
        foreach ($students as $student) {
            $studentinfo = $DB->get_record('user', ['id' => $student->studentid]);
            //array_push($arraystudents,$studentinfo->firstname . ' ' . $studentinfo->lastname);
            $arraystudents[$student->studentid] = $studentinfo->firstname . ' ' . $studentinfo->lastname;
        }
        
        // This allows the user to make multiple choice.
        $select = $mform->addElement('select', 'members', get_string('members'), $arraystudents, $attributes);
        $select->setMultiple(true);
        $mform->setType('members', PARAM_NOTAGS);
        $mform->addRule('members', null, 'required', null, 'client');

        $this->add_action_buttons(true, get_string('createblock', 'mod_findpartner'));

        $this->set_data($data);

    }

    /**
     * Validation of the form
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        global $COURSE;

        $errors = parent::validation($data, $files);

        $task = $data['task'];
        if (strlen($task) > self::TASK_MAXLEN) {
            $errors['task'] = get_string('maxcharlenreached', 'mod_findpartner');
        }
        return $errors;
    }
}
