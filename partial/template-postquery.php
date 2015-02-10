 <?php
	// Our include
	define('WP_USE_THEMES', false);
	require_once('../../../../wp-load.php');

	//Get variable from AJAX POST
	$ajaxFilter = $_POST['ajaxFilter'];

	//Determine if filter is being reset
	if($ajaxFilter == '*'){
		$ajaxFilter = array('policy','quality','webinar','institute','post');
	}
	if($ajaxFilter == 'education'){
		$ajaxFilter = 'webinar';
	}

	//Count our posts and set the $output variable
	$postCount = 0;
	$output = '';
	$today = mktime(0, 0, 0, date('n'), date('j'));
	if($ajaxFilter != 'education'){
		$args = array(
			'posts_per_page' => 20,
			'post_type' => $ajaxFilter,
			'ignore_sticky_posts' => 1,
		);
	}else{
		$args = array(
			'order' => 'asc',
			'posts_per_page' => 20,
			'post_type' => $ajaxFilter,
			'ignore_sticky_posts' => 1,
			'meta_query'  => array(
				array(
					'key' => 'webinar_date',
					'value' => $today,
					'compare' => '>='
				)
			),
			'orderby' => 'meta_value',
			'meta_key' => 'webinar_date',
		);
	}


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
		}else if($postType == 'webinar'){
			$add = 'homewebinar';
			$postTerms = wp_get_post_terms( get_the_ID(), 'webinartopics' );
			$newTerm = array();
			foreach($postTerms as $term){
				array_push($newTerm, $term->slug);
			}
			//check post type and apply a color
			if(in_array('policy',$newTerm)){
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
		}else if($postType == 'institute'){
			$postColor = 'bluee';
		}else{
			$postColor = 'bluee';
		}
		$terms = wp_get_post_terms(get_the_ID(), 'series');
		if($terms){
			$termLink = get_term_link($terms[0], 'series');
		}



	    $output .= '<div class="homepost close post long columns '. $postColor .' '. $postType .' '.$add.'">
	    		<div class="graybarright"></div>
	  			<div class="item-bar"></div>
    			<div class="item-icon"><a href="'.$termLink.'">'.$terms[0]->name.'</a><img src="'. $templateDIR .'/images/icon-'. $postType .'.png" /></div>
    			<div class="item-content">
	    			<div class="item-header">
	    				<h2><a href="';
	    if(get_field('link_to_media')){
			$output .= get_field('uploaded_file');
	    }else{
		    $output .= $postLink;
	    }
	    $output .= '">'. $postTitle .'</a></h2>
	    				<span class="item-date">'. $postTime .' ||</span>
	    				<span class="item-author"><a href="'.get_author_posts_url(get_the_author_meta("ID")).'/?prof=article">'. $postAuthor .'</a></span>
	    			</div>';
	    if(get_field('link_to_media')){
	    	$output .= "<a href='". get_field('uploaded_file')."'><img src='".get_bloginfo('template_directory')."/images/".$postType."-doc.png' /></a>";
	    }else{
		 	$output .= '<p>'. $postExcerpt .'
	    			</p><a class="more" href="'. $postLink .'"> view more » </a>';
	    }
	    $output .= '<div class="item-tags">';
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
							<h2>No Entries Found</h2>
						</div>
						<p>Sorry, there were no entries found under $ajaxFilter. Try another filter!</p>
					</div>
					<div class='bot-border'></div>
				   </div>";
	} wp_reset_query();
	echo $output; ?>