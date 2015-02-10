<?php
	// Our include
	define('WP_USE_THEMES', false);
	require_once('../../../../wp-load.php');

	$curID = get_current_user_id();
	$contacts = $wpdb->get_results("SELECT user_id, friend_id
									FROM wp_aeh_connections
									WHERE friend_ID = $curID
									AND consent_date = 0");
	$myCont = array();
	foreach($contacts as $contact){
		array_push($myCont, $contact->user_id);
	}

	foreach($myCont as $user){
			$uData = get_userdata($user);
			$fName = $uData->first_name;
			$lName = $uData->last_name;
			$staff = get_usermeta($user,'aeh_staff');
			$ava = get_avatar($user, 96);
			$link = get_permalink(276);
	$output.= "<div class='pending-contact'>
					<div class='appdeny'>
						<button data-uid='$user' data-curid='$curID' class='approve'>Approve</button>
						<button data-uid='$user' data-curid='$curID' class='deny'>Deny</button>
					</div>
				<a class='member-icon' href='$link?member=$user'>
					<div class='group-memberavatar friendedmeicon'>";

	if($staff == 'Y'){$output .= '<div class="hospMem"></div>'; }

	$output .=			"<span class='group-membername'>$fName $lName</span>
						$ava
					</div>
				</a>
			</div>";
	}
	echo $output;
?>