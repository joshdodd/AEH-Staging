<?php
	/* =================
		Include Dependencies
	================== */
	define('WP_USE_THEMES', false);
	require_once('../../../../wp-load.php');
	include_once('../emailGenerator/email-head.php');
	include_once('../emailGenerator/email-foot.php');


	/* =================
		Gather $_REQUEST vars
	================== */
	$ids = $_REQUEST['ids'];
	$adLoc = $_REQUEST['adLoc'];
	$adDir = $_REQUEST['adDir'];
	$subHead = $_REQUEST['colorHead'];
	$emailType = $_REQUEST['emailType'];
	$adIntro = $_REQUEST['intro'];


	/* =================
		Split id arrays
	================== */
	$postsLeft = array();
	$postsRight = array();
	$i = 1;
	foreach($ids as $id){
		if($i%=2){
			array_push($postsLeft, $id);
		}else{
			array_push($postsRight, $id);
		}$i++;
	}

	if($emailType == 'action'){
		//Action email query
		$color = '#F05135';
	}elseif($emailType == 'quality'){
		//Quality email query
		$color = '#28BDB3';
	}elseif($emailType == 'institute'){
		//Institute email query
		$color = '#00AEEF';
	}elseif($emailType == 'education'){
		//Education email query - webinars
		$color = '#565656';
	}elseif($emailType == 'ehen'){
		//EHEN email query
		$color = '#00AEEF';
	}elseif($emailType == 'full'){
		//Full email query
		$color = '#F05135';
	}else{
		return false;
	}

	/* =================
		Render
	================== */
	//Output header
	$output .= email_header($color, $subHead, $emailType);


	//If there are more than 8 posts, create a table of contents
	if($postcount > 8){
		$output .= "
			<table style='background: transparent; border-bottom: 1px solid #ccc; border-top: 1px solid #ccc;' width='100%' cellspacing='0' cellpadding='0'>
			<tbody>
			<tr>
			<td style='padding: 15px;'>
			<span style='font-size: 14px; font-weight: bold;'>In this Issue:</span>";
			foreach($posts as $post){
			setup_postdata($post);
			$postTitle = get_the_title();
			$postID = get_the_ID();
			$output .= "
			<a style='color: #666; font-size: 12px; font-family: Arial, Helvetica, sans-serif;' href='#post-$postID'>$postTitle</a>";
			}
			$output .= "
			</td>
			</tr>
			</tbody>
			</table>";

	}

			$output .= "
			<table bgcolor='#FFFFFF' border='0' width='100%' cellpadding='0' cellspacing='0'>
			<tbody>
			<tr>
			<td style='padding-top: 10px; padding-right: 20px; padding-bottom: 10px; padding-left: 20px; color: #555555; font-size: 12px; font-family: Arial, Helvetica, sans-serif; line-height: 150%;' valign='top'>$adIntro</td>
			</tr>
			</tbody>
			</table>";

	if($adDir && $adLoc == 'top-full'){
		$output .= "
			<table bgcolor='#FFFFFF' width='100%' cellpadding='0' cellspacing='0'>
			<tbody>
			<tr>
			<td style='padding: 3px 0px 3px 0px;' valign='top' rowspan='1' colspan='1'><img src='$adDir' style='width: 100%; height: auto;' border='0'></td>
			</tr>
			</tbody>
			</table>";
	}

	//Output the query(ies)
	$output .= "
			<table border='0' width='100%' cellspacing='0' cellpadding='0'>
			<tbody>
			<tr>
			<td style='padding-left: 12px; padding-right: 12px;' rowspan='1' colspan='2'>
			<div style='width: 100%; padding-bottom: 10px; border-top-color: rgb(211, 211, 211); border-top-width: 1px; border-top-style: solid; font-size: 5pt;'>&nbsp;</div></td>
			</tr>
			<tr>
<!-- BEGIN LEFT COLUMN -->
			<td style='padding: 0px 0px 12px 12px; border-right-width: 1px; border-right-style: solid; border-right-color: #d3d3d3;' valign='top' width='50%'>";
			if($adDir && $adLoc == 'top-left'){
			$output .= "
			<table bgcolor='#FFFFFF' width='100%' cellpadding='0' cellspacing='0'>
			<tbody>
			<tr>
			<td style='padding: 3px 0px 3px 0px;' valign='top'><img src='$adDir' style='width: 100%; height: auto;' border='0'></td>
			</tr>
			</tbody>
	                </table>";
	}

	//Left Column
	//$output .=$col1Posts;
	foreach($postsLeft as $id){
		$post = get_post($id);
		setup_postdata($post);
		$postTitle = get_the_title();
		$postExcerpt = get_the_excerpt();
		$postID = get_the_ID();

		$postColor = '';
		$postTime = get_the_time('M j, Y');
		$templateDIR = get_bloginfo('template_directory');
		$postAuthor = get_the_author();
		$postLink = get_permalink();
		$postTags = get_the_tags();

		$postType = get_post_type( get_the_ID() );

		//check post type and apply a color
		if($postType == 'policy' || $postType == 'post'){
			$postType = 'policy';
			$lock = 'policy';
			$postColor = '#f05135';
		}else if($postType == 'quality'){
			$postColor = '#28bdb3';
			$lock = 'quality';
		}else if($postType == 'webinar'){
			$lock = 'webinar';
			$postType = 'education';
			$postColor = '#565656';
			$postTime = date('M j, Y', get_field('webinar_date'));
			$postAuthor = '<a href="'.get_field('registration_link').'">Register Now</a>';
		}else if($postType == 'institute'){
			$postColor = '#00AEEF';
			$lock = 'institute';
		}else{
			$lock = 'webinar';
			$postColor = '#f05135';
		}
		$terms = wp_get_post_terms(get_the_ID(), 'series');
		if($terms){
			$termLink = get_term_link($terms[0], 'series');
		} wp_reset_postdata();

		//top part
		$output .= "
<!-- BEGIN CONTENT ITEM -->
			<table id='post-$postID' style='border-left-width: 1px; border-left-style: solid; border-left-color: #d3d3d3;' bgcolor='#FFFFFF' width='100%' cellpadding='0' cellspacing='0'>
			<tbody>
			<tr>
			<td style='padding: 3px 5px 0px 5px; font-size: 5pt; font-family: Arial, Helvetica, sans-serif; color: #ffffff;' valign='top'>
			<div style='width: 100%; border-top-width: 8px; border-top-style: solid; border-top-color: $postColor;'>&nbsp;</div>
			</td>
			</tr>
			<tr>
			<td style='width: 100%; padding-right: 10px; text-align: right;'><img src='http://essentialhospitals.org/wp-content/uploads/2014/09/$postType-icon-16.png' width='16' height='16' border='0'></td>
			</tr>
			</tbody>
			</table>";

		//content part
		$output .= "
			<table style='border-left-width: 1px; border-left-style: solid; border-left-color: #d3d3d3;' width='100%' cellspacing='0' cellpadding='0'>
			<tbody>
			<tr>
			<td style='padding-top: 3px; padding-right: 25px; padding-bottom: 7px; padding-left: 25px; text-align: left;' valign='top'>
			<div style='font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 10pt; line-height: 150%; padding-bottom: 5px; text-align: left;'>
			<span class='title-edit'>
				<a style='color: $postColor; text-decoration: none;' href='$postLink' target='_blank'>

					<b>$postTitle</b>
				</a>
			</span>
			</div>
			<div style='color: #5a5a5a; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; padding-bottom: 5px; text-align: left;'>$postTime || <em>$postAuthor</em></div>
			<div style='color: #5a5a5a; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; text-align: left;'>
			<span class='excerpt-edit' style='color: #5a5a5a; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; line-height: 150%;'>$postExcerpt&nbsp;&nbsp;</span><span style='color: $postColor; font-style: italic; font-family: Georgia, Times, serif; font-size: 8pt;'><em><a style='color: $postColor; text-decoration: none;' href='$postLink' target='_blank'>view more»</a></em></span>
			</div>
			</td>
			</tr>
			</tbody>
			</table>";

		//bottom part
		$output .= "
			<table style='padding: 0; border-left-width: 1px; border-left-style: solid; border-left-color: #d3d3d3;' bgcolor='#FFFFFF' border='0' width='100%' cellpadding='0' cellspacing='0'>
			<tbody>
			<tr>
			<td style='padding-top: 3px; padding-right: 5px; padding-bottom: 15px; padding-left: 5px;' valign='top'>
			<div style='border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #d3d3d3;'>&nbsp;</div>
			</td>
			</tr>
			</tbody>
			</table>
<!-- END CONTENT ITEM -->
";
	}

		if($adDir && $adLoc == 'bottom-left'){
		$output .= "
			<table bgcolor='#FFFFFF' border='0' width='100%' cellpadding='0' cellspacing='0'>
			<tbody>
			<tr>
			<td style='padding: 3px 0px 3px 0px;' valign='top'><img src='$adDir' style='width: 100%; height: auto;' border='0'></td>
			</tr>
			</tbody>
			</table>";
	}

	$output .= "
			</td>
<!-- END LEFT COLUMN -->

<!-- BEGIN RIGHT COLUMN -->
			<td style='padding:0px 12px 12px 0px;' valign='top' width='50%'>";
		if($adDir && $adLoc == 'top-right'){
		$output .= "
			<table style='border-right-width: 1px; border-right-style: solid; border-right-color: #d3d3d3;' bgcolor='#FFFFFF' border='0' width='100%' cellpadding='0' cellspacing='0'>
			<tbody>
			<tr>
			<td style='padding: 3px 0px 3px 0px;' valign='top'><img src='$adDir' style='width: 100%; height: auto;' border='0'></td>
			</tr>
			</tbody>
			</table>";
	}

	//Right Column
	//$output .=$col2Posts;
	foreach($postsRight as $id){
		$post = get_post($id);
		setup_postdata($post);
		$postTitle = get_the_title();
		$postExcerpt = get_the_excerpt();
		$postID = get_the_ID();

		$postColor = '';
		$postTime = get_the_time('M j, Y');
		$templateDIR = get_bloginfo('template_directory');
		$postAuthor = get_the_author();
		$postLink = get_permalink();
		$postTags = get_the_tags();

		$postType = get_post_type( get_the_ID() );

		//check post type and apply a color
		if($postType == 'policy' || $postType == 'post'){
			$postType = 'policy';
			$lock = 'policy';
			$postColor = '#f05135';
		}else if($postType == 'quality'){
			$postColor = '#28bdb3';
			$lock = 'quality';
		}else if($postType == 'webinar'){
			$lock = 'webinar';
			$postType = 'education';
			$postColor = '#565656';
			$postTime = date('M j, Y', get_field('webinar_date'));
			$postAuthor = '<a href="'.get_field('registration_link').'">Register Now</a>';
		}else if($postType == 'institute'){
			$postColor = '#00AEEF';
			$lock = 'institute';
		}else{
			$lock = 'webinar';
			$postColor = '#f05135';
		}
		$terms = wp_get_post_terms(get_the_ID(), 'series');
		if($terms){
			$termLink = get_term_link($terms[0], 'series');
		} wp_reset_postdata();

		//top part
		$output .= "
<!-- BEGIN CONTENT ITEM -->
			<table id='post-$postID' style='border-right-width: 1px; border-right-style: solid; border-right-color: #d3d3d3;' bgcolor='#FFFFFF' width='100%' cellpadding='0' cellspacing='0'>
			<tbody>
			<tr>
			<td style='padding: 3px 5px 0px 5px; font-size: 5pt; font-family: Arial, Helvetica, sans-serif; color: #ffffff;' valign='top'>
			<div style='width: 100%; border-top-width: 8px; border-top-style: solid; border-top-color: $postColor;'>&nbsp;</div>
			</td>
			</tr>
			<tr>
			<td style='width: 100%; padding-right: 10px; text-align: right;'><img src='http://essentialhospitals.org/wp-content/uploads/2014/09/$postType-icon-16.png' width='16' height='16' border='0'></td>
			</tr>
			</tbody>
			</table>";

		//content part
		$output .= "
			<table style='border-right-width: 1px; border-right-style: solid; border-right-color: #d3d3d3;' width='100%' cellspacing='0' cellpadding='0'>
			<tbody>
			<tr>
			<td style='padding-top: 3px; padding-right: 25px; padding-bottom: 7px; padding-left: 25px; text-align: left;' valign='top'>
			<div style='font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 10pt; line-height: 150%; padding-bottom: 5px; text-align: left;'><span class='title-edit'><a style='color: $postColor; text-decoration: none;' href='$postLink' target='_blank'><b>$postTitle</b></a></span></div>
			<div style='color: #5a5a5a; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; padding-bottom: 5px; text-align: left;'>$postTime || <em>$postAuthor</em></div>
			<div style='color: #5a5a5a; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; text-align: left;'><span class='excerpt-edit' style='color: #5a5a5a; font-family: Arial, Helvetica, sans-serif; margin-bottom: 5px; font-size: 10pt; line-height: 150%;'>$postExcerpt&nbsp;&nbsp;</span><span style='font-style: italic; font-family: Georgia, Times, serif; font-size: 8pt;'><em><a style='color: $postColor; text-decoration: none;' href='$postLink' target='_blank'>view more»</a></em></span></div>
			</td>
			</tr>
			</tbody>
			</table>";

		//bottom part
		$output .= "
			<table style='border-right-width: 1px; border-right-style: solid; border-right-color: #d3d3d3; padding: 0;' bgcolor='#FFFFFF' border='0' width='100%' cellpadding='0' cellspacing='0'>
			<tbody>
			<tr>
			<td style='padding-top: 3px; padding-right: 5px; padding-bottom: 15px; padding-left: 5px;' valign='top'>
			<div style='border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #d3d3d3;'>&nbsp;</div>
			</td>
			</tr>
			</tbody>
			</table>
<!-- END CONTENT ITEM -->
";
	}
		if($adDir && $adLoc == 'bottom-right'){
		$output .= "
			<table bgcolor='#FFFFFF' border='0' width='100%' cellpadding='0' cellspacing='0'>
			<tbody>
			<tr>
			<td style='padding: 3px 0px 3px 0px;' valign='top'><img src='$adDir' style='width: 100%; height: auto;' border='0' /></td>
			</tr>
			</tbody>
			</table>";
	}

	$output .= "
			</td>
			</tr>
			</tbody>
			</table>";

		if($adDir && $adLoc == 'bottom-full'){
		$output .= "
			<table bgcolor='#FFFFFF' border='0' width='100%' cellpadding='0' cellspacing='0'>
			<tbody>
			<tr>
			<td style='padding: 3px 0px 3px 0px;' valign='top'><img src='$adDir' style='width: 100%; height: auto;' border='0'></td>
			</tr>
			</tbody>
			</table>";
}

	//Output footer
	$output .= email_footer($color);
	echo $output;
?>