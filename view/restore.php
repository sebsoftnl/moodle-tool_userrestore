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
 * Processor file for user exclusion by file restores
 *
 * File         restore.php
 * Encoding     UTF-8
 *
 * @package     tool_userrestore
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

use tool_userrestore\util;

admin_externalpage_setup('tooluserrestore');

$history = optional_param('history', 0, PARAM_INT);
$context       = \context_system::instance();

$pageurl = new moodle_url('/' . $CFG->admin . '/tool/userrestore/view/restore.php');

require_capability('tool/userrestore:administration', $context);
require_capability('moodle/user:update', context_system::instance());
if (\tool_userrestore\util::count_users_to_undelete() === 0) {
        echo $OUTPUT->header();
        echo '<div id="tool-userrestore-container">';
        echo '<div>';
        \tool_userrestore\util::print_view_tabs(array(), 'restore');
        echo '</div>';
        \tool_userrestore\util::print_notification(get_string('msg:no-users-to-restore', 'tool_userrestore'), 'warn');
        echo '</div>';
        echo $OUTPUT->footer();
} else {
    $mform = new \tool_userrestore\forms\restore\user($PAGE->url);
    if ($mform->is_cancelled()) {
        redirect($pageurl);
    } else if ($data = $mform->get_data()) {
        echo $OUTPUT->header();
        echo '<div id="tool-userrestore-container">';
        echo '<div>';
        \tool_userrestore\util::print_view_tabs(array(), 'restore');
        echo '</div>';
        $mform->process();
        echo '<br/>';
        echo util::continue_button($pageurl, get_string('button:backtoform', 'tool_userrestore'));
        echo '</div>';
        echo $OUTPUT->footer();
    } else {
        echo $OUTPUT->header();
        echo '<div id="tool-userrestore-form-container">';
        echo '<div>';
        \tool_userrestore\util::print_view_tabs(array(), 'restore');
        echo '</div>';
        echo '<div>' . get_string('page:view:restore.php:introduction', 'tool_userrestore') . '</div>';
        echo $mform->display();
        echo '</div>';
        echo $OUTPUT->footer();
    }
}