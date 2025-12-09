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
 * this file contains the user selection form to exclude users
 *
 * File         user.php
 * Encoding     UTF-8
 *
 * @package     tool_userrestore
 *
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_userrestore\forms;

defined('MOODLE_INTERNAL') || die;
use tool_userrestore\util;

require_once($CFG->libdir . '/formslib.php');

/**
 * tool_userrestore\forms\reset
 *
 * @package     tool_userrestore
 *
 * @copyright   Sebsoft.nl
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class reset extends \moodleform {
    /**
     * form definition
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('advcheckbox', 'resetconfig', get_string('userrestore:reset:config', 'tool_userrestore'));
        $mform->setDefault('resetconfig', 1);
        $mform->addHelpButton('resetconfig', 'userrestore:reset:config', 'tool_userrestore');

        $mform->addElement('advcheckbox', 'resetdata', get_string('userrestore:reset:data', 'tool_userrestore'));
        $mform->setDefault('resetdata', 1);
        $mform->addHelpButton('resetdata', 'userrestore:reset:data', 'tool_userrestore');

        $this->add_action_buttons(true, get_string('reset', 'tool_userrestore'));
    }
}
