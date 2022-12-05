Version 3.6.3 (build 2019060103)
* Removed "LIMIT 1" and replaced with IGNORE_MISSING in util.php (DB engine compatibility).

----------

Version 3.6.2 (build 2019060102)
* Unserialsation of event logs now checks logstore settings for JSON serialisation or json encoded data.
* Added setting to enable or disable the user_deleted observer due to the fact bulk deletion is severely impacted by this.
  The default is now <i>not</i> to use the observer.
* Changed default cachefill task to run every 10 minutes by default.
* Changed mimimal Moodle version to 3.7
* Added limited cachefill (10 records at once)

----------

Version 3.6.1 (build 2019060101)
* Updated privacy provider.
----------

Version 3.6.0 (build 2019060100)
* Added caching to speed up overviews
----------

Version 3.5.0 (build 2018050300)
* Added privacy provider
* Validated functionality for Moodle 3.5 onwards
* Minimum required Moodle version: 3.5
----------

Version 3.3.0 (build 2017092500)
* Fixed deprecated pix_url references (replaced y image_url)
* Added setting for maximum number of users to display on restore.php / restore form
* Added paginag bar on restore.php
* Validated functionality for Moodle 3.3 onwards
* Minimum required Moodle version: 3.3
----------

Version 3.0.0 (build 2017050100)
* Code overhaul to comply to Moodle standards
* Validated functionality for Moodle 3.0 onwards
* Minimum required Moodle version: 3.0
