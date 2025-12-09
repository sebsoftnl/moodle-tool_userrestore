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
 * this file contains the task to fill the deleted userinfo data.
 *
 * File         fillrestoredata.php
 * Encoding     UTF-8
 *
 * @package     tool_userrestore
 *
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_userrestore\task;

/**
 * Description of fillrestoredata
 *
 * @package     tool_userrestore
 *
 * @copyright   Sebsoft.nl
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class fillrestoredata extends \core\task\scheduled_task {
    /**
     * Return the localised name for this task
     *
     * @return string task name
     */
    public function get_name() {
        return get_string('task:fillrestoredata', 'tool_userrestore');
    }

    /**
     * Executes the task
     *
     * @return void
     */
    public function execute() {
        global $DB;
        $done = \tool_userrestore\config::get('logstoreread');
        if ($done) {
            return;
        }

        $lastid = \tool_userrestore\config::get('lastlogstoreid');
        if (is_null($lastid)) {
            $lastid = 0;
        }

        // Process per 50 records.
        $limit = 50;
        $offset = 0;

        $sql = 'SELECT id, relateduserid as userid, userid as usercreated, other, timecreated
                FROM {logstore_standard_log}
                WHERE eventname = ? AND id > ?';
        $params = ['\\core\\event\\user_deleted', $lastid];
        $records = $DB->get_records_sql($sql, $params, $offset, $limit);

        foreach ($records as $record) {
            $restoredata = $record->other;
            if (!\tool_userrestore\util::is_jsonformat()) {
                // Convert from serialized to JSON.
                $restoredata = json_encode(unserialize($restoredata));
            }
            $r = $DB->get_record('tool_userrestore_data', ['refid' => $record->id]);
            if ($r) {
                $r->userid = $record->userid;
                $r->restoredata = $restoredata;
                $r->usercreated = $record->usercreated;
                $r->timecreated = $record->timecreated;
                $DB->update_record('tool_userrestore_data', $r);
            } else {
                $r = (object)[
                    'userid' => $record->userid,
                    'refid' => $record->id,
                    'restoredata' => $restoredata,
                    'usercreated' => $record->usercreated,
                    'timecreated' => $record->timecreated,
                ];
                $DB->insert_record('tool_userrestore_data', $r);
            }
            $lastid = $record->id;
        }

        // Set last ID.
        \tool_userrestore\config::set('lastlogstoreid', $lastid, true);
        // And mark done if so.
        if (count($records) < $limit) {
            // Mark done.
            \tool_userrestore\config::set('logstoreread', 1, true);
            // And disable task.
            $DB->execute(
                'UPDATE {task_scheduled} SET disabled = 1 WHERE classname = ?',
                ['\\tool_userrestore\\task\\fillrestoredata']
            );
        }
    }
}
