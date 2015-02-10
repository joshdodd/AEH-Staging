<div class="panel msgs clearfix" id="run-msgs">
	<h2 class="heading">Private Messages (<?php echo $numNew; ?>)</h2>
	<?php
		foreach($msgs as $msg){
			$fromU = get_userdata($msg->from_user);
			$wholeThread = $cartpaujPMS->getWholeThread($msg->id);
			$lastThread = end($wholeThread);
			$msgExcerpt = substr($lastThread->message_contents, 0, 100); ?>
			<div class="msglist">
				<div class="gutter">
					<a href="<?php bloginfo('url'); ?>/member-network-messages/?pmaction=viewmessage&id=<?php echo $msg->id; ?>"><strong><?php echo $fromU->user_firstname; ?> <?php echo $fromU->user_lastname; ?></strong></a> <em><?php echo $msgExcerpt; ?></em>
				</div>
			</div>
	<?php } ?>
	<a class="readmore" href="<?php echo get_permalink(248); ?>">Your inbox &raquo;</a>
</div>