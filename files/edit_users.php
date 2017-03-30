<?php

require('includes/header.php');

require('includes/database_connect.php');

// See if there's a request to remove a user
if((isset($_GET['removeid'])) AND (is_numeric($_GET['removeid']))){

   $removeid=$_GET['removeid'];

   // Check that it's an actual user to remove
   $query="SELECT email FROM users WHERE user_id='$removeid' LIMIT 1";
   $result=mysqli_query($dbc, $query);
   $count=mysqli_num_rows($result);
   if($count==1){
   
      // Check that it isn't the currently logged in user
      $row=mysqli_fetch_assoc($result);
      if($row['email']==$_SESSION['email']){
         echo '<div class="error">You cannot remove access for yourself. Someone else has to remove you.</div>';
      } else {
         $stmt=mysqli_prepare($dbc, "DELETE FROM users WHERE user_id=?");
         mysqli_stmt_bind_param($stmt, 'i', $removeid);
         mysqli_stmt_execute($stmt);
         mysqli_stmt_close($stmt);
         echo '<div class="feedback">Successfully removed access for ' . $row['email'] . '</div>';
         
      }
   
   } else {
   // If the user doesn't exist, she can't be removed
      echo '<div class="error">That is not a valid user to remove.</div>';
   }

// If it's not a removal but an addition...
} elseif(isset($_GET['newuser'])){

   $newuser=$_GET['newuser'];

   // Get the config settings
   require('config.php');

   // Check that it's a valid email address and that the email is in the approved domains
   if ((!filter_var($newuser, FILTER_VALIDATE_EMAIL) === false) AND (email_domain_check($newuser, $approved_domains))){ 
      
      $stmt=mysqli_prepare($dbc, "INSERT INTO users (email) VALUES (?)");
      mysqli_stmt_bind_param($stmt, 's', $newuser);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      echo '<div class="feedback">Successfully added access for ' . $newuser . '</div>';

   } else {

      echo '<div class="error">You can add only email addresses that are in the approved domains array.</div>';
   }

}

echo '<p>These users are authorized to view recovery keys:</p>';

$query="SELECT user_id, email FROM users ORDER BY email";
$result=mysqli_query($dbc, $query);
echo '<table>
<thead>
<tr>
<th>Authorized user</th>
<th></th>
</tr>
</thead>
<tbody>';
// Get the actual list of users
while($row=mysqli_fetch_array($result)){

   echo '<tr><td>' . $row['email'] . '</td><td><a href="' . $_SERVER['PHP_SELF'] . '?removeid=' . $row['user_id'] . '">Remove user</a></td></tr>';

}
echo '</tbody></table>';
// Form to add a new user
echo '<p><form name="adduser" action="' . $_SERVER['PHP_SELF'] . '" method="get"><tr><td>Email address of new user to add? <input name="newuser" type="text" size="40"></td><td><input type="submit" value="Add this user"></form></p>';

include('includes/database_disconnect.php');

include('includes/footer.php');
?>