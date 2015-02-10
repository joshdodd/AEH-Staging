<?php get_header();
	 
	$bannerArr = get_field('banners',29); 
 
	 while ( have_posts() ) : the_post();
		$pageTheme = get_field('theme');

		if($pageTheme == 'policy'){
			$menu = 'action';
		}elseif($pageTheme == ''){
			$menu = 'general';
		}elseif($pageTheme != 'policy'){
			$menu = $pageTheme;
		}

		//Get featuredIMG
		if($pageTheme == 'policy'){
			$fPID = 62;
			$speakerIMG  = wp_get_attachment_url( get_post_thumbnail_id(62) );
			$bannerTitle = "Action";
		}elseif($pageTheme == 'quality'){
			$fPID = 64;
			$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id(64) );
			$pageTitle= "Quality";
		}elseif($pageTheme == 'institute'){
			$fPID = 621;
			$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id(621) );
			$pageTitle = "Essential Hospitals Institute" ;
		}elseif($pageTheme == 'education'){
			$fPID = 472;
			$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id(472) );
			$pageTitle = "Education" ;
		}else{
			$fPID = 645;
			$randArr = array_rand($bannerArr);
			$speakerIMG = $bannerArr[$randArr]['image'];
			$bannerSize = "";
			$parents = get_post_ancestors( $post->ID );
			$chck_id = ($parents) ? $parents[count($parents)-1]: $parent_id;
			$pageTitle = "ABOUT";
			$pageTheme = 'policy';

			if($chck_id == 645)
				{$bannerSize = ""; $pageTitle = "ABOUT"; $pageTheme = 'policy';}
		}

		//$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id($fPID) );
		 $bannerTitle = get_field('bannerTitle');
	endwhile;	 
?>
	
<div id="featured-img" class="page-single  <?php echo $pageTheme; ?>" style="background-image:url(<?php echo $speakerIMG; ?>); ">
	<div class="container">
		<div id="featured-intro">
			<h3> <span><?php echo $pageTitle; ?> </span><br /> <?php if($bannerTitle != ''){ echo $bannerTitle; }else{ the_title(); }?> </h3>
		</div>
	</div>
</div>

<div id="contentWrap" class="education landing <?php echo $pageTheme; ?> super-special-page">
	<div class="gutter">
		<div class="container">

			<?php
				if(has_nav_menu('primary-menu')){
					$defaults = array(
						'theme_location'  => 'primary-menu',
						'menu'            => 'primary-menu',
						'container'       => 'div',
						'container_class' => '',
						'container_id'    => 'pageNav',
						'menu_class'      => 'quality',
						'menu_id'         => '',
						'echo'            => true,
						'fallback_cb'     => 'wp_page_menu',
						'before'          => '',
						'after'           => '',
						'link_before'     => '',
						'link_after'      => '',
						'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						'depth'           => 2,
						'walker'          => ''
					); wp_nav_menu( $defaults );
				}
			?>
			<div id="breadcrumbs">
				<ul>
					<li><a href="<?php echo home_url(); ?>">Home</a>
						<?php
						$defaults = array(
						'theme_location'  => 'primary-menu',
						'menu'            => 'primary-menu',
						'container'       => '',
						'container_class' => '',
						'container_id'    => '',
						'menu_class'      => 'menu',
						'menu_id'         => '',
						'echo'            => true,
						'fallback_cb'     => 'wp_page_menu',
						'before'          => '',
						'after'           => '',
						'link_before'     => '',
						'link_after'      => '',
						'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						'depth'           => 0,
						'walker'          => ''
					); wp_nav_menu( $defaults ); ?>
					</li>
				</ul>
			</div>

			<div id="contentPrimary">
				<div class="graybar"></div>
				<div class="graybarX"></div>

				<div class="gutter clearfix">
					<div id="institutePostBox" class="eduoverride">

						<!--  Ask and Share Box -->
						<div class="stamp first">
							<div class="panel">
								<h3 id="sharetitle">Share</h3>
								<div id="share">
									<!-- AddThis Button BEGIN -->
									<div class="addthis_toolbox addthis_32x32_style" style="">
									<a class="addthis_button_facebook"></a>
									<a class="addthis_button_twitter"></a>
									<a class="addthis_button_linkedin"></a>
									<a class="addthis_button_pinterest_share"></a>
									<a class="addthis_button_google_plusone_share"></a>
									<a class="addthis_button_email"></a>
									<a class="addthis_button_digg"></a>
									<a class="addthis_button_evernote"></a>
									<a class="addthis_button_compact"></a>
									</div>
									<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
									<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=naphsyscom"></script>
									<!-- AddThis Button END -->
								</div>
							</div>
							<div class="panel ask">
								<div class="gutter clearfix">
									<h2>Ask a Question</h2>
									<p>Contact our team with questions or suggestions regarding education and training opportunities.</p>
									<?php echo do_shortcode('[formidable id=6]'); ?>
								</div>
							</div>
						</div>
						<!--  END Share Box -->

						
						<div class="stamp pad">
							<!--  Upcoming Events Box -->
							<div class="panel grey">
								<div class="item-icon grayy">Upcoming Events
									<img src="<?php bloginfo('template_directory'); ?>/images/icon-education.png" />
								</div>
								<?php
								$today = mktime(0, 0, 0, date('n'), date('j'));
								$args = array(
									'post_type' => 'events',
									'posts_per_page' => 3,
									'order' => 'asc',
									'post_status' => 'publish',
									'meta_query'  => array(
										array(
											'key' => 'date',
											'value' => $today,
											'compare' => '>='
										)
									),
									'orderby' => 'meta_value',
									'meta_key' => 'date',
								);
								$query = new WP_Query($args);

								if ( $query->have_posts() ) { while ( $query->have_posts() ) { 
									$query->the_post();
									$postType = get_field('section');
									$date = get_post_meta( get_the_ID(), 'date', 'true');
									//check post type and apply a color
									if($postType =='policy'){
										$postColor = 'redd';
									}else if($postType =='quality'){
										$postColor = 'greenn';
									}else if($postType =='education'){
										$postColor = 'grayy';
									}else if($postType =='institute'){
										$postColor = 'bluee';
									}else{
										$postColor = 'redd';
									} ?>
									<div class="entry webinar">
										<div class="gutter clearfix">
											<div class="entry-content">
												<p>
													<div class="title <?php echo $postColor; ?>">
														<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
													</div> 
													<span class="date"><?php echo date('M j, Y', $date);?>
													</span> | 
													<span class="excerpt"><?php $exc = get_the_excerpt(); echo substr($exc, 0, 50); ?>
													</span>
												</p>
											</div>
										</div>
									</div>
								<?php }
								echo '<a class="readmore" href="'.get_post_type_archive_link('events').'/?timeFilter=future">All Upcoming Events &raquo;</a>';
								} ?>
							</div>
							<!-- END Upcoming Events Box -->

							<!-- Archived Events Box -->
							<div class="panel grey">
								<div class="item-icon grayy">Past Events
									<img src="<?php bloginfo('template_directory'); ?>/images/icon-education.png" />
								</div>
								<?php
								$today = mktime(0, 0, 0, date('n'), date('j'));
								$args = array(
									'post_type' => 'events',
									'posts_per_page' => 3,
									'order' => 'desc',
									'post_status' => 'publish',
									'meta_query'  => array(
										array(
											'key' => 'date',
											'value' => $today,
											'compare' => '<='
										)
									),
									'orderby' => 'meta_value',
									'meta_key' => 'date',
								);
								$query = new WP_Query($args);

								if ( $query->have_posts() ) { while ( $query->have_posts() ) { 
									$query->the_post();
									$postType = get_field('section');
									$date = get_post_meta( get_the_ID(), 'date', 'true');
									//check post type and apply a color
									if($postType =='policy'){
										$postColor = 'redd';
									}else if($postType =='quality'){
										$postColor = 'greenn';
									}else if($postType =='education'){
										$postColor = 'grayy';
									}else if($postType =='institute'){
										$postColor = 'bluee';
									}else{
										$postColor = 'redd';
									} ?>
									<div class="entry webinar">
										<div class="gutter clearfix">
											<div class="entry-content">
												<p>
													<div class="title <?php echo $postColor; ?>">
														<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
													</div> 
													<span class="date"><?php echo date('M j, Y', $date);?>
													</span> | 
													<span class="excerpt"><?php $exc = get_the_excerpt(); echo substr($exc, 0, 50); ?>
													</span>
												</p>
											</div>
										</div>
									</div>
								<?php }
								echo '<a class="readmore" href="'.get_post_type_archive_link('events').'/?timeFilter=past">All Archived Events &raquo;</a>';
								} ?>
						 
							</div>
						<!-- END Archived Events Box -->

 
						</div>
						

						<!-- Main Content Box -->
						<?php while ( have_posts() ) : the_post(); ?>
						<div class="stamp post grayy education about wide long columns">
							<div class="graybarright"></div>
							<div class="item-bar"></div>
							<div class="item-icon grayy">Essential Hospitals Events
								<img src="<?php bloginfo('template_directory'); ?>/images/icon-education.png" />
							</div>
							<div class="item-content">
								<?php the_content(); ?>
							</div>
							<div class="bot-border"></div>

						
						</div>
						<?php endwhile; ?>
						<!-- END Main Content Box -->

						
						<?php
						$args = array(
								'post_type' => 'alert',
								'posts_per_page'=> 4,
								'orderby'   => 'date',
								'order'     => 'asc',
								'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'field'    => 'slug',
										'terms'    => array('announcements'),
										'operator' => 'IN'
									),
									array(
										'taxonomy' => 'category',
										'field'    => 'slug',
										'terms'    => array( 'events' ),
										'operator' => 'IN'
									)
								)
							);
							$query = new WP_Query($args);
							if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post(); ?>
								<div class="panel announcement education-alert event grey post fluid" style="width: 272px !important;">
									<div class="item-icon grayy"><?php updates ?>
										<img src="<?php bloginfo('template_directory'); ?>/images/icon-education.png" />
									</div>
									<div class="gutter">
										<h2><a href="<?php the_field('link'); ?>"><?php the_field('heading'); ?></a></h2>
										<a href="<?php the_field('link'); ?>"><?php the_field('label'); ?> &raquo;</a>
									</div>
									<div class="bot-border"></div>
								</div>
						<?php } } wp_reset_query(); ?>
						
						


						 

						 



						
					</div><!-- END PostBox -->	
				</div><!-- END Gutter --> 
			</div><!-- End of Primary -->
		</div><!-- End of Container -->
	</div> <!-- End of Gutter -->
</div><!-- End of Content -->

<?php get_footer(); ?>