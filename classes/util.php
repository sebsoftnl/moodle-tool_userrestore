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
 * file contains the general utility class for this tool
 *
 * File         util.php
 * Encoding     UTF-8
 *
 * @package     tool_userrestore
 *
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_userrestore;

/**
 * tool_userrestore\util
 *
 * @package     tool_userrestore
 *
 * @copyright   Sebsoft.nl
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class util {
    /**
     * __construct() DO NOT SHOW / ALLOW TO BE CALLED: Open source version
     */
    private function __construct() {
        // Open source version.
    }

    /**
     * Get read progress information.
     * @return array
     */
    final public static function get_read_progress() {
        global $DB;
        $lastid = config::get('lastlogstoreid') ?? 0;
        $lscount = $DB->count_records('logstore_standard_log');
        $lscountid = $DB->count_records_sql(
            'SELECT COUNT(id) FROM {logstore_standard_log} WHERE id <= ?',
            [$lastid]
        );
        return [
            'total' => $lscount,
            'current' => $lscountid,
            'percentage' => round(100 * $lscountid / $lscount),
        ];
    }

    /**
     * Check if logstore is storing the "other" field in JSON or otherwise.
     *
     * @return bool
     */
    public static function is_jsonformat() {
        $value = get_config('logstore_standard', 'jsonformat');
        return (bool)$value;
    }

    /**
     * Count users that are applicable for undeletion.
     *
     * @return boolean
     */
    final public static function count_users_to_undelete() {
        global $CFG, $DB;

        $onlywithdata = (bool)config::get('undeletetrackedonly');

        $wheres = ['confirmed = 1', 'deleted = 1', 'mnethostid = :mnethostid'];
        $params = ['mnethostid' => $CFG->mnet_localhost_id];

        // Do NOT take users into account that have no restore data.
        $sql = 'SELECT COUNT(id) FROM {user} WHERE ' . implode(' AND ', $wheres);
        if ($onlywithdata) {
            $sql .= ' AND id IN (SELECT userid FROM {tool_userrestore_data})';
        }

        return $DB->count_records_sql($sql, $params);
    }

    /**
     * Return count of "userdata" records.
     * @return int
     */
    public static function count_data_records() {
        global $DB;
        return $DB->count_records_sql('SELECT count(distinct userid) FROM {tool_userrestore_data}');
    }

    /**
     * Load users that are applicable for undeletion.
     *
     * @param bool $autoconvert true to automatically convert relevant data due to the process of deleting
     * @param int $limitfrom start results at
     * @param int $limitnum number of results
     * @return boolean
     */
    final public static function load_users_to_undelete(
        $autoconvert = false,
        $limitfrom = 0,
        $limitnum = 0
    ) {
        global $CFG, $DB;
        $wheres = ['confirmed = 1', 'deleted = 1', 'mnethostid = :mnethostid'];
        $params = ['mnethostid' => $CFG->mnet_localhost_id];
        $userfieldsapi = \core_user\fields::for_name();
        $namefields = $userfieldsapi->get_sql('', false, '', '', false)->selects;
        $fields = 'id, ' . $namefields . ', timemodified';

        $onlywithdata = (bool)config::get('undeletetrackedonly');

        // Do NOT take users into account that have no restore data.
        $sql = 'SELECT ' . $fields . ' FROM {user} WHERE ' . implode(' AND ', $wheres);
        if ($onlywithdata) {
            $sql .= ' AND id IN (SELECT userid FROM {tool_userrestore_data})';
        }
        $sql .= ' ORDER BY firstname ASC';
        $users = $DB->get_records_sql($sql, $params, $limitfrom, $limitnum);
        if ($autoconvert) {
            self::convert_undelete_users($users, $onlywithdata);
        }
        return $users;
    }

    /**
     * Convert given records that indicate deleted moodle users to something usable.
     *
     * @param array $users list of user objects
     * @param bool $withdataonly true to only convert users with tracked datas
     * @return void
     */
    final public static function convert_undelete_users(&$users, $withdataonly = true) {
        global  $DB;

        foreach ($users as &$user) {
            // FIrst, consider regular tracked userdata!
            $sql = 'SELECT ud.*, ' . $DB->sql_fullname('u2.firstname', 'u2.lastname') . ' as deletedby ';
            $sql .= 'FROM {tool_userrestore_data} ud ';
            $sql .= 'JOIN {user} u ON ud.userid=u.id ';
            $sql .= 'JOIN {user} u2 ON ud.usercreated=u2.id ';
            $sql .= 'WHERE ud.userid = ? ORDER BY ud.timecreated DESC';
            $params = [$user->id];
            $logrecord = $DB->get_record_sql($sql, $params, IGNORE_MISSING);
            if (empty($logrecord)) {
                $user->hasrestoredata = false;
            } else {
                $olddata = json_decode($logrecord->restoredata, true);
                $user->email = $olddata['email'];
                $user->username = $olddata['username'];
                $user->hasrestoredata = true;
                $user->deletedby = $logrecord->deletedby;
            }

            // Set date user was deleted.
            $user->timedeleted = $user->timemodified;
        }

        // Re-loop when we also need users without tracked data.
        if (!$withdataonly) {
            foreach ($users as &$user) {
                if ($user->hasrestoredata) {
                    // Skip; this one's loaded.
                    continue;
                }

                $sql = 'SELECT *';
                $sql .= 'FROM {user} u ';
                $sql .= 'WHERE id = ?';
                $params = [$user->id];
                $logrecord = $DB->get_record_sql($sql, $params, IGNORE_MISSING);
                if (empty($logrecord)) {
                    $user->hasrestoredata = false;
                } else {
                    // Fallback method.
                    $dotpos = strrpos($logrecord->username, '.');
                    $user->email = substr($logrecord->username, 0, $dotpos);
                    $user->username = $user->email;
                    $user->hasrestoredata = true;
                    $user->deletedby = '-';
                }

                // Set date user was deleted.
                $user->timedeleted = $user->timemodified;
            }
        }
    }

    /**
     * Performs the actual user undeletion by updating the users table
     * and restoring the information
     *
     * @param \stdClass $user
     * @param bool $sendemail whether or not to send an email to the user to restore
     * @param string $emailsubject email subject
     * @param string $emailbody email body
     */
    final public static function do_undelete_user($user, $sendemail = false, $emailsubject = '', $emailbody = '') {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/user/lib.php');

        // To be sure, work from original record.
        $userrecord = $DB->get_record('user', ['id' => $user->id, 'deleted' => 1]);
        if (empty($userrecord)) {
            // Just in case...
            throw new exception('restore:deleted-user-not-found');
        }

        // Check MNET host.
        if ($userrecord->mnethostid !== $CFG->mnet_localhost_id) {
            // Refuse to restore.
            throw new exception('restore:user-mnet-not-local', '', $userrecord);
        }

        $updateuser = null;
        $sql = 'SELECT ud.restoredata ';
        $sql .= 'FROM {tool_userrestore_data} ud JOIN {user} u ON ud.userid=u.id ';
        $sql .= 'WHERE ud.userid = ? ORDER BY ud.timecreated DESC';
        $params = [$user->id];
        $logrecord = $DB->get_record_sql($sql, $params, IGNORE_MISSING);
        if (!empty($logrecord)) {
            $olddata = json_decode($logrecord->restoredata, true);
            $updateuser = new \stdClass();
            $updateuser->id = $userrecord->id;
            $updateuser->deleted = 0;
            $updateuser->suspended = 0; // Or Moodle won't send emails.
            $updateuser->email = $olddata['email'];
            $updateuser->username = $olddata['username'];
            $updateuser->idnumber = $olddata['idnumber'];
            $updateuser->picture = $olddata['picture'];
            $updateuser->mnethostid = $olddata['mnethostid'];
            $updateuser->timemodified = time();
        }

        if ($updateuser === null) {
            // Fallback method.
            $dotpos = strrpos($userrecord->username, '.');
            $updateuser = new \stdClass();
            $updateuser->id = $userrecord->id;
            $updateuser->deleted = 0;
            $updateuser->suspended = 0; // Or Moodle won't send emails.
            $updateuser->email = substr($userrecord->username, 0, $dotpos);
            $updateuser->username = $updateuser->email;
            $updateuser->timemodified = time();
        }

        // Check if we have a user with either this email or username; this would be an error.
        $checkuser = $DB->get_record('user', ['username' => $updateuser->username, 'deleted' => 0]);
        if ($checkuser !== false) {
            if ($checkuser->id != $updateuser->id) {
                throw new exception('restore:username-exists', '', $checkuser);
            }
        }
        $checkuser = $DB->get_record('user', ['email' => $updateuser->email, 'deleted' => 0]);
        if ($checkuser !== false) {
            if ($checkuser->id != $updateuser->id) {
                throw new exception('restore:email-exists', '', $checkuser);
            }
        }

        user_update_user($updateuser, false);

        // Continue to work with the now changed database record so emailing will not fail.
        $user = $DB->get_record('user', ['id' => $user->id, 'deleted' => 0]);
        // Process email if applicable.
        $emailsent = false;
        if ($sendemail) {
            $emailsent = self::process_user_undeleted_email($user, $emailsubject, $emailbody);
        }

        // Keep a copy of user context, we need it for event.
        $usercontext = \context_user::instance($user->id);
        // Any plugin that needs to do something should register this event.
        // Trigger event.
        $event = event\user_restored::create([
            'objectid' => $userrecord->id,
            'relateduserid' => $userrecord->id,
            'context' => $usercontext,
            'other' => [
                'username' => $updateuser->username,
                'email' => $updateuser->email,
                'mnethostid' => $userrecord->mnethostid,
            ],
        ]);
        $event->add_record_snapshot('user', $userrecord);
        $event->trigger();
        // Create status record.
        self::process_status_record($user, ($emailsent === true));
    }

    /**
     * Process a status record.
     * This will insert a new status record and move all existing status records for the given user to the logs.
     *
     * @param \stdClass $user user record
     * @param bool $emailsent whether or not the email was sent
     */
    final public static function process_status_record($user, $emailsent) {
        global $DB;
        // Move existing record to log.
        $recordstolog = $DB->get_records('tool_userrestore_status', ['userid' => $user->id]);
        foreach ($recordstolog as $record) {
            unset($record->id);
            $DB->insert_record('tool_userrestore_log', $record);
        }
        $DB->delete_records('tool_userrestore_status', ['userid' => $user->id]);
        // Insert new record.
        $statusrecord = (object)[
            'userid' => $user->id,
            'restored' => 1,
            'mailsent' => ($emailsent ? 1 : 0),
            'mailedto' => $user->email,
            'timecreated' => time(),
        ];
        $DB->insert_record('tool_userrestore_status', $statusrecord);
    }

    /**
     * Send an e-mail due to a user being restored
     *
     * @param \stdClass $user
     * @param string $subject email subject
     * @param string $body email body
     * @return void
     */
    public static function process_user_undeleted_email($user, $subject = '', $body = '') {
        global $CFG;
        if (empty($body)) {
            return false;
        }
        if (empty($subject)) {
            $subject = get_string('email:user:restore:subject', 'tool_userrestore');
        }
        // Prepare and send email.
        $from = \core_user::get_support_user();
        $find = [];
        $find['{firstname}'] = $user->firstname;
        $find['{lastname}'] = $user->lastname;
        $find['{fullname}'] = fullname($user);
        $find['{username}'] = $user->username;
        $find['{signature}'] = fullname($from);
        $find['{contact}'] = $from->email;
        $loginurl = new \moodle_url($CFG->wwwroot . '/login/index.php', ['username' => $user->username]);
        $find['{loginlink}'] = '<a href="' . $loginurl . '">' . $loginurl . '</a>';

        $messagehtml = str_replace(array_keys($find), array_values($find), $body);
        $messagetext = format_text_email($messagehtml, FORMAT_HTML);
        $rs = email_to_user($user, $from, $subject, $messagetext, $messagehtml);
        return $rs;
    }

    /**
     * Clean history logs (if enabled in global config) older than the configured duration.
     *
     * @return boolean
     */
    public static function clean_logs() {
        global $DB;
        if (!(bool)config::get('enablecleanlogs')) {
            return false;
        }
        $DB->delete_records_select('tool_userrestore_log', 'timecreated < ?', [time() - (int)config::get('cleanlogsafter')]);
        return true;
    }

    /**
     * Print a notification message.
     *
     * @param string $msg the notification message to display
     * @param string $class class or type of message. Please use either 'success', 'warn' or 'error'
     * @return void
     */
    public static function print_notification($msg, $class = 'success') {
        global $OUTPUT;
        $pix = '<img src="' . $OUTPUT->image_url('msg_' . $class, 'tool_userrestore') . '"/>';
        echo '<div class="tool-userrestore-notification-' . $class . '">' . $pix . ' ' . $msg . '</div>';
    }

    /**
     * Returns HTML to display a continue button that goes to a particular URL.
     *
     * @param string|moodle_url $url The url the button goes to.
     * @param string $buttontext the text to show on the button.
     * @return string the HTML to output.
     */
    public static function continue_button($url, $buttontext) {
        global $OUTPUT;
        if (!($url instanceof \moodle_url)) {
            $url = new \moodle_url($url);
        }
        $button = new \single_button($url, $buttontext, 'get');
        $button->class = 'continuebutton';

        return $OUTPUT->render($button);
    }

    /**
     * Create a tab object with a nice image view, instead of just a regular tabobject
     *
     * @param string $id unique id of the tab in this tree, it is used to find selected and/or inactive tabs
     * @param string $pix image name
     * @param string $component component where the image will be looked for
     * @param string|moodle_url $link
     * @param string $text text on the tab
     * @param string $title title under the link, by defaul equals to text
     * @param bool $linkedwhenselected whether to display a link under the tab name when it's selected
     * @return \tabobject
     */
    public static function pictabobject(
        $id,
        $pix = null,
        $component = 'tool_userrestore',
        $link = null,
        $text = '',
        $title = '',
        $linkedwhenselected = false
    ) {
        global $OUTPUT;
        $img = '';
        if (!empty($pix)) {
            $img = '<img src="' . $OUTPUT->image_url($pix, $component) . '"> ';
        }
        return new \tabobject($id, $link, $img . $text, empty($title) ? $text : $title, $linkedwhenselected);
    }

    /**
     * print the tabs for the overview pages.
     *
     * @param array $params basic url parameters
     * @param string $selected id of the selected tab
     */
    public static function print_view_tabs($params, $selected) {
        global $CFG, $OUTPUT;
        $tabs = [];
        // Add restore tab.
        $restore = self::pictabobject(
            'restore',
            'restore',
            'tool_userrestore',
            new \moodle_url('/' . $CFG->admin . '/tool/userrestore/view/restore.php', $params),
            get_string('link:restore', 'tool_userrestore') . ' (' . self::count_users_to_undelete() . ')'
        );
        $tabs[] = $restore;
        // Add logs tabs.
        $logs = self::pictabobject(
            'logs',
            'logs',
            'tool_userrestore',
            new \moodle_url('/' . $CFG->admin . '/tool/userrestore/view/log.php', $params + ['history' => 0]),
            get_string('table:logs', 'tool_userrestore')
        );
        $logs->subtree[] = self::pictabobject(
            'log_latest',
            null,
            'tool_userrestore',
            new \moodle_url('/' . $CFG->admin . '/tool/userrestore/view/log.php', $params + ['history' => 0]),
            get_string('table:log:latest', 'tool_userrestore')
        );
        $logs->subtree[] = self::pictabobject(
            'log_all',
            null,
            'tool_userrestore',
            new \moodle_url('/' . $CFG->admin . '/tool/userrestore/view/log.php', $params + ['history' => 1]),
            get_string('table:log:all', 'tool_userrestore')
        );
        $tabs[] = $logs;
        // Add reset tab.
        $reset = self::pictabobject(
            'reset',
            null,
            'tool_userrestore',
            new \moodle_url('/' . $CFG->admin . '/tool/userrestore/view/reset.php', $params),
            get_string('link:reset', 'tool_userrestore')
        );
        $tabs[] = $reset;
        echo $OUTPUT->tabtree($tabs, $selected);
    }
}
