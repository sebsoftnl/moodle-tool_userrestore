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
 * general global tool settings
 *
 * File         settings.php
 * Encoding     UTF-8
 *
 * @package     tool_userrestore
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * */
defined('MOODLE_INTERNAL') || die('moodle_internal not defined');

if ($hassiteconfig) {
    $temp = new admin_settingpage('restoresettings', new lang_string('restoresettings', 'tool_userrestore'));
    // Header.
    $image = '<a href="http://www.sebsoft.nl" target="_new"><img src="' .
            $OUTPUT->pix_url('logo', 'tool_userrestore') . '" /></a>&nbsp;&nbsp;&nbsp;';
    $donate = '<a href="https://customerpanel.sebsoft.nl/sebsoft/donate/intro.php" target="_new"><img src="' .
            $OUTPUT->pix_url('donate', 'tool_userrestore') . '" /></a>';
    $header = '<div class="tool-userrestore-logopromo">' . $image . $donate . '</div>';
    $temp->add(new admin_setting_heading('tool_userrestore_logopromo',
            get_string('promo', 'tool_userrestore'),
            get_string('promodesc', 'tool_userrestore', $header)));

    // Default settings.
    $temp->add(new admin_setting_heading('tool_userrestore_restoresettings',
            get_string('restoresettings', 'tool_userrestore'),
            get_string('restoresettingsdesc', 'tool_userrestore')));

    $temp->add(new admin_setting_configcheckbox('tool_userrestore/enablecleanlogs',
            get_string('setting:enablecleanlogs', 'tool_userrestore'),
            get_string('setting:desc:enablecleanlogs', 'tool_userrestore'),
            '1', '1', '0'));
    $temp->add(new admin_setting_configduration('tool_userrestore/cleanlogsafter',
            get_string('setting:cleanlogsafter', 'tool_userrestore'),
            get_string('setting:desc:cleanlogsafter', 'tool_userrestore'),
            70 * 86400, 86400));

    $ADMIN->add('tools', $temp);
}

$ADMIN->add('accounts', new admin_externalpage('tooluserrestore', get_string('pluginname', 'tool_userrestore'),
    "{$CFG->wwwroot}/{$CFG->admin}/tool/userrestore/view/restore.php", 'moodle/user:update'
));