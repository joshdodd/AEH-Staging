<?php /* Template Name: Member Network - Discussions */
if (!is_user_logged_in()){
	header('Location: '.get_bloginfo('url').'/membernetwork/member-login/');
}
get_header();
$currentUser = get_current_user_id();
$isPrivate   = get_user_meta($currentUser, 'aeh_member_type', true);
$isStaff     = get_user_meta($currentUser, 'aeh_staff', true); ?>

<div id="membernetwork" class="<?php echo $isPrivate; ?>">
	<div class="container">
		<h1 class="title"><span class="grey">Essential Hospitals</span> Member Network | Discussions</h1>

		<?php get_template_part('membernetwork/content','usernav'); ?>

		<?php $memberArray = array(); ?>

		<?php if(is_user_logged_in()){ ?>

		<div id="membercontent" class="group">
			<div class="gutter clearfix">
				<div class="onefourth disc-dashboard">
					<div class="panel">
						<div class="gutter clearfix">
							<?php get_template_part('membernetwork/searchform','discussion'); ?>
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
								 <li><a href="<?php echo get_permalink(257); ?>">View all Community Discussions</a></li>
								 	<?php
										if($isPrivate == 'hospital'){
											echo '<li><a href="'.get_permalink(257).'/?assocmem=true">View all Community Discussions for association members</a></li>';
										}
								 	?>
									<?php
									if($isStaff == 'Y'){
										echo '<li><a href="'.get_permalink(257).'/?staffmem=true">View all Community Discussions for staff members</a></li>';
									}
									?>
								</ul>
						</div>
					</div>
				</div>
				<div class="threefourth disc-content">
					<div class="panel">
						<button id="newdisc">Start a New Discussion</button>
						<div class="gutter clearfix">
							<?php get_template_part('membernetwork/content','discussionnew'); ?>
							<h2 class="heading quality">Discussions</h2>
							<div id="wrapDisc">
								<?php

									if( isset($_GET['staffmem']) && $isStaff == 'Y' ){

										$args = array( 'post_type' => 'discussion',
														'posts_per_page' => 10,
														'tax_query' => array(
																'relation' => 'IN',
															array(
																'taxonomy' => 'discussions',
																'field' => 'slug',
																'terms' => array('staff'),
															)
														) );

									}else{

										if($isPrivate == 'hospital'){
											if(isset($_GET['assocmem'])){
												$args = array( 'post_type' => 'discussion',
															   'posts_per_page' => 10,
															   'tax_query' => array(
															   		'relation' => 'IN',
																	array(
																		'taxonomy' => 'discussions',
																		'field' => 'slug',
																		'terms' => array('private'),
																	)
																) );
											}else{
												$args = array( 'post_type' => 'discussion',
															   'posts_per_page' => 10,
															   'tax_query' => array(
															   		'relation' => 'IN',
																	array(
																		'taxonomy' => 'discussions',
																		'field' => 'slug',
																		'terms' => array('private','public'),
																	)
																) );
											}
										}else{
											$args = array( 'post_type' => 'discussion',
														   'posts_per_page' => 10,
														   'tax_query' => array(
														   		'relation' => 'IN',
																array(
																	'taxonomy' => 'discussions',
																	'field' => 'slug',
																	'terms' => array('public'),
																)
															) );
										}

									}



									$loop = new WP_Query( $args );
									while ( $loop->have_posts() ) : $loop->the_post();
									$authID = get_the_author_meta('ID');
									$author = get_userdata($authID);
									$authAv = get_avatar($authID, 78);
									$postID = get_the_ID(); ?>
										<div class="discussion-entry">
											<?php
												$isPrivate = wp_get_post_terms( $postID, 'discussions', array('fields' => 'slugs') );
												if(in_array( 'private', $isPrivate )){
													echo '<div class="membersonly"></div>';
												}
												if(in_array( 'staff', $isPrivate )){
													echo '<div class="staffonly"></div>';
												}
											?>
											<div class="gutter clearfix">
												<div class="discussion-meta">
													<h3 class="discussion"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
													<p>Discussion Started by <?php echo $author->user_firstname; ?> <?php echo $author->user_lastname; ?> on <?php the_time('M j, g:i a');?> || <span class="orange"><em><?php comments_number( '(0 replies)', '(1 reply)', '(% replies)' ); ?></em></span></p>
												</div>
												<div class="discussion-user">
													<span><?php echo $author->user_firstname; ?> <?php echo $author->user_lastname; ?></span>
													<?php echo $authAv; ?>
												</div>
												<div class="discussion-content">
													<?php the_excerpt(); ?>
													<?php
														$args = array(
															'number' => '1',
															'post_id' => $postID, // use post_id, not post_ID
														);
														$comments = get_comments($args);
														foreach($comments as $comment){ ?>
															<span class="discussion-comment"><?php echo $comment->comment_content; ?> | <?php comment_date( 'M j, g:i a', $comment->comment_ID ); ?> <a href="<?php the_permalink(); ?>">view more &raquo;</a></span>
													<?php } ?>
												</div>
												<div class="discussion-tax">
													<span class="tag">
														<?php
															$terms = wp_get_post_terms( $postID, 'discussion_tags', $args );
															foreach($terms as $term){
																echo $term->name.' ';
															}
														?>
													</span>
												</div>
											</div>
										</div>
								<?php endwhile;?>
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
