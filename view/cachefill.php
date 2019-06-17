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

admin_externalpage_setup('tooluserrestore');

raise_memory_limit(MEMORY_HUGE);
set_time_limit(0);

$redirect = optional_param('redirect', null, PARAM_LOCALURL);
$confirm = optional_param('confirm', 0, PARAM_INT);
$smart = optional_param('smart', false, PARAM_BOOL);
$purge = optional_param('purge', 0, PARAM_INT);
$context       = \context_system::instance();

$pageparams = [];
if (!empty($redirect)) {
    $pageparams['redirect'] = $redirect;
}
$pageurl = new moodle_url('/' . $CFG->admin . '/tool/userrestore/view/cachefill.php', $pageparams);

require_capability('tool/userrestore:administration', $context);

if ($confirm) {
    tool_userrestore\deletedusercache::fill_cache($smart);
    if ($redirect) {
        redirect($redirect);
    } else {
        redirect($pageurl);
    }
} else if ($purge) {
    tool_userrestore\deletedusercache::purge();
    if ($redirect) {
        redirect($redirect);
    } else {
        redirect($pageurl);
    }
} else {
    echo $OUTPUT->header();
    echo '<div id="tool-userrestore-container">';
    echo '<div>';
    \tool_userrestore\util::print_view_tabs(array(), 'cache');
    echo '</div>';
    if (tool_userrestore\deletedusercache::has_all_entries()) {
        \tool_userrestore\util::print_notification(get_string('cache:iscomplete', 'tool_userrestore'), 'success');
        $confirmpurge = new moodle_url($pageurl, ['purge' => 1]);
        echo $OUTPUT->single_button($confirmpurge, get_string('cache:purge', 'tool_userrestore'), 'get');
    } else {
        \tool_userrestore\util::print_notification(get_string('cache:fillneeded', 'tool_userrestore'), 'warn');
        $confirmfill = new moodle_url($pageurl, ['confirm' => 1]);
        echo $OUTPUT->single_button($confirmfill, get_string('cache:fill', 'tool_userrestore'), 'get');
        $confirmfillsmart = new moodle_url($pageurl, ['confirm' => 1, 'smart' => 1]);
        echo $OUTPUT->single_button($confirmfillsmart, get_string('cache:fill:smart', 'tool_userrestore'), 'get');
    }
    echo '</div>';
    echo $OUTPUT->footer();
}