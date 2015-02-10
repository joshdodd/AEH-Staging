<?php /* Template Name: REAL */
	
get_header();
	global $post;
	$postSt = $post->post_status;
	$current_user = wp_get_current_user();
	$cUID = $current_user->ID;
	$cUStaff = get_user_meta($cUID,'aeh_member_type',true); //if($cUStaff == 'hospital')
	$bannerArr = get_field('banners',29); 
 
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
		<div id="content" class="page-single default <?php echo $pageTheme; ?> real">
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
						); wp_nav_menu( $defaults ); //href="<?php get_bloginfo('url')..>/real" target="_blank"> GO TO REAL LEARNING MODULE</a>

						?>
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
							<?php if(is_user_logged_in() && $cUStaff == "hospital"){?>
								<?php update_user_meta( $cUID, 'REAL', 'True'); ?>
								<span class="policy reserve button">
									<form method="post" id="real-form">
										 <input type="submit" id="real-btn" value="clicker"  >
										 <input type="hidden" id="real-id" value="<?php echo $cUID; ?>">
									</form>	 
										</span><br><br> 
								<div id="myBox">ss</div>

							<?php
							}
							else{?>
								<p><strong>Ask Every Patient: REAL learning module is a benefit for members of America's Essential Hospitals. Members may sign in at right to access the module</strong></p>
							<?php } ?>		

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
						//CHECK IF USER LOGGED IN - DISPLAY USER WIDGET OR SIGN IN
						if(is_user_logged_in()){
						$currentUser = get_current_user_id();
						$user_info = get_userdata($currentUser);
						$user_avatar = get_avatar($currentUser); ?>
						<div id="userProfile">
							<div class="gutter clearfix">
								<div id="userAvatar" class="clearfix">
									<div class="group-memberavatar">
										<span class="group-membername"><?php echo $user_info->user_firstname.' ' .$user_info->user_lastname; ?></span>
										<a href="<?php echo get_permalink(274); ?>"><?php echo $user_avatar; ?></a>
									</div>
								</div>
								<div id="userInfo">
									<span class="uinfo fName"><?php echo $user_info->user_firstname; ?></span>
									<span class="uinfo jobTitle"><?php echo $user_info->job_title; ?></span>
									<span class="uinfo org"><?php echo $user_info->employer; ?></span>

									<span class="uinfo email"><a href="mailto:<?php echo $user_info->user_email; ?>"><?php echo $user_info->user_email; ?></a></span>
								</div>
								<div id="userMeta">
									<ul class="social">
										<?php if ($user_info->linkedin) { ?>
											<li class="linkedin"><a href="<?php echo $user_info->linkedin; ?>">linkedin</a></li>
										<?php } ?>
										<?php if ($user_info->twitter) { ?>
											<li class="twitter"><a href="<?php echo $user_info->twitter; ?>">twitter</a></li>
										<?php } ?>
										<?php if ($user_info->facebook) { ?>
											<li class="facebook"><a href="<?php echo $user_info->facebook; ?>">facebook</a></li>
										<?php } ?>
										<li class="mail"><a href="mailto:<?php echo $user_info->user_email; ?>">mail</a></li>
									</ul>
									<span class="edit"><a href="<?php echo get_permalink(274); ?>?a=edit">[edit profile]</a></span>
								</div>
							</div>
						</div>
					<?php }else{ ?>
						<div class="panel signin">
							<div class="gutter">
								<h2>Sign In</h2>
								<p>Sign in to access the interactive tool</p>
								 <?php $args = array(
						        'echo' => true,
						        'redirect' => site_url( $_SERVER['REQUEST_URI'] ),
						        'form_id' => 'loginform',
						        'label_username' => __( 'Username' ),
						        'label_password' => __( 'Password' ),
						        'label_remember' => __( 'Remember Me' ),
						        'label_log_in' => __( '&raquo;' ),
						        'id_username' => 'user_login',
						        'id_password' => 'user_pass',
						        'id_remember' => 'rememberme',
						        'id_submit' => 'wp-submit',
						        'remember' => false,
						        'value_username' => NULL,
						        'value_remember' => false );
						        wp_login_form($args); ?>
							</div>
						</div>
					<?php } ?>
 
						<div class="gutter">
							<?php the_field('thirdColumn'); ?>
						</div>
					</div>
					</div>
				</div>
			</div>
		<?php endwhile; // end of the loop. ?>
		</div><!-- End of Content -->
 

<?php get_footer(); ?>
 