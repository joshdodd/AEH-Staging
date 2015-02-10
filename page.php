<?php get_header();
	global $post;
	$postSt = $post->post_status;
	$current_user = wp_get_current_user();
	$cUID = $current_user->ID;
	$cUStaff = get_user_meta($cUID,'aeh_member_type',true);
	$bannerArr = get_field('banners',29); ?>
	<?php
	if($postSt == 'private'){
		if($cUStaff == 'hospital'){

		while ( have_posts() ) : the_post();
		$pageTheme = get_field('theme');

		if($pageTheme == 'policy'){
			$menu = 'action';
		}elseif($pageTheme == ''){
			$menu = 'general';
		}elseif($pageTheme != 'policy'){
			$menu = $pageTheme;
		}


		//Get featuredIMG
		if($pageTheme == 'policy'){
			$fPID = 62;
			$speakerIMG  = wp_get_attachment_url( get_post_thumbnail_id(62) );
			$pageTitle = "Action";
		}elseif($pageTheme == 'quality'){
			$fPID = 64;
			$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id(64) );
		$pageTitle = "Quality";
		}elseif($pageTheme == 'institute'){
			$fPID = 621;
			$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id(621) );
			$pageTitle = "Essential Hospitals Institute" ;
		}elseif($pageTheme == 'education'){
			$fPID = 472;
			$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id(472) );
			$pageTitle = "Education" ;
		}else{
			$fPID = 645;
			$randArr = array_rand($bannerArr);
			$speakerIMG = $bannerArr[$randArr]['image'];
			$pageTheme = 'policy';
			$bannerSize = "";
			$parents = get_post_ancestors( $post->ID );
			$chck_id = ($parents) ? $parents[count($parents)-1]: $parent_id;
			$pageTitle = "ABOUT";
			$pageTheme = 'policy';

			if($chck_id == 645)
				{$bannerSize = ""; $pageTitle = "ABOUT"; $pageTheme = 'policy';}
		}

		//$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id($fPID) );
		 $bannerTitle = get_field('bannerTitle');



		 ?>
<div id="hellothereguys" data-arr="<?php var_dump($bannerArr); ?>"></div>
		<div id="featured-img" class="page-single  <?php echo $pageTheme; ?>" style="background-image:url(<?php echo $speakerIMG; ?>); ">
			<div class="container">
				<div id="featured-intro">
					<h3> <span><?php echo $pageTitle; ?> </span> <?php if($bannerTitle != ''){ echo $bannerTitle; }else{ the_title(); }?> </h3>
				</div>
			</div>
		</div>
		<div id="content" class="page-single default <?php echo $pageTheme; ?>">
			<div class="container">
				<?php
					if(has_nav_menu('general-nav')){
						$defaults = array(
							'theme_location'  => 'general-nav',
							'menu'            => 'general-nav',
							'container'       => 'div',
							'container_class' => '',
							'container_id'    => 'pageNav',
							'menu_class'      => 'quality',
							'menu_id'         => '',
							'echo'            => true,
							'fallback_cb'     => 'wp_page_menu',
							'before'          => '',
							'after'           => '',
							'link_before'     => '',
							'link_after'      => '',
							'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
							'depth'           => 0,
							'walker'          => ''
						);
						wp_nav_menu( $defaults );
					}
				?>
				<div id="contentColumnWrap">
					<div class="graybarright"></div>
					<div class="graybarleft"></div>
					<div id="columnBalance">
						<div id="contentPrimary" class="heightcol">
							<div class="gutter">
								<h1><?php the_title(); ?></h1>
								<?php the_content(); ?>
							</div>
						</div>
						<div id="contentSecondary" class="heightcol">
							<div class="gutter">
								<?php the_field('secondColumn'); ?>
							</div>
						</div>
						<div id="contentTertiary" class="heightcol interior-submenu">
							<div class="pagebar"></div>
							<?php
								$defaults = array(
									'theme_location'  => 'primary-menu',
									'menu'            => 'primary-menu',
									'container'       => 'div',
									'container_class' => 'page-nav',
									'container_id'    => 'interior-sub',
									'menu_class'      => '',
									'menu_id'         => '',
									'echo'            => true,
									'fallback_cb'     => 'wp_page_menu',
									'before'          => '',
									'after'           => '',
									'link_before'     => '',
									'link_after'      => '',
									'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
									'depth'           => 0,
									'walker'          => ''
								);
								wp_nav_menu( $defaults );
							?>
							<div class="gutter">
								<div class="panel">
									<h3 id="sharetitle">Share</h3>
									<div id="share">
										<!-- AddThis Button BEGIN -->
										<div class="addthis_toolbox addthis_32x32_style" style="">
										<a class="addthis_button_facebook"></a>
										<a class="addthis_button_twitter"></a>
										<a class="addthis_button_linkedin"></a>
										<a class="addthis_button_pinterest_share"></a>
										<a class="addthis_button_google_plusone_share"></a>
										<a class="addthis_button_email"></a>
										<a class="addthis_button_digg"></a>
										<a class="addthis_button_evernote"></a>
										<a class="addthis_button_compact"></a>
										</div>
										<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
										<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=naphsyscom"></script>
										<!-- AddThis Button END -->
									</div>
								</div>
								<?php the_field('thirdColumn'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endwhile; // end of the loop. ?>
		</div><!-- End of Content -->
	<?php }else{ while ( have_posts() ) : the_post();
		$pageTheme = get_field('theme');

		if($pageTheme == 'policy'){
			$menu = 'action';
		}elseif($pageTheme == ''){
			$menu = 'general';
		}elseif($pageTheme != 'policy'){
			$menu = $pageTheme;
		}

		if($pageTheme == 'policy'){
			$fPID = 62;
			$speakerIMG  = wp_get_attachment_url( get_post_thumbnail_id(62) );
			$pageTitle = "Action";
		}elseif($pageTheme == 'quality'){
			$fPID = 64;
			$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id(64) );
			$pageTitle = "Quality";
		}elseif($pageTheme == 'institute'){
			$fPID = 621;
			$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id(621) );
			$pageTitle = "Essential Hospitals Institute" ;
		}elseif($pageTheme == 'education'){
			$fPID = 472;
			$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id(472) );
			$pageTitle = "Education" ;
		}else{
			$fPID = 645;
			$randArr = array_rand($bannerArr);
			$speakerIMG = $bannerArr[$randArr]['image'];
			$bannerSize = "";
			$parents = get_post_ancestors( $post->ID );
			$chck_id = ($parents) ? $parents[count($parents)-1]: $parent_id;
			$pageTitle = "ABOUT";
			$pageTheme = 'policy';

			if($chck_id == 645)
				{$bannerSize = ""; $pageTitle = "ABOUT"; $pageTheme = 'policy';}
		}

		//$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id($fPID) );
		 $bannerTitle = get_field('bannerTitle');



		 ?>

		<div id="featured-img" class="page-single  <?php echo $pageTheme; ?>" style="background-image:url(<?php echo $speakerIMG; ?>); ">
			<div class="container">
				<div id="featured-intro">
					<h3> <span><?php echo $pageTitle; ?> </span> <?php if($bannerTitle != ''){ echo $bannerTitle; }else{ the_title(); }?> </h3>
				</div>
			</div>
		</div>
		<div id="content" class="page-single default <?php echo $pageTheme; ?>">
			<div class="container">
				<?php
					if(has_nav_menu('primary-menu')){
						$defaults = array(
							'theme_location'  => 'primary-menu',
							'menu'            => 'primary-menu',
							'container'       => 'div',
							'container_class' => '',
							'container_id'    => 'pageNav',
							'menu_class'      => 'quality',
							'menu_id'         => '',
							'echo'            => true,
							'fallback_cb'     => 'wp_page_menu',
							'before'          => '',
							'after'           => '',
							'link_before'     => '',
							'link_after'      => '',
							'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
							'depth'           => 2,
							'walker'          => ''
						); wp_nav_menu( $defaults );
					}
				?>
				<div id="breadcrumbs">
					<ul>
						<li><a href="<?php echo home_url(); ?>">Home</a>
							<?php
							$defaults = array(
							'theme_location'  => 'primary-menu',
							'menu'            => 'primary-menu',
							'container'       => '',
							'container_class' => '',
							'container_id'    => '',
							'menu_class'      => 'menu',
							'menu_id'         => '',
							'echo'            => true,
							'fallback_cb'     => 'wp_page_menu',
							'before'          => '',
							'after'           => '',
							'link_before'     => '',
							'link_after'      => '',
							'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
							'depth'           => 0,
							'walker'          => ''
						); wp_nav_menu( $defaults ); ?>
						</li>
					</ul>
				</div>
				<div id="contentColumnWrap">
					<div class="graybarright"></div>
					<div class="graybarleft"></div>
					<div id="columnBalance">
						<div id="contentPrimary" class="heightcol">
						<div class="gutter">
							<h1><?php the_title(); ?></h1>
							<?php the_excerpt(); ?>
							<p id="login-lock">You must be an association member to access this page</p>
						</div>
					</div>
						<div id="contentSecondary" class="heightcol">
						<div class="gutter">

						</div>
					</div>
						<div id="contentTertiary" class="heightcol interior-submenu">
						<div class="pagebar"></div>

					</div>
					</div>
				</div>
			</div>
		<?php endwhile; // end of the loop. ?>
		</div>
	<?php } ?>

	<?php }else{
	 while ( have_posts() ) : the_post();
		$pageTheme = get_field('theme');

		if($pageTheme == 'policy'){
			$menu = 'action';
		}elseif($pageTheme == ''){
			$menu = 'general';
		}elseif($pageTheme != 'policy'){
			$menu = $pageTheme;
		}

		//Get featuredIMG
		if($pageTheme == 'policy'){
			$fPID = 62;
			$speakerIMG  = wp_get_attachment_url( get_post_thumbnail_id(62) );
			$bannerTitle = "Action";
		}elseif($pageTheme == 'quality'){
			$fPID = 64;
			$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id(64) );
			$pageTitle= "Quality";
		}elseif($pageTheme == 'institute'){
			$fPID = 621;
			$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id(621) );
			$pageTitle = "Essential Hospitals Institute" ;
		}elseif($pageTheme == 'education'){
			$fPID = 472;
			$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id(472) );
			$pageTitle = "Education" ;
		}else{
			$fPID = 645;
			$randArr = array_rand($bannerArr);
			$speakerIMG = $bannerArr[$randArr]['image'];
			$bannerSize = "";
			$parents = get_post_ancestors( $post->ID );
			$chck_id = ($parents) ? $parents[count($parents)-1]: $parent_id;
			$pageTitle = "ABOUT";
			$pageTheme = 'policy';

			if($chck_id == 645)
				{$bannerSize = ""; $pageTitle = "ABOUT"; $pageTheme = 'policy';}
		}

		//$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id($fPID) );
		 $bannerTitle = get_field('bannerTitle');



		 ?>

		<div id="featured-img" class="page-single  <?php echo $pageTheme; ?>" style="background-image:url(<?php echo $speakerIMG; ?>); ">
			<div class="container">
				<div id="featured-intro">
					<h3> <span><?php echo $pageTitle; ?> </span><br /> <?php if($bannerTitle != ''){ echo $bannerTitle; }else{ the_title(); }?> </h3>
				</div>
			</div>
		</div>
		<div id="content" class="page-single default <?php echo $pageTheme; ?>">
			<div class="container">
				<?php
					if(has_nav_menu('primary-menu')){
						$defaults = array(
							'theme_location'  => 'primary-menu',
							'menu'            => 'primary-menu',
							'container'       => 'div',
							'container_class' => '',
							'container_id'    => 'pageNav',
							'menu_class'      => 'quality',
							'menu_id'         => '',
							'echo'            => true,
							'fallback_cb'     => 'wp_page_menu',
							'before'          => '',
							'after'           => '',
							'link_before'     => '',
							'link_after'      => '',
							'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
							'depth'           => 2,
							'walker'          => ''
						); wp_nav_menu( $defaults );
					}
				?>
				<div id="breadcrumbs">
					<ul>
						<li><a href="<?php echo home_url(); ?>">Home</a>
							<?php
							$defaults = array(
							'theme_location'  => 'primary-menu',
							'menu'            => 'primary-menu',
							'container'       => '',
							'container_class' => '',
							'container_id'    => '',
							'menu_class'      => 'menu',
							'menu_id'         => '',
							'echo'            => true,
							'fallback_cb'     => 'wp_page_menu',
							'before'          => '',
							'after'           => '',
							'link_before'     => '',
							'link_after'      => '',
							'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
							'depth'           => 0,
							'walker'          => ''
						); wp_nav_menu( $defaults ); ?>
						</li>
					</ul>
				</div>
				<div id="contentColumnWrap">
					<div class="graybarright"></div>
					<div class="graybarleft"></div>
					<div id="columnBalance">
						<div id="contentPrimary" class="heightcol">
						<div class="gutter">
							<h1><?php the_title(); ?></h1>
							<?php the_content(); ?>
						</div>
					</div>
						<div id="contentSecondary" class="heightcol">
						<div class="gutter">
							<?php the_field('secondColumn'); ?>
						</div>
					</div>
						<div id="contentTertiary" class="heightcol interior-submenu">
						<div class="pagebar"></div>
						<?php
							$defaults = array(
								'theme_location'  => 'primary-menu',
								'menu'            => 'primary-menu',
								'container'       => 'div',
								'container_class' => 'page-nav',
								'container_id'    => 'interior-sub',
								'menu_class'      => '',
								'menu_id'         => '',
								'echo'            => true,
								'fallback_cb'     => 'wp_page_menu',
								'before'          => '',
								'after'           => '',
								'link_before'     => '',
								'link_after'      => '',
								'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
								'depth'           => 0,
								'walker'          => ''
							);
							wp_nav_menu( $defaults );
						?>
						<div class="gutter">
							<div class="panel">
								<h3 id="sharetitle">Share</h3>
								<div id="share">
									<!-- AddThis Button BEGIN -->
									<div class="addthis_toolbox addthis_32x32_style" style="">
									<a class="addthis_button_facebook"></a>
									<a class="addthis_button_twitter"></a>
									<a class="addthis_button_linkedin"></a>
									<a class="addthis_button_pinterest_share"></a>
									<a class="addthis_button_google_plusone_share"></a>
									<a class="addthis_button_email"></a>
									<a class="addthis_button_digg"></a>
									<a class="addthis_button_evernote"></a>
									<a class="addthis_button_compact"></a>
									</div>
									<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
									<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=naphsyscom"></script>
									<!-- AddThis Button END -->
								</div>
							</div>
							<?php the_field('thirdColumn'); ?>
						</div>
					</div>
					</div>
				</div>
			</div>
		<?php endwhile; // end of the loop. ?>
		</div><!-- End of Content -->
	<?php } ?>

<?php get_footer(); ?>