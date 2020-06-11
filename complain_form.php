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

class complain_form extends moodleform {

    /**
     * Maximum length of the workblock complain.
     */
    const COMPLAIN_MAXLEN = 1024;


    /**
     * Definition of the form
     */
    public function definition() {

        $mform = $this->_form;
        list($data) = $this->_customdata;

        $mform->addElement('hidden', 'id');

        $mform->setType('id', PARAM_INT);
        // This is necessary to save the workblockid in makecomplain.
        $mform->addElement('hidden', 'workblockid');

        $mform->setType('workblockid', PARAM_INT);
        $mform->addElement('textarea', 'complain', get_string('complain', 'mod_findpartner'),
                array('wrap' => 'virtual', 'maxlength' => self::COMPLAIN_MAXLEN - 1, 'rows' => '3', 'cols' => '102', ''));

        $mform->setType('complain', PARAM_NOTAGS);

        $mform->addRule('complain', null, 'required', null, 'client');

        $this->add_action_buttons(true, get_string('send', 'mod_findpartner'));

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

        $complain = $data['complain'];
        if (strlen($complain) > self::COMPLAIN_MAXLEN) {
            $errors['complain'] = get_string('maxcharlenreached', 'mod_findpartner');
        }
        return $errors;
    }
}
