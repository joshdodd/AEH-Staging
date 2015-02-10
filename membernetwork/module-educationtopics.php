<?php 
$currentUser = get_current_user_id();
$groups = get_user_meta($currentUser, 'groupMem', true);
if($groups){
foreach($groups as $group){
	$post = get_post($group);
	$terms = wp_get_post_terms($post->ID, 'educationtopics');
	$termCount = count($terms);
}
if($termCount > 0){ ?>
<div class="panel">
	<h2 class="heading">My Educational Programs</h2>
	<?php foreach($groups as $group){
		$post = get_post($group);
		$title = $post->post_title;
		$desc = $post->post_excerpt;
		$link = get_permalink($group);
		$type = get_post_type($post->ID);
		$terms = wp_get_post_terms($post->ID, 'educationtopics');
		$termCount = count($terms);
		if($termCount > 0){ ?>
		<div class="grouplist">
			<div class="gutter">
				<span class="title"><a href="<?php echo $link; ?>"><?php echo $title; ?></a></span>
				<span class="desc"><?php echo $desc; ?></span>
			</div>
		</div>

<?php } } ?>
</div>
<?php } } ?>