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
 * observer.
 *
 * File         observer.php
 * Encoding     UTF-8
 *
 * @package     tool_userrestore
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_userrestore;

use core\event\course_module_viewed;
use core\event\user_loggedin;
use core\event\course_module_completion_updated;
use tool_userrestore\caches\userstatistics;

defined('MOODLE_INTERNAL') || die();

/**
 * tool_userrestore\observer
 *
 * @package     tool_userrestore
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class observer {

    /**
     * User deleted handler storing the info in cache
     *
     * @param \core\event\user_deleted $event
     */
    public static function user_deleted(\core\event\user_deleted $event) {
        global $CFG;
        if ($event->other['mnethostid'] != $CFG->mnet_localhost_id) {
            // Remote user: do not store.
            return;
        }
        $noids = array_keys(get_admins());
        $noids[] = $CFG->siteguest;
        if (in_array($event->relateduserid, $noids)) {
            // Guest or admin user: do not store.
            return;
        }
        // Store info in cache.
        deletedusercache::replace_info($event->relateduserid);
    }

}
