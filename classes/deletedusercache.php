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
 * this file contains the tool specific cache.
 *
 * File         deletedcache.php
 * Encoding     UTF-8
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_userrestore;

defined('MOODLE_INTERNAL') || die;

/**
 * tool_userrestore\deletedusercache
 *
 * @package     tool_userrestore
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class deletedusercache {

    /**
     * @var string
     */
    const CACHE_STORE = 'deletedusercache';

    /**
     * @var string
     */
    const KEY_USER_DELETED = 'udeleted';

    /**
     * Get deleted user info.
     *
     * @param int $userid
     * @return stdClass
     */
    public static function get_info($userid) {
        return self::get_deleted_userinfo($userid);
    }

    /**
     * Reaplce or insert deleted user info.
     *
     * @param int $userid
     * @return stdClass
     */
    public static function replace_info($userid) {
        return self::set_deleted_userinfo($userid, self::load_deleted_userinfo($userid));
    }

    /**
     * Get deleted user info.
     *
     * @param int $userid
     * @return stdClass
     */
    protected static function get_deleted_userinfo($userid) {
        $cache = \cache::make('tool_userrestore', self::CACHE_STORE);
        $key = self::KEY_USER_DELETED . $userid;

        if ($cache->has($key)) {
            return $data = $cache->get($key);
        } else {
            return self::set_deleted_userinfo($userid, self::load_deleted_userinfo($userid));
        }
        return null;
    }

    /**
     * Set deleted user info
     *
     * @param int $userid
     * @param stdClass $info
     * @return user record
     */
    protected static function set_deleted_userinfo($userid, $info) {
        $cache = \cache::make('tool_userrestore', self::CACHE_STORE);
        $key = self::KEY_USER_DELETED . $userid;
        $data = [
            'ud_timecreated' => time(),
            'ud_record' => $info
        ];
        $cache->set($key, $data);
        return $data;
    }

    /**
     * Load deleted user info
     *
     * @param int $userid
     * @return user record
     */
    protected static function load_deleted_userinfo($userid) {
        global $DB;
        $fields = 'id, username, email, ' . get_all_user_name_fields(true) . ', timemodified';
        $user = $DB->get_record('user', ['id' => $userid], $fields);
        $reference = [$user];
        util::convert_undelete_users($reference);
        util::append_deleted_users_loginfo($reference);

        return reset($reference);
    }

    /**
     * Refill complete cache.
     *
     * @param bool $smartfill if true, the cache will not be purged in advance and we'll only add missing info.
     */
    public static function fill_cache($smartfill = false) {
        global $DB, $CFG;
        // Clear cache.
        $cache = \cache::make('tool_userrestore', self::CACHE_STORE);
        if (!$smartfill) {
            $cache->purge();
        }
        // Load userids.
        $noids = array_keys(get_admins());
        $noids[] = $CFG->siteguest;
        list($notinsql, $params) = $DB->get_in_or_equal($noids, SQL_PARAMS_NAMED, 'uid', false, 0);
        $params['mnethostid'] = $CFG->mnet_localhost_id;
        $select = 'deleted = 1 AND confirmed = 1 AND mnethostid = :mnethostid AND id '.$notinsql;
        $userids = $DB->get_fieldset_select('user', 'id', $select, $params);
        if (empty($userids)) {
            return true;
        }
        // And load data.
        foreach ($userids as $userid) {
            $key = self::KEY_USER_DELETED . $userid;
            if ($smartfill && $cache->has($key)) {
                continue;
            }
            self::set_deleted_userinfo($userid, self::load_deleted_userinfo($userid));
        }
    }

    /**
     * Validate if the cache is "complete".
     *
     * @return bool
     */
    public static function has_all_entries() {
        global $DB, $CFG;
        $cache = \cache::make('tool_userrestore', self::CACHE_STORE);
        // Load userids.
        $noids = array_keys(get_admins());
        $noids[] = $CFG->siteguest;
        list($notinsql, $params) = $DB->get_in_or_equal($noids, SQL_PARAMS_NAMED, 'uid', false, 0);
        $params['mnethostid'] = $CFG->mnet_localhost_id;
        $select = 'deleted = 1 AND confirmed = 1 AND mnethostid = :mnethostid AND id '.$notinsql;
        $userids = $DB->get_fieldset_select('user', 'id', $select, $params);
        if (empty($userids)) {
            return true;
        }
        // And validate data.
        foreach ($userids as &$key) {
            $key = self::KEY_USER_DELETED . $key;
        }
        return $cache->has_all($userids);
    }

    /**
     * Purge.
     */
    public static function purge() {
        $cache = \cache::make('tool_userrestore', self::CACHE_STORE);
        $cache->purge();
    }

}