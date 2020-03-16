<?php


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
            
        $mform->addElement('text', 'groupname', get_string('group_name', 'mod_findpartner'), array('size' => '100', 'maxlength' => self::GROUP_NAME_MAXLEN - 1));
        $mform->setType('groupname', PARAM_TEXT);

        $mform->addElement('textarea', 'description', get_string('group_description', 'mod_findpartner'),
                array('wrap' => 'virtual', 'maxlength' => self::DESCRIPTION_MAXLEN - 1, 'rows' => '3', 'cols' => '102', ''));
        
        $mform->setType('description', PARAM_NOTAGS);
          
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
    public function validation($data,$files) {
        global $COURSE;

        $errors = parent::validation($data,$files);

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
