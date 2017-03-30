<?php

require_once('config.php');

include('includes/header_include.php');

// Google authentication implementation from http://usefulangle.com/post/9/google-login-api-with-php-curl
// © UsefulAngle.com 2016. All code MIT license. 

// Show the Google+ login button
echo '<a href="https://accounts.google.com/o/oauth2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode($CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . $CLIENT_ID . '&access_type=online' . '"><img src="sign-in-button.png" border="0" width="300"></a>';

include('includes/footer.php');

?>