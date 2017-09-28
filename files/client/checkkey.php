<?php

// Functions to validate input... if it turns out we need this in more than one file, we can move it to another place and include that here

function is_valid_serial($test_serial){
	// Serial numbers have to be exactly 12 characters and only alphanumeric
	if((strlen($test_serial)==12) AND (ctype_alnum($test_serial))){
		return true;
	} else {
		return false;
	}
// End function
}

function is_valid_recovery($test_recovery){
	// Recovery keys have 24 alphanumeric characters separated by hyphens in groups of four
	if(
		(strlen($test_recovery)==29) AND 
		($test_recovery[4]=="-") AND 
		($test_recovery[9]=="-") AND 
		($test_recovery[14]=="-") AND 
		($test_recovery[19]=="-") AND 
		($test_recovery[24]=="-") AND 
		(ctype_alnum(str_replace("-","",$test_recovery)))
	){
		return true;
	} else {
		return false;
	}
// End function
}

// This page processes the data sent from the client (serial number of machine and FileVault personal recovery key)
// See if the post data is set
// Post naming changed to be Crypt-compatible
if((isset($_POST['serial'])) AND (isset($_POST['recovery_password']))){

	// Set them as local variables
	$serial=strtoupper($_POST['serial']);
	$recovery_key=strtoupper($_POST['recovery_password']);

	if((is_valid_serial($serial)) AND (is_valid_recovery($recovery_key))){

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

			// Not going to validate these, because they could be anything. We're using prepared statements, so SQL injection attacks should be a non-issue.
			// See if the other POST variables are set... if not, assign a blank
			if(isset($_POST['macname'])){

				// Doesn't make sense to get more than the first 20 characters, though
				$comp_hostname=substr($_POST['macname'],0,19);

			} else {

				$comp_hostname='';

			}

			if(isset($_POST['username'])){

				// The max is 255... not sure we have to truncate this, but might as well to be safe
				$fv_users=substr($_POST['username'],0,254);

			} else {

				$fv_users='';
			}

			// Go ahead and insert into the database
			$stmt=mysqli_prepare($dbc, "INSERT INTO recovery_keys (recovery_key, serial, comp_hostname, fv_users, timestamp) VALUES (?, ?, ?, ?, UNIX_TIMESTAMP(NOW()))");
			mysqli_stmt_bind_param($stmt, 'ssss', $recovery_key, $serial, $comp_hostname, $fv_users);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
			//echo 'added';

		// If there's an exact match, say so
		}/* else {
			echo 'exists';	
		}*/

		// Disconnect from the database
		require('../includes/database_disconnect.php');

	// End checking post data is valid
	} else {
		http_response_code(500);
	}
// End checking post data is set
} else {
	// Proper variables not sent
	http_response_code(500);
}

?>
