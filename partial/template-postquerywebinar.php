<?php
	// Our include
	define('WP_USE_THEMES', false);
	require_once('../../../../wp-load.php');

	//Get variable from AJAX POST
	$ajaxFilter = $_POST['ajaxFilter'];
	$timeFilter = $_POST['timeFilter'];

	//Determine if filter is being reset
	$terms = get_terms('webinartopics', 'orderby=count&hide_empty=0');
	 foreach($terms as $term){
	 	$webinars[] = $term->slug;
	 }
	if($ajaxFilter == 'all'){
		$ajaxFilter = $webinars;
	}
	if($ajaxFilter == ''){
		$ajaxFilter = $webinars;
	}
	if(!isset($ajaxFilter)){
		$ajaxFilter = $webinars;
	}

	if($timeFilter == 'future'){
		 $sortCompare = '>=';
		 $sortOrder = 'asc';
		 $timeEx = 'upcoming webinars';
	 }elseif($timeFilter == 'publish'){
		 $sortCompare = '<=';
		 $sortOrder = 'desc';
		 $timeEx = 'recorded webinars';
	 }else{
		 $sortCompare = '!=';
		 $sortOrder = 'asc';
		 $timeEx = 'webinars';
	 }
	 $today = mktime(0, 0, 0, date('n'), date('j'));


	//Count our posts and set the $output variable
	$postCount = 0;
	$output = '';


	$args = array(
		'post_type' => 'webinar',
		'order' => $sortOrder,
		'post_status' => 'all',
		'meta_query'  => array(
			array(
				'key' => 'webinar_date',
				'value' => $today,
				'compare' => $sortCompare
			)
		),
		'tax_query' => array(
			array(
				'taxonomy' => 'webinartopics',
				'field' => 'slug',
				'terms' => $ajaxFilter
			)
		),
		'orderby' => 'meta_value',
		'meta_key' => 'webinar_date',
	);
	//print_r($args);
	query_posts( $args ); if(have_posts()){ while ( have_posts() ) { the_post();
		$postTitle = get_the_title();
		$postExcerpt = get_the_excerpt();

		/*$line=$postExcerpt;
		if (preg_match('/^.{1,100}\b/s', $postExcerpt, $match))
		{
		    $postExcerpt=$match[0];
		}*/

		$postColor = '';
		$postTime = get_the_time('M j, Y');
		$templateDIR = get_bloginfo('template_directory');
		$postAuthor = get_the_author();
		$postLink = get_permalink();
		$postTags = get_the_tags();

		$postType = get_post_type( get_the_ID() );

		$postTerms = wp_get_post_terms( get_the_ID(), 'webinartopics' );
		$newTerm = array();
		foreach($postTerms as $term){
			array_push($newTerm, $term->slug);
		}
		//check post type and apply a color
		if(in_array('policy',$newTerm) || in_array('advocacy',$newTerm)){
			$postColor = 'redd';
			$postType  = 'policy';
		}else if(in_array('quality',$newTerm)){
			$postColor = 'greenn';
			$postType  = 'quality';
		}else if(in_array('education',$newTerm)){
			$postColor = 'grayy';
			$postType  = 'education';
		}else if(in_array('institute',$newTerm)){
			$postColor = 'bluee';
			$postType  = 'institute';
		}else{
			$postColor = 'grayy';
			$postType  = 'education';
		}
		$teaser = get_field('teaser');


	    $output .= '<div class="post long columns tall '. $postColor .' '. $postType .' ">
	    		<div class="graybarright"></div>
	  			<div class="item-bar"></div>
    			<div class="item-icon"><img src="'. $templateDIR .'/images/icon-'. $postType .'.png" /></div>
    			<div class="item-content">
	    			<div class="item-header">
	    				<h2>';

	    $isPrivate = get_field('private_webinar');
	     if($isPrivate){
			$output .= "<div class='private-webinar $postColor'></div>";
		 }
	    $output .= '<a href="'.$postLink.'">'. $postTitle .'</a></h2>
	    				<span class="item-date">'. date('M j, Y', get_field('webinar_date')) .' || '.date('g:i A T',get_field('webinar_date')).'</span>
	    			</div>
	    			'. get_the_excerpt() .'
	    			<a class="more" href="'. $postLink .'"> view more &raquo; </a>';
	    if ( get_post_meta($post->ID, 'webinar_date', true) > $today ) {
			$output .= '<span class="reserve button '.$postType.'"><a href="'.$postLink.'">Reserve Your Spot</a></span>';
		}else{
			$output .= '';
		}

	    $output .=  '<div class="item-tags">';
	    if($postTags){
		    $cnt = 0;
		    foreach($postTags as $tag){
		    	$tagSlug = $tag->slug;
				$tagSlug = str_replace('-',' ', $tagSlug);
				if ($cnt != 0) {$output .= ", ";}
			    $output .= '<a href="'.get_bloginfo('url').'/tag/'.$tag->slug.'">'.$tagSlug.'</a>';
			    $cnt++;
		    }
	    }
	    $output .= '</div>
	    		</div>
	    		<div class="bot-border"></div>
	  		</div>';

	$postCount = $postCount++;
	} }else{
		$output = "<div class='post long columns'>
					<div class='graybarright'></div>
					<div class='item-bar'></div>
					<div class='item-content'>
						<div class='item-header'>
							<h2>No Webinars Found</h2>
						</div>
						<p>Sorry, there were no $timeEx found under $ajaxFilter. Try another filter!</p>
					</div>
					<div class='bot-border'></div>
				   </div>";
	} wp_reset_query();
	echo $output; ?>