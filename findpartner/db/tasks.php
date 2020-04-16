<?php
defined('MOODLE_INTERNAL') || die();
$tasks = array(
    
    array(
        'classname' => 'mod_findpartner\task\autogroup_task',
        'blocking' => 0,
        'minute' => '*/1',
        'hour' => '*',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*'
    )
);

