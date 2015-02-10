<?php /*
 * Template Name: Member Network - Create Group
 */
 if (!is_user_logged_in()){
	header('Location: '.get_bloginfo('url').'/membernetwork/member-login/');
}
	get_header(); ?>
<?php $currentUser = get_current_user_id();
	$user_info = get_userdata($currentUser);
	$user_avatar = get_avatar($currentUser); ?>

<div id="membernetwork">
	<div class="container">
		<h1 class="title"><span class="grey">Essential Hospitals</span> Member Network</h1>
		<?php get_template_part('membernetwork/content','usernav'); ?>
		<?php if(is_user_logged_in()){ ?>
		<div id="membercontent" class="group dashboard">
			<div class="graybarleft"></div>
			<div class="graybarright"></div>
			<div class="gutter clearfix">
				<div class="twothird groupcol">
					<h2 class="heading">Start a private group</h2>
					<?php get_template_part( 'membernetwork/module', 'creategroup' ); ?>
				</div>

				<div class="group-resources groupcol">
					<?php include(locate_template('membernetwork/module-dashProfile.php')); ?>
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