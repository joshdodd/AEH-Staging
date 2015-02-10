<?php /*
 * Template Name: Member Network - Guest Blog
 */
 if (!is_user_logged_in()){
	header('Location: '.get_bloginfo('url').'/membernetwork/member-login/');
}
	get_header(); ?>
<?php $currentUser = get_current_user_id();
	$user_info = get_userdata($currentUser);
	$user_avatar = get_avatar($currentUser);
	global $cartpaujPMS;
	$numNew = $cartpaujPMS->getNewMsgs();
	$msgs = $cartpaujPMS->getMsgs();
	$firstrun = get_user_meta($currentUser, 'firstrun', true);
	if(!$firstrun){
		echo '<script src="'.get_bloginfo('template_url').'/js/intro.js"></script>';
		echo '<script src="'.get_bloginfo('template_url').'/js/firstrun.js"></script>';
		echo '<script>
			$(window).bind("load", function() {
			   firstRun('.$currentUser.');
			});</script>';
	}
	 ?>

<div id="membernetwork">
	<div class="container">
		<h1 class="title"><span class="grey">Essential Hospitals</span> Member Network</h1>
		<?php get_template_part('membernetwork/content','usernav'); ?>




		<?php if(is_user_logged_in()){ ?>

		<div id="membercontent" class="group dashboard guestblog">
			<div class="graybarleft"></div>
			<div class="graybarright"></div>
			<div class="gutter clearfix">
				<div class="twothird groupcol" id="formcont">
					<h2 class="heading">Guest Blogger</h2>
					<div class="gutter">
						<?php the_content(); ?>
					</div>
				</div>

				<div class="group-resources groupcol">
					<div id="userProfile">
						<div class="gutter clearfix">
							<div id="userAvatar" class="clearfix">
								<div class="group-memberavatar">
									<span class="group-membername"><?php echo $user_info->user_firstname.' ' .$user_info->user_lastname; ?></span>
									<a href="<?php echo get_permalink(274); ?>"><?php echo $user_avatar; ?></a>
								</div>
							</div>
							<div id="userInfo">
								<span class="uinfo fName"><?php echo $user_info->user_firstname; ?></span>
								<span class="uinfo jobTitle"><?php echo $user_info->job_title; ?></span>
								<span class="uinfo org"><?php echo $user_info->employer; ?></span>
								 
								<span class="uinfo email"><a href="mailto:<?php echo $user_info->user_email; ?>"><?php echo $user_info->user_email; ?></a></span>
							</div>
							<div id="userMeta">
								<ul class="social">
									<?php if ($user_info->linkedin) { ?>
										<li class="linkedin"><a href="<?php echo $user_info->linkedin; ?>">linkedin</a></li>
									<?php } ?>
									<?php if ($user_info->twitter) { ?>
										<li class="twitter"><a href="<?php echo $user_info->twitter; ?>">twitter</a></li>
									<?php } ?>
									<?php if ($user_info->facebook) { ?>
										<li class="facebook"><a href="<?php echo $user_info->facebook; ?>">facebook</a></li>
									<?php } ?>
									<li class="mail"><a href="mailto:<?php echo $user_info->user_email; ?>">mail</a></li>
								</ul>
								<span class="edit"><a href="<?php echo get_permalink(274); ?>?a=edit">[edit profile]</a></span>
							</div>
						</div>
					</div>
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