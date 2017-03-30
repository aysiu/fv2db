<?php

// This page processes the data sent from the client (serial number of machine and FileVault personal recovery key)
// See if the post data is set
if((isset($_POST['serial'])) AND (isset($_POST['recovery_key']))){

   // Set them as local variables
   $serial=$_POST['serial'];
   $recovery_key=$_POST['recovery_key'];

   // Connect to the database
   require('../includes/database_connect.php');

   // See if this key is already set
   $stmt=mysqli_prepare($dbc, "SELECT recovery_id
            FROM recovery_keys
            WHERE serial=? AND recovery_key=?");
   mysqli_stmt_bind_param($stmt, 'ss', $serial, $recovery_key);
   mysqli_stmt_execute($stmt);
   mysqli_stmt_bind_result($stmt, $recovery_id_temp);

   // Initialize a test variable
   $recovery_id='';
   while(mysqli_stmt_fetch($stmt)){
      $recovery_id=$recovery_id_temp;

   }
   mysqli_stmt_close($stmt);

   // If there is nothing in the database with that ID and that recovery, let's add the new recovery
   if($recovery_id==''){

      // See if the other POST variables are set... if not, assign a blank
      if(isset($_POST['comp_hostname'])){

         $comp_hostname=$_POST['comp_hostname'];

      } else {

         $comp_hostname='';

      }

      if(isset($_POST['fv_users'])){

         $fv_users=$_POST['fv_users'];

      } else {

         $fv_users='';
      }

      // Go ahead and insert into the database
      $stmt=mysqli_prepare($dbc, "INSERT INTO recovery_keys (recovery_key, serial, comp_hostname, fv_users, timestamp) VALUES (?, ?, ?, ?, UNIX_TIMESTAMP(NOW()))");
      mysqli_stmt_bind_param($stmt, 'ssss', $recovery_key, $serial, $comp_hostname, $fv_users);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      echo 'added';

   // If there's an exact match, say so
   } else {
      echo 'exists';   
   }

   // Disconnect from the database
   require('../includes/database_disconnect.php');

// End checking post data is set
} else {
   echo 'Variables not set';
}

?>