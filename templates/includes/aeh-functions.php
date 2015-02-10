<?php

// page permalinks
	$page_member_profile = '/member-profile';
	$page_my_profile = '/my-profile';
	$page_registration = '/registration';
	$page_login = '/registration';
	$page_my_connections = '/my-connections';

// get user meta for current user.
	$current_user= wp_get_current_user();
	$username    = $current_user->user_login;
	$useremail   = $current_user->user_email;
	$firstname   = $current_user->user_firstname;
	$lastname    = $current_user->user_lastname;
	$displayname = $current_user->display_name;
	$userID      = $current_user->ID;
	
	$hospitalmember = false;
	$publicmember   = false;
	$membertype     = get_user_meta($userID, "aeh_member_type", TRUE);
	if ($membertype == "hospital"){$hospitalmember = true;}else{$publicmember = true;}
	
	
function customnewsfeed($id){
	$customnewsfeed = false;
	$newsfeedmeta = get_user_meta($id, "custom_news_feed", TRUE);
	if ($newsfeedmeta !=""){$customnewsfeed = unserialize($newsfeedmeta);}
	return $customnewsfeed;
}

//checkes whether the uses is behind a (transparent) proxy, and returns the found IP address.
function get_ip_address(){

    if (isset($_SERVER)) {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && ip2long($_SERVER["HTTP_X_FORWARDED_FOR"]) !== false) {
            $ipadres = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_CLIENT_IP"])  && ip2long($_SERVER["HTTP_CLIENT_IP"]) !== false) {
            $ipadres = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $ipadres = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR') && ip2long(getenv('HTTP_X_FORWARDED_FOR')) !== false) {
            $ipadres = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP') && ip2long(getenv('HTTP_CLIENT_IP')) !== false) {
            $ipadres = getenv('HTTP_CLIENT_IP');
        } else {
            $ipadres = getenv('REMOTE_ADDR');
        }
    }
    return $ipadres;
}


	/* 	Outputs the 3 icon wide connection list
		$output is appended to input variable or the default is blank,
		$id is whose list it is, 
		$pending is a flag: 'friends' = list friends, 'friended' = list friends who have friended you and need approval, anything else = pending friends
		$limit = how many to output (usually a multiple of 3 minus 1)
	*/
function output_connections($output = "", $id, $pending, $limit = 8){ 

	global $page_member_profile;
	$profile_base = get_site_url() . $page_member_profile;
	
	
	if ($pending == 'friended'){
		$boxclass     = "friendedmeicon";
		$profile_link = false;
		$deny_button  = true;
		$sql = "
			SELECT 	wp_aeh_connections.user_ID AS friendID, 
					consent_date AS consent, 
					aeh_staff.meta_value AS staff, 
					first_name.meta_value AS first_name, 
					last_name.meta_value AS last_name
			FROM wp_aeh_connections
            LEFT JOIN wp_usermeta AS first_name ON first_name.user_id = wp_aeh_connections.user_ID AND first_name.meta_key = 'first_name'
			LEFT JOIN wp_usermeta AS last_name ON last_name.user_id   = wp_aeh_connections.user_ID AND last_name.meta_key  = 'last_name'
			LEFT JOIN wp_usermeta AS aeh_staff ON aeh_staff.user_id   = wp_aeh_connections.user_ID AND aeh_staff.meta_key  = 'aeh_staff'
			WHERE wp_aeh_connections.friend_ID=$id AND consent_date=0
			ORDER BY consent ASC
			LIMIT $limit";
	}else{
		$boxclass     = "myfriendicon";
		$profile_link = true;
		$deny_button  = false;
		if ($pending == 'friends'){
			$sql = "
				SELECT 	if (wp_aeh_connections.user_ID = $id, wp_aeh_connections.user_ID, wp_aeh_connections.friend_ID) AS userID,
						if (wp_aeh_connections.friend_ID = $id, wp_aeh_connections.user_ID, wp_aeh_connections.friend_ID) AS friendID,
						consent_date AS consent, 
						aeh_staff.meta_value AS staff, 
						first_name.meta_value AS first_name, 
						last_name.meta_value AS last_name
				FROM wp_aeh_connections
				LEFT JOIN wp_usermeta AS first_name ON first_name.user_id = if (wp_aeh_connections.user_ID = $id, friend_ID, wp_aeh_connections.user_ID)
				AND first_name.meta_key = 'first_name'
				LEFT JOIN wp_usermeta AS last_name ON last_name.user_id = if (wp_aeh_connections.user_ID = $id, friend_ID, wp_aeh_connections.user_ID)
				AND last_name.meta_key = 'last_name'
				LEFT JOIN wp_usermeta AS aeh_staff ON aeh_staff.user_id = if (wp_aeh_connections.user_ID = $id, friend_ID, wp_aeh_connections.user_ID)
				AND aeh_staff.meta_key = 'aeh_staff'
				WHERE (wp_aeh_connections.user_ID=$id AND wp_aeh_connections.consent_date>0)
				OR (wp_aeh_connections.friend_ID=$id AND wp_aeh_connections.consent_date>0)";

		}else{ // assume pending friends with any other value
			$sql = "
				SELECT 	wp_aeh_connections.friend_ID AS friendID, 
						consent_date AS consent, 
						aeh_staff.meta_value AS staff, 
						first_name.meta_value AS first_name, 
						last_name.meta_value AS last_name
				FROM wp_aeh_connections
				LEFT JOIN wp_usermeta AS first_name ON first_name.user_id = friend_ID
				AND first_name.meta_key = 'first_name'
				LEFT JOIN wp_usermeta AS last_name ON last_name.user_id = friend_ID
				AND last_name.meta_key = 'last_name'
				LEFT JOIN wp_usermeta AS aeh_staff ON aeh_staff.user_id = friend_ID
				AND aeh_staff.meta_key = 'aeh_staff'
				WHERE wp_aeh_connections.user_ID=$id AND wp_aeh_connections.consent_date=0
				ORDER BY consent ASC
				LIMIT $limit";
		}
	}
	
	$sql = mysql_query($sql);
		
	while ($row = mysql_fetch_array($sql)){
		$consent   = $row['consent'];
		$friendID  = $row['friendID'];
		$staff     = $row['staff'];
		$firstname = $row['first_name'];
		$lastname  = $row['last_name'];
		$gravurl   = get_avatar_url(get_avatar($friendID));
		$deny      = "";
		if ($deny_button){
			$deny  = "
			<button class='approveme' data-friendID='$friendID' name='$friendID'>Approve</button>
			<button class='deny-button' name='$friendID'>Deny</button>";
		}
		$mem_icon  = "
		<div class='group-memberavatar friendedmeicon'>
			<div class='hospMem'></div>
			<span class='group-membername'>$firstname $lastname</span>
			<img src='$gravurl'>
			$deny
		</div>";
		if ($profile_link){ // wrap the icon in a hyperlink to the member profile page if you want the profile link
			$output .= "
			<a href='$profile_base?member=$friendID'>$mem_icon</a>
			";
		}else{ // if not then just output the profile icon
			$output .= "
			$mem_icon
			";			
		}
	}
	return $output;
}

// Wordpress get avatar returns img tag. Use this to return the URL portion only
function get_avatar_url($get_avatar){
    preg_match("/src='(.*?)'/i", $get_avatar, $matches);
    return $matches[1];
}
?>