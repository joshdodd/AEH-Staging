<?php /* Template for Group custom post type */
	get_header();


	$pageTheme = get_field('theme');
	$mnclass = "mnbanner";

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
			$pageTitle = '';
			$mnclass = "";
		}



	?>


<div id="membernetwork" class="<?php echo $mnclass; ?>">


	<?php if($pageTitle != '') { ?>
	<div id="featured-img" class="page-single <?php echo $pageTheme; ?> " style="background-image:url(<?php echo $speakerIMG; ?>); ">
		<div class="container">
			<div id="featured-intro">
				<h3>
					<span><?php echo $pageTitle; ?> </span><br /> <?php the_title(); ?>
				</h3>
			</div>
		</div>
	</div>
	<?php } ?>

	<div class="container">

	<?php if ($pageTitle == '') {?>

		 <h1 class="title"><span class="grey">Essential Hospitals</span> Member Network </h1>
	<?php } ?>


		<?php wp_reset_postdata();
            global $post;
			$memberArray = array();
			$currentUser = get_current_user_id();
			if($post->post_parent){
				$parent = array_reverse(get_post_ancestors($post->ID));
				$members = get_post_meta($parent[0], 'autp');
			}else{
				$members = get_post_meta($post->ID, 'autp');
			}
			//print_r($members);
				foreach($members as $member){
					foreach($member as $user){
						$id = $user['user_id'];
						array_push($memberArray,$id);
					}  }
				if (in_array($currentUser, $memberArray)) {
				    $checker = true;
				}else{
					$checker = false;
				} ?>

		<?php
		if ($pageTitle != ''){
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
			} ?>
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
		<?php
		}
		else{
			get_template_part('membernetwork/content','usernav');
		}
		?>


		<div id="membercontent" class="group groupcont">
			<div class="graybarleft"></div>
			<div class="graybarright"></div>
			<div class="gutter clearfix">
				<div class="group-details groupcol">
					<div class="panel description">
						<h2 class="heading"><?php the_title(); ?></h2>
							<div class="gutter">
								<?php if(is_user_logged_in() && $checker == true){
									the_content();
								}else{
									echo "<p>";
									the_field('teaser');
									echo "</p>";
									echo "<h4>You are not a member of this group.</h4>";
								}
								?>
							</div>
					</div>
					<?php if(is_user_logged_in() && $checker == true){
						  get_template_part('membernetwork/content','groupdiscussion'); } ?>
					</div>

				<div class="group-members groupcol">
					<div class="panel">
						<h2 class="heading">Group Members</h2>
						<?php get_template_part('membernetwork/content','groupmembers'); ?>
					</div>
					<?php if(is_user_logged_in() && $checker == true){ ?>
					<?php $webinars = get_field('related_webinars');
					if($webinars){ ?>
					<div class="panel">
						<h2 class="heading">Related Webinars</h2>
						<div class="gutter">
							<?php foreach($webinars as $post){
								global $post;
								setup_postdata($post);
								echo '<div class="grouplist"><div class="gutter"><span class="title">';
									$isPrivate = get_field('private_webinar');
									if($isPrivate){
										echo '<div class="private-webinar redd"></div>';
									}
								echo '<a href="'.get_permalink().'">'.get_the_title().'</a></span></div></div>';
								wp_reset_postdata();
							} ?>
						</div>
					</div>
					<?php } ?>

					<?php 

					//$meta = get_post_meta( get_the_ID() );
					//$eventids = $meta['related_events'][0];
					$eventids  = get_field('related_events');
					//******************NEED TO EDIT THIS HERE TO GET ID FROM EVENT LOOP*************//
					if($eventids){ ?>
					<div class="panel">
						<h2 class="heading">Related Events</h2>
						<div class="gutter">
							<?php foreach($eventids as $post){
								global $post;
								setup_postdata($post);
								echo '<a href="'.get_permalink().'">'.get_the_title().'</a></span></div></div>';
								wp_reset_postdata();
							} ?>
						</div>
					</div>
					<?php } ?>

					<?php if(get_field('middle_column')){ while(has_sub_field('middle_column')){ ?>
						<div class="panel colrepeat">
							<h2 class="heading"><?php the_sub_field('title'); ?></h2>
							<div class="gutter">
								<?php the_sub_field('content'); ?>
							</div>
						</div>
					<?php }
					} } ?>
				</div>


				<div class="group-resources groupcol">
					<?php if(is_user_logged_in() && $checker == true){ ?>
					<?php
							if($post->post_parent){
							$parent = array_reverse(get_post_ancestors($post->ID));
							$postlink = get_permalink($parent[0]);
							$posttitle = get_the_title($parent[0]);
							$args = array(
								'depth'        => 0,
								'show_date'    => '',
								'date_format'  => get_option('date_format'),
								'child_of'     => $parent[0],
								'exclude'      => '',
								'include'      => '',
								'title_li'     => '',
								'echo'         => 0,
								'authors'      => '',
								'sort_column'  => 'menu_order, post_title',
								'link_before'  => '',
								'link_after'   => '',
								'walker'       => '',
								'post_type'    => 'group',
							    'post_status'  => 'publish'
							); }else{
								$postlink = get_permalink($post->ID);
								$posttitle = get_the_title($post->ID);
								$args = array(
									'depth'        => 0,
									'show_date'    => '',
									'date_format'  => get_option('date_format'),
									'child_of'     => $post->ID,
									'exclude'      => '',
									'include'      => '',
									'title_li'     => '',
									'echo'         => 0,
									'authors'      => '',
									'sort_column'  => 'menu_order, post_title',
									'link_before'  => '',
									'link_after'   => '',
									'walker'       => '',
									'post_type'    => 'group',
								    'post_status'  => 'publish'
								);
							}
							$pages = wp_list_pages($args);
								if($pages != ''){
									echo '<div class="panel subnav group"><div class="gutter"><ul class="submenu-group">';
									echo '<li class="parent"><a href="'.$postlink.'">'.$posttitle.'</a></li>';
									echo wp_list_pages($args);
									echo '</ul></div></div>';
								}

							 ?>

					<?php if(get_field('right_column')){ while(has_sub_field('right_column')){ ?>
						<div class="panel colrepeat">
							<h2 class="heading"><?php the_sub_field('title'); ?></h2>
							<div class="gutter">
								<?php the_sub_field('content'); ?>
							</div>
						</div>
					<?php }
					} ?>
					<?php }elseif(is_user_logged_in() && $checker == false){
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
								<p>Sign in to access this group</p>
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
				</div>
			</div>
		</div>


		</div>
	</div>

<?php get_footer('sans'); ?>