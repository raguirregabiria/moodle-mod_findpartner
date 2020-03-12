<?php

//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");
 
class group_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;
 
        $mform = $this->_form;
        // Adding the "name" field for the name of the group.
        $mform->addElement('text', 'name', get_string('group_name', 'mod_findpartner'), array('size' => '64'));
        // $mform->addRule('name', null, 'required', null, 'client');
        // $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        
    }
    //Custom validation should be added here
    
    function validation($data, $files) {
        return 0;
    }
}