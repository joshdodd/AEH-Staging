<?php /*
 * Template Name: Member Network - Dashboard
 */
 if (!is_user_logged_in()){
	header('Location: '.get_bloginfo('url').'/membernetwork/member-login/');
}
	get_header();
	$currentUser = get_current_user_id();
	$user_info = get_userdata($currentUser);
	$user_avatar = get_avatar($currentUser);
	global $cartpaujPMS;
	$numNew = $cartpaujPMS->getNewMsgs();
	$msgs = $cartpaujPMS->getMsgs();
	$firstrun = get_user_meta($currentUser, 'firstrun', true);
	$aeh_member = get_user_meta($currentUser,'aeh_member_type',true);
	if(!$firstrun){
		if($aeh_member == 'hospital'){
			echo '<script src="'.get_bloginfo('template_url').'/js/intro.js"></script>';
			echo '<script src="'.get_bloginfo('template_url').'/js/firstrun.js"></script>';
			echo '<script>
				$(window).bind("load", function() {
				   firstRun('.$currentUser.');
				});</script>';
		}else{
			echo '<script src="'.get_bloginfo('template_url').'/js/intro.js"></script>';
			echo '<script src="'.get_bloginfo('template_url').'/js/firstrunPublic.js"></script>';
			echo '<script>
				$(window).bind("load", function() {
				   firstRun('.$currentUser.');
				});</script>';
		}
	}
	 ?>

<div id="membernetwork">
	<div class="container">
		<h1 class="title"><span class="grey">Essential Hospitals</span> Member Network</h1>
		<?php get_template_part('membernetwork/content','usernav'); ?>
		<?php if(is_user_logged_in()){ ?>
		<div id="membercontent" class="group dashboard">
			<div class="graybarleft"></div>
			<div class="graybarright"></div>
			<div class="gutter clearfix">
				<div class="group-details groupcol" id="run-news">
					<div class="panel membernews">
						<?php get_template_part( 'membernetwork/module', 'membernews' ); ?>
					</div>
				</div>
				<div class="group-members groupcol">
					<?php if($aeh_member == 'hospital'){
						include(locate_template('/membernetwork/module-dashGroups.php')); } ?>
					<?php if($aeh_member == 'hospital'){
						get_template_part( 'membernetwork/module', 'educationtopics' ); } ?>
					<div id="run-disccomm">
						<?php include(locate_template('/membernetwork/module-dashDiscussion.php')); ?>
						<?php include(locate_template('/membernetwork/module-dashComments.php')); ?>
					</div>
				</div>

				<div class="group-resources groupcol">
					<?php include(locate_template('/membernetwork/module-dashProfile.php')); ?>
					<?php if($aeh_member == 'hospital'){
						include(locate_template('/membernetwork/module-dashWebinars.php')); } ?>
					<?php if($aeh_member == 'hospital'){
						include(locate_template('/membernetwork/module-dashMessages.php')); } ?>
					<?php if($aeh_member == 'hospital'){
						echo '<div class="panel" id="run-connect">';
						get_template_part( 'membernetwork/module', 'connections' );
						echo '</div>'; } ?>

				</div>
			</div>
		</div>
		<?php } elseif(!is_user_logged_in()){ ?>
			<div id="membercontent" class="group">
				<div class="gutter">
					<h2>You must be logged in to view this page</h2>
				</div>
			</div>
			<?php } ?>

	</div>
</div>

<?php get_footer('sans'); ?>