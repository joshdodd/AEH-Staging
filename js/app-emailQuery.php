<?php
	// Our include
	define('WP_USE_THEMES', false);
	require_once('../../../../wp-load.php');
	include_once('../emailGenerator/email-head.php');
	include_once('../emailGenerator/email-foot.php');

	$emailType = $_POST['emailType'];
	$startDate = $_POST['startDate'];
	$endDate = $_POST['endDate'];
	$topHead = $_POST['topHead'];
	$subHead = $_POST['subHead'];
	$file    = $_FILES['file'];


	$newStart = strtotime($startDate);
		$nsWP = date('F jS, Y',$newStart);
		//$nsYear = date();
	$newEnd   = strtotime($endDate);
		$neWP = date('F jS, Y',$newEnd);
		$neYear = intval(date('Y',$newEnd));
		$neMonth = intval(date('n',$newEnd));
		$neDay = intval(date('j',$newEnd));

	if($emailType == 'action'){
		//Action email query
		$color = '#F05135';
		$args = array(
			'post_type' => 'any',
			'tax_query' => array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'series',
					'field'    => 'slug',
					'terms'    => array('analysis','newsline','podcasts'),
				),
				array(
					'taxonomy' => 'category',
					'field'    => 'slug',
					'terms'    => array('dear-congress'),
				)
			),
			'date_query' => array(
				array(
					'after'     => $nsWP,
					'before'    => array(
						'year'  => $neYear,
						'month' => $neMonth,
						'day'   => $neDay,
					),
					'inclusive' => true,
				),
			),
			'posts_per_page' => -1,
		);
	}elseif($emailType == 'quality'){
		//Quality email query
		$color = '#28BDB3';
		$args = array(
			'post_type' => 'quality',
			'date_query' => array(
				array(
					'after'     => $nsWP,
					'before'    => array(
						'year'  => $neYear,
						'month' => $neMonth,
						'day'   => $neDay,
					),
					'inclusive' => true,
				),
			),
			'posts_per_page' => -1,
		);
	}elseif($emailType == 'institute'){
		//Institute email query
		$color = '#0397D6';
		$args = array(
			'post_type' => 'institute',
			'date_query' => array(
				array(
					'after'     => $nsWP,
					'before'    => array(
						'year'  => $neYear,
						'month' => $neMonth,
						'day'   => $neDay,
					),
					'inclusive' => true,
				),
			),
			'posts_per_page' => -1,
		);
	}elseif($emailType == 'education'){
		//Education email query - webinars
		$color = '#565656';
		$args = array(
			'post_type' => 'webinar',
			'meta_query' => array(
				array(
					'key' => 'webinar_date',
					'value' => array($newStart,$newEnd),
					'type' => 'datetime',
					'compare' => 'BETWEEN'
				)
			),
			'posts_per_page' => -1,
		);
		//Education email query - Most recent announcement
		$argsX = array(
			'post_type' => 'alert',
			'posts_per_page' => 1
		);

	}elseif($emailType == 'ehen'){
		//EHEN email query
		$args = array(
			'post_type' => 'any',
			'tag_id' => 483,
			'posts_per_page' => -1,
			'date_query' => array(
				array(
					'after'     => $nsWP,
					'before'    => array(
						'year'  => $neYear,
						'month' => $neMonth,
						'day'   => $neDay,
					),
					'inclusive' => true,
				),
			),
		);
	}elseif($emailType == 'full'){
		//Full email query

	}else{
		return false;
	}


	//Prepare the queries
	if($argsX){
		$query = new WP_Query($argsX);
		if($query->have_posts()){ while ( $query->have_posts() ) { $query->the_post();

		}}
	}
	$query = new WP_Query($args);
	$postcount = $query->post_count;
	$posts = $query->get_posts();
		$col1Posts = array_slice($posts, 0, $postcount/2);
		$col2Posts = array_slice($posts, $postcount/2);


	//Output header
	$output .= email_header($color, $topHead, $subHead);

	//Output the query(ies)
	$output .=  "<table style='background:transparent; ' border='0' width='100%' cellspacing='0' cellpadding='0'>
	                <tbody>

	                     <!-- ^^^^^^^  LEFT COLUMN ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ -->
	                    <tr>
	                        <td style='padding:0px 12px 3px 12px; border-top-width: 1px; border-top-style: solid; border-top-color: #d3d3d3; width:90%' valign='top' width:'90%'   rowspan='1' colspan='2' align='center'></td>
	                    </tr>

		                    <td style='padding:0px 0px 12px 12px;' valign='top' width='50%' rowspan='1' colspan='1' align='center'>";
	//Left Column
	//$output .=$col1Posts;
	foreach($col1Posts as $post){
		setup_postdata($post);
		$postTitle = get_the_title();
		$postExcerpt = get_the_excerpt();

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
			$postColor = 'grayy';
			$postType = 'education';
		}else if($postType == 'institute'){
			$postColor = 'bluee';
		}else{
			$postColor = 'bluee';
		}
		$terms = wp_get_post_terms(get_the_ID(), 'series');
		if($terms){
			$termLink = get_term_link($terms[0], 'series');
		}

		//top part
		$output .= "<table style='border-left-width: 1px; border-left-style: solid; border-left-color: #d3d3d3; border-right-width: 1px; border-right-style: solid; border-right-color: #d3d3d3; border-top:none;' bgcolor='#FFFFFF' border='0' width='100%' padding='0px 0px' cellpadding='0' cellspacing='0' >
	                    <tbody>
	                        <tr><td style='padding: 3px 0px 0px 0px; font-size: 5pt; font-family: Arial, Helvetica, sans-serif; color: #ffffff;' valign='top' rowspan='1' colspan='1' align='center'>

	                            <table style='width: 278px; border-style: none; border-width: 0px; height: 8px; background-color: #ed523c; padding: 0px 0px;' cellpadding='0' cellspacing='0'>
	                                <tbody>
	                                    <tr><td style='word-wrap: break-word; word-break: break-all; vertical-align: top; font-family: Arial, Helvetica, sans-serif; color: #ffffff; font-size: 5px; font-style: normal; font-weight: normal; width: 280px; height: 8px;' rowspan='1' colspan='1'>&nbsp;
	                                    </td></tr>
	                                </tbody>
	                            </table>
	                            <span>&nbsp;</span>
	                        </td>
	                        </tr>
	                        <tr><td style='padding-right:10px;'><img style='float:right;' src='http://essentialhospitals.org/wp-content/themes/EssentialHospitals/images/icon-policy.png'></td></td>
	                    </tbody>
	                </table>";

		//content part
		$output .= "<table style='border-left-width: 1px; border-left-style: solid; border-left-color: #d3d3d3; border-right-width: 1px; border-right-style: solid; border-right-color: #d3d3d3;' width='100%' cellspacing='0' cellpadding='0' >
	                    <tbody>

	                        <tr>
	                            <td style='color:#5a5a5a;font-family:Arial,Helvetica,sans-serif;margin-bottom:5px;font-size:10pt;line-height:150%;padding: 7px 25px;' valign='top' rowspan='1' colspan='1' align='left'>

	                                <div style='color:#ED523C;font-family:Arial,Helvetica,sans-serif;font-weight:bold;font-size:10pt;line-height: 150%; margin-bottom: 5px;'><span><b>$postTitle</b></span></div>
	                                <div style='color:#5a5a5a;font-family:Arial,Helvetica,sans-serif;font-size:10pt; padding-bottom:8px;'>FEB 11, 2014 || <em>$postAuthor</em></div>
	                                <div style='color:#5a5a5a;font-family:Arial,Helvetica,sans-serif;font-size:10pt;'>
	                                <span style='color:#5a5a5a;font-family:Arial,Helvetica,sans-serif;margin-bottom:5px;font-size:10pt;line-height:150%;'>$postExcerpt&nbsp;&nbsp;</span><span style='color:#ed523c;font-style:italic;font-family:georgia,times,serif;font-size:8pt;'><em><a style='  color: rgb(237, 82, 60); text-decoration: none;' track='on' href='$postLink'  linktype='1' target='_blank'>view more &raquo;</a></em></span>
	                                </div> <br>
	                            </td>
	                        </tr>
	                     </tbody>
	                 </table>";

		//bottom part
		$output .= "<table style='border-left-width: 1px; border-left-style: solid; border-left-color: #d3d3d3; border-right-width: 1px; border-right-style: solid; border-right-color: #d3d3d3;' bgcolor='#FFFFFF' border='0' width='100%' padding='0px 0px' cellpadding='0' cellspacing='0' >
	                    <tbody>
	                        <tr>
	                            <td style='padding: 3px 0px 3px 0px; font-size: 5pt; font-family: Arial, Helvetica, sans-serif; color: #ffffff;' valign='top' rowspan='1' colspan='1' align='center'>
	                            <table style='width: 278px; border-style: none; border-width: 1px; height: 1px; background-color:  #d3d3d3; padding: 0px 0px;' cellpadding='0' cellspacing='0'>
	                                <tbody>
	                                    <tr><td style='word-wrap: break-word; word-break: break-all; vertical-align: top; font-family: Arial, Helvetica, sans-serif; color: #ffffff; font-size: 1px; font-style: normal; font-weight: normal; width: 265px; height: 1px;' rowspan='1' colspan='1'>&nbsp;
	                                    </td></tr>
	                                </tbody>
	                            </table>
	                            <span>&nbsp;</span>
	                        </td></tr>
	                    </tbody>
	                </table>";
	}


	$output .= "</td>
                <td style='padding:0px 12px 12px 0px;' valign='top' width='50%' rowspan='1' colspan='1' align='center'>";


	//Right Column
	//$output .=$col2Posts;
	foreach($col2Posts as $post){
		setup_postdata($post);
		$postTitle = get_the_title();
		$postExcerpt = get_the_excerpt();

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
			$postColor = 'grayy';
			$postType = 'education';
		}else if($postType == 'institute'){
			$postColor = 'bluee';
		}else{
			$postColor = 'bluee';
		}
		$terms = wp_get_post_terms(get_the_ID(), 'series');
		if($terms){
			$termLink = get_term_link($terms[0], 'series');
		}

		//top part
		$output .= "<table style='border-left-width: 1px; border-left-style: solid; border-left-color: #d3d3d3; border-right-width: 1px; border-right-style: solid; border-right-color: #d3d3d3; border-top:none;' bgcolor='#FFFFFF' border='0' width='100%' padding='0px 0px' cellpadding='0' cellspacing='0' >
	                    <tbody>
	                        <tr><td style='padding: 3px 0px 0px 0px; font-size: 5pt; font-family: Arial, Helvetica, sans-serif; color: #ffffff;' valign='top' rowspan='1' colspan='1' align='center'>

	                            <table style='width: 278px; border-style: none; border-width: 0px; height: 8px; background-color: #ed523c; padding: 0px 0px;' cellpadding='0' cellspacing='0'>
	                                <tbody>
	                                    <tr><td style='word-wrap: break-word; word-break: break-all; vertical-align: top; font-family: Arial, Helvetica, sans-serif; color: #ffffff; font-size: 5px; font-style: normal; font-weight: normal; width: 280px; height: 8px;' rowspan='1' colspan='1'>&nbsp;
	                                    </td></tr>
	                                </tbody>
	                            </table>
	                            <span>&nbsp;</span>
	                        </td>
	                        </tr>
	                        <tr><td style='padding-right:10px;'><img style='float:right;' src='http://essentialhospitals.org/wp-content/themes/EssentialHospitals/images/icon-policy.png'></td></td>
	                    </tbody>
	                </table>";

		//content part
		$output .= "<table style='border-left-width: 1px; border-left-style: solid; border-left-color: #d3d3d3; border-right-width: 1px; border-right-style: solid; border-right-color: #d3d3d3;' width='100%' cellspacing='0' cellpadding='0' >
	                    <tbody>

	                        <tr>
	                            <td style='color:#5a5a5a;font-family:Arial,Helvetica,sans-serif;margin-bottom:5px;font-size:10pt;line-height:150%;padding: 7px 25px;' valign='top' rowspan='1' colspan='1' align='left'>

	                                <div style='color:#ED523C;font-family:Arial,Helvetica,sans-serif;font-weight:bold;font-size:10pt;line-height: 150%; margin-bottom: 5px;'><span><b>$postTitle</b></span></div>
	                                <div style='color:#5a5a5a;font-family:Arial,Helvetica,sans-serif;font-size:10pt; padding-bottom:8px;'>FEB 11, 2014 || <em>$postAuthor</em></div>
	                                <div style='color:#5a5a5a;font-family:Arial,Helvetica,sans-serif;font-size:10pt;'>
	                                <span style='color:#5a5a5a;font-family:Arial,Helvetica,sans-serif;margin-bottom:5px;font-size:10pt;line-height:150%;'>$postExcerpt&nbsp;&nbsp;</span><span style='color:#ed523c;font-style:italic;font-family:georgia,times,serif;font-size:8pt;'><em><a style='  color: rgb(237, 82, 60); text-decoration: none;' track='on' href='$postLink'  linktype='1' target='_blank'>view more &raquo;</a></em></span>
	                                </div> <br>
	                            </td>
	                        </tr>
	                     </tbody>
	                 </table>";

		//bottom part
		$output .= "<table style='border-left-width: 1px; border-left-style: solid; border-left-color: #d3d3d3; border-right-width: 1px; border-right-style: solid; border-right-color: #d3d3d3;' bgcolor='#FFFFFF' border='0' width='100%' padding='0px 0px' cellpadding='0' cellspacing='0' >
	                    <tbody>
	                        <tr>
	                            <td style='padding: 3px 0px 3px 0px; font-size: 5pt; font-family: Arial, Helvetica, sans-serif; color: #ffffff;' valign='top' rowspan='1' colspan='1' align='center'>
	                            <table style='width: 278px; border-style: none; border-width: 1px; height: 1px; background-color:  #d3d3d3; padding: 0px 0px;' cellpadding='0' cellspacing='0'>
	                                <tbody>
	                                    <tr><td style='word-wrap: break-word; word-break: break-all; vertical-align: top; font-family: Arial, Helvetica, sans-serif; color: #ffffff; font-size: 1px; font-style: normal; font-weight: normal; width: 265px; height: 1px;' rowspan='1' colspan='1'>&nbsp;
	                                    </td></tr>
	                                </tbody>
	                            </table>
	                            <span>&nbsp;</span>
	                        </td></tr>
	                    </tbody>
	                </table>";
	}

	$output .= "</td></tbody></table>";
	$output .= email_footer($color);

	echo $output;

?>