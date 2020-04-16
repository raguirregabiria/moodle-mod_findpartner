<?php

namespace mod_findpartner\task;
defined('MOODLE_INTERNAL') || die();



class autogroup_task extends \core\task\scheduled_task {
    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('autogroup', 'mod_findpartner');
    }

    public function execute() {
        global $DB;
        $ins = (object)array('student' => 756585, 'groupid' => 8888, 'message' => "vamo a ver si sale esto");
        $DB->insert_record('findpartner_request', $ins, $returnid = true. $bulk = false);
    }
}
