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
 * Privacy provider.
 *
 * File         provider.php
 * Encoding     UTF-8
 *
 * @package     tool_userrestore
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_userrestore\privacy;

defined('MOODLE_INTERNAL') || die;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\transform;
use core_privacy\local\request\writer;

/**
 * Privacy provider.
 *
 * @package     tool_userrestore
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
        \core_privacy\local\metadata\provider,
        \core_privacy\local\request\plugin\provider {

    /**
     * Provides meta data that is stored about a user with tool_userrestore
     *
     * @param  collection $collection A collection of meta data items to be added to.
     * @return  collection Returns the collection of metadata.
     */
    public static function get_metadata(collection $collection) : collection {
        $collection->add_database_table(
            'tool_userrestore_status',
            [
                'userid' => 'privacy:metadata:tool_userrestore:userid',
                'restored' => 'privacy:metadata:tool_userrestore:restored',
                'mailsent' => 'privacy:metadata:tool_userrestore:mailsent',
                'mailedto' => 'privacy:metadata:tool_userrestore:mailedto',
                'timecreated' => 'privacy:metadata:tool_userrestore:timecreated',
            ]
        );
        $collection->add_database_table(
            'tool_userrestore_log',
            [
                'userid' => 'privacy:metadata:tool_userrestore:userid',
                'restored' => 'privacy:metadata:tool_userrestore:restored',
                'mailsent' => 'privacy:metadata:tool_userrestore:mailsent',
                'mailedto' => 'privacy:metadata:tool_userrestore:mailedto',
                'timecreated' => 'privacy:metadata:tool_userrestore:timecreated',
            ]
        );
        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param   int           $userid       The user to search.
     * @return  contextlist   $contextlist  The list of contexts used in this plugin.
     */
    public static function get_contexts_for_userid(int $userid) : contextlist {
        $contextlist = new \core_privacy\local\request\contextlist();
        // Since this system works on a global level (it hooks into the authentication system), the only context is CONTEXT_SYSTEM.
        $contextlist->add_system_context();
        return $contextlist;
    }

    /**
     * Export all user data for the specified user, in the specified contexts, using the supplied exporter instance.
     *
     * @param   approved_contextlist    $contextlist    The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;
        if (empty($contextlist->count())) {
            return;
        }
        $user = $contextlist->get_user();

        foreach ($contextlist->get_contexts() as $context) {
            if ($context->contextlevel != CONTEXT_SYSTEM) {
                continue;
            }
            $contextid = $context->id;
            // Add suspension status records.
            $sql = "SELECT ss.* FROM {tool_userrestore_status} ss WHERE ss.userid = :userid";
            $params = ['userid' => $user->id];
            $alldata = [];
            $statuses = $DB->get_recordset_sql($sql, $params);
            foreach ($statuses as $status) {
                $alldata[$contextid][] = (object)[
                        'userid' => $status->userid,
                        'restored' => transform::yesno($status->restored),
                        'mailsent' => transform::yesno($status->mailsent),
                        'mailedto' => $status->mailedto,
                        'timecreated' => transform::datetime($status->timecreated),
                    ];
            }
            $statuses->close();

            // The data is organised in: {? }/statuses.json.
            array_walk($alldata, function($statusdata, $contextid) {
                $context = \context::instance_by_id($contextid);
                writer::with_context($context)->export_related_data(
                    ['tool_userrestore'],
                    'statuses',
                    (object)['status' => $statusdata]
                );
            });

            // Add suspension log records.
            $sql = "SELECT ul.* FROM {tool_userrestore_log} ul WHERE ul.userid = :userid";
            $params = ['userid' => $user->id];
            $alldata = [];
            $statuslogs = $DB->get_recordset_sql($sql, $params);
            foreach ($statuslogs as $statuslog) {
                $alldata[$contextid][] = (object)[
                        'userid' => $statuslog->userid,
                        'restored' => transform::yesno($status->restored),
                        'mailsent' => transform::yesno($status->mailsent),
                        'mailedto' => $statuslog->mailedto,
                        'timecreated' => transform::datetime($statuslog->timecreated),
                    ];
            }
            $statuslogs->close();

            // The data is organised in: {?}/statuslogs.json.
            // where X is the attempt number.
            array_walk($alldata, function($statuslog, $contextid) {
                $context = \context::instance_by_id($contextid);
                writer::with_context($context)->export_related_data(
                    ['tool_userrestore'],
                    'statuslogs',
                    (object)['statuslog' => $statuslog]
                );
            });
        }
    }

    /**
     * Delete all use data which matches the specified context.
     *
     * @param context $context The module context.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;
        if ($context->contextlevel != CONTEXT_SYSTEM) {
            return;
        }

        // Delete hammering records.
        $DB->delete_records('tool_userrestore_status');
        // Delete log records.
        $DB->delete_records('tool_userrestore_log');
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts and user information to delete information for.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist->count())) {
            return;
        }

        foreach ($contextlist->get_contexts() as $context) {
            if ($context->contextlevel != CONTEXT_SYSTEM) {
                continue;
            }

            $user = $contextlist->get_user();
            // Delete hammering records.
            $DB->delete_records('tool_userrestore_status', ['userid' => $user->id]);
            // Delete log records.
            $DB->delete_records('tool_userrestore_log', ['userid' => $user->id]);
        }
    }
}
