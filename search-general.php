<?php
$rand = rand(1,9);
			$speakerIMG = "http://mlinson.staging.wpengine.com/wp-content/uploads/2013/11/AEH_generalbanner" .$rand . "_222.jpg";
			$pageTheme  = 'policy';
			$bannerSize = "";
			?>

<?php if ( have_posts() ) : ?>
		<div id="featured-img" class="page-single  <?php echo $pageTheme; ?>" style="background-image:url(<?php echo $speakerIMG; ?>); ">
			<div class="container">
				<div id="featured-intro">
					<h3><span><?php printf( __( 'SEARCH RESULTS %s' ), '</span><br/>' . get_search_query() . '</span>' ); ?></h3>
				</div>
			</div>
		</div>
		<div id="content" class="page-single default <?php echo $pageTheme; ?>">
				<div class="container">
					<div id="contentColumnWrap">
						<div class="graybarright"></div>
						<div class="graybarleft"></div>
						<div id="contentPrimary" class="heightcol">
							<div class="gutter">
								<?php while ( have_posts() ) : the_post(); ?>
									<div class="post">
										<h2 class="<?php echo get_post_type(); ?>"><a class="<?php echo get_post_type(); ?>" href="<?php if(get_post_type() == 'alert'){$link = get_field('link');if($link){echo $link;}else{the_permalink();}}else{the_permalink();} ?>"><?php the_title(); ?></a></h2>
										<?php if(get_post_type() == 'post'){ ?>
											<p class="postinfo">By <?php the_author(); ?> | Categories: <?php the_category(', '); ?> | <?php comments_popup_link(); ?></p>
										<?php } ?>
										<p><?php the_excerpt(); ?></p>
									</div>
									<hr>
								<?php endwhile; ?>
						</div><!-- End of Content -->
					</div>
				</div>
			</div>
		</div>

	<?php else : ?>
		<h1>Nothing Found</h1>
		<p>Nothing matched your search criteria. Please try again with some different keywords.</p>

		<?php get_search_form(); ?>
	<?php endif; ?>