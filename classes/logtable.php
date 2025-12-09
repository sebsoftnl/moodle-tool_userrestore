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
 * this file contains the status table class to display certain users statusses.
 *
 * File         logtable.php
 *
 * @package     tool_userrestore
 *
 * Encoding     UTF-8
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_userrestore;

defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir . '/tablelib.php');

/**
 * tool_userrestore\logtable
 *
 * @package     tool_userrestore
 *
 * @copyright   Sebsoft.nl
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class logtable extends \table_sql {
    /**
     * Do we render the history or the current status?
     *
     * @var bool
     */
    protected $showhistory;

    /**
     * Create a new instance of the logtable
     *
     * @param bool $showhistory if true, shows historic statusses. If false shows current statusses
     */
    public function __construct($showhistory = true) {
        global $USER;
        parent::__construct(__CLASS__ . '-' . $USER->id . ((int)$showhistory));
        $this->showhistory = (bool)$showhistory;
    }

    /**
     * Set the sql to query the db.
     * This method is disabled for this class, since we use internal queries
     *
     * @param string $fields
     * @param string $from
     * @param string $where
     * @param array|null $params
     * @throws exception
     */
    public function set_sql($fields, $from, $where, ?array $params = null) {
        // We'll disable this method.
        throw new exception('err:statustable:set_sql');
    }

    /**
     * Display the general status log table.
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     */
    public function render_log($pagesize, $useinitialsbar = true) {
        global $DB;
        $this->define_columns(['userid', 'name', 'restored', 'mailsent', 'mailedto', 'timecreated']);
        $this->define_headers([
            get_string('th:userid', 'tool_userrestore'),
            get_string('th:name', 'tool_userrestore'),
            get_string('th:restored', 'tool_userrestore'),
            get_string('th:mailsent', 'tool_userrestore'),
            get_string('th:mailedto', 'tool_userrestore'),
            get_string('th:timecreated', 'tool_userrestore'),
        ]);
        $fields = 'l.id,l.userid,' . $DB->sql_fullname('u.firstname', 'u.lastname') .
                ' AS name,l.restored,l.mailsent,l.mailedto,l.timecreated,NULL AS action';
        $table = ($this->showhistory ? 'tool_userrestore_log' : 'tool_userrestore_status');
        $from = '{' . $table . '} l LEFT JOIN {user} u ON l.userid=u.id';
        $where = '1 = 1';
        $params = [];
        parent::set_sql($fields, $from, $where, $params);
        $this->out($pagesize, $useinitialsbar);
    }

    /**
     * Render visual representation of the 'timecreated' column for use in the table
     *
     * @param \stdClass $row
     * @return string time string
     */
    public function col_timecreated($row) {
        return userdate($row->timecreated);
    }

    /**
     * Render visual representation of the 'action' column for use in the table
     *
     * @param \stdClass $row
     * @return string actions
     */
    public function col_action($row) {
        $actions = [];
        return implode('', $actions);
    }
}
