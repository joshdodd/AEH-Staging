<?php $currentUser = get_current_user_id();
	$user_info = get_userdata($currentUser);
	$user_avatar = get_avatar($currentUser);
	global $cartpaujPMS;
	$numNew = $cartpaujPMS->getNewMsgs();
	$msgs = $cartpaujPMS->getMsgs(); ?>
<h2 class="heading">My Messages (<?php echo $numNew; ?>)</h2>
<?php
	foreach($msgs as $msg){
		$fromU = get_userdata($msg->from_user);
		$wholeThread = $cartpaujPMS->getWholeThread($msg->id);
		$lastThread = end($wholeThread);
		$msgExcerpt = substr($lastThread->message_contents, 0, 100); ?>
		<div class="msglist">
			<div class="gutter">
				<a href="<?php bloginfo('url'); ?>/messages/?pmaction=viewmessage&id=<?php echo $msg->id; ?>"><strong><?php echo $fromU->user_firstname; ?> <?php echo $fromU->user_lastname; ?></strong>  <em><?php echo $msgExcerpt; ?></em></a>
			</div>
		</div>
<?php } ?>
<a class="readmore" href="<?php echo get_permalink(248); ?>">view more >></a>