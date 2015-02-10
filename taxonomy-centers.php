<?php
	get_header();
?>
<?php $speakerIMG = wp_get_attachment_url( get_post_thumbnail_id(621) ); ?>
<div id="featured-img" class="institute" style="background-image:url(<?php echo $speakerIMG; ?>);">
	<div class="container">
		<div id="featured-intro">

			<h3><span>Essential Hospitals Institute </span><br /><?php single_cat_title(); ?></h3>
		</div>
	</div>
</div>

<?php
	$queried_object = get_queried_object();
	$term_id = $queried_object->term_id;
	$term_slug = $queried_object->slug;
    $term_meta = get_option( "taxonomy_term_$term_id" );
?>


<!--<script src="<?php bloginfo('template_directory'); ?>/js/infinite.js"></script>!-->
<div id="contentWrap" class="institute">
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

				<a href="<?php echo site_url('/feed/'); ?>?center=<?php echo $term_slug; ?>" target="_blank">
					<div id="rssFeedIcon" class="institute">
						Subscribe
					</div>
				</a>

			</div>
			<div id="layoutPos">
				<div id="contentPrimary">
					<div class="graybar"></div>
					<div class="graybarX"></div>
				<div class="gutter clearfix">
					<div id="institutePostBox" class="infinite" data-center="<?php echo $term_slug; ?>">

					<div class="stamp first">
						<?php
							if(has_nav_menu('institute-nav')){
								$defaults = array(
									'theme_location'  => 'institute-nav',
									'menu'            => 'institute-nav',
									'container'       => 'div',
									'container_class' => 'panel subnav',
									'container_id'    => '',
									'menu_class'      => 'institute',
									'menu_id'         => '',
									'echo'            => true,
									'fallback_cb'     => 'wp_page_menu',
									'before'          => '',
									'after'           => '',
									'link_before'     => '',
									'link_after'      => '',
									'items_wrap'      => '<div class="bottombar"></div><div class="gutter"><ul>%3$s</ul></div>',
									'depth'           => 0,
									'walker'          => ''
								);
								wp_nav_menu( $defaults );
							}
						?>


						<?php if(single_cat_title('',false) == 'Research Center'){ ?>
						<div class="panel twitter clearfix bluee">
							<div class="item-icon">Research Tweets <img src="<?php bloginfo('template_directory'); ?>/images/icon-institute.png" /></div>
							<?php display_latest_tweets('OurHospitals','research',2); ?>
							<div class="bot-border"></div>
						</div>
						<?php } ?>


						<?php
						$args = array(
							'post_type' => 'alert',
							'posts_per_page'=> 1,
							'orderby'   => 'date',
							'order'     => 'asc',
							'tax_query' => array(
								'relation' => 'AND',
								array(
									'taxonomy' => 'category',
									'field'    => 'slug',
									'terms'    => array( 'announcement' )
								),
								array(
									'taxonomy' => 'category',
									'field'    => 'slug',
									'terms'    => array( 'institute' )
								),
								array(
									'taxonomy' => 'category',
									'field'    => 'slug',
									'terms'    => array( 'updates' ),
									'operator' => 'NOT IN'
								)
							)
						);
						$query = new WP_Query($args);
						if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post(); ?>
							<div class="panel announcement">
								<div class="bottombar"></div>
								<div class="next"><img src="<?php bloginfo('template_directory'); ?>/images/instituteAnnouncement.png" /></div>
								<div class="gutter">
									<h2><?php the_field('heading'); ?></h2>
									<a href="<?php the_field('link'); ?>"><?php the_field('label'); ?> &raquo;</a>
								</div>
							</div>
						<?php } } wp_reset_query(); ?>

					</div>

					<div class="stamp post bluee institute about wide long columns">
						<div class="graybarright"></div>
						<div class="item-bar"></div>
						<div class="item-icon">About <img src="<?php bloginfo('template_directory'); ?>/images/icon-institute.png" /></div>
						<div class="item-content">
							<h2>About the <?php single_cat_title(); ?></h2>
							<p><?php echo $term_meta['about_center']; ?></p>
						</div>
						<div class="bot-border"></div>
					</div>




					<?php $today = mktime(0, 0, 0, date('n'), date('j'));
						$args = array(
							'post_type' => 'webinar',
							'orderby' => 'meta_value',
							'order' => 'asc',
							'posts_per_page' => 1,
							'post_status' => 'all',
							'meta_query'  => array(
								array(
									'key' => 'webinar_date',
									'value' => $today,
									'compare' => '>='
								)
							)
						);
						$query = new WP_Query($args);
						if(count($posts) > 0){ ?>
							<?php if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post(); ?>
								<div class="panel webinar stamp">
									<div class="item-bar"></div>
									<div class="item-icon">Upcoming Webinar <img src="<?php bloginfo('template_directory'); ?>/images/icon-education.png" /></div>
									<div class="item-content clearfix">
										<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
										<?php the_excerpt();
											echo '<span class="institute reserve button institute"><a href="'.get_field('registration_link').'">Reserve Your Spot</a></span>'; ?>
									</div>
									<div class="bot-border"></div>
								</div>
							<?php }  ?>
						<?php } } wp_reset_query(); ?>



					<?php
					$args = array(
						'post_type' => array('policy','quality','institute'),
						'meta_key' => 'sticky_topic',
						'meta_value' => $term_slug,
						'meta_compare' => '='
					);
					$query = new WP_Query($args);
					$layoutArray = array('tall','short');
					if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post();
						$rand_key = array_rand($layoutArray, 1);
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
							} ?>
					<div class="post long columns bluee fluid <?php echo get_post_type( get_the_ID() ); ?> <?php echo $layoutArray[$rand_key]; ?>">
							<div class="graybarright"></div>
				  			<div class="item-bar"></div>
			    			<div class="item-icon">
			    				<?php $terms = wp_get_post_terms(get_the_ID(), 'series');
			    					if($terms){
				    					$termLink = get_term_link($terms[0], 'series');
					    				echo "<a href='".$termLink."'>".$terms[0]->name."</a>";
				    				}
			    				?>
			    				<img src="<?php bloginfo('template_directory'); ?>/images/icon-<?php echo get_post_type( get_the_ID() ); ?>.png" />
			    			</div>
			    			<div class="item-content">
				    			<div class="item-header">
				    				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				    				<span class="item-date"><?php the_time('M j, Y'); ?> ||</span>
				    				<span class="item-author"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>/?prof=article"><?php the_author(); ?></a></span>
				    			</div>
				    			<p><?php $exc = get_the_excerpt(); echo substr($exc, 0, 100); ?><a class="more" href="<?php the_permalink(); ?>"> view more » </a>
				    			</p>
				    			<div class="item-tags">
				    				<?php the_tags(' ',' ',' '); ?>
				    			</div>
				    		</div>
				    		<div class="bot-border"></div>
				  		</div>
				<?php } } wp_reset_query(); ?>


				<?php if($queried_object->slug == 'transformationcenter'){ ?>
						<?php
					$args = array(
						'post_type'  => 'institute',
						'posts_per_page' => 1,
						'series' => 'ehen-outcomes'
					);
					$query = new WP_Query($args);
					$layoutArray = array('tall','short');
					if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post();
						$rand_key = array_rand($layoutArray, 1);
					?>
							<div class="post long columns fluid clearfix <?php echo $layoutArray[$rand_key]; ?> bluee institute">
								<div class="graybarright"></div>
					  			<div class="item-bar"></div>
				    			<div class="item-icon">
				    				<?php $terms = wp_get_post_terms(get_the_ID(), 'series');
				    					if($terms){
					    					$termLink = get_term_link($terms[0], 'series');
						    				echo "<a href='".$termLink."'>".$terms[0]->name."</a>";
					    				}
				    				?>
									<?php if($postType != 'post'){ ?>
					    				<img src="<?php bloginfo('template_directory'); ?>/images/icon-institute.png" />
				    				<?php } ?>
				    			</div>
				    			<div class="item-content">
					    			<div class="item-header">
					    				<h2><a href="<?php if(get_field('link_to_media')){the_field('uploaded_file');}else{the_permalink();} ?>"><?php the_title(); ?></a></h2>
					    				<span class="item-date"><?php the_time('M j, Y'); ?> ||</span>
					    				<span class="item-author"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>/?prof=article"><?php the_author(); ?></a></span>
					    			</div>
					    			<?php if(get_field('link_to_media')){ ?>
										<a href="<?php the_field('uploaded_file'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/institute-doc.png" /></a>
									<?php }else{ ?>
										<p><?php $exc = get_the_excerpt(); echo $exc; ?><a class="more" href="<?php the_permalink(); ?>"> view more » </a></p>
									<?php } ?>
					    			<div class="item-tags">
					    				<?php the_tags(' ',' ',' '); ?>
					    			</div>
					    		</div>
					    		<div class="bot-border"></div>
					  		</div>

					<?php } } ?>

					<?php
					$args = array(
						'post_type'  => 'institute',
						'posts_per_page' => 1,
						'series' => 'in-the-field'
					);
					$query = new WP_Query($args);
					$layoutArray = array('tall','short');
					if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post();
						$rand_key = array_rand($layoutArray, 1);
					?>
							<div class="post long columns fluid clearfix <?php echo $layoutArray[$rand_key]; ?> bluee institute">
								<div class="graybarright"></div>
					  			<div class="item-bar"></div>
				    			<div class="item-icon">
				    				<?php $terms = wp_get_post_terms(get_the_ID(), 'series');
				    					if($terms){
					    					$termLink = get_term_link($terms[0], 'series');
						    				echo "<a href='".$termLink."'>".$terms[0]->name."</a>";
					    				}
				    				?>
									<?php if($postType != 'post'){ ?>
					    				<img src="<?php bloginfo('template_directory'); ?>/images/icon-institute.png" />
				    				<?php } ?>
				    			</div>
				    			<div class="item-content">
					    			<div class="item-header">
					    				<h2><a href="<?php if(get_field('link_to_media')){the_field('uploaded_file');}else{the_permalink();} ?>"><?php the_title(); ?></a></h2>
					    				<span class="item-date"><?php the_time('M j, Y'); ?> ||</span>
					    				<span class="item-author"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>/?prof=article"><?php the_author(); ?></a></span>
					    			</div>
					    			<?php if(get_field('link_to_media')){ ?>
										<a href="<?php the_field('uploaded_file'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/institute-doc.png" /></a>
									<?php }else{ ?>
										<p><?php $exc = get_the_excerpt(); echo $exc; ?><a class="more" href="<?php the_permalink(); ?>"> view more » </a></p>
									<?php } ?>
					    			<div class="item-tags">
					    				<?php the_tags(' ',' ',' '); ?>
					    			</div>
					    		</div>
					    		<div class="bot-border"></div>
					  		</div>

					<?php } } ?>
				<?php }elseif($queried_object->slug == 'researchcenter'){ ?>
						<?php
					$args = array(
						'post_type'  => 'institute',
						'posts_per_page' => 1,
						'series' => 'publications'
					);
					$query = new WP_Query($args);
					$layoutArray = array('tall','short');
					if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post();
						$rand_key = array_rand($layoutArray, 1);
					?>
							<div class="post long columns fluid clearfix <?php echo $layoutArray[$rand_key]; ?> bluee institute">
								<div class="graybarright"></div>
					  			<div class="item-bar"></div>
				    			<div class="item-icon">
				    				<?php $terms = wp_get_post_terms(get_the_ID(), 'series');
				    					if($terms){
					    					$termLink = get_term_link($terms[0], 'series');
						    				echo "<a href='".$termLink."'>".$terms[0]->name."</a>";
					    				}
				    				?>
									<?php if($postType != 'post'){ ?>
					    				<img src="<?php bloginfo('template_directory'); ?>/images/icon-institute.png" />
				    				<?php } ?>
				    			</div>
				    			<div class="item-content">
					    			<div class="item-header">
					    				<h2><a href="<?php if(get_field('link_to_media')){the_field('uploaded_file');}else{the_permalink();} ?>"><?php the_title(); ?></a></h2>
					    				<span class="item-date"><?php the_time('M j, Y'); ?> ||</span>
					    				<span class="item-author"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>/?prof=article"><?php the_author(); ?></a></span>
					    			</div>
					    			<?php if(get_field('link_to_media')){ ?>
										<a href="<?php the_field('uploaded_file'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/institute-doc.png" /></a>
										<p><?php $exc = get_the_excerpt(); echo $exc; ?><a class="more" href="<?php the_permalink(); ?>"> view more » </a></p>
									<?php }else{ ?>
										<p><?php $exc = get_the_excerpt(); echo $exc; ?><a class="more" href="<?php the_permalink(); ?>"> view more » </a></p>
									<?php } ?>
					    			<div class="item-tags">
					    				<?php the_tags(' ',' ',' '); ?>
					    			</div>
					    		</div>
					    		<div class="bot-border"></div>
					  		</div>

					<?php } } ?>
					<?php
					$args = array(
						'post_type'  => 'institute',
						'posts_per_page' => 1,
						'series' => 'research-project'
					);
					$query = new WP_Query($args);
					$layoutArray = array('tall','short');
					if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post();
						$rand_key = array_rand($layoutArray, 1);
					?>
							<div class="notrunc post long columns fluid clearfix <?php echo $layoutArray[$rand_key]; ?> bluee institute">
								<div class="graybarright"></div>
					  			<div class="item-bar"></div>
				    			<div class="item-icon">
				    				<?php $terms = wp_get_post_terms(get_the_ID(), 'series');
				    					if($terms){
					    					$termLink = get_term_link($terms[0], 'series');
						    				echo "<a href='".$termLink."'>".$terms[0]->name."</a>";
					    				}
				    				?>
									<?php if($postType != 'post'){ ?>
					    				<img src="<?php bloginfo('template_directory'); ?>/images/icon-institute.png" />
				    				<?php } ?>
				    			</div>
				    			<div class="item-content">
					    			<div class="item-header">
					    				<h2><a href="<?php if(get_field('link_to_media')){the_field('uploaded_file');}else{the_permalink();} ?>"><?php the_title(); ?></a></h2>
					    				<span class="item-date"><?php the_time('M j, Y'); ?> ||</span>
					    				<span class="item-author"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>/?prof=article"><?php the_author(); ?></a></span>
					    			</div>
					    			<?php if(get_field('link_to_media')){ ?>
										<a href="<?php the_field('uploaded_file'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/institute-doc.png" /></a>
										<p><?php $exc = get_the_excerpt(); echo $exc; ?><a class="more" href="<?php the_permalink(); ?>"> view more » </a></p>
									<?php }else{ ?>
										<p><?php $exc = get_the_excerpt(); echo $exc; ?><a class="more" href="<?php the_permalink(); ?>"> view more » </a></p>
									<?php } ?>
					    			<div class="item-tags">
					    				<?php the_tags(' ',' ',' '); ?>
					    			</div>
					    		</div>
					    		<div class="bot-border"></div>
					  		</div>

					<?php } } ?>
				<?php } ?>


					<?php
						$args = array(
							'post_type' => $postType,
							'centers'  => $term_slug,
							'posts_per_page' => 2,
							'paged' => $page
						);
						$query = new WP_Query($args);
					if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post();
						$rand_key = array_rand($layoutArray, 1);
					?>
					<div class="notrunc post long columns bluee fluid <?php echo get_post_type( get_the_ID() ); ?> <?php echo $layoutArray[$rand_key]; ?> ">
						<div class="graybarright"></div>
			  			<div class="item-bar"></div>
		    			<div class="item-icon">
		    				<?php $terms = wp_get_post_terms(get_the_ID(), 'series');
		    					if($terms){
			    					$termLink = get_term_link($terms[0], 'series');
				    				echo "<a href='".$termLink."'>".$terms[0]->name."</a>";
			    				}
		    				?>
							<?php if($postType != 'post'){ ?>
			    				<img src="<?php bloginfo('template_directory'); ?>/images/icon-<?php echo get_post_type( get_the_ID() ); ?>.png" />
		    				<?php } ?>
		    			</div>
		    			<div class="item-content">
			    			<div class="item-header">
			    				<h2><a href="<?php if(get_field('link_to_media')){the_field('uploaded_file');}else{the_permalink();} ?>"><?php the_title(); ?></a></h2>
			    				<span class="item-date"><?php the_time('M j, Y'); ?> ||</span>
			    				<span class="item-author"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>/?prof=article"><?php the_author(); ?></a></span>
			    			</div>
			    			<?php if(get_field('link_to_media')){ ?>
								<a href="<?php the_field('uploaded_file'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/<?php echo get_post_type( get_the_ID() ); ?>-doc.png" /></a>
								<p><?php $exc = get_the_excerpt(); echo $exc; ?><a class="more" href="<?php the_permalink(); ?>"> view more » </a></p>
							<?php }else{ ?>
								<p><?php $exc = get_the_excerpt(); echo $exc; ?><a class="more" href="<?php the_permalink(); ?>"> view more » </a></p>
							<?php } ?>
			    			<div class="item-tags">
			    				<?php the_tags(' ',' ',' '); ?>
			    			</div>
			    		</div>
			    		<div class="bot-border"></div>
			  		</div>

					<?php } }else{ echo '<div class="post bluee institute short long columns">
						<div class="graybarright"></div>
						<div class="item-bar"></div>
						<div class="item-icon">No Posts <img src="'.get_bloginfo('template_directory').'/images/icon-institute.png" /></div>
						<div class="item-content">
							<h2>No Posts Found</h2>
							<p>check back often, there is more to come</p>
						</div>
						<div class="bot-border"></div>
					</div>'; } ?>

					<div id="infinite-indicator"><img src="<?php bloginfo('template_directory'); ?>/images/loader-gif.gif"><span>Loading More Articles</span></div>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>
</div>
<?php
	get_footer();
?>