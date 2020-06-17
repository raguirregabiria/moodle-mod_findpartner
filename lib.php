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
 * @copyright   2020 Rodrigo Aguirregabiria Herrero
 * @copyright   2020 Manuel Alfredo Collado Centeno
 * @copyright   2020 GIETA Universidad Politécnica de Madrid (http://gieta.etsisi.upm.es/)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Return if the plugin supports $feature.
 *
 * @param string $feature Constant representing the feature.
 * @return true | null True if the feature is supported, null otherwise.
 */
function findpartner_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the mod_findpartner into the database.
 *
 * Given an object containing all the necessary data, (defined by the form
 * in mod_form.php) this function will create a new instance and return the id
 * number of the instance.
 *
 * @param object $moduleinstance An object from the form.
 * @param mod_findpartner_mod_form $mform The form.
 * @return int The id of the newly inserted record.
 */
function findpartner_add_instance($moduleinstance, $mform = null) {
    global $DB;

    $moduleinstance->timecreated = time();
    $moduleinstance->timemodified = time();

    $moduleinstance->id = $DB->insert_record('findpartner', $moduleinstance);

    // Add calendar events if necessary.
    findpartner_set_events($moduleinstance);
    if (!empty($moduleinstance->completionexpected)) {
        \core_completion\api::update_completion_date_event($moduleinstance->coursemodule, 'findpartner', $moduleinstance->id,
                $moduleinstance->completionexpected);
    }

    return $moduleinstance->id;
}

/**
 * Updates an instance of the mod_findpartner in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param object $moduleinstance An object from the form in mod_form.php.
 * @param mod_findpartner_mod_form $mform The form.
 * @return bool True if successful, false otherwise.
 */
function findpartner_update_instance($moduleinstance, $mform = null) {
    global $DB;

    $moduleinstance->timemodified = time();
    $moduleinstance->id = $moduleinstance->instance;

    $DB->update_record('findpartner', $moduleinstance);

    // Add calendar events if necessary.
    findpartner_set_events($moduleinstance);
    if (!empty($moduleinstance->completionexpected)) {
        \core_completion\api::update_completion_date_event($moduleinstance->coursemodule, 'findpartner', $moduleinstance->id,
                $moduleinstance->completionexpected);
    }
    return true;
}

/**
 * Removes an instance of the mod_findpartner from the database.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false on failure.
 */
function findpartner_delete_instance($id) {
    global $DB;

    $exists = $DB->get_record('findpartner', array('id' => $id));
    if (!$exists) {
        return false;
    }

    $DB->delete_records('findpartner', array('id' => $id));

    return true;
}

/**
 * This creates new events given as closeopen by $findpartner.
 *
 * @param stdClass $findpartner
 * @return void
 */
function findpartner_set_events($findpartner) {
    global $DB, $CFG;

    // Include calendar/lib.php.
    require_once($CFG->dirroot.'/calendar/lib.php');
    require_once($CFG->dirroot . '/mod/findpartner/locallib.php');

    // Get CMID if not sent as part of $findpartner.
    if (!isset($findpartner->coursemodule)) {
        $cm = get_coursemodule_from_instance('findpartner',
                $findpartner->id, $findpartner->course);
        $findpartner->coursemodule = $cm->id;
    }

    // Get old event.
    $oldevent = null;
    $oldevent = $DB->get_record('event',
    array('modulename' => 'findpartner',
        'instance' => $findpartner->id, 'eventtype' => FINDPARTNER_EVENT_TYPE_DUE));

    if ($findpartner->dateclosuregroups) {
        // Create calendar event.
        $event = new stdClass();
        $event->type = CALENDAR_EVENT_TYPE_ACTION;
        $event->name = $findpartner->name .' ('.get_string('duedate', 'mod_findpartner').')';
        $event->description = format_module_intro('findpartner', $findpartner, $findpartner->coursemodule);
        $event->courseid = $findpartner->course;
        $event->groupid = 0;
        $event->userid = 0;
        $event->modulename = 'findpartner';
        $event->instance = $findpartner->id;
        $event->eventtype = FINDPARTNER_EVENT_TYPE_DUE;
        $event->visible   = instance_is_visible('findpartner', $findpartner);
        $event->timestart = $findpartner->dateclosuregroups;
        $event->timeduration = 0;
        $event->timesort = $event->timestart + $event->timeduration;

        if ($oldevent) {
            $event->id = $oldevent->id;
        } else {
            unset($event->id);
        }
        // Create also updates an existing event.
        calendar_event::create($event);
    } else {
        // Delete calendar event.
        if ($oldevent) {
            $calendarevent = calendar_event::load($oldevent);
            $calendarevent->delete();
        }
    }
}
