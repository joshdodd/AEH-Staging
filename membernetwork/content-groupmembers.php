<?php
global $post;
if($post->post_parent){
	$parent = array_reverse(get_post_ancestors($post->ID));
	$members = get_post_meta($parent[0], 'autp');
}else{
	$members = get_post_meta($post->ID, 'autp');
}
foreach($members as $member){
	$first = array_slice($member, 0, 9);
	$second = array_slice($member,9);

	echo "<div id='firstninemembers'>";
	foreach($first as $user){
		$id	= $user['user_id'];
		$user_info = get_userdata($id);
		$user_avatar = get_avatar($id); ?>

		<div class="group-memberavatar">
			<span class="group-membername"><?php echo $user_info->user_firstname.' ' .$user_info->user_lastname; ?></span>
			<a href="<?php echo get_permalink(276); ?>?member=<?php echo $id;?>"><?php echo $user_avatar; ?></a>
		</div>
<?php }
	echo "</div>";
	if($second){
		echo "<div id='leftovermembers'>";
		foreach($second as $user){
			$id	= $user['user_id'];
			$user_info = get_userdata($id);
			$user_avatar = get_avatar($id); ?>

			<div class="group-memberavatar">
				<span class="group-membername"><?php echo $user_info->user_firstname.' ' .$user_info->user_lastname; ?></span>
				<a href="<?php echo get_permalink(276); ?>?member=<?php echo $id;?>"><?php echo $user_avatar; ?></a>
			</div>
	<?php }
		echo "</div><div id='moremembers'>See all Members</div>";
	}
 } ?>