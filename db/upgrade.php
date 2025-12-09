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
 * Upgrade script for tool_userrestore.
 *
 * File         upgrade.php
 * Encoding     UTF-8
 *
 * @package     tool_userrestore
 *
 * @copyright   Sebsoft.nl
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrade the plugin.
 *
 * @copyright   Sebsoft.nl
 * @author      RvD <helpdesk@sebsoft.nl>
 *
 * @param int $oldversion the version we are upgrading from
 * @return bool always true
 */
function xmldb_tool_userrestore_upgrade(int $oldversion): bool {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2025120100) {
        $table = new xmldb_table('tool_userrestore_data');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('refid', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, null, 'id');
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, null, 'refid');
        $table->add_field('restoredata', XMLDB_TYPE_TEXT, 'medium', null, XMLDB_NOTNULL, null, null, 'userid');
        $table->add_field('usercreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'restoredata');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null, 'usercreated');
        // Add keys.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        // Add indices.
        $table->add_index('idx-refid', XMLDB_INDEX_NOTUNIQUE, ['refid']);
        $table->add_index('idx-userid', XMLDB_INDEX_NOTUNIQUE, ['userid']);
        $table->add_index('idx-usercreated', XMLDB_INDEX_NOTUNIQUE, ['usercreated']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        // Block_coupon savepoint reached.
        upgrade_plugin_savepoint(true, 2025120100, 'tool', 'userrestore');
    }

    return true;
}
