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


require_once($CFG->dirroot.'/calendar/lib.php');
 
$event = new stdClass();
$event->eventtype = 'date closure groups'; // Constant defined somewhere in your code - this can be any string value you want. It is a way to identify the event.
$event->type = CALENDAR_EVENT_TYPE_STANDARD; // This is used for events we only want to display on the calendar, and are not needed on the block_myoverview.
$event->name = get_string('calendarstart', 'scorm', $scorm->name);
$event->description = format_module_intro('scorm', $scorm, $cmid);
$event->courseid = $scorm->course;
$event->groupid = 0;
$event->userid = 0;
$event->modulename = 'findpartner';
$event->instance = $scorm->id;
$event->timestart = $scorm->timeopen;
$event->visible = instance_is_visible('scorm', $scorm);
$event->timeduration = 0;
 
calendar_event::create($event);
