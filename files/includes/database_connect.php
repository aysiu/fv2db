<?php

// This could be referenced from the top-level directory or the directory underneath, so we'll try two different paths to get to the config file

$config_present=1;

if(file_exists('config.php')){

   // Include values from config file
   require('config.php');

} elseif(file_exists('../config.php')){

   // Include values from config file
   require('../config.php');

} else {

   $config_present=0;

}

// If it's present in one of the locations, then we can go ahead and try to connect to the database
if($config_present==1){

   // See if there's already an existing database connection
   if(((isset($dbc)) AND ($dbc!='')) OR (!isset($dbc))){

      // Actually connect to the database
      $dbc = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME) or
         die("Error " . mysqli_error($dbc));

   // End checking the connection
   }

}
?>
