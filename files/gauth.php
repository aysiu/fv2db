<?php

// Google authentication implementation from http://usefulangle.com/post/9/google-login-api-with-php-curl
// © UsefulAngle.com 2016. All code MIT license. 

ob_start();

session_start();

require_once('config.php');
require_once('google-login-api.php');

include('includes/header_include.php');

// Google passes a parameter 'code' in the Redirect Url
if(isset($_GET['code'])) {
   try {
      $gapi = new GoogleLoginApi();
      
      // Get the access token 
      $data = $gapi->GetAccessToken($CLIENT_ID, $CLIENT_REDIRECT_URL, $CLIENT_SECRET, $_GET['code']);
      
      // Get user information
      $user_info = $gapi->GetUserProfileInfo($data['access_token']);
      
      // Get specifically the email address
      $user_email = strtolower(trim($user_info[emails][0]["value"]));

      // See if this email is in the list of authorized users
      require('includes/database_connect.php');
      
      $stmt=mysqli_prepare($dbc, "SELECT email FROM users WHERE email=? LIMIT 1");
      mysqli_stmt_bind_param($stmt, 's', $user_email);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $user_email_temp);
      $user_email_test='';
      while(mysqli_stmt_fetch($stmt)){
         $user_email_test=$user_email_temp;
      }
      mysqli_stmt_close($stmt);

      if($user_email_test!=''){

         // Now that the user is logged in you may want to start some session variables
         $_SESSION['logged_in'] = 1;
         $_SESSION['email'] = $user_email;

         // You may now want to redirect the user to the home page of your website
         header('Location: index.php');

      } else {
         
         echo '<div class="error">You are not authorized to log into the FileVault database.</div>
         <p><a href="login.php">Return to login screen?</a></p>';
      
      }
      
      //echo '<pre>';print_r($user_info); echo '</pre>';
   
   }
   catch(Exception $e) {
      echo $e->getMessage();
      exit();
   }
}

ob_flush();

?>