<?php /*
 * Template Name: Member Network - Messages
 */
 if (!is_user_logged_in()){
	header('Location: '.site_url().'/membernetwork/member-login/?redir=contacts');
}
	get_header();
	$currentUser = get_current_user_id();
	$user_info = get_userdata($currentUser);
	$aeh_member = get_user_meta($currentUser,'aeh_member_type',true);
	$memberaccess = get_user_meta($currentUser,'MN_MemberAccess',true);
	global $cartpaujPMS;
	$numNew = $cartpaujPMS->getNewMsgs();
	$msgs = $cartpaujPMS->getMsgs(); ?>
<div id="membernetwork" class="<?php echo $aeh_member; ?>">
	<div class="container">
		<h1 class="title"><span class="grey">Essential Hospitals</span> Member Network | My Messages</h1>
		<?php get_template_part('membernetwork/content','usernav'); ?>
		<?php if($aeh_member == 'hospital' || $memberaccess == true){ ?>
		<div id="membercontent" class="messages">
			<div class="gutter clearfix">
				<div class="groupcol onefourth user-messages">
					<div class="panel">
						<div class="gutter clearfix">
							<span class="title"><?php echo $user_info->user_firstname; ?> <?php echo $user_info->user_lastname; ?></span>
							<span class="desc">you have <span class="orange"><a href="<?php echo get_permalink(248); ?>">(<?php echo $numNew; ?> new messages)</a></span></span>
							<span class="send"><a href="?pmaction=newmessage">Send a message</a></span>
						</div>
					</div>
				</div>
				<div class="groupcol threefourth mess-messages">
					<div class="panel">
						<div class="gutter">
							<?php the_content(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php }else{ ?>
			<div id="membercontent" class="group">
				<div class="gutter">
					<h2>You must be an Association Member to access Messages.</h2>
				</div>
			</div>
		<?php } ?>
	</div>
</div>

<?php get_footer('sans'); ?>