<?php
/*
Template Name: Member Network - AJAX Remove Friend
*/

// ajax to remove your friend

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') { // check if valid AJAX request

	if (isset($_GET['id'])){

		include ("includes/aeh_config.php");
		include ("includes/aeh-functions.php"); // me = $userID from functions file
		$encryptedID  = $_GET['id'];			// encrypted connection ID to remove
		$time 		  = time(); 				// current time

		$friendID = substr($encryptedID, 32);
		$sql = mysql_query("
			SELECT `connection_ID`, `consent_date`, `request_date`
			FROM `wp_aeh_connections`
			WHERE (`user_ID` = $userID AND `friend_ID` = $friendID)
				OR(`user_ID` = $friendID AND `friend_ID` = $userID)
		");
		$result = mysql_fetch_array($sql);

		$connectionID = $result['connection_ID'];
		$consent      = $result['consent_date'];
		$request      = $result['request_date'];

		$check = strrev(md5($consent - $request)) . $friendID;

		$output = 'Error!'; // presume an error
		if ($check == $encryptedID){
			if (mysql_query("DELETE FROM `wp_aeh_connections` WHERE `connection_ID` = $connectionID")){
				$output = 'Connection Removed!';
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