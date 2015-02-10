<div class="panel" id="run-comments">
	<h2 class="heading">Recent Comments on content</h2>
	<?php $comments = get_comments(array(
						'number'=> 3,
						'post_type' => array('policy','institute','quality','posts'),
						));
		if(sizeof($comments) > 0){
			foreach($comments as $comment){
			$title = get_the_title($comment->comment_post_ID);
			$author = $comment->comment_author;
			$link = get_permalink($comment->comment_post_ID);
			$categories = get_the_category($comment->comment_post_ID);
			$posttype = get_post_type($comment->comment_post_ID); ?>
			<div class="commentlist <?php echo $posttype; ?> <?php foreach($categories as $cat){ echo $cat->slug." "; }?>">
				<div class="gutter">
					<span class="title"><a href="<?php echo $link; ?>"><?php echo $title; ?></a></span>
					<span class="desc"><strong><?php echo $author; ?></strong> commented: <em><?php comment_excerpt($comment->comment_ID); ?></em></span>
					<p class="time"><?php the_time('M j, Y'); ?> at <?php the_time('g:ia'); ?></span>
				</div>
			</div>
		<?php }
		}else{
			echo "<p>No ongoing comment dialogues on our articles right now.</p>";
		} ?>

</div>