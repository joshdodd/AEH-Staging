<?php

	// Our include
	define('WP_USE_THEMES', false);
	require_once('../../../../wp-load.php');


	$userQuery = $_POST['userQuery'];
	$userQueryLen = strlen($userQuery);
	$userQuery = strtolower($userQuery);

	global $wpdb;
	$search = $userQuery;
	$users = $wpdb->get_results("SELECT ID, display_name
			    				 FROM $wpdb->users
			    				 WHERE display_name LIKE '%$search%'
			    				 ORDER BY display_name");
	foreach($users as $user){
		$userName = $user->display_name;
		$userID   = $user->ID;
		$userAva  = get_avatar( $userID, 20);
		$userNameTest = strtolower($userName);
		if(substr($userNameTest, 0, $userQueryLen) == $userQuery) {
			$output .= "<div class='autp_fillentry'><a data-ID='$userID'>$userName $userAva</a></div>";
		}
	}
	echo $output;
?>