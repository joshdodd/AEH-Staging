<?php
	// Our include
	define('WP_USE_THEMES', false);
	require_once('../../../../wp-load.php');

	$page = $_POST['page'];
	$postType = $_POST['posttype'];
	$center = $_POST['center'];

	$args = array(
		'post_type' => $postType,
		'centers'  => $center,
		'posts_per_page' => 10,
		'paged' => $page
	);
	$query = new WP_Query($args);
	$layoutArray = array('tall','short');
	if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post();
		$rand_key = array_rand($layoutArray, 1);
		$exc = get_the_excerpt();
		$newExc = substr($exc, 0, 100);

		$output .= '<div class="post long columns fluid '.$layoutArray[$rand_key].' bluee institute">
			<div class="graybarright"></div>
				<div class="item-bar"></div>
			<div class="item-icon">';

		$terms = wp_get_post_terms(get_the_ID(), 'series');
			if($terms){
				$termLink = get_term_link($terms[0], 'series');
				$output .= "<a href='".$termLink."'>".$terms[0]->name."</a>";
			}
		if($postType != 'post'){
			$output .= '<img src="'.get_bloginfo('template_directory').'/images/icon-institute.png" />';
		}

		$output .=	'</div>
			<div class="item-content">
				<div class="item-header">
					<h2><a href="';

		if(get_field('link_to_media')){
			$output .= get_field('uploaded_file');
		}else{
			$output .= get_permalink();
		}

		$output .= '">'.get_the_title().'</a></h2>
					<span class="item-date">'.get_the_time('M j, Y').' ||</span>
					<span class="item-author">'.get_the_author().'</span>
				</div>';


		if(get_field('link_to_media')){
			$output .= '<p><a class="pdf-doc" href="'.get_field('uploaded_file').'"><img src="'.get_bloginfo('template_directory').'/images/institute-doc.png" /></a>';
			$output .= $newExc;
			$output .= "</p>";
		}else{
			$output .=	'<p>'.$newExc.'<a class="more" href="'.get_permalink().'"> view more » </a></p>';
		}

		$output .= '<div class="item-tags">
					'.get_the_tags(' ',' ',' ').'
				</div>
			</div>
			<div class="bot-border"></div>
		</div>';
	} }else{
		$output = 'end';
	}
	echo $output;
?>