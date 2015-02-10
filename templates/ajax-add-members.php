<?php 
/*
Template Name: Member Network - AJAX add members
*/
// add individual member to your connections pending list
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

		if (mysql_num_rows(mysql_query("SELECT * FROM `wp_aeh_connections` WHERE `user_ID`=$userID AND `friend_ID`=$friendID"))){
			// friend request already exists so nothing to do - this is really an error case because we shouldn't be here in this instance
			$output .= "<div class='add-friend exists'>You already made a friend request to $name.</div>";
		}else{
			$sql = mysql_query("SELECT * FROM `wp_aeh_connections` WHERE `user_ID`=$friendID AND `friend_ID`=$userID");
			if (mysql_num_rows($sql)){
				// This is where you add a friend but the friend requested to add you as a friend first
				// in this case, just approve the friend request because consent is implied
				$connections  = mysql_fetch_array($sql);
				$connectionID = $connections['connection_ID'];
				mysql_query("
					UPDATE `wp_aeh_connections` SET `consent_date`=$time
					WHERE `connection_ID`=$connectionID
				");
				$output .= "<div class='add-friend'>$name is now in your Connections list.</div>";
			}else{
				// brand new friend request so add to DB
				mysql_query("
					INSERT INTO `wp_aeh_connections` (`user_ID`, `friend_ID`, `request_date`)
					VALUES ($userID, $friendID, $time)
				");
				$output .= "<div class='add-friend'>Friend request sent to $name!</div>";
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