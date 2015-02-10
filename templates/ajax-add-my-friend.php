<?php
/*
Template Name: Member Network - AJAX Add My Friend
*/

// ajax to add the person who friended you

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') { // check if valid AJAX request

	if (isset($_GET['id'])){

		include ("includes/aeh_config.php");
		include ("includes/aeh-functions.php"); // me = $userID from functions file
		$friendID     = $_GET['id'];			// connection ID
		$time 		  = time(); 				// current time

		$output = "";

		$sql = mysql_query("
			SELECT ID, first_name.meta_value AS first_name, last_name.meta_value AS last_name
			FROM `wp_users`
			LEFT JOIN wp_usermeta AS first_name ON first_name.user_id = ID
			AND first_name.meta_key = 'first_name'
			LEFT JOIN wp_usermeta AS last_name ON last_name.user_id = ID
			AND last_name.meta_key = 'last_name'
			WHERE `ID` = $friendID"
		);
		$names = mysql_fetch_array($sql);
		$name  = $names['first_name'] . " " . $names['last_name'];

		if (mysql_query("UPDATE wp_aeh_connections SET `consent_date` = $time WHERE `user_ID` = $friendID AND `friend_ID` = $userID")){
			$output = "<p>Added $name to your Connections</p>";
		}

	}else{ // if get variable 'id' not present then we shouldn't be here anyway so display error message and do nothing else.
		$output .= "No data. Contact technical support."; // error if get variable not present
	}
}else{
	$output .= "Direct page access not allowed!"; // print this if not AJAX request
}

echo $output;
?>