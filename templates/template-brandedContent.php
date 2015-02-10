<?php /* Template Name: Branded Content: Faces  */
get_header('branded'); ?>

<section id="hoverbanner">
	<div class="container" id="bannerAb">
		<div id="banner_text">
			<div class="gutter">
		        <span class="orange">
		          <em>Essential People</em>
		          <em>Essential Communities</em>
		          <span class="bold">Essential Hospitals</span>
		        </span>
		        <span class="secondary">These are the faces<br>and stories behind<br>our essential hospitals<br>across the country</span>
			</div>
	      </div>
	</div>
</section>

<section id="brand-focus">
	<div id="brand-scrollable">
	<?php global $post;
	$args = array(
		'posts_per_page'   => -1,
		'offset'           => 0,
		'category'         => '',
		'orderby'          => 'post_date',
		'order'            => 'DESC',
		'include'          => '',
		'exclude'          => '',
		'meta_key'         => '',
		'meta_value'       => '',
		'post_type'        => 'story',
		'post_mime_type'   => '',
		'post_parent'      => '',
		'post_status'      => 'publish',
		'suppress_filters' => true );
		$posts = get_posts($args);
		$i = 0;
		foreach($posts as $post){ setup_postdata($post); ?>
			<div class="brand-focus-entry" id="entry-<?php echo $post->ID; ?>">
				<div class="brand-focus-info">
					<div class="gutter">
						<h2><?php $title = get_the_title();
							$titleBreak = explode(' ',$title);
							foreach($titleBreak as $title){
								echo "<span>$title</span>";
							} ?></h2>
						<span class="brand-focus-position"><?php echo get_field('occupation'); ?></span>
						<span class="brand-focus-hospital"><?php echo get_field('hospital'); ?></span>
						<div class="brand-focus-about">
							<div class="legacy-condensed active">
								<?php echo get_field('legacy_cond'); ?>
							</div>
							<div class="legacy-expand">
								<?php echo get_field('legacy_expand');?>
							</div>
							<div class="readmore">Read More on <?php the_title(); ?> &raquo;</div>
						</div>
					</div>
				</div>
				<?php $image = wp_get_attachment_image_src( get_field('portrait'), 'story-focus' ); ?>
				<img src="<?php echo $image[0]; ?>" />
			</div>
		<?php $i++; } wp_reset_postdata(); ?>

	</div>
</section>

<section id="brand-nav">
	<div class="container">
		<?php global $post;
		$args = array(
			'posts_per_page'   => -1,
			'offset'           => 0,
			'category'         => '',
			'orderby'          => 'post_date',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => 'story',
			'post_mime_type'   => '',
			'post_parent'      => '',
			'post_status'      => 'publish',
			'suppress_filters' => true );
			$posts = get_posts($args);
			$i = 0;
			$counter = 1;
			foreach($posts as $post){ setup_postdata($post); ?>
				<div class="brand-nav-entry <?php echo cycle('left', 'center', 'right') ?>" id="nav-entry-<?php echo $post->ID; ?>">
					<div class="brand-nav-info">
						<div class="gutter">
							<h2><span>Name: </span><?php the_title(); ?></h2>
							<span class="brand-nav-position"><?php echo get_field('occupation'); ?></span>
							<span class="brand-nav-hospital"><?php echo get_field('hospital'); ?></span>
							<div class="brand-nav-about"><?php echo get_field('legacy_cond');?></div>
						</div>
					</div>
					<?php $image = wp_get_attachment_image_src( get_field('portrait'), 'story-focus' ); ?>
					<img src="<?php echo $image[0]; ?>" />
				</div>
			<?php $i++; $counter++; } wp_reset_postdata(); ?>
	</div>
</section>

<?php get_footer('sans'); ?>