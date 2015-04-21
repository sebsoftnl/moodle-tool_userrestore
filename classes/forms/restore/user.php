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
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_userrestore\forms\restore;
use tool_userrestore\util;

require_once($CFG->libdir . '/formslib.php');

/**
 * tool_userrestore\forms\user
 *
 * @package     tool_userrestore
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class user extends \moodleform {

    /**
     *
     * @var array
     */
    protected $potentialusers;

    /**
     * form definition
     */
    public function definition() {
        /* @var $DB \moodle_database */
        $mform = $this->_form;
        // This element is only here so the form will actually get submitted.
        $mform->addElement('hidden', 'processor', 1);
        $mform->setType('processor', PARAM_INT);

        // Load users.
        $this->potentialusers = \tool_userrestore\util::load_users_to_undelete(true, true);
        foreach ($this->potentialusers as $user) {
            // Register hidden element so user ids are tracked.
            $mform->addElement('hidden', 'user_'.$user->id, 0);
            $mform->setType('user_'.$user->id, PARAM_INT);
        }
        // Add fake elements for all deleted users.
        $mform->addElement('html', $this->create_fake_user_elements($this->potentialusers));
        $this->add_checkbox_controller(1); // Hoping this works.

        // Register send HTML option.
        $mform->addElement('advcheckbox', 'sendmail', '', get_string('form:label:sendmail', 'tool_userrestore'));
        $mform->setType('sendmail', PARAM_BOOL);
        $mform->setDefault('sendmail', 1);

        // Register HTML input.
        $mform->addElement('text', 'mailsubject', get_string('form:label:subject', 'tool_userrestore'), array('size' => 40));
        $mform->setType('mailsubject', PARAM_RAW);
        $mform->setDefault('mailsubject', get_string('email:user:restore:subject', 'tool_userrestore'));

        $mform->addElement('static', 'emailtemplatesdesc', '', get_string('form:static:email:template', 'tool_userrestore'));

        $mform->addElement('editor', 'mailbody', get_string('form:label:email', 'tool_userrestore'));
        $mform->setType('mailbody', PARAM_RAW);
        $mform->setDefault('mailbody', array('text' => get_string('email:user:restore:body', 'tool_userrestore')));

        $this->add_action_buttons(false, get_string('button:userrestore:continue', 'tool_userrestore'));
    }

    /**
     * Process the posted form
     *
     * @throws \moodle_exception
     */
    public function process() {
        global $DB;
        $data = $this->get_data();
        if ($data === null) {
            return false;
        }

        // Gather users.
        $data->restoreids = array();
        foreach ($data as $key => $value) {
            if (stristr($key, 'user_') !== false) {
                if ((bool)$value) {
                    $data->restoreids[] = (int)str_replace('user_', '', $key);
                }
            }
        }

        if ($data->sendmail) {
            $mailcontents = $data->mailbody['text'];
        }

        foreach ($data->restoreids as $uid) {
            try {
                $user = $this->potentialusers[$uid];
                \tool_userrestore\util::do_undelete_user($user, $data->sendmail, $data->mailsubject, $data->mailbody['text']);
                util::print_notification(get_string('restore:user-restored', 'tool_userrestore', $user), 'success');
            } catch (\moodle_exception $ex) {
                util::print_notification($ex->getMessage(), 'error');
            }
        }
    }

    /**
     * Generate a table and some 'non-registered' checkboxes for given users.
     * The form process will pick this up due to the hidden elements for users.
     *
     * @param array $users
     * @return string fake element output, to be registered as a 'html' element type.
     */
    protected function create_fake_user_elements($users) {
        // Prepare html.
        $strloginfoyes = get_string('yes');
        $strloginfono = get_string('no');
        $html = '<div class="fitem"><div class="fitemtitle">';
        $html .= '<label>' . get_string('label:users:potential', 'tool_userrestore') . '</label></div><div class="felement">';
        // Generate table.
        $table = new \html_table();
        $table->head = array('&nbsp', get_string('username'), get_string('email'),
            get_string('timedeleted', 'tool_userrestore'),
            get_string('deletedby', 'tool_userrestore'), get_string('hasloginfo', 'tool_userrestore'));
        $table->data = array();
        foreach ($users as $user) {
            $table->data[] = array(
                '<input type="checkbox" name="user_' . $user->id . '" id="id_user_' . $user->id .
                    '" value="1" class="checkboxgroup1" />',
                $user->username,
                $user->email,
                date('Y-m-d', $user->timedeleted),
                $user->deletedby,
                $user->fromlogstore ? $strloginfoyes : $strloginfono);
        }
        $html .= \html_writer::table($table);
        $html .= '</div></div>';
        return $html;
    }

}