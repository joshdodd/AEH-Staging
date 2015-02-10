<?php
	// Our include
	define('WP_USE_THEMES', false);
	require_once('../../../../wp-load.php');

	//Get variable from AJAX POST
	$ajaxFilter = $_POST['ajaxFilter'];
	$archiveFilter = $_POST['archiveFilter'];

	//Determine if filter is being reset
	if($ajaxFilter == 'reset'){
		$ajaxFilter = '';
	}

	//Count our posts and set the $output variable
	$postCount = 0;
	$output = '';


	$args = array(
		'posts_per_page' => '-1',
		'post_type' 	 => $ajaxFilter,
		'series'		 => $archiveFilter,
	);
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
			$postColor = 'redd';
		}
$terms = wp_get_post_terms(get_the_ID(), 'series');
		if($terms){
			$termLink = get_term_link($terms[0], 'series');
		}

	    $output .= '<div class="close post long columns '. $postColor .' '. $postType .' ">
	    		<div class="graybarright"></div>
	  			<div class="item-bar"></div>
    			<div class="item-icon"><a href="'.$termLink.'">'.$terms[0]->name.'</a><img src="'. $templateDIR .'/images/icon-'. $postType .'.png" /></div>
    			<div class="item-content">
	    			<div class="item-header">
	    				<h2><a href="'. $postLink .'">'. $postTitle .'</a></h2>
	    				<span class="item-date">'. $postTime .' ||</span>
	    				<span class="item-author"><a href="'.get_author_posts_url(get_the_author_meta("ID")).'/?prof=article">'. $postAuthor .'</a></span>
	    			</div>
	    			<p>'. $postExcerpt .'
	    			</p><a class="more" href="'. $postLink .'"> view more » </a>
	    			<div class="item-tags">';
		if($postTags){
		    $cnt = 0;
		    foreach($postTags as $tag){
		    	$tagSlug = $tag->slug;
				$tagSlug = str_replace('-',' ', $tagSlug);
				if ($cnt != 0) {$output .= ", ";}
			    $output .= '<a href="'.get_bloginfo('url').'/tag/'.$tag->slug.'">$$$'.$tagSlug.'</a>';
			    if($cnt == 3){break;}
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
							<h2>No Entries Found</h2>
						</div>
						<p>Sorry, there were no entries found under $ajaxFilter. Try another filter!</p>
					</div>
					<div class='bot-border'></div>
				   </div>";
	} wp_reset_query();
	echo $output; ?>