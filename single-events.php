<?php get_header();
if(have_posts()){while(have_posts()){
the_post(); ?>

<div id="membernetwork" class="mnbanner">

	<!-- begin Banner !-->
	<?php $speakerIMG = wp_get_attachment_url( get_post_thumbnail_id(472) ); ?>
	<div id="featured-img" class="page-single education" style="background-image:url(<?php echo $speakerIMG; ?>); ">
		<div class="container">
			<div id="featured-intro">
				<h3><span class="grey">EDUCATION</span> <br/> <?php the_title(); ?></h3>
			</div>
		</div>
	</div>
	<!-- end Banner !-->


	<!-- begin Content !-->
	<?php 
		$event_id = get_the_ID();
		$meta = get_post_meta( get_the_ID() );
		$date 						 = $meta['date'][0];
		$time = date( 'g:i A', $date );
		$date = date( 'l, F j, Y', $date );
		$end_date  = $meta['end_date'][0];
		if($end_date !=''){
			$end_time = date( 'g:i A', $end_date);
			$end_date = date( 'l, F j, Y',$end_date );
		}

		$location 				 = wpautop($meta['location'][0]);
		$intro 						 = $meta['intro'][0];
		$registration_link = $meta['registration_link'][0];
		$agenda       		 = wpautop($meta['agenda'][0]);
		$hotel_info   		 = wpautop($meta['hotel_info'][0]);
		$travel_info  		 = wpautop($meta['travel_info'][0]);
		$speakers     		 = get_field('speakers_faculty');
		$homework 	  		 = wpautop($meta['homework'][0]);
		$scholarships 		 = wpautop($meta['scholarships'][0]);
		$dates 						 = wpautop($meta['dates'][0]);
		$plug 						 = wpautop($meta['plug'][0]);
		$contact 					 = wpautop($meta['contact'][0]);
		$links 						 = wpautop($meta['related_shit'][0]);
		$social 					 = $meta['social'][0];
		$photos 					 = $meta['photos'][0];
		$discussions 			 = wpautop($meta['discussions'][0]);
		$expense 				 = $meta['expense'][0];
		$partnership 			 = wpautop($meta['partnership'][0]);
		$hide_event 			 = $meta['hide_event'][0];
		$section 					 = $meta['section'][0];
		$custom_left_title			 = $meta['custom_field_left_title'][0];
		$custom_left				 = wpautop($meta['custom_field_left'][0]);
		$custom_middle_title		 = $meta['custom_field_middle_title'][0];
		$custom_middle				 = wpautop($meta['custom_field_middle'][0]);
		$minisite					 = $meta['minisite'][0];

		// audience check
		$audience = $meta['audience'][0];
		if($audience != NULL && $audience != ''){
			$audience = get_term_by('ID', $audience, 'audience')->slug;
			$audienceTitle = get_term_by('ID', $audience, 'audience')->name;
			if($audience != 'open'){
				$lock = true;
			}else{
				$lock = false;
			}
		}else{
			$lock = false;
		}
		// login check
		if(is_user_logged_in()){
			$lock = false;
		}

		// group check
		$group = $meta['group'][0];
		if($group != NULL && $audience != ''){
			$group = unserialize($group);
			$group = $group[0];
		 	

			$events = get_post_meta($group, 'related_events',true);
 		
 			if(!$events)
 			{
 				$events[0] = $event_id;
 	 
 			}
 			else
 			{
 				if(!in_array($event_id, $events) ){
 					array_push($events, $event_id);
 				}
 
 			}
 
 	 
			
	 

			update_post_meta($group, 'related_events', $events);




			$groupTitle = get_post($group)->post_title;
			if(is_user_logged_in()){
				$uid = get_current_user_id();
				$umem = get_user_meta($uid,'groupMem',true);
				if($umem != '' && $umem != NULL && $umem != false){
					if(in_array($group,$umem)){
						$groupLock = false;
					}else{
						$groupLock = true;
						$lock = true;
					}
				}else{
					$groupLock = true;
					$lock = true;
				}
			}else{
				$groupLock = true;
				$lock = true;
			}
		} ?>

	<div class="container">
		
		<?php $memberArray = array();
			$currentUser = get_current_user_id();
			$members = get_post_meta($post->ID, 'autp');
				foreach($members as $member){
					foreach($member as $user){
						$id = $user['user_id'];
						array_push($memberArray,$id);
					}  }
				if (in_array($currentUser, $memberArray)) {
				    $checker = true;
				}else{
					$checker = false;
				}
 
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
		<!-- end Breadcrumbs !-->


		<div id="membercontent" class="group groupcont webinarLinkColor-<?php echo $theme; ?>">
			<div class="graybarleft"></div>
			<div class="graybarright"></div>
			<div class="gutter clearfix">

				<div class="group-details groupcol">
					<h2 class="heading <?php echo $section; ?>"><?php the_title(); ?></h2>

					<!-- begin Descriptions !-->
					<div class="panel description">
						<div class="gutter">
							<?php if($date != '' && $date != '0'){ ?>
								<p>
									<strong>Date: </strong><?php echo $date; ?> , 
									<?php if($time != '' && $time != '0'){ ?>
										<?php echo $time; ?>
									<?php } ?>
									<?php if($end_date != '' && $end_date != '0'){ ?>
										- <?php echo $end_date; ?>
										<?php if($end_time != '' && $end_time != '0'){ ?>
										<?php echo $end_time; ?>
										<?php } 
									}?>

								</p>
							<?php } ?>
 
							
							<?php if($location != '' && $location != '0'){ ?>
								<p>
									<strong>Location: </strong><?php echo $location; ?>
								</p>
							<?php } ?>
							<p>
								<!-- begin Intro !-->
									<?php if($intro != '' && $agenda != '0' && $lock == true && $groupLock == true){
													echo $intro;
												} ?>
								<!-- end Intro !-->
							</p>

							<!-- begin Lock Message !-->
							<?php if($lock == true){
								 	if($groupLock == true){ ?>
										<p>This event is restricted to <em><?php echo $groupTitle; ?></em> members.</p>
									<?php }else{ ?>
										<p>This is a <em><?php echo $audienceTitle; ?></em> event. You must be logged in to access it.</p>
									<?php } ?>
							<?php } ?>
							<!-- end Lock Message !-->

							<?php if($lock == false ){ ?>
								<?php the_content(); ?>
								<?php if( ($registration_link != '' && $registration_link != NULL && $registration_link != "0" && $lock == false) ){
										echo "<div class='panel description'>
														<div class='gutter'>
															<span class='reserve button policy'><a href='$registration_link'>Register</a>
														</div>
													</div>";
									} ?>
							<?php } ?>
						</div>
					</div>
					<!-- end Descriptions !-->


					<!-- begin Agenda !-->
					<?php if( ($agenda != '' && $agenda != NULL && $agenda != "0" && $lock == false) ){ ?>
						<div class="panel description">
							<h2 class="heading">Agenda</h2>
							<div class="gutter">
									<?php echo $agenda; ?>
							</div>
						</div>
					<?php } ?>
					<!-- end Agenda !-->

					<!-- begin Speakers !-->
					<?php if( (count($speakers) > 0 && $speakers != '0' && $lock == false) ){ ?>
						<div class="panel description">
							<h2 class="heading">Speakers</h2>
								<?php foreach($speakers as $speaker){ ?>
									<div class='speaker-container cf clearfix'>
										<div class='speaker-image'>
											<img src='<?php echo $speaker['image']['url']; ?>' />
										</div>
										<div class='speaker-data'>
											<h3 class='speaker-name'><?php echo $speaker['name']; ?></h3>
											<span class='speaker-bio'><?php echo $speaker['bio']; ?></span>
											<?php if($speaker['link'] != ''){ ?>
												<span class='speaker-link'><br/>
													<a href='<?php echo $speaker['link']; ?>'>View full bio</a>
												</span>
											<?php } ?>
										</div>
									</div>
								<?php } ?>
						</div>
					<?php } ?>
					<!-- end Speakers !-->

					<!-- begin Photos !-->
					<?php if( ($photos != '' && $photos != NULL && $photos != "0" && $lock == false) ){ ?>
						<div class="panel description">
							<h2 class="heading">Photos From Event</h2>
							<div class="gutter">
									<?php echo $photos; ?>
							</div>
						</div>
					<?php } ?>
					<!-- end Photos !-->

					<!-- begin photos !-->
					<?php if( ($plug != '' && $plug != NULL && $plug != "0" && $lock == false) ){ ?>
						<div class="panel description">
							<h2 class="heading">Plug</h2>
							<div class="gutter">
									<?php echo $plug; ?>
							</div>
						</div>
					<?php } ?>
					<!-- end Plug !-->

					<!-- begin Partnerships !-->
					<?php if( ($partnership != '' && $partnership != NULL && $partnership != "0" && $lock == false) ){ ?>
						<div class="panel description">
							<h2 class="heading">Partnerships</h2>
							<div class="gutter">
									<?php echo $partnership; ?>
							</div>
						</div>
					<?php } ?>
					<!-- end Partnerships !-->

					<!-- begin Custom Left !-->
					<?php if( ($custom_left_title != '' && $custom_left_title != NULL && $custom_left_title  != "0" && $lock == false) ){ ?>
						<div class="panel description">
							<h2 class="heading"><?php echo $custom_left_title; ?></h2>
							<div class="gutter">
									<?php echo $custom_left; ?>
							</div>
						</div>
					<?php } ?>
					<!-- end Custom Left !-->


				</div>



				<div class="group-members groupcol">

					<!-- begin Hotel Info !-->
					<?php if( ($hotel_info != '' && $hotel_info != NULL && $hotel_info != "0" && $lock == false) ){ ?>
						<div class="panel description">
							<h2 class="heading">Hotel Info</h2>
							<div class="gutter">
									<?php echo $hotel_info; ?>
							</div>
						</div>
					<?php } ?>
					<!-- end Hotel Info !-->

					<!-- begin Travel Info !-->
					<?php if( ($travel_info != '' && $travel_info != NULL && $travel_info != "0" && $lock == false) ){ ?>
						<div class="panel description">
							<h2 class="heading">Travel Info</h2>
							<div class="gutter">
									<?php echo $travel_info; ?>
							</div>
						</div>
					<?php } ?>
					<!-- end Travel Info !-->

					<!-- begin Expenses Reimbursement !-->
					<?php if( ($expense != '' && $expense != NULL && $expense != "0" && $lock == false) ){ ?>
						<div class="panel description">
							<h2 class="heading">Expense Reimbursement</h2>
							<div class="gutter">
								 <?php echo $expense; ?> 
							</div>
						</div>
					<?php } ?>
					<!-- end Expenses Reimbursement !-->

					<!-- begin Prework !-->
					<?php if( ($homework != '' && $homework != NULL && $homework != "0" && $lock == false) ){ ?>
						<div class="panel description">
							<h2 class="heading">Prework</h2>
							<div class="gutter">
									<?php echo $homework; ?>
							</div>
						</div>
					<?php } ?>
					<!-- end Prework !-->

					<!-- begin Related Presentations !-->
					<?php if( $lock == false ){ ?>
						<?php global $aeh_presentations;
							$aeh_presentations->related_presentations(); ?>
					<?php } ?>
					<!-- end Related Presentations !-->

					<!-- begin Scholarships !-->
					<?php if( ($scholarships != '' && $scholarships != NULL && $scholarships != "0" && $lock == false) ){ ?>
						<div class="panel description">
							<h2 class="heading">Scholarships</h2>
							<div class="gutter">
									<?php echo $scholarships; ?>
							</div>
						</div>
					<?php } ?>
					<!-- end Scholarships !-->

					<!-- begin Dates !-->
					<?php if( ($dates != '' && $dates != NULL && $dates != "0" && $lock == false) ){ ?>
						<div class="panel description">
							<h2 class="heading">Additional Dates</h2>
							<div class="gutter">
									<?php echo $dates; ?>
							</div>
						</div>
					<?php } ?>
					<!-- end Dates !-->

					<!-- begin Custom Middle !-->
					<?php if( ($custom_middle_title != '' && $custom_middle_title != NULL && $custom_middle_title  != "0" && $lock == false) ){ ?>
						<div class="panel description">
							<h2 class="heading"><?php echo $custom_middle_title; ?></h2>
							<div class="gutter">
									<?php echo $custom_middle; ?>
							</div>
						</div>
					<?php } ?>
					<!-- end Custom MIddle !-->

				</div>



				<div class="group-resources groupcol">
					<?php if(is_user_logged_in()){
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
					<?php } ?>

					<!-- begin Contact !-->
					<?php if( ($contact != '' && $contact != NULL && $contact != "0" && $lock == false) ){ ?>
						<div class="panel description">
							<h2 class="heading">Contact Info</h2>
							<div class="gutter">
									<?php echo $contact; ?>
							</div>
						</div>
					<?php } ?>
					<!-- end Contact !-->

					<!-- begin Links !-->
					<?php if( ($links != '' && $links != NULL && $links != "0" && $lock == false) ){ ?>
						<div class="panel description">
							<h2 class="heading">Additional Links</h2>
							<div class="gutter">
									<?php echo $links; ?>
							</div>
						</div>
					<?php } ?>
					<!-- end Links !-->

				</div>

			</div>
		</div>


	</div>
	<!-- end Content !-->


</div>

<?php } } get_footer(); ?>
