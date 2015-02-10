<?php
if(is_user_logged_in()){ ?>

<div id="memberdash">
	<div class="gutter">
		<div id="dashnav">
			<a id="logout" href="<?php echo wp_logout_url( home_url() ); ?>" title="Logout">Logout</a>
			<?php
			global $current_user;
			$cuser = get_currentuserinfo();
			if(current_user_can('edit_published_pages')){ ?>
				<a id="admin" href="<?php echo admin_url();?>" title="Dashboard">Admin</a>
			<?php } ?>
			<?php get_template_part( 'membernetwork/content', 'usernav' ); ?>
		</div>
		<div id="dashfriends">
			<?php get_template_part( 'membernetwork/module', 'contactsMy' ); ?>
		</div>
		<div id="dashmessages">
			<?php get_template_part( 'membernetwork/module', 'messages' ); ?>
		</div>
		<div id="dashgroups">
		<h2 class="heading">My Private Groups</h2>
		<?php $currentUser = get_current_user_id();
			$groups = get_user_meta($currentUser, 'groupMem', true);
			if($groups){
			foreach($groups as $group){
				$post = get_post($group);
				$title = $post->post_title;
				$desc = $post->post_excerpt;
				$link = get_permalink($group);
				$type = get_post_type($post->ID);
				if($type == 'group'){ ?>
				<div class="grouplist">
					<div class="gutter">
						<span class="title"><a href="<?php echo $link; ?>"><?php echo $title; ?></a></span>
						<span class="desc"><?php echo $desc; ?></span>
					</div>
				</div>
			<?php } } } ?>
		<div class="dashboard-button"> <a href="<?php echo get_permalink(392); ?>" id="addnewgroup">Start a Private Group</a> </div>
		</div>
		<div id="dashnews">
			<?php get_template_part( 'membernetwork/module', 'membernews' ); ?>
		</div>
	</div>
</div>

<?php } ?>