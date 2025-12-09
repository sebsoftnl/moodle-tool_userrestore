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
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('tooluserrestore');

raise_memory_limit(MEMORY_HUGE);
set_time_limit(0);

$redirect = optional_param('redirect', null, PARAM_LOCALURL);
$confirm = optional_param('confirm', 0, PARAM_INT);
$context = \context_system::instance();

$pageparams = [];
if (!empty($redirect)) {
    $pageparams['redirect'] = $redirect;
}
$pageurl = new moodle_url('/' . $CFG->admin . '/tool/userrestore/view/reset.php', $pageparams);

require_capability('tool/userrestore:administration', $context);

$form = new tool_userrestore\forms\reset($pageurl);
if ($form->is_cancelled()) {
    if ($redirect) {
        redirect($redirect);
    } else {
        redirect($pageurl);
    }
} else if ($data = $form->get_data()) {
    if ($data->resetconfig) {
        \tool_userrestore\config::set('logstoreread', null, true);
        \tool_userrestore\config::set('lastlogstoreid', null, true);
        // And ENABLE task.
        $DB->execute('UPDATE {task_scheduled} SET disabled = 0 WHERE classname = ?', ['\\tool_userrestore\\task\\fillrestoredata']);
        \core\notification::add(get_string('result:config:reset', 'tool_userrestore'), 'success');
    }
    if ($data->resetdata) {
        $DB->execute('DELETE FROM {tool_userrestore_data}');
        \core\notification::add(get_string('result:data:reset', 'tool_userrestore'), 'success');
    }
    if ($redirect) {
        redirect($redirect);
    } else {
        redirect($pageurl);
    }
} else {
    echo $OUTPUT->header();
    echo '<div id="tool-userrestore-container">';
    echo '<div>';
    \tool_userrestore\util::print_view_tabs([], 'reset');
    echo '</div>';
    echo $OUTPUT->notification(get_string('userrestore:reset', 'tool_userrestore'), 'info', false);
    echo $OUTPUT->notification(get_string('userrestore:reset:warn', 'tool_userrestore'), 'warning', false);
    echo $form->render();
    echo '</div>';
    echo $OUTPUT->footer();
}
