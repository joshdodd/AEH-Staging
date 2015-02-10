<?php /* Template for Discussions custom post type */
	get_header(); ?>
<div id="membernetwork">
	<div class="container">
		<h1 class="title"><span class="grey">Essential Hospitals</span> Member Network | Discussions</h1>

		<?php get_template_part('membernetwork/content','usernav'); ?>

		<?php $memberArray = array();
			$currentUser = get_current_user_id();
			$postID = get_the_ID(); ?>

		<?php if(is_user_logged_in()){ ?>
			<?php
			//form processing
			if (!empty($_POST)){
				if(isset($_POST['sub-id'])){
					update_user_meta($currentUser, 'subscribe-'.$postID, true);
				}
			}
			//get meta
			$sub = get_user_meta($currentUser, 'subscribe-'.$postID, true);
				if($sub == true){
					$subForm = "value='Subscribed' disabled='disabled'";
				}else{
					$subForm = "value='Subscribe'";
				} ?>
		<div id="membercontent" class="group">
			<div class="gutter clearfix">
				<h2 class="heading">Discussions
					<form id='topic-sub' method="post">
						<input type='hidden' name='sub-id' id='sub-id' value='<?php echo $postID; ?>' />
						<input type='submit' <?php echo $subForm; ?> />
					</form>
				</h2>
				<div class="onefourth disc-dashboard">
					<div class="panel">
						<div class="gutter clearfix">
							<?php get_template_part('membernetwork/searchform','discussion'); ?>
						</div>
					</div>
					<div class="panel date">
						<div class="gutter clearfix">
							<span class="bump sortdate">Sort by Date &raquo;</span>
						</div>
					</div>
					<div class="panel topic">
						<div class="gutter clearfix">
							<p class="topic"><strong>POPULAR TOPICS</strong></p>
								<ul id="discussiontags">
							 <?php $tags = wp_tag_cloud( array( 'taxonomy' => 'discussion_tags', 'orderby' => 'count', 'format' => 'array', 'smallest' => 11, 'largest' => 11, 'unit' => 'px' ) );
							 	if($tags){
								 foreach($tags as $tag){ ?>
								 	<li><?php echo $tag; ?></li>
								 <?php } } ?>
								</ul>
						</div>
					</div>
				</div>
				<div class="threefourth disc-content">
					<div class="panel">
						<div class="gutter clearfix">
							<h2 class="heading quality"><?php the_title(); ?>

							<?php $pid = get_the_id();
								$pterm = wp_get_post_terms($pid,'discussions');
								foreach($pterm as $term){
									if($term->parent == 38 || $term->parent == 110){
										$x = true;
										$slug = $term->slug;
									}else{
										$x = false;
									}
								}
								if($x == true){
									$pdisc = get_post($slug);
									$ptitle = $pdisc->post_title;
								}
							?>
							<span class="back-to-disc">
							<?php if($x != true){
									echo '<a href="'.get_permalink(257).'">&laquo; Back to Discussions</a>';
								}else{
									echo '<a href="'.get_permalink($slug).'">&laquo; Back to '.$ptitle.'</a>';
								}
							?>

							</span>


							</h2>
							<div id="disc-content" class="orig">
								<div class="gutter clearfix">
									<span class="tags"><?php $posttags = wp_get_post_terms( $postID, 'discussion_tags' );
												if ($posttags) {
													  $i = 0;
													  $len = count($posttags);
												      foreach($posttags as $tag) {
												      	if ($i == $len - 1) {
													        echo $tag->name;
													    } else{
													        echo $tag->name . ', ';
													    }
													    $i++;

												      } } ?> </span>
									<h2>Original Post:</h2>
									<div class="reply sendto">Reply</div>
									<div class="disc-author">
										<?php $authorID = get_the_author_meta('ID');
											$author = get_userdata($authorID);
											$authAv = get_avatar($authorID, 78); ?>
										<div class="group-memberavatar">
											<span class="group-membername"><?php echo $author->user_firstname; ?> <?php echo $author->user_lastname; ?></span>
											<a href="<?php echo get_permalink(276); ?>?member=<?php echo $authorID;?>"><?php echo $authAv; ?></a>
										</div>
									</div>
									<div class="disc-info">
										<div class="gutter">
											<div class="auth-meta">
												<span class="date"><?php the_time('M j, g:i a');?></span>

											<?php the_content(); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div id="disc-comments">
								<div class="gutter">
									<h2>Replies:</h2>
									<?php comments_template('/comments-discussion.php'); ?>
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>

		<?php  } else{ ?>
			<div id="membercontent" class="group">
				<div class="gutter">
					<h2>You must be logged in to view this page</h2>
				</div>
			</div>
			<?php } ?>

		</div>
	</div>
</div>

<?php get_footer('sans'); ?>
