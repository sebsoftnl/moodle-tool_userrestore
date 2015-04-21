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
 * Processor file for displaying status logs
 *
 * File         log.php
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
$context       = \context_system::instance();

$history = optional_param('history', 0, PARAM_INT);
$thispageurl = new moodle_url('/' . $CFG->admin . '/tool/userrestore/view/log.php', array('history' => $history));

require_capability('tool/userrestore:viewstatus', $context);
echo $OUTPUT->header();
echo '<div class="tool-userrestore-container">';
echo '<div>';
$selected = (((int)$history === 0)) ? 'log_latest' : 'log_all';
\tool_userrestore\util::print_view_tabs(array(), $selected);
echo '</div>';
echo '<div>' . get_string('page:view:log.php:introduction', 'tool_userrestore') . '</div>';
$table = new \tool_userrestore\logtable((bool)$history);
$table->baseurl = $thispageurl;
$table->render_log(25);
echo '</div>';
echo $OUTPUT->footer();