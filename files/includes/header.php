<?php

session_start();

// Make sure the user is logged in
if((!isset($_SESSION['logged_in'])) OR ($_SESSION['logged_in']!=1)){
  	// Redirect to login screen
  header('Location: login.php');
}

// Right now only one page needs the one function defined, but it may be a good idea to just include this at the top in case we ever need to add other functions various pages need
include('functions.php');

// Include header HTML stuff
include('header_include.php');

// Top navigation menu
echo '<div class="nav-menu"><a href="index.php">View recovery keys</a> | <a href="edit_users.php">Edit admin user access</a> | <a href="logout.php">Log out</a></div>';

?>