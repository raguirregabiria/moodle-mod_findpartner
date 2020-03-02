<?php

global $DB;
$course = $DB->get_record('course', array('id' => $courseid));
$info = get_fast_modinfo($course);
print_object($info);

?>