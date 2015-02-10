<div id="memberNews" class="clearfix">
<h2 class="heading">Headlines selected for you</h2>
<?php
	$userID   = get_current_user_id();
	$usermeta = get_user_meta($userID, 'custom_news_feed', true);
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$args =  array(
		'posts_per_page' => 10,
        'post_type' => array('policy','institute','quality'),
        'tax_query' => array(
			'relation' => 'OR',
			array(
				'taxonomy' => 'policytopics',
				'field' => 'slug',
				'terms' => $usermeta
			),
			array(
				'taxonomy' => 'qualitytopics',
				'field' => 'slug',
				'terms' => $usermeta
			),
			array(
				'taxonomy' => 'educationtopics',
				'field' => 'slug',
				'terms' => $usermeta
			),
			array(
				'taxonomy' => 'institutetopics',
				'field' => 'slug',
				'terms' => $usermeta
			)
		),
		'paged' => $paged,
    );
    $query = new WP_Query($args);
    // The Loop
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
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
			<div class="post long columns <?php echo $postColor; ?>  <?php echo get_post_type( get_the_ID() ); ?> ">
	  			<div class="item-bar"></div>
    			<div class="item-icon"><img src="<?php bloginfo('template_directory'); ?>/images/icon-<?php echo $postType; ?>.png" /></div>
    			<div class="item-content">
	    			<div class="item-header">
	    				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	    				<span class="item-date"><?php the_time('M j, Y'); ?> ||</span>
	    				<span class="item-author"><?php the_author(); ?></span>
	    			</div>
	    			<p><?php the_excerpt(); ?><a class="more" href="<?php the_permalink(); ?>"> view more » </a></p>
	    			<div class="item-tags">
	    				<?php the_tags(' ',' ',' '); ?>
	    			</div>
	    		</div>
	    		<div class="bot-border"></div>
	  		</div>
		<?php } ?>
		<div id="memberNews-pag">
			<div class="gutter clearfix">
				<span id="memberNews-back"><?php echo get_next_posts_link( 'Older Entries', $query->max_num_pages ); ?></span>
				<span id="memberNews-next"><?php echo get_previous_posts_link( 'Newer Entries' ); ?></span>
			</div>
		</div>
		<?php }  else {
		wp_reset_postdata();
			echo "<p>Customize this article feed. In the right panel, select your interest areas.</p>";
			get_template_part( 'membernetwork/module', 'allnews' );
			 }
	 ?>

</div>