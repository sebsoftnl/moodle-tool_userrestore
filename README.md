# SEBSOFT USERRESTORE PLUGIN

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

# Version 4.0.0

Version 4 onwards brings a new strategy. Where version 3 used a cache which was extracted from the logstore,
version 4 uses it's own table to store restorable user data. The main (and really, only) reason for this change
is that the logstore is _incredibly_ slow on MariaDB/MySQL when the dataset is "large".
Version 4 therefore hooks into the user_deleted events to store restorable data.
Version 4 also fetches it's initial data from the logstore (using a background task).
All data can _still_ be reset. The aforementioned background task will then perform a full run
again, determining all restorable data again (of course the amount of restorable data depends on
_cleaning_ settings of the logstore. Once the logstore is cleaned, we no longer have this data available).

# Important note

Moodle removes "ALL" relevant data upon deleting a user account, and only the record in the
user table itself remains (or so it should be).
This utility can't do anything about this and is only capable of restoring the record from the user table.

Having said this, this is really not entirely true.
Plugins are supposed to either hook into the user_deleted event (older code) or implement
a "pre_user_delete()" hook.
Even Moodle _itself_ does not clean all user data upon removal (this is especially true for activity modules).
Multiple forum threads and issues exist, but more information can be found e.g.:
- <https://moodle.org/mod/forum/discuss.php?d=424058>
- <https://moodle.org/mod/forum/discuss.php?d=376103>

# Beneficial feature

Taking the above into account, this tool also happens to have the added feature of being able to undelete a user
and investigate the information that was never deleted upon user deletion (using the privacy API).
Please be aware that actual implementation of the privacy API is required to be
implemented by plugins to be able to see the remaining user data.
If a plugin does _not_ (correctly or fully) implement the privacy API, it's not
visible from the Moodle interface that a specific scope may still contain user data.

# INSTALLATION

- Copy the userrestore folder to your admin/tool directory.
- Configure your admin tool.
- We're ready to run!
