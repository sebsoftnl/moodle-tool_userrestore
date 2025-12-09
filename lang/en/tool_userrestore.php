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
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['button:backtoform'] = 'Back to userrestore form';
$string['button:userrestore:continue'] = 'Restore users';
$string['config:cleanlogs:disabled'] = 'Automatic logcleaning is disabled in global configuration';
$string['deletedby'] = 'Deleted by';
$string['email:user:restore:body'] = '<p>Dear {fullname}</p>
<p>Your account has been restored</p>
<p>However, your username may not have been retrieved correctly due to how moodle handles user deletion
and which data was present at the time of deletion. Your username is {username}</p>
<p>If you feel this is unintended or have any questions,
please contact {contact}</p>
<p>You should be able to use your old password to log in to the site.<br/>
If not, use the email address to which this email was sent to request a password reset.<br/>
Please log in to the site to re-enter all your information using the link below:<br/>
{loginlink}</p>
<p>Regards<br/>{signature}</p>';
$string['email:user:restore:subject'] = 'Your account has been restored';
$string['event:user:restored'] = 'User restored';
$string['form:label:email'] = 'E-mail body';
$string['form:label:sendmail'] = 'Send E-mail';
$string['form:label:subject'] = 'E-mail subject';
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
$string['hasloginfo'] = 'Has log info?';
$string['label:users:potential'] = 'Potential users';
$string['link:reset'] = 'Reset';
$string['link:restore'] = 'Restore users';
$string['msg:no-users-to-restore'] = 'There are no deleted user accounts found to restore.';
$string['notice:readprogress'] = 'Logstore reading has not completely finished yet, so not all userdata may be available. Current read progress: {$a->percentage}% (of {$a->total} records).';
$string['page:view:log.php:introduction'] = 'The table below shows the logs of statusses that users have been restored.';
$string['page:view:restore.php:introduction'] = 'This form enables you to select users to restore and optionally send them an
email about their user accounts being restored. Please note, in the table below the username and emailaddress represents the original
information of the user as retreived from the event log records.';
$string['pluginname'] = 'User Restore';
$string['privacy:metadata:tool_userrestore:mailedto'] = 'E-mail address of the restored user';
$string['privacy:metadata:tool_userrestore:mailsent'] = 'Whether or not an email has been sent';
$string['privacy:metadata:tool_userrestore:restored'] = 'Whether or not the account was restored';
$string['privacy:metadata:tool_userrestore:restoredata'] = 'Account restore data';
$string['privacy:metadata:tool_userrestore:timecreated'] = 'Time the record was created.';
$string['privacy:metadata:tool_userrestore:userid'] = 'The primary key of the Moodle user for which account has been restored.';
$string['privacy:metadata:tool_userrestore_log'] = 'The userrestore log contains historic/logging information about restored users';
$string['privacy:metadata:tool_userrestore_status'] = 'The userrestore status contains information about restored users';
$string['privacy:metadata:tool_userrestore_data'] = 'The userrestore data contains information about deleted users';
$string['promo'] = 'userrestore plugin for Moodle';
$string['promodesc'] = 'This plugin is written by Sebsoft Managed Hosting &amp; Software Development
    (<a href=\'https://www.sebsoft.nl/\' target=\'_new\'>https://sebsoft.nl</a>).<br /><br />
    {$a}<br /><br />';
$string['reset'] = 'Reset';
$string['restore:deleted-user-not-found'] = 'Can\'t restore user: deleted user was not found';
$string['restore:email-exists'] = 'Can\'t restore user: email address \'{$a->email}\' already exists for a different active user';
$string['restore:username-exists'] = 'Can\'t restore user: username \'{$a->username}\' already exists for a different active user';
$string['restore:user-mnet-not-local'] = 'Can\'t restore user: mnet host for user to be restored is not the configured local mnet host';
$string['restore:user-restored'] = 'User <i>\'{$a->username}\'</i> (\'{$a->email}\') was successfully restored';
$string['restoresettings'] = 'User Restore Settings';
$string['restoresettingsdesc'] = '';
$string['result:config:reset'] = 'User restore config was reset.';
$string['result:data:reset'] = 'User restore user data was reset.';
$string['setting:desc:cleanlogsafter'] = 'Configure the frequency after which logs are cleaned. Any logs older than this setting will physically be removed.';
$string['setting:desc:enablecleanlogs'] = 'Enables or disables automatic cleaning of the history log.';
$string['setting:desc:maxrestoreusers'] = 'This sets the maximum number of users displayed on the restore users form.';
$string['setting:desc:undeletetrackedonly'] = 'This marks whether we only restore for users with tracked data or for all users. Tracked data means we have restore information available from either the logstore or from tracked events';
$string['setting:cleanlogsafter'] = 'Clean logs frequency';
$string['setting:enablecleanlogs'] = 'Enable logcleaning';
$string['setting:maxrestoreusers'] = 'Maximum restore users';
$string['setting:undeletetrackedonly'] = 'Restore only users with available/tracked restore data';
$string['table:log:all'] = 'Historic restore log';
$string['table:log:latest'] = 'Latest restore logs';
$string['table:logs'] = 'Logs';
$string['task:fillrestoredata'] = 'Fill user restore data';
$string['task:logclean'] = 'Clean logs for user restore';
$string['th:action'] = 'Action';
$string['th:mailedto'] = 'E-mailed to';
$string['th:mailsent'] = 'E-mail sent';
$string['th:name'] = 'Name';
$string['th:restored'] = 'Restored';
$string['th:timecreated'] = 'Created on';
$string['th:userid'] = 'User ID';
$string['timedeleted'] = 'Deleted at';
$string['userrestore:administration'] = 'User restored administration';
$string['userrestore:reset'] = 'This page allows you to reset the user restore data.<br/>';
$string['userrestore:reset:config'] = 'Reset configuration data';
$string['userrestore:reset:config_help'] = 'This option resets the state of the read data from the logstore. Effectively, this means all logs will be re-read';
$string['userrestore:reset:data'] = 'Reset user data';
$string['userrestore:reset:data_help'] = 'This option removes all known (cached) user data.';
$string['userrestore:reset:warn'] = 'Please be aware that if the Moodle logs are periodically cleaned up, chances are that this may also remove restorable user data.';
$string['userrestore:viewstatus'] = 'View userrestore status';
