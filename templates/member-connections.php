<?php
/*
Template Name: Member Network - Connections
*/
include ("includes/aeh_config.php");
include ("includes/aeh-functions.php");
if (!is_user_logged_in()){
	header('Location: '.site_url().'/membernetwork/member-login/?redir=contacts');
}
get_header();
$currentUser = get_current_user_id();
	$user_info = get_userdata($currentUser);
	$aeh_member = get_user_meta($currentUser,'aeh_member_type',true);
?>
<div id="membernetwork" class="<?php echo $aeh_member; ?>">
	<div class="container">
		<h1 class="title"><span class="grey">Essential Hospitals</span> Member Network | Contacts</h1>
		<?php get_template_part('membernetwork/content','usernav'); ?>
		<?php if($aeh_member == 'hospital'){ ?>
		<div id="connectioncontent" class="group">
			<div class="gutter clearfix">
				<h2 class='heading'>Browse all network members</h1>
				<div class='clisting'>
					<ul id="paginationc">

					</ul>
				</div>
			</div>
		</div>
		<div id='sidebar-connections'></div>
		<?php }else{
			echo '<h2>You must be an Association Member to access Contacts</h2>';
		} ?>
	</div>
</div>

<?php get_footer('sans'); ?>