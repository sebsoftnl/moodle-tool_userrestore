
SEBSOFT USERRESTORE PLUGIN

The Sebsoft User Restore Plugin offers you the possibility to restore user accounts
that were deleted from moodle.

When the plugin has been installed, you can select a number of user accounts to restore and optionally
send an email informing them their accounts have been restored.

This plugin takes the following into account:
- Users to be restored are checked on e-mail:
  if an e-mail address already exists for an active account, this user will not be restored.
- Users to be restored are checked on username:
  if an username already exists for an active account, this user will not be restored.
- Users to be restored are checked on MNET host:
  if the MNET host of the account to be restored is not the locally configured one, this user will not be restored.

Important note:
Moodle removes ALL relevant data upon deleting a user account, and only the record in the
user table itself remains. This utility can't do anything about this and is only capable of restoring the
record from the user table.

Important note for moodle 2.7 and up:
Before Moodle 2.7 there is NO way we can retrieve all information. However, with Moodle 2.7 and the new
event logging tables, the original user information is stored in the event data. Therefore, from Moodle 2.7
onwards, this plugin will try and restore the original user information from there.
This effectively means, that from Moodle 2.7 onwards, we will have the correct original username, email,
idnumber, picture and mnethostid. Before that, only the email could  be restored, and even that method is
not foolproof (due to the fact the username is cleaned with PARAM_USERNAME upon deletion).

INSTALLATION

- Copy the userrestore folder to your admin/tool directory.
- Configure your admin tool.
- We're ready to run!
