<?php /* Template Name: Authors */
get_header();
	global $post;
	$postSt = $post->post_status;
	$current_user = wp_get_current_user();
	$cUID = $current_user->ID;
	$cUStaff = get_user_meta($cUID,'staff_mem',true);
	if($postSt == 'private'){
		if($cUStaff == 'Y'){ ?>
			<?php while ( have_posts() ) : the_post();
		$pageTheme = get_field('theme');
		$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>
		<div id="featured-img" class="page-single <?php echo $pageTheme; ?>" style="background-image:url(<?php echo $speakerIMG; ?>);">
			<div class="container">
				<div id="featured-intro">
					<h3><?php the_field('bannerTitle'); ?></h3>
				</div>
			</div>
		</div>
		<div id="content" class="page-single default <?php echo $pageTheme; ?>">
			<div class="container">
				<?php
					if(has_nav_menu('general-nav')){
						$defaults = array(
							'theme_location'  => 'general-nav',
							'menu'            => 'general-nav',
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
							'depth'           => 0,
							'walker'          => ''
						);
						wp_nav_menu( $defaults );
					}
				?>
				<div id="contentColumnWrap">
					<div class="graybarright"></div>
					<div class="graybarleft"></div>
					<div id="contentPrimary" class="heightcol">
						<div class="gutter">
							<h2><?php the_title(); ?></h2>
							<?php the_content(); ?>
						</div>
					</div>
					<div id="contentSecondary" class="heightcol">
						<div class="gutter">
							<ul>
							<?php $args = array(
								'depth'        => 0,
								'show_date'    => '',
								'date_format'  => get_option('date_format'),
								'child_of'     => 868,
								'exclude'      => '',
								'include'      => '',
								'title_li'     => __('Pages'),
								'echo'         => 1,
								'authors'      => '',
								'sort_column'  => 'menu_order, post_title',
								'link_before'  => '',
								'link_after'   => '',
								'walker'       => '',
								'post_type'    => 'page',
							    'post_status'  => 'publish'
							); wp_list_pages($args); ?>
							</ul>

							<?php the_field('secondColumn'); ?>
						</div>
					</div>
					<div id="contentTertiary" class="heightcol">
						<div class="gutter">
							<?php the_field('third_column'); ?>
						</div>
					</div>
				</div>
			</div>
		<?php endwhile; // end of the loop. ?>
		</div><!-- End of Content -->
	<?php }else{ ?>
		<div>Private</div>
	<?php } ?>

	<?php }else{ ?>
		<?php while ( have_posts() ) : the_post();
		$pageTheme = get_field('theme');
		$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>
		<div id="featured-img" class="page-single <?php echo $pageTheme; ?>" style="background-image:url(<?php echo $speakerIMG; ?>);">
			<div class="container">
				<div id="featured-intro">
					<h3><?php the_field('bannerTitle'); ?></h3>
				</div>
			</div>
		</div>
		<div id="content" class="page-single default <?php echo $pageTheme; ?>">
			<div class="container">
				<?php
					if(has_nav_menu('general-nav')){
						$defaults = array(
							'theme_location'  => 'general-nav',
							'menu'            => 'general-nav',
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
							'depth'           => 0,
							'walker'          => ''
						);
						wp_nav_menu( $defaults );
					}
				?>
				<div id="contentColumnWrap">
					<div class="graybarright"></div>
					<div class="graybarleft"></div>
					<div id="contentPrimary" class="heightcol">
						<div class="gutter">
							<h2><?php the_title(); ?></h2>
							<?php the_content(); ?>
							<?php $authLink = get_field('author_link'); ?>
							<a class="readmore" href="<?php site_url(); ?>/authors?authID=<?php echo $authLink['ID']; ?>">view more &raquo;</a>
						</div>
					</div>
					<div id="contentSecondary" class="heightcol">
						<div class="gutter">
							<h3>All Authors</h3>
							<ul>
								<?php $args = array(
									'depth'        => 0,
									'show_date'    => '',
									'date_format'  => get_option('date_format'),
									'child_of'     => 868,
									'exclude'      => '',
									'include'      => '',
									'title_li'     => '',
									'echo'         => 1,
									'authors'      => '',
									'sort_column'  => 'menu_order, post_title',
									'link_before'  => '',
									'link_after'   => '',
									'walker'       => '',
									'post_type'    => 'page',
								    'post_status'  => 'publish'
								); wp_list_pages($args); ?>
							</ul>
							<?php the_field('secondColumn'); ?>
						</div>
					</div>
					<div id="contentTertiary" class="heightcol">
						<div class="gutter">
							<?php the_field('third_column'); ?>
						</div>
					</div>
				</div>
			</div>
		<?php endwhile; // end of the loop. ?>
		</div><!-- End of Content -->
	<?php } ?>

<?php get_footer(); ?>