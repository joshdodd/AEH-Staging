<?php
	/* =================
		Include Dependencies
	================== */
	define('WP_USE_THEMES', false);
	require_once('../../../../wp-load.php');
	include_once('../emailGenerator/email-head.php');
	include_once('../emailGenerator/email-foot.php');


	/* =================
		Upload Ad field
	================== */
	if($_FILES['application-form-ad'] != NULL){


		$upload_url = get_template_directory().'/images/ads/';
		$echoURL = 'http://essentialhospitals.org/wp-content/themes/EssentialHospitals/images/ads/';
		//echo $upload_url.'<br>';
		$allowedExts = array("gif", "jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["application-form-ad"]["name"]);
		$extension = end($temp);
		if ((($_FILES["application-form-ad"]["type"] == "image/gif")
		|| ($_FILES["application-form-ad"]["type"] == "image/jpeg")
		|| ($_FILES["application-form-ad"]["type"] == "image/jpg")
		|| ($_FILES["application-form-ad"]["type"] == "image/pjpeg")
		|| ($_FILES["application-form-ad"]["type"] == "image/x-png")
		|| ($_FILES["application-form-ad"]["type"] == "image/png"))
		&& ($_FILES["application-form-ad"]["size"] < 2000000)
		&& in_array($extension, $allowedExts)){
		  if ($_FILES["application-form-ad"]["error"] > 0){
		  	//echo "Return Code: " . $_FILES["application-form-ad"]["error"] . "<br>";
		  }else{
		    //echo "Upload: " . $_FILES["application-form-ad"]["name"] . "<br>";
		    //echo "Type: " . $_FILES["application-form-ad"]["type"] . "<br>";
		    //echo "Size: " . ($_FILES["application-form-ad"]["size"] / 1024) . " kB<br>";
		    //echo "Temp file: " . $_FILES["application-form-ad"]["tmp_name"] . "<br>";
		    if (file_exists($upload_url . $_FILES["application-form-ad"]["name"])){
		    	chmod($upload_url . $_FILES["application-form-ad"]["name"], 0775);
		    	$adDir = $echoURL.$_FILES['application-form-ad']['name'];
			  	//echo 'file exists<br>'.$adDir;
		    }else{
		      	move_uploaded_file($_FILES["application-form-ad"]["tmp_name"], $upload_url . $_FILES["application-form-ad"]["name"]);
		      	chmod($upload_url . $_FILES["application-form-ad"]["name"], 0775);
			  	$adDir = $echoURL.$_FILES['application-form-ad']['name'];
			  	//echo 'file uploaded<br>'.$adDir;
		      }
		    }
		  }
		else{
			echo "Invalid file";
		}

	}

	/* =================
		Gather variables
	================== */
	$emailType = $_POST['emailType'];
	$startDate = $_POST['application-form-startdate'];
	$endDate = $_POST['application-form-enddate'];
	$subHead = $_POST['application-form-subheader'];
	$adLoc = $_POST['ad-loc'];
	$adIntro = $_POST['application-form-intro'];

	//echo $emailType.'<br>';
	//echo $startDate.'<br>';
	//echo $endDate.'<br>';
	//echo $topHead.'<br>';
	//echo $subHead.'<br>';


	/* =================
		Generate the Email
	================== */
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
		$query = new WP_Query($args);
		$postcount = $query->post_count;
		$posts = $query->get_posts();
			$col1Posts = array_slice($posts, 0, $postcount/2);
			$col2Posts = array_slice($posts, $postcount/2);
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
		$query = new WP_Query($args);
		$postcount = $query->post_count;
		$posts = $query->get_posts();
			$col1Posts = array_slice($posts, 0, $postcount/2);
			$col2Posts = array_slice($posts, $postcount/2);
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
		$query = new WP_Query($args);
		$postcount = $query->post_count;
		$posts = $query->get_posts();
			$col1Posts = array_slice($posts, 0, $postcount/2);
			$col2Posts = array_slice($posts, $postcount/2);
	}elseif($emailType == 'education'){
		//Education email query - webinars
		$color = '#565656';
		$args = array(
			'post_type' => 'webinar',
			'meta_query' => array(
				array(
					'key' => 'webinar_date',
					'value' => array($newStart,$newEnd),
					'type' => 'numeric',
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
		$query = new WP_Query($args);
		$postcount = $query->post_count;
		$posts = $query->get_posts();
			$col1Posts = array_slice($posts, 0, $postcount/2);
			$col2Posts = array_slice($posts, $postcount/2);

	}elseif($emailType == 'ehen'){
		//EHEN email query
		$color = '#565656';
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
		$query = new WP_Query($args);
		$postcount = $query->post_count;
		$posts = $query->get_posts();
			$col1Posts = array_slice($posts, 0, $postcount/2);
			$col2Posts = array_slice($posts, $postcount/2);
	}elseif($emailType == 'full'){
		//Full email query
		$color = '#f05135';

		//Newsline/Action
		$argsA = array(
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

		//Institute and Quality
		$argsIQ = array(
			'post_type' => array('institute','quality'),
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

		//Webinars
		$argsW = array(
			'post_type' => 'webinar',
			'meta_query' => array(
				array(
					'key' => 'webinar_date',
					'value' => array($newStart,$newEnd),
					'type' => 'numeric',
					'compare' => 'BETWEEN'
				)
			),
			'posts_per_page' => -1,
		);
		$queryA = new WP_Query($argsA);
			$queryACount = $queryA->post_count;
			$postsA = $queryA->get_posts();
		$queryIQ = new WP_Query($argsIQ);
			$queryIQCount = $queryIQ->post_count;
			$postsIQ = $queryIQ->get_posts();
		$queryW = new WP_Query($argsW);
			$queryWCount = $queryW->post_count;
			$postsW = $queryW->get_posts();

		$postcount = $queryACount+$queryIQCount+$queryWCount;
		$posts = array_merge($postsA,$postsIQ,$postsW);
			$col1Posts = array_slice($posts, 0, $postcount/2);
			$col2Posts = array_slice($posts, $postcount/2);

	}else{
		return false;
	}
	//Prepare the queries
	if($argsX){
		$query = new WP_Query($argsX);
		if($query->have_posts()){ while ( $query->have_posts() ) { $query->the_post();

		}}
	}

	if($adDir){
		$adPrev = "<div id='ad-preview'><h2>Ad Preview</h2><img src='$adDir'/></div>";
	}else{
		$adPrev = "";
	}
	//sortable output
	$output .= "<div id='email-gen'>Generate Email</div>
				$adPrev
				<div id='ad-intro' class='hidden hideme'>$adIntro</div>
				<div id='ad-dir' class='hidden hideme'>$adDir</div>
				<div id='ad-loc' class='hidden hideme'>$adLoc</div>
				<div id='email-type' class='hidden hideme'>$emailType</div>
				<div id='color-head' class='hidden hideme'>$subHead</div>
				<h2>Article Order (left to right, top to bottom)</h2>";
	foreach($posts as $post){
		setup_postdata($post);
		$title = get_the_title();
		$id = $post->ID;
		$postType = get_post_type(get_the_ID());
		$output .= "<li data-id='$id'>$title <div class='colorbox $postType'></div><div class='deleteme'></div></li>";
	}
	echo $output;

?>