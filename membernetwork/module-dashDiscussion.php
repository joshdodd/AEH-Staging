<div class="panel" id="run-discussion">
	<h2 class="heading">Community Discussions updates</h2>
		<?php $comments = get_comments(array('user_id' => $currentUser));
			$commentArray = array();
			foreach($comments as $comment){
				array_push($commentArray,$comment->comment_post_ID);
			}
			$commentArray = array_unique($commentArray);

			if(sizeof($commentArray) > 0){
				$commentArray = array_slice($commentArray,0,3);
				$query = new WP_Query( array( 'post_type' => 'discussion', 'post__in' => $commentArray ) );
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post(); ?>
						<div class="grouplist">
							<div class="gutter">
								<span class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>
								<span class="desc"><?php the_excerpt(); ?></span>
							</div>
						</div>
				<?php } } else { ?>
					<p>After you’ve contributed to Community Discussions, the most recent message posted to each will appear here.</p>
			<?php } wp_reset_postdata(); }else{
				echo "<p>After you’ve contributed to Community Discussions, the most recent message posted to each will appear here.</p>";
			} ?>
	<div class="dashboard-button"><a href="<?php echo get_permalink(257); ?>#newdisc?discuss=new">Join or Start a New Discussion</a></div>
</div>