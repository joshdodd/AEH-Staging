<?php
/*
Template Name: Member Network - AJAX ALL MEMBERS
*/
	include ("includes/aeh_config.php");
	include ("includes/aeh-functions.php"); // me = $userID from functions file
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') { // check if valid AJAX request
	if (isset($_POST['data'])){

		$vars      = $_POST['data'];
		$sortby    = $vars['sortBy']; // first_name, last_name, job_title
		$perpage   = $vars['perPage'];// how many to display per page
		$pageno    = $vars['pageNo']; // which page number in the pagination 1-
		$sortdir   = $vars['sortDir'];// asc or desc
		$filter    = $vars['filter']; // hospital member or not
		$search    = $vars['search']; // search term
		$exception = "";
		$searchterm= "";

		if ($filter == "aeh"){$exception = "AND (aeh_staff.meta_value ='Y')";}
		if (strlen($search)>=2){$searchterm = "AND (last_name.meta_value LIKE '%$search%')";}


		$sql = mysql_query("
			SELECT DISTINCT ID
			FROM wp_users
			LEFT JOIN wp_usermeta AS aeh_member_type ON aeh_member_type.user_id = ID
			AND aeh_member_type.meta_key = 'aeh_member_type'

			LEFT JOIN wp_usermeta AS aeh_staff ON aeh_staff.user_id = ID
			AND aeh_staff.meta_key = 'aeh_staff'

			LEFT JOIN wp_usermeta AS last_name ON last_name.user_id = ID
			AND last_name.meta_key = 'last_name'
			LEFT JOIN wp_aeh_connections ON if(ID = wp_aeh_connections.user_ID,ID = wp_aeh_connections.user_ID,ID = wp_aeh_connections.friend_ID)
			WHERE (aeh_member_type.meta_value LIKE '%hospital%')
			AND (ID<>$userID)
			$exception
			$searchterm
		");
		if ($sql){
			$count = mysql_num_rows($sql); // count = number of profiles found that are not in your connection list
			$pages = ceil($count/$perpage);// pages = how many pages required for pagination (below)
		}else{
			$count = 0;
			$pages = 0;
		}

		$output = "<div id='membercount'><span>$count</span> members found</div>";
		if ($filter == "all"){
			$output .= "<div id='filterstaff' data-filter='all'>Show only Staff Members</div>";
		}else{
			$output .= "<div id='filterstaff' data-filter='aeh'>Showing All Members</div>";
		}

		$profile_base = get_site_url() . $page_member_profile;

		$start = ($pageno-1)*$perpage;
		$sql = "
		SELECT
			DISTINCT ID,
			first_name.meta_value AS first_name,
			last_name.meta_value AS last_name,
			job_title.meta_value AS job_title,
			aeh_staff.meta_value AS staff,
                        wp_aeh_connections.user_ID AS user_ID,
			friend_ID,

			consent_date AS consent,
			interactions,
                        last_contact

		FROM wp_users

		JOIN wp_usermeta AS job_title ON job_title.user_id = ID
			AND job_title.meta_key = 'job_title'

		LEFT JOIN wp_aeh_connections ON
		if ((wp_aeh_connections.user_ID = $userID) AND (wp_aeh_connections.friend_ID = ID) AND (consent_date = 0), ID,
			if ((wp_aeh_connections.friend_ID = $userID) AND (wp_aeh_connections.user_ID = ID) AND (consent_date = 0), ID,
				if ((wp_aeh_connections.user_ID = $userID) AND (wp_aeh_connections.friend_ID = ID) AND (consent_date > 0), consent_date,
					if ((wp_aeh_connections.friend_ID = $userID) AND (wp_aeh_connections.user_ID = ID) AND (consent_date > 0), ID = wp_aeh_connections.user_ID, 0))))

		LEFT JOIN wp_usermeta AS first_name ON first_name.user_id = ID
			AND first_name.meta_key = 'first_name'

		LEFT JOIN wp_usermeta AS last_name ON last_name.user_id = ID
			AND last_name.meta_key = 'last_name'

		LEFT JOIN wp_usermeta AS aeh_member_type ON aeh_member_type.user_id = ID
			AND aeh_member_type.meta_key = 'aeh_member_type'

		LEFT JOIN wp_usermeta AS aeh_staff ON aeh_staff.user_id = ID
			AND aeh_staff.meta_key = 'aeh_staff'

		WHERE (aeh_member_type.meta_value LIKE '%hospital%')

		AND (ID<>$userID)

			$exception
			$searchterm

			ORDER BY $sortby $sortdir
			LIMIT $start, $perpage";
		$result = mysql_query($sql);
		$selected = " selected";
		$sel10 = "10'"; if ($perpage == 10) $sel10 .= $selected;
		$sel20 = "20'"; if ($perpage == 20) $sel20 .= $selected;
		$sel50 = "50'"; if ($perpage == 50) $sel50 .= $selected;
		$sel100 = "100'"; if ($perpage == 100) $sel100 .= $selected;
		$perpage_options = "
		<div class='styled-select'>
			<select id='perpage'>
				<option value='$sel10>10 per page</option>
				<option value='$sel20>20 per page</option>
				<option value='$sel50>50 per page</option>
				<option value='$sel100>100 per page</option>
			</select>
		</div>";
		$output .= "<table id='connection-table'>
		<thead>
			<tr>
				<th class='profilesearch'><input type='text' id='profile-search' value='$search' placeholder='Search by Last Name' /></th>
				<th class='sortby'>Sort By:</th>
				<th class='sortby-btn'><div class='job-title'>Job Title</div></th>
				<th class='sortby-btn'><div class='last-name'>Last Name</div></th>
			</tr>
		</thead>
		</table>
			<div class='pageselect-cont'>
				<div class='perpagesel'>$perpage_options</div>
			</div>

		";
		$n = $start + 1; $lr = "";
		while($row = mysql_fetch_array($result)){
			$user_id      = $row['ID'];
			$grav         = get_avatar($user_id,$gravatar_width);
			$first_name   = $row['first_name'];
			$last_name    = $row['last_name'];
			$jobtitle     = $row['job_title'];
			$consent      = $row['consent'];
			$interactions = $row['interactions'];
			$last_contact = $row['last_contact'];

			/*
			$emailparts   = explode ('@',$email);

			$zql = mysql_query("SELECT `organization`,`staff` FROM wp_aeh_email WHERE `domain`='" . $emailparts[1] . "'");
			$org = mysql_fetch_array($zql);
			$organization = $org['organization'];
			*/


			if (is_null($consent)){ // null = no connection to this person
				$addbutton = "
				<button alt='$user_id' title='add $first_name $last_name to your connections' class='add-button'>Add $first_name as Contact</button>";
			}elseif($consent>0){    // greater than zero means already a friend
				$addbutton = "<button class='added-button'>Already a Contact</button><h4>Already a Contact!</h4>";
			}else{ 					// 0 = friend request sent and waiting for it
				$addbutton = "<button class='added-button'>Added Contact</button><h4>Contact Request Sent</h4>";
			}

			$output .= "
			<div id='add$user_id' class='member-meta$lr'>
				<a href='$profile_base?member=$user_id'>
					<div class='grav-style'>";

			if ($row['staff']=='Y'){
				$output .= "<div class='hospMem'></div>";
			}

			$output .= "$grav</div>
					<div class='member-style'>$first_name $last_name</div>
					<div class='job-style'>$jobtitle</div>
					<div class='org-style'>$organization</div>
				</a>
				<div class='add-connection'>$addbutton</div>
			</div>";
			if ($lr==""){$lr=" rt";}else{$lr="";}
			$n++;
		}

		if ($pages>1){
			$output .= "
			<ul id='paginationc'>";
			for($i=1; $i<=$pages; $i++){
				$output .= "<li id='$i'>$i</li>";
			}
			$output .= "
			</ul>";
		}

		echo $output;
	}else{ // if post array not present then we shouldn't be here anyway so display error message and do nothing else.
		echo "No data. Contact technical support.";
	}
}else{
	echo "Direct page access not allowed!";
}
?>