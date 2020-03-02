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
 * System to find partners for group projects
 *
 * @package    findpartner
 * @copyright Rodrigo Aguirregabiria 2020  
 * @copyright Manuel ALfredo Collado Centeno 2020
 * @copyright GIETA, 2020 
 * @copyright Universidad Politecnica de Madrid (UPM)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$plugin->version   = 2020030200; // The current module version (Date: YYYYMMDDXX)
$plugin->requires  = 2018051700; // Requires this Moodle version
$plugin->cron      = 0;          // Period for cron to check this module (secs)
$plugin->component = 'mod_findpartner'; // Full name of the plugin (used for diagnostics).
$plugin->maturity  = MATURITY_STABLE;
$plugin->release   = "0.01"; // User-friendly version number.
