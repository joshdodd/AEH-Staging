<?php
// ----- User Roles Setup ----- //
function AEH_roles_setup(){
	// ----- Remove old roles
	global $wp_roles;
	$roles = $wp_roles->roles;
	unset($roles['administrator']);
	$roles = array_keys($roles);
	foreach($roles as $role){
		remove_role($role);
	}
	//Content Creator
	$capabilities = array(
	    'delete_posts'			   => true,
		'delete_published_posts'   => true,
		'edit_posts'			   => true,
		'edit_published_posts'	   => true,
		//'edit_published_webinars'  => true,
		//'edit_others_posts'		   => true,
		//'edit_others_webinars'	   => true,
		'edit_private_webinars'	   => true,
		'read'					   => true,
		'upload_files'			   => true,
		'moderate_comments'		   => true,
		//'edit_private_posts' 	   => true,
		//'edit_private_pages' 	   => true,
		'read_private_posts' 	   => true,
		'read_private_pages' 	   => true,
		'edit_pages'           	   => true,
	    //'edit_others_pages'	   	   => true,
	    'edit_published_pages' 	   => true,
	    'level_7' => true,);
	add_role(content_creator, 'Content Creator', $capabilities );

	//Member
	$capabilities = array(
		'read' 				 => true,
		'read_private_posts' => true,
		'read_private_pages' => true,
		'level_2' => true,);
	add_role(member, 'Member', $capabilities );
}
// ----- Trigger on theme activation ----- //
add_action( 'after_setup_theme', 'AEH_roles_setup' );


// ----- Roles Capabilities Meta ----- //
function AEH_user_fields( $user ) {
	global $current_user;
	get_currentuserinfo();
	$adminRole = implode(', ',$current_user->roles);
	$postTypes = get_post_types();
	$staffPermission = get_the_author_meta( 'staffPermissions', $user->ID );
	$guestBlogger = get_the_author_meta( 'guestBlogger', $user->ID );
	$userRole = implode(', ',$user->roles);
	$aeh_member = get_user_meta($user->ID, 'aeh_member_type', true);
	$user_email = $user->user_email;
	$userID = $user->ID;
	$imisid = get_user_meta($user->ID, 'aeh_imis_id', true);


	if(($aeh_member  == 'hospital')&&($imisid != '')){
		$imisdata = get_imis_user($imisid);
		if($imisdata){
			$prefix        = $imisdata['prefix'];
			$firstname     = $imisdata['firstname'];
			$middlename    = $imisdata['middlename'];
			$lastname      = $imisdata['lastname'];
			$suffix        = $imisdata['suffix'];
			$designation   = $imisdata['designation'];
			$workphone     = $imisdata['workphone'];
			$fax           = $imisdata['fax'];
			$addressnum    = $imisdata['addressnum'];
			$address1      = $imisdata['address1'];
			$city          = $imisdata['city'];
			$state         = $imisdata['state'];
			$zip           = $imisdata['zip'];
			$country       = $imisdata['country'];
			$company       = $imisdata['company'];
			$co_id         = $imisdata['companyID'];
			$title         = $imisdata['title'];
			$mobile        = $imisdata['mobile'];
			$asst_name     = $imisdata['asst_name'];
			$asst_phone    = $imisdata['asst_phone'];
			$asst_email    = $imisdata['asst_email'];
			$webinterest   = $imisdata['webinterest'];



			update_user_meta($userID, 'first_name', $firstname);
			update_user_meta($userID, 'middle_name', $middlename);
			update_user_meta($userID, 'last_name', $lastname);
			update_user_meta($userID, 'address_number', $addressnum);
			update_user_meta($userID, 'street_address', $address1);
			update_user_meta($userID, 'city', $city);
			update_user_meta($userID, 'state', $state);
			update_user_meta($userID, 'zip_code', $zip);
			update_user_meta($userID, 'country', $country);
			update_user_meta($userID, 'phone', $workphone);
			update_user_meta($userID, 'fax', $fax);
			update_user_meta($userID, 'CO_ID', $co_id);
			update_user_meta($userID, 'designation', $designation);
			update_user_meta($userID, 'mobile_phone', $mobile);
			update_user_meta($userID, 'hospital_name', $company);
			update_user_meta($userID, 'job_title', $title);
			update_user_meta($userID, 'assistant_name', $asst_name);
			update_user_meta($userID, 'assistant_phone', $asst_phone);
			update_user_meta($userID, 'assistant_email', $asst_email);
			update_user_meta($userID, 'imisWebInterests', $webinterest);
			update_user_meta($userID, 'suffix', $suffix);
			update_user_meta($userID, 'title', $prefix);
		}
	}
	//Hidden Fields for autofill: ?>

	<?php if($aeh_member  == 'hospital'){?>
	<script src="<?php echo get_template_directory_uri(); ?>/js/iMISCompany.js"></script>
	<div id="hiddenVals">
		<div id="company_current"><?php echo get_the_author_meta('hospital_name',$user->ID); ?></div>
		<div id="company_list">
			<option value="">-- Select a Hospital --</option>
			<?php $org = get_site_option('company_list');
				  $hq = $org['hq'];
				  $company = $org['company'];
				  $address = $org['address'];
				  $city = $org['city'];
				  $state = $org['state'];
				  $id = $org['id'];
				  $sort= $org['company_sort'];
				  $len = count($hq);
				  for($i = 1; $i <= $len; $i++){ ?>
				  	<option value="<?php echo $company[$i]; ?>"><?php echo $company[$i]; ?> (<?php echo $hq[$i]; ?>), <?php echo $city[$i]; ?>, <?php echo $state[$i]; ?></option>
				  <?php } ?>
		</div>
		<div id="company_id">
			<?php for($i = 1; $i <= $len; $i++){ ?>
				<option value="<?php echo $id[$i]; ?>"><?php echo $id[$i]; ?></option>
			<?php } ?>
		</div>
		<div id="company_sort">
			<?php for($i = 1; $i <= $len; $i++){ ?>
				<option value="<?php echo $sort[$i]; ?>"><?php echo $sort[$i]; ?></option>
			<?php } ?>
		</div>
	</div>
	<table>
		<tbody>
			<tr class="hidden">
				<td><label>Company ID</label></td>
				<td><select name="ISFcompany_id" id="company_id">
					<?php for($i = 1; $i <= $len; $i++){ ?>
						<option value="<?php echo $id[$i]; ?>"><?php echo $id[$i]; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>

			<tr class="hidden">
				<td><label>Company Sort</label></td>
				<td><select name="ISFcompany_sort" id="company_sort">
					<?php for($i = 1; $i <= $len; $i++){ ?>
						<option value="<?php echo $sort[$i]; ?>"><?php echo $sort[$i]; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>

			<tr class="hidden">
				<td><label>Company address</label></td>
				<td><select name="ISFcompany_address" id="company_address">
					<?php for($i = 1; $i <= $len; $i++){ ?>
						<option value="<?php echo $address[$i]; ?>"><?php echo $address[$i]; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>

			<tr class="hidden">
				<td><label>Company city</label></td>
				<td><select name="ISFcompany_city" id="company_city">
					<?php for($i = 1; $i <= $len; $i++){ ?>
						<option value="<?php echo $city[$i]; ?>"><?php echo $city[$i]; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>

			<tr class="hidden">
				<td><label>Company state</label></td>
				<td><select name="ISFcompany_state" id="company_state">
					<?php for($i = 1; $i <= $len; $i++){ ?>
						<option value="<?php echo $state[$i]; ?>"><?php echo $state[$i]; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>





			<tr class="hidden">
				<td><label>Company zip</label></td>
				<td><select name="company_zip" id="company_zip">
					<?php for($i = 1; $i <= $len; $i++){ ?>
						<option value="<?php echo $zipc[$i]; ?>"><?php echo $zipc[$i]; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr class="hidden">
				<td><label>Company work phone</label></td>
				<td><select name="company_workphone" id="company_workphone">
					<?php for($i = 1; $i <= $len; $i++){ ?>
						<option value="<?php echo $wphone[$i]; ?>"><?php echo $wphone[$i]; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr class="hidden">
				<td><label>Company fax</label></td>
				<td><select name="company_fax" id="company_fax">
					<?php for($i = 1; $i <= $len; $i++){ ?>
						<option value="<?php echo $wfax[$i]; ?>"><?php echo $wfax[$i]; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
		</tbody>
	</table>


	<?php
}

	if($adminRole == 'administrator'){ ?>

			<h3>Member Network Access</h3>
			<table class="form-table">
				<tr>
					<th>
						Member Network access for non-hospital members
					</th>
					<td>
						<input type="checkbox" name="MN_MemberAccess" <?php $act = get_user_meta($user->ID, 'MN_MemberAccess', true);if($act == true){echo "checked='checked'";} ?>/>
						<?php echo $act;?>
					</td>
				</tr>
			</table>


		<h3>Activation Link</h3>
		<table class="form-table">
			<tr>
				<td>
					<?php echo get_user_meta($user->ID, 'activation_guid', true); ?>
				</td>
			</tr>
			<tr>
				<td>
					<a href="http://essentialhospitals.org/membercenter/member-activation?memid=<?php echo $user->ID; ?>">http://essentialhospitals.org/membercenter/member-activation?memid=<?php echo $user->ID; ?></a>
				</td>
			</tr>
			<tr>
				<td>User Id: <?php echo $user->ID;?><br>
				User <?php $act = get_user_meta($user->ID, 'verified', true);if($act == true){echo "is";}else{echo "is not";} ?> activated</td>
			</tr>
		</table>
		<?php if($userRole == 'content_creator'){ ?>
		<h3>Staff permissions</h3>

		<table class="form-table">
			<tr>
				<th><label for="staffPermissions">Staff Permissions</label><br>
					<span class="description">Sections that site staff can access</span>
				</th>
				<td>
					<input type="checkbox" name="staffPermissions[]" value="post" <?php if($staffPermission){if(in_array('post', $staffPermission)){ echo 'checked="checked"'; }} ?>> Posts<br>
					<input type="checkbox" name="staffPermissions[]" value="page" <?php if($staffPermission){if(in_array('page', $staffPermission)){ echo 'checked="checked"'; }} ?>> Pages<br>
					<input type="checkbox" name="staffPermissions[]" value="policy" <?php if($staffPermission){if(in_array('policy', $staffPermission)){ echo 'checked="checked"'; }} ?>> Policy<br>
					<input type="checkbox" name="staffPermissions[]" value="quality" <?php if($staffPermission){if(in_array('quality', $staffPermission)){ echo 'checked="checked"'; }} ?>> Quality<br>
					<input type="checkbox" name="staffPermissions[]" value="institute" <?php if($staffPermission){if(in_array('institute', $staffPermission)){ echo 'checked="checked"'; }} ?>> Institute<br>
					<input type="checkbox" name="staffPermissions[]" value="group" <?php if($staffPermission){if(in_array('group', $staffPermission)){ echo 'checked="checked"'; }} ?>> Groups<br>
					<input type="checkbox" name="staffPermissions[]" value="webinar" <?php if($staffPermission){if(in_array('webinar', $staffPermission)){ echo 'checked="checked"'; }} ?>> Webinars<br>
					<input type="checkbox" name="staffPermissions[]" value="alert" <?php if($staffPermission){if(in_array('alert', $staffPermission)){ echo 'checked="checked"'; }} ?>> Alerts
				</td>
			</tr>



		</table>
		<?php }elseif($userRole == 'member'){ ?>
			<h3>Member Permissions</h3>
			<?php echo $guestBlogger; ?>
			<table class="form-table">
				<tr>
					<th><label for="guestBlogger">Guest Blogger?</label><br>
						<span class="description">Can Member post to Blog?</span>
					</th>
					<td>
						<input type="checkbox" name="guestBlogger" value="true" <?php if($guestBlogger){ echo "checked='checked''"; } ?> >
					</td>
				</tr>
			</table>
		<?php }
	}
}
add_action( 'show_user_profile', 'AEH_user_fields' );
add_action( 'edit_user_profile', 'AEH_user_fields' );

function AEH_save_user_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_usermeta( $user_id, 'staffPermissions', $_POST['staffPermissions'] );
	update_usermeta( $user_id, 'guestBlogger', $_POST['guestBlogger'] );
	update_usermeta( $user_id, 'MN_MemberAccess', $_POST['MN_MemberAccess'] );
}
add_action( 'personal_options_update', 'AEH_save_user_fields' );
add_action( 'edit_user_profile_update', 'AEH_save_user_fields' );



// ----- Roles Dashboard Restrictions ----- //
function wps_get_comment_list_by_user($clauses) {
	if (is_admin()) {
		global $user_ID, $wpdb;
		$clauses['join'] = ", wp_posts";
		$clauses['where'] .= " AND wp_posts.post_author = ".$user_ID."
		AND wp_comments.comment_post_ID = wp_posts.ID";
	};
		return $clauses;
	};
	if(!current_user_can('edit_others_posts')) {
	add_filter('comments_clauses', 'wps_get_comment_list_by_user');
}

function AEH_roles_restrictions($user){
	global $current_user;
	get_currentuserinfo();
	$staffPermission = get_the_author_meta( 'staffPermissions', $current_user->ID );
	if(!$staffPermission){ $staffPermission = array(); }
	$userRole = implode(', ',$current_user->roles);
	$permArray = array('policy','quality','institute','webinar','alert','group','post','page');
	$diffArray = array_diff($permArray,$staffPermission);

	//remove menu items without permission
	if($userRole == 'administrator'){

	}elseif($staffPermission){
		remove_menu_page('tools.php');
		foreach($diffArray as $term){
			if($term == 'post'){
				remove_menu_page('edit.php');
			}else{
				remove_menu_page('edit.php?post_type='.$term);
			}
		}
		remove_menu_page('tools.php');
	}else{
		remove_menu_page('edit.php?post_type=page');			//Pages
		remove_menu_page('edit.php?post_type=policy');			//Policy
		remove_menu_page('edit.php?post_type=quality');			//Quality
		remove_menu_page('edit.php?post_type=institute');		//Institute
		remove_menu_page('edit.php?post_type=webinar');			//Webinar
		remove_menu_page('edit.php?post_type=alert');			//Alert
		remove_menu_page('edit.php?post_type=group');			//Group
		//remove_menu_page('edit.php?post_type=discussion');		//Discussion
		remove_menu_page('edit.php');							//Posts
		remove_menu_page('tools.php');
	}
}
add_action( 'admin_menu', 'AEH_roles_restrictions' );

// ----- Rename Posts to Blog ----- //
function edit_admin_menus() {
	global $menu;

	$menu[5][0] = 'Blog'; // Change Posts to Recipes
}
add_action( 'admin_menu', 'edit_admin_menus' );


// ----- Role Scripts ----- //
function my_enqueue($hook) {
	if('edit.php' != $hook && !current_user_can('publish_posts')){
		$postType = get_post_type();
		$permArray = array('policy','quality','institute','webinar','alert','group','post','page');
		if(in_array($postType, $permArray)){
			wp_enqueue_script( 'CCcontrol', get_template_directory_uri() . '/js/CCcontrol.js',array( 'jquery' ) );
		}
	}
}
add_action( 'admin_enqueue_scripts', 'my_enqueue' );
