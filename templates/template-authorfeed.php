<?php
/* Template Name: Author Feed */
get_header();
$authID = $_GET['authID'];
$author = get_userdata($authID); ?>

<?php $speakerIMG = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>
<div id="featured-img" class="archive tag">
	<div class="container">
		<div id="featured-intro">
			<h3 class="tag">All Articles by: <?php echo $author->first_name; ?> <?php echo $author->last_name; ?></h3>
		</div>
	</div>
</div>
<div id="postFeatured">
	<div class="container fullborder">
 		<div class="eightteen columns filters">

 		</div>
 	</div>
    <div class ="grayblock"></div>

	<div class="container twelve columns content">


		<div id="contentWrap" class="action author">
			<div class="gutter">
				<div class="container">
					<div id="contentPrimary">
						<div class="graybar"></div>
						<div class="gutter">
							<div id="postBox" class="clearfix">


								<div id="fader" class="clearfix scrollable">
										<div class="items">
										<?php
										$args = array(
											'author' => $authID,
											'post_type' => array('policy','institute','quality'),
											'posts_per_page' => -1
										);
										$query = new WP_Query($args);
										if ( $query->have_posts() ) while ( $query->have_posts() ) : $query->the_post();
											$postType = get_post_type( get_the_ID() );

											//check post type and apply a color
											if($postType == 'policy'){
												$postColor = 'redd';
											}else if($postType == 'quality'){
												$postColor = 'greenn';
											}else if($postType == 'education'){
												$postColor = 'grayy';
											}else if($postType == 'institute'){
												$postColor = 'bluee';
											}else{
												$postColor = 'bluee';
											}
										?>

										<div class="post long columns <?php echo $postColor; ?>  <?php echo get_post_type( get_the_ID() ); ?> ">
											<div class="graybarright"></div>
								  			<div class="item-bar"></div>
							    			<div class="item-icon">
							    				<?php $terms = wp_get_post_terms(get_the_ID(), 'series');
							    					if($terms){
								    					$termLink = get_term_link($terms[0], 'series');
									    				echo "<a href='".$termLink."'>".$terms[0]->name."</a>";
								    				}
							    				?>
							    				<img src="<?php bloginfo('template_directory'); ?>/images/icon-<?php echo $postType; ?>.png" /></div>
							    			<div class="item-content">
								    			<div class="item-header">
								    				<h2><?php the_title(); ?></h2>
								    				<span class="item-date"><?php the_time('M j, Y'); ?> ||</span>
								    				<span class="item-author"><?php the_author(); ?></span>
								    			</div>
								    			<p><?php $exc = get_the_excerpt(); echo substr($exc, 0, 100); ?><a class="more" href="<?php the_permalink(); ?>"> view more » </a>
								    			</p>
								    			<div class="item-tags">
								    				<?php the_tags(' ',' ',' '); ?>
								    			</div>
								    		</div>
								    		<div class="bot-border"></div>
								  		</div>


									<?php endwhile; wp_reset_query();?>
										</div>
								</div>
							</div>


						</div>
					</div>
				</div>
			</div>
		</div>


	</div><!-- End of Content -->
	<div id="prev" title="Show previous"> </div>
	<div id="next" title="Show more Articles"> </div>

	<a id="prevbtn" title="Show previous">  </a>
	<a id="nextbtn" title="Show more">  </a>
</div>

<?php get_footer(); ?>