<?php
	// Our include
	define('WP_USE_THEMES', false);
	require_once('../../../../wp-load.php');

	//Get variable from AJAX POST
	$ajaxFilter = $_POST['getSearch'];

	//Count our posts and set the $output variable
	$output = '';


	$args = array(
		'post_type' => array('quality','policy','institute','post'),
		'posts_per_page' => 5,
		's' => $ajaxFilter
	);
	query_posts( $args ); while ( have_posts() ) : the_post();
		$postTitle = get_the_title();
		$postType = get_post_type( get_the_ID() );

		if(get_field('link_to_media')){
			$postLink = get_field('uploaded_file');
	    }else{
		    $postLink = get_permalink();
	    }

	    $output .= '<div class="searchresult '.$postType.'"><div class="gutter"><h3><a href="'.$postLink.'">'.$postTitle.'</a></h3></div></div>';

	endwhile; wp_reset_query();
	echo $output; ?>