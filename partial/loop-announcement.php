<?php
$sticky = get_option( 'sticky_posts' );
$args = array(
	'post_type' => 'alert',
	'posts_per_page'=> 3,
	'orderby'   => 'date',
	'order'     => 'asc',
	'tax_query' => array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'category',
			'field'    => 'slug',
			'terms'    => array('announcements'),
			'operator' => 'IN'
		),
		array(
			'taxonomy' => 'category',
			'field'    => 'slug',
			'terms'    => array( 'home' ),
			'operator' => 'IN'
		),
	)
);
$query = new WP_Query($args);
if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post();
	$postType = get_post_type( get_the_ID() ); ?>
<div class="post long columns announcement <?php echo get_post_type( get_the_ID() ); ?> ">
			<div class="graybarright"></div>
			<div class="bgfade">
	  			<div class="item-bar"></div>
				<div class="item-content">
	    			<div class="item-header">
	    				<h2><?php echo get_field('heading'); ?></h2>
	    			</div>
	    			<a class="floatright" href="<?php echo get_field('link'); ?>"><?php echo get_field('label'); ?></a>
	    		</div>
			</div>
  		</div>
<?php } } wp_reset_query(); ?>