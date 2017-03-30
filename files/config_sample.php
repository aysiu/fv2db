<?php

// MySQL database stuff
$DB_NAME='NAMEOFYOURMYSQLDATABASE';    // The name of the database
$DB_USER='MYSQLUSERNAME';     // Your MySQL username
$DB_PASSWORD='MYSQLPASSWORD'; // ...and password
// You probably won't need to modify this, but you can
$DB_HOST='localhost';

/* Page title
This is what shows up in the browser tab */
$page_title='FileVault Database';

/* Google Apps domain name
Sanity check for adding authorized users
Leave this array empty if you don't want a sanity check */
$approved_domains=array('YOURGOOGLEDOMAIN.COM', 'YOUROTHERGOOGLEDOMAIN.COM');

// Google+ API stuff for logging into the FileVault database

/* Google App Client Id */
$CLIENT_ID='GOOGLEAPPCLIENTID';

/* Google App Client Secret */
$CLIENT_SECRET='GOOGLEAPPCLIENTSECRET';

/* What's the full path to where you're hosting this? */
$MAIN_SITE='https://YOURFILEVAULTMAINSITE.com';





/////////// Don't edit anything below this line
// Check if there is a slash is missing from the end of the main site address
if(substr($MAIN_SITE,-1)!='/'){
   // If it's missing, add it back in
   $MAIN_SITE.='/';
}
// Create the client redirect URL based on the main site and the gauth.php file
$CLIENT_REDIRECT_URL=$MAIN_SITE . 'gauth.php';

?>