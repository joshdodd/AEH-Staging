<?php
$userID = get_current_user_id();
$site_url = get_site_url();
function output_connectionsX($output = "", $id, $pending, $limit = 8){

	global $page_member_profile;
	$profile_base = get_site_url() . $page_member_profile;

	if ($pending == 'friended'){
		$boxclass = "friendedmeicon"; $profile_link = false;
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
		$boxclass = "myfriendicon"; $profile_link = true;
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
		//$consent   = $row['consent'];
		$friendID  = $row['friendID'];
		$staff     = $row['staff'];
		$firstname = $row['first_name'];
		$lastname  = $row['last_name'];
		$gravurl   = get_avatar( $friendID, 70);
		$mem_icon  = "
		<div class='group-memberavatar'>
			<span class='group-membername'>$firstname $lastname</span>
			$gravurl
		</div>";
		if ($profile_link){ // wrap the icon in a hyperlink to the member profile page if you want the profile link
			$output .= "
			<a href='$site_url/membernetwork/member-profile/?member=$friendID'>$mem_icon</a>
			";
		}else{ // if not then just output the profile icon
			$output .= "
			$mem_icon
			";
		}
	}
	return $output;
}

$members = output_connectionsX("",$userID,'friends',8);
if ($members!=""){ $blocks++; ?>
<h2 class='heading'>My Contacts</h2>
	<div class='gutter clearfix'>
		<div class="myfriends">

			<div class="pendingnotify"></div>

			<div class="membercontent">

				<?php echo $members; ?>

			</div>

		</div>

	</div>
<?php } ?>