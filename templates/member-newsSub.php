<?php
/*
Template Name: Member Network - News Sub
*/
if (is_user_logged_in()){
get_header();
include ("includes/aeh_config.php");
include ("includes/aeh-functions.php");
$metakey 	 = "custom_news_feed";
$usermeta    = get_user_meta($userID, $metakey, TRUE);
$aeh_staff   = get_user_meta($userID,'aeh_member_type',true);
$currentUser = get_current_user_id();
	$user_info = get_userdata($currentUser);
	$user_avatar = get_avatar($currentUser);
?>
<div id="membernetwork" class="<?php echo $aeh_staff; ?>">
	<div class="container">
		<h1 class="title"><span class="grey">Essential Hospitals</span> Member Network | My News</h1>
			<?php get_template_part('membernetwork/content','usernav'); ?>

			<div class="group-details groupcol">
				<?php get_template_part( 'membernetwork/module', 'membernewsfull' ); ?>
			</div>
			<div class="group-members groupcol">
				<?php get_template_part( 'membernetwork/module', 'newssubscribe' ); ?>
			</div>
			<div class="group-resources groupcol">
				<?php include(locate_template('membernetwork/module-dashProfile.php')); ?>
			</div>

<?php
			}else{
				header('Location: http://meshdevsite.com/membercenter/member-login/');
		}
?>

	</div>
</div>

<?php get_footer('sans'); ?>