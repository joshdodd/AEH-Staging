<?php
	// Our include
	define('WP_USE_THEMES', false);
	require_once('../../../../wp-load.php');

	global $wpdb;
	$curID = get_current_user_id();

	//GET for action
	if(isset($_POST['caction'])){
		$action = $_POST['caction'];
	}else{
		$action = 'reset';
	}
	//GET for page
	if(isset($_POST['cpage'])){
		$page = $_POST['cpage'];
	}else{
		$page = 0;
	}
	//GET for offset
	if(isset($_POST['coffset'])){
		$offset = $_POST['coffset'];
	}else{
		$offset = 20;
	}
	//GET for search (search resets page variable)
	if(isset($_POST['csearch'])){
		$search = $_POST['csearch'];
		$page = 0;
	}

	$renderStart = $page * $offset;
	$output .= "<div data-render='$renderStart' data-page='$page' data-offset='$offset'></div>";


	if($action == 'reset'){
		$page = 0;
		$offset = 20;
		$sort = 'last_name';
		$search = '';
	}


	//Get pending/requested/my contacts
	$contacts = $wpdb->get_results("SELECT user_id, friend_id
											FROM wp_aeh_connections
											WHERE user_ID = $curID
											AND consent_date > 0
											OR friend_id = $curID
											AND consent_date > 0");
	$myContacts = array();
	foreach($contacts as $contact){
		if($contact->user_id == $curID){
			array_push($myContacts, $contact->friend_id);
		}else{
			array_push($myContacts,$contact->user_id);
		}
	}
	$contacts = $wpdb->get_results("SELECT user_id, friend_id
											FROM wp_aeh_connections
											WHERE friend_ID = $curID
											AND consent_date = 0");
	$pendingContacts = array();
	foreach($contacts as $contact){
		array_push($pendingContacts, $contact->user_id);
	}
	$curID = get_current_user_id();
			//echo $curID;
			//Get contacts for current user
			$contacts = $wpdb->get_results("SELECT user_id, friend_id
											FROM wp_aeh_connections
											WHERE user_ID = $curID
											AND consent_date = 0");
	$requestedContacts = array();
	foreach($contacts as $contact){
		array_push($requestedContacts, $contact->friend_id);
	}



	//GET for sort
	if(isset($_POST['csort'])){
		$sort = $_POST['csort'];
	}
	//GET for search (search resets page variable)
	if(isset($_POST['csearch'])){
		$search = $_POST['csearch'];
		$page = 0;
	}


	//Query users
	if($sort == 'search'){
		$users = $wpdb->get_results("SELECT DISTINCT users.ID
									 FROM wp_users users, wp_usermeta search, wp_usermeta hospmem
									 WHERE users.ID = search.user_id
									 AND search.meta_key = 'job_title'
									 AND users.ID != $curID
									 AND (users.display_name LIKE '%$search%' OR search.meta_value LIKE '%$search%')
									 AND (hospmem.meta_key = 'aeh_member_type' AND hospmem.meta_value = 'hospital')
									 ORDER BY ID ASC");
	}elseif($sort == 'job-function'){
		$users = $wpdb->get_results("SELECT DISTINCT $wpdb->users.ID
								 FROM $wpdb->users, $wpdb->usermeta
								 WHERE $wpdb->users.ID = $wpdb->usermeta.user_id
								 AND $wpdb->usermeta.meta_key = 'job_function'
								 AND $wpdb->users.display_name != ''
								 AND $wpdb->users.ID != $curID
								 AND $wpdb->usermeta.meta_value LIKE '%$search%'
								 ORDER BY $wpdb->users.ID ASC
								 LIMIT $offset OFFSET $renderStart");
	}elseif($sort == 'staff'){
		$users = $wpdb->get_results("SELECT DISTINCT $wpdb->users.ID
								 FROM $wpdb->users, $wpdb->usermeta
								 WHERE $wpdb->users.ID = $wpdb->usermeta.user_id
								 AND $wpdb->usermeta.meta_key = 'aeh_staff'
								 AND $wpdb->users.display_name != ''
								 AND $wpdb->users.ID != $curID
								 AND $wpdb->usermeta.meta_value = 'Y'
								 ORDER BY $wpdb->users.ID ASC");
	}else{
		$users = $wpdb->get_results("SELECT DISTINCT users.ID
									FROM wp_users users, wp_usermeta membertype
									WHERE users.ID = membertype.user_id
									AND (membertype.meta_key = 'aeh_member_type' AND membertype.meta_value = 'hospital')
									AND users.display_name != ''
									AND users.ID != $curID
								 	UNION
									SELECT DISTINCT users.ID
									FROM wp_users users, wp_usermeta verification
									WHERE users.ID = verification.user_id
									AND (verification.meta_key = 'verified' AND verification.meta_value = '1')
									AND users.display_name != ''
									AND users.ID != $curID
									ORDER BY ID ASC
									LIMIT $renderStart , $offset");
	}





	//Render Member Count and render Users
	//$output .= '<div id="membercount">'.count($membercount).' Members Found</div>';
	$curid = get_current_user_id();
	foreach($users as $user){
		$uData = get_userdata($user->ID);
		$user_id = $user->ID;
		$fName = $uData->first_name;
		$lName = $uData->last_name;
		$staff = get_usermeta($user->ID,'aeh_staff');
		$title = get_usermeta($user->ID,'job_title');
		$hospital = get_usermeta($user->ID, 'hospital_name');
		$ava = get_avatar($user->ID, 128);

		$output .= "<div id='add".$user->ID."' class='member-meta'>
			<a href='".get_permalink(276)."?member=".$user->ID."'>
				<div class='grav-style'>";
		if($staff == 'Y'){$output .= '<div class="hospMem"></div>';}
		$output .= "$ava
				</div>
				<div class='member-style'>$fName $lName</div>
				<div class='job-style'>$title</div>
				<div class='org-style'>$hospital</div>
			</a>";

		if(in_array($user->ID, $myContacts)){
			$output .= "<div class='my-connection'>
				<button class='added-button'>$fName is a Contact</button>
			</div>";
		}elseif(in_array($user->ID, $pendingContacts)){
			$output .= "<div class='pending-connection'>
				<button class='added-button'>$fName has added you</button>
			</div>";
		}elseif(in_array($user->ID, $requestedContacts)){
			$output .= "<div class='requested-connection'>
				<button class='added-button'>You have requested $fName</button>
			</div>";
		}else{
			$output .= "<div class='add-connection'>
				<button data-curid='$curid' data-uid='$user_id' title='add $fName to your connections' class='add-button contact-add'>Add $fName to Contacts</button>
			</div>";
		}

		$output .= "</div>";

		}
		if($sort == 'staff' || count($users) <= 0){
			$output .= "<style id='loadingstyle'>#loadmore{display:none;}</style>";
		}
		echo $output;
?>