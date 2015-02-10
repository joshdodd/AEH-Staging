<?php
/*
Template Name: Member Network - AJAX Deny Friend
*/

// ajax to deny your friend's request

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') { // check if valid AJAX request

	if (isset($_GET['id'])){

		include ("includes/aeh_config.php");
		include ("includes/aeh-functions.php"); // me = $userID from functions file
		$friendID     = $_GET['id'];			// friend ID to deny

		$sql = mysql_query("
			SELECT `connection_ID`
			FROM `wp_aeh_connections`
			WHERE (`user_ID` = $friendID AND `friend_ID` = $userID)
		");

		$output = 'Error!'; // presume an error
		if ($sql){
			$result = mysql_fetch_array($sql);
			$connectionID = $result['connection_ID'];
			if (mysql_query("DELETE FROM `wp_aeh_connections` WHERE `connection_ID` = $connectionID")){
				$output = 'Connection Denied!';
			}
		}

	}else{ // if get variable 'id' not present then we shouldn't be here anyway so display error message and do nothing else.
		$output .= "No data. Contact technical support."; // error if get variable not present
	}
}else{
	$output .= "Direct page access not allowed!"; // print this if not AJAX request
}

echo $output;
?>