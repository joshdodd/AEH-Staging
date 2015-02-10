<div class="panel">
                    <h2 class="heading">Programs and Groups</h2><?php $groups = get_user_meta($currentUser, 'groupMem', true);
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
                            <span class="title"><a href="<?php echo $link; ?>"><?php echo $title; ?></a></span> <span class="desc"><?php echo $desc; ?></span>
                        </div>
                    </div><?php } } }else{
	echo '<p>No group memberships</p>';
} ?>
				<div class="dashboard-button"> <a href="<?php echo get_permalink(392); ?>" id="addnewgroup">Start a Private Group</a> </div>
                </div>