<?php
	// Our include
	define('WP_USE_THEMES', false);
	require_once('../../../../wp-load.php');

	global $wpdb;
	$curID = get_current_user_id();
	//echo $curID;
	//Get contacts for current user
	$contacts = $wpdb->get_results("SELECT user_id, friend_id
									FROM wp_aeh_connections
									WHERE user_ID = $curID
									AND consent_date > 0
									OR friend_id = $curID
									AND consent_date > 0");
	//print_r($contacts);
	//Create ID array from contacts; exclude currentuser ID
	$myCont = array();
	foreach($contacts as $contact){
		if($contact->user_id == $curID){
			array_push($myCont, $contact->friend_id);
		}else{
			array_push($myCont,$contact->user_id);
		}
	}



	if(count($myCont) > 0){
		foreach($myCont as $user){
			$uData = get_userdata($user);
			$fName = $uData->first_name;
			$lName = $uData->last_name;
			$staff = get_usermeta($user,'aeh_staff');
			$ava = get_avatar($user, 96);
			$link = get_permalink(276);
		$output.=	"<a class='member-icon' href='$link?member=$user'>
				<div class='group-memberavatar friendedmeicon'>";
		if($staff == 'Y'){$output .= "<div class='hospMem'></div>";}

		$output .=	"<span class='group-membername'>$fName $lName</span>
					$ava
				</div>
			</a>";
	} }else{
		$output .= 'No contacts yet';
		}
	echo $output;
?>