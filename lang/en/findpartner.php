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
 * Plugin strings are defined here.
 *
 * @package     mod_findpartner
 * @category    string
 * @copyright   2020 Rodrigo Aguirregabiria Herrero, Manuel Alfredo Collado Centeno, GIETA UPM
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Find your partner';
$string['modulename'] = 'Find your partner';
$string['modulename_help'] = '<p>Lets participants create and select groups. Features: </p><ul><li>Participant can create groups, give them a description</li><li>Participants can ask to join groups</li></ul>';
$string['modulename_link'] = 'mod/findpartner/view';
$string['findpartnername'] = 'Find your partner';
$string['findpartnersettings'] = 'Options';
$string['findpartnerfieldset'] = 'Set fields';
$string['findpartnername_help'] = 'Put a name for the activity';
$string['minmembers'] = 'Minimum of members per group';
$string['maxmembers'] = 'Maximum of members per group';
$string['dateclosuregroups'] = 'Date when students can\'t join groups anymore';
$string['error_minmembers'] = 'Error: a group must be formed by two or more members.';
$string['error_maxmembers'] = 'Error: the maximum of members must be greater or equal to the minimum of members.';
$string['group_name'] = 'Name of the group';
$string['group_description'] = 'Description of the group. Please make it good.';
$string['creategroup'] = 'Create the group';
$string['maxcharlenreached'] = 'Maximum lenght of the string reached';
$string['description'] = 'Description';
$string['members'] = 'Members';
$string['request'] = 'Message of the request';
$string['send'] = 'Send message';
$string['send_request'] = 'Send a request';
$string['inagroup'] = 'You are already in a group. You can\'t create a group.';
$string['viewrequest'] = 'View request';
$string['requestmessage'] = 'Request message';
$string['accept'] = 'Accept';
$string['deny'] = 'Deny';
$string['cancel'] = 'Cancel';
$string['joinmessage'] = 'If you click the following button you are accepting to join this activity to find partners for your group. In case you don\'t find any, the system will match you automatically.';
$string['exitactivity'] = 'Exit activity';
$string['exitgroup'] = 'Exit group';
$string['groupnameexists'] = 'There is another group with this name. Please, set another name.';
$string['groupfull'] = 'The group is full, you cannot accept more requests.';
$string['norequest'] = 'There are not more request left.';
$string['viewgroup'] = 'View group';
$string['userid'] = 'User id';
$string['firstname'] = 'Firstname';
$string['lastname'] = 'Lastname';
$string['email'] = 'email';
$string['enrolstudents'] = 'Enrol students';
$string['enrol'] = 'Enrol';
$string['deenrolstudents'] = 'De-enrol students';
$string['deenrol'] = 'De-enrol';
$string['duedate'] = 'End date for joining groups';
$string['autogroup'] = 'Autogroup task';
$string['alertcontract'] = 'You have 24 hours to decide between all the members of the group if you want to make contracts.';
$string['contractyes'] = 'I want to make a contract';
$string['contractno'] = 'I don\'t want to make a contract';
$string['task'] = 'Content of the task';
$string['create_block'] = 'Create a block of work';
$string['createblock'] = 'Create block';
$string['membersform'] = 'Members to assign to the workblock';
$string['membershelp'] = 'You can select more than one student to the workblock. To do this you have to select the users you want with hold down + ctrl (command in mac).';
$string['membershelp_help'] = 'You can select more than one student to the workblock. To do this you have to select the users you want with hold down + ctrl (command in mac).';
$string['workblock'] = 'Task';
$string['memberstable'] = 'Members assigned to the task';
$string['workblockstatus'] = 'Status';
$string['edit'] = 'Edit';
$string['complain'] = 'Complain';
$string['complains'] = 'Complaints';
$string['sendcomplain'] = 'Send a complaint';
$string['accepted'] = 'Accepted';
$string['dennied'] = 'Denied, the admin has to edit it.';
$string['pending'] = 'Pending of approval';
$string['verified'] = 'Verified, the task is done and correct';
$string['complete'] = 'Completed, pending of validation';
$string['done'] = 'Done';
$string['verify'] = 'Task is done';
$string['noverify'] = 'Task is not done';
$string['viewcontracts'] = 'View contracts';
$string['edited'] = 'This workblock has been edited. The students made a new version';
$string['datecreation'] = 'Date of workblock creation';
$string['date'] = 'Date';
$string['time'] = 'Time';
$string['datemodified'] = 'Date of last status modification';
$string['contacttype'] = 'Contact method type';
$string['contactmethod'] = 'Contact';
$string['contactmethodhelp'] = 'For example: student@college.es, @user ';
$string['contacttypehelp'] = 'Type of method contact (email, twitter, etc)';
$string['mandatorycontact'] = 'You must add a method of contact for your group partners.';
$string['editcontact'] = 'Edit your contact info.';
$string['showcontact'] = 'Your contact info will only be shown to the students of your group.';
$string['save'] = 'Save';
$string['viewmembers'] = 'View members and contact';
$string['editcontact'] = 'Edit contact info';
$string['useemail'] = 'I want to use the official email';
$string['enddate'] = 'End date';
$string['endactivitydate'] = 'After this date students will not be able to edit workblocks';
$string['alreadysent'] = 'Already sent';
$string['minimum'] = 'minimum';
$string['whatcontracts'] = 'What is a contract?';
$string['whencontracts'] = 'When are contracts aviable?';
$string['whycontracts'] = 'Why use contracts?';
$string['howcontracts'] = 'How do contracts work?';
$string['whatcontractstext'] = 'It is an agreement that recognizes the duties of the members of the group. It divides the task that the group must do.';
$string['whencontractstext'] = 'Once the closure group date comes, students must decide if they want to use this functionality. Half or more of the votes must be \'yes\' to access this functionality.';
$string['whycontractstext'] = 'The goal of this project is to provide tools to students about teamwork. Students must communicate with each other in order to achieve success. A group member who doesn\'t work is an unacceptable situation that we want to avoid. Also, professors usually have a problem when they want to evaluate teamwork abilities, so they will have access to information about contracts (group members, tasks distribution, complaints...).';
$string['howcontractstext'] = 'The admin of the group can create workblocks. A workblock is a task compound by a name, the members in charge, and a status (pending of approval, accepted, completed, and validated). <br>Each workblock must be voted by all members in order to be accepted. <br>An accepted workblock can be set as completed by a member in charge. <br>A completed workblock must be voted as verified by the rest of the group. <br>Any student can send a complaint whenever they want unless the workblock is already verified. <br>When a workblock has at least one complaint or if it is denied, the admin can edit it.';
$string['autogenerated'] = 'Auto-generated group';
$string['enrolall'] = 'Enrol all students';
$string['completegroups'] = 'Autocomplete groups';
$string['autocompleteclose'] = 'Autocomplete and close groups';
$string['buttonfunction'] = 'The button on the left will complete the groups but students will be able to change groups. <br> The button on the right will complete groups and close them.';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';