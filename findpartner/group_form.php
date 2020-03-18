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

class group_form extends moodleform {

    /**
     * Maximum length of the group description.
     */
    const DESCRIPTION_MAXLEN = 1024;
    /**
     * Maximum length of a group name.
     */
    const GROUP_NAME_MAXLEN = 254;

    /**
     * Definition of the form
     */
    public function definition() {

        $mform = $this->_form;
        list($data) = $this->_customdata;

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->addElement('text', 'groupname', get_string('group_name', 'mod_findpartner'),
            array('size' => '100', 'maxlength' => self::GROUP_NAME_MAXLEN - 1));
        $mform->setType('groupname', PARAM_TEXT);
        $mform->addRule('groupname', null, 'required', null, 'client');
        $mform->addElement('textarea', 'description', get_string('group_description', 'mod_findpartner'),
                array('wrap' => 'virtual', 'maxlength' => self::DESCRIPTION_MAXLEN - 1, 'rows' => '3', 'cols' => '102', ''));
        $mform->setType('description', PARAM_NOTAGS);
        $mform->addRule('description', null, 'required', null, 'client');
        $this->add_action_buttons(true, get_string('creategroup', 'mod_findpartner'));
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

        $description = $data['description'];
        if (strlen($description) > self::DESCRIPTION_MAXLEN) {
            $errors['description'] = get_string('maxcharlenreached', 'mod_findpartner');
        }
        $groupname = $data['groupname'];
        if (strlen($groupname) > self::GROUP_NAME_MAXLEN) {
            $errors['groupname'] = get_string('maxcharlenreached', 'mod_findpartner');
        }
        if (groups_get_group_by_name($COURSE->id, $groupname)) {
            $errors['groupname'] = get_string('groupnameexists', 'group', $groupname);
        }
        return $errors;
    }
}
