<?php
/*
Template Name: Member Network - My Profile
*/
// this page displays your own profile information and allows you to edit it
include ("includes/aeh_config.php");
include ("includes/aeh-functions.php");

global $wpdb;
$currentUser = get_current_user_id();
$user_info = get_userdata($currentUser);
$user_avatar = get_avatar($currentUser);
get_header();

$usermeta = get_user_meta($userID);
$aeh_member = $usermeta['aeh_member_type'][0];
$user_email = $usermeta['user_email'][0];
$imisid = $usermeta['aeh_imis_id'][0];


/*
FUNCTION TO PULL DOWN IMIS INFO
1. call does_imis_user_exist and pull back imis data values
2. store each value individually
3. update all wp user meta
*/
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


$usermeta       = get_user_meta($userID);
$firstname      = $usermeta['first_name'][0];
$lastname       = $usermeta['last_name'][0];
$nickname       = $usermeta['nickname'][0];
$description    = $usermeta['description'][0];
$user_email     = $usermeta['user_email'][0];
$membersince    = $usermeta['user_registered'];
$twitter        = $usermeta['twitter'][0];
$linkedin       = $usermeta['linkedin'][0];
$jobfunction    = $usermeta['job_function'][0];
$hospital_name  = $usermeta['hospital_name'][0];
$company_id     = $usermeta['CO_ID'][0];
$company_sort   = $usermeta['COMPANY_SORT'][0];
$employer       = $usermeta['employer'][0];
$title          = $usermeta['title'][0];
$jobtitle       = $usermeta['job_title'][0];
$facebook       = $usermeta['facebook'][0];
$visibility     = $usermeta['aeh_visibility'][0];
$aeh_staff      = $usermeta['aeh_staff'][0];
$aeh_member     = $usermeta['aeh_member_type'][0];
$newsinterest   = get_user_meta($userID, 'custom_news_feed', true); ?>

	<div id="companyorg" class="hidden hideme">
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
			  	<option <?php if($hospital_name == $company[$i]){echo 'selected="selected"';}?> value="<?php echo $company[$i]; ?>"><?php echo $company[$i]; ?> (<?php echo $hq[$i]; ?>), <?php echo $city[$i]; ?>, <?php echo $state[$i]; ?> </option>
			  <?php } ?>
	</div>
	<div id="companyid" class="hidden hideme">
		<?php for($i = 1; $i <= $len; $i++){ ?>
			<option <?php if($company_id == $id[$i]){echo 'selected="selected"';} ?> value="<?php echo $id[$i]; ?>"><?php echo $id[$i]; ?></option>
		<?php } ?>
	</div>
	<div id="companysort" class="hidden hideme">
		<?php for($i = 1; $i <= $len; $i++){ ?>
			<option <?php if($company_sort == $sort[$i]){echo 'selected="selected"';} ?> value="<?php echo $sort[$i]; ?>"><?php echo $sort[$i]; ?></option>
		<?php } ?>
	</div>

	<table>
		<tbody>
			<tr class="hidden">
				<td><label>Company address</label></td>
				<td><select name="ISFcompany_address" id="ISFcompany_address">
					<?php for($i = 1; $i <= $len; $i++){ ?>
						<option value="<?php echo $address[$i]; ?>"><?php echo $address[$i]; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>

			<tr class="hidden">
				<td><label>Company city</label></td>
				<td><select name="ISFcompany_city" id="ISFcompany_city">
					<?php for($i = 1; $i <= $len; $i++){ ?>
						<option value="<?php echo $city[$i]; ?>"><?php echo $city[$i]; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>

			<tr class="hidden">
				<td><label>Company state</label></td>
				<td><select name="ISFcompany_state" id="ISFcompany_state">
					<?php for($i = 1; $i <= $len; $i++){ ?>
						<option value="<?php echo $state[$i]; ?>"><?php echo $state[$i]; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr class="hidden">
				<td><label>Company zip</label></td>
				<td><select name="ISFcompany_zip" id="ISFcompany_zip">
					<?php for($i = 1; $i <= $len; $i++){ ?>
						<option value="<?php echo $zipc[$i]; ?>"><?php echo $zipc[$i]; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr class="hidden">
				<td><label>Company work phone</label></td>
				<td><select name="ISFcompany_workphone" id="ISFcompany_workphone">
					<?php for($i = 1; $i <= $len; $i++){ ?>
						<option value="<?php echo $wphone[$i]; ?>"><?php echo $wphone[$i]; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr class="hidden">
				<td><label>Company fax</label></td>
				<td><select name="ISFcompany_fax" id="ISFcompany_fax">
					<?php for($i = 1; $i <= $len; $i++){ ?>
						<option value="<?php echo $wfax[$i]; ?>"><?php echo $wfax[$i]; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
		</tbody>
	</table>

    <div id="membernetwork">
        <div class="container">
            <h1 class="title"><span class="grey">Essential Hospitals</span> Member Network | My Profile</h1>
            <?php get_template_part('membernetwork/content','usernav'); $output = "";?>
            <div <?php if(!is_user_logged_in()){echo "id='mem-redirect'";} ?> class="groupcol clearfix <?php if(!is_user_logged_in()){echo "floatleft fullwidth";}else{echo "group-details";}?>">
                <?php if(is_user_logged_in()){
                	include(locate_template('/membernetwork/module-profileData.php'));
                }else{
	                the_content();
                } ?>
            </div>

            <?php if(is_user_logged_in()){
	            if($aeh_member == 'hospital'){
            	echo '<div class="group-members groupcol">';
            	include(locate_template('/membernetwork/module-profileContacts.php'));
            	echo '</div>'; } } ?>

			<?php if(is_user_logged_in()){ ?>
            <div class="group-resources groupcol">
                <?php include(locate_template('/membernetwork/module-profileDiscussions.php')); ?>
                <?php if($aeh_member == 'hospital'){
                	include(locate_template('/membernetwork/module-profileGroups.php')); } } ?>
            </div>
        </div>
<?php get_footer('sans'); ?>