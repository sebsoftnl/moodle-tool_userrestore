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
 * Language file for tool_userrestore, EN
 *
 * File         tool_userrestore.php
 * Encoding     UTF-8
 *
 * @package     tool_userrestore
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['pluginname'] = 'User Restore';

$string['promo'] = 'userrestore plugin for Moodle';
$string['promodesc'] = 'This plugin is written by Sebsoft Managed Hosting & Software Development
    (<a href=\'http://www.sebsoft.nl/\' target=\'_new\'>http://sebsoft.nl</a>).<br /><br />
    {$a}<br /><br />';

$string['timedeleted'] = 'Deleted at';
$string['deletedby'] = 'Deleted by';
$string['hasloginfo'] = 'Has log info?';

$string['link:restore'] = 'Restore users';
$string['link:log'] = 'Restore users logs';
$string['link:viewstatus'] = 'View statuslist';
$string['link:log:overview'] = 'View status change logs';
$string['link:currentstatus:overview'] = 'View current status changes';

$string['form:label:sendmail'] = 'Send E-mail';
$string['form:label:email'] = 'E-mail body';
$string['form:label:subject'] = 'E-mail subject';
$string['button:userrestore:continue'] = 'Restore users';
$string['form:static:email:template'] = 'You can use the following template strings in your email.
They will automatically be replaced with the correct variables. Please use the exactly as indicated, or the result might be unexpected.
<ul>
<li>{firstname} - First name of the user that\'s restored</li>
<li>{lastname} - Last name of the user that\'s restored</li>
<li>{fullname} - Full name of the user that\'s restored</li>
<li>{username} - Restored user\'s username (this MAY be different from before the account was deleted)</li>
<li>{signature} - Signature (full name of the support user for the moodle site)</li>
<li>{contact} - Contact emailaddress (based on the support user for the moodle site)</li>
<li>{loginlink} - Login link for the site (based on restored username)</li>
</ul>
';

$string['restoresettings'] = 'User Restore Settings';
$string['restoresettingsdesc'] = '';
$string['setting:enablecleanlogs'] = 'Enable logcleaning';
$string['setting:desc:enablecleanlogs'] = 'Enables or disables automatic cleaning of the history log.';
$string['setting:cleanlogsafter'] = 'Clean logs frequency';
$string['setting:desc:cleanlogsafter'] = 'Configure the frequency after which logs are cleaned. Any logs older than this setting will physically be removed.';
$string['config:cleanlogs:disabled'] = 'Automatic logcleaning is disabled in global configuration';

$string['page:view:restore.php:introduction'] = 'This form enables you to select users to restore and optionally send them an
email about their user accounts being restored. Please note, in the table below the username and emailaddress represents the original
information of the user as retreived from the event log records.';
$string['page:view:log.php:introduction'] = 'The table below shows the logs of statusses that users have been restored.';

$string['config:tool:disabled'] = 'User Restore Utility is disabled in global tool configuration';

$string['err:statustable:set_sql'] = 'set_sql() is disabled. This table defines it\'s own and is not customomizable';
$string['label:users:potential'] = 'Potential users';
$string['restore:username-exists'] = 'Can\'t restore user: username \'{$a->username}\' already exists for a different active user';
$string['restore:email-exists'] = 'Can\'t restore user: email address \'{$a->email}\' already exists for a different active user';
$string['restore:user-mnet-not-local'] = 'Can\'t restore user: mnet host for user to be restored is not the configured local mnet host';
$string['restore:user-restored'] = 'User <i>\'{$a->username}\'</i> (\'{$a->email}\') was successfully restored';
$string['restore:deleted-user-not-found'] = 'Can\'t restore user: deleted user was not found';

$string['th:userid'] = 'User ID';
$string['th:name'] = 'Name';
$string['th:restored'] = 'Restored';
$string['th:mailsent'] = 'E-mail sent';
$string['th:mailedto'] = 'E-mailed to';
$string['th:timecreated'] = 'Created on';
$string['th:action'] = 'Action';

$string['button:backtocourse'] = 'Back to course';
$string['button:backtoform'] = 'Back to userrestore form';

$string['email:user:restore:subject'] = 'Your account has been restored';
$string['email:user:restore:body'] = '<p>Dear {fullname}</p>
<p>Your account has been restored</p>
<p>However, your username may not have been retrieved correctly due to how moodle handles user deletion
and whether or not event logs have been cleaned. Your username is {username}</p>
<p>If you feel this is unintended or have any questions,
please contact {contact}</p>
<p>You should be able to use your old password to log in to the site.<br/>
If not, use the email address to which this email was sent to request a password reset.<br/>
Please log in to the site to re-enter all your information using the link below:<br/>
{loginlink}</p>
<p>Regards<br/>{signature}</p>';
$string['table:logs'] = 'Logs';
$string['table:log:all'] = 'Historic restore log';
$string['table:log:latest'] = 'Latest restore logs';
$string['task:logclean'] = 'Clean logs for user restore';
$string['msg:no-users-to-restore'] = 'There are no deleted user accounts found to restore.';