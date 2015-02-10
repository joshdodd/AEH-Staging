<?php get_header(); ?>

<?php  $speakerIMG = wp_get_attachment_url( get_post_thumbnail_id(472) );   ?>
<div id="featured-img" class="education webinar archive" style="background-image:url(<?php echo $speakerIMG ?>);">
	<div class="container">
		<div id="featured-intro">
				<h3><span>EDUCATION</span><br/>Events</h3>
		</div>
	</div>
</div>
<div id="contentWrap" class="education webinar archive">
	<a id="prevbtn" title="Show previous"> </a>
	<a id="nextbtn" title="Show more"> </a>
	<div class="gutter">
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

				<a href="<?php echo site_url('/feed/?post_type=events'); ?>" target="_blank">
					<div id="rssFeedIcon" class="education">
						Subscribe
					</div>
				</a>

			</div>

			<div id="postFeatured">
				<div class="eightteen columns filters-events">
					<!-- begin Category Filters !-->
	 				<span> FILTER BY ››</span>
	 				<div id="red_btn" data-filter="policy" class="filter_btn-events ">
	 					<img src="http://essentialhospitals.org/wp-content/themes/EssentialHospitals/images/policy.png">
	 					<span>Action</span>
	 				</div>
	 				<div id="green_btn" data-filter="quality" class="filter_btn-events ">
	 					<img src="http://essentialhospitals.org/wp-content/themes/EssentialHospitals/images/quality.png">
	 					<span>Quality</span>
	 				</div>
	 				<div id="gray_btn" data-filter="education" class="filter_btn-events ">
	 					<img src="http://essentialhospitals.org/wp-content/themes/EssentialHospitals/images/edu.png">
	 					<span>Education</span>
	 				</div>
	 				<div id="blue_btn" data-filter="institute" class="filter_btn-events ">
	 					<img src="http://essentialhospitals.org/wp-content/themes/EssentialHospitals/images/inst.png">
	 					<span>Institute</span>
	 				</div>
					<div id="blue_btn" data-filter="*" class="filter_btn-events ">
						<span>All Sections</span>
					</div>
	 				<!-- end Category Filters !-->

	 				
	 			</div>
			</div>


			<div id="contentPrimary">
				<div class="graybar"></div>
				<div class="gutter clearfix">

					<!-- begin Time Filters !-->
					 
	 				<div id="timeFilter-events">
						<?php if(isset($_GET['timeFilter'])){
								$time = $_GET['timeFilter'];
						} ?>
					 
							<span class="timePhrase">Filter by >></span><br />
							<div data-filter="future" class="timeButton-events <?php if($time == 'future'){ echo 'active'; }?>">
								<a>Upcoming Events</a><br />
							</div>
							<div data-filter="past" class="timeButton-events <?php if($time == 'past'){ echo 'active'; }?>">
								<a>Previous Events</a><br />
							</div>
							<div data-filter="*" class="timeButton-events <?php if(!isset($time)){ echo 'active'; } ?>">
								<a>View All</a><br />
							</div>
			 				<div class="bot-border"></div>
					</div>
					<!-- end Time Filters !-->

					<span class="filterby">Filter By Month >></span>
					<select name="eventMonth" id="monthFilter">
						<option value="*">Select Month</option>
						<option value="1">January</option>
						<option value="2">February</option>
						<option value="3">March</option>
						<option value="4">April</option>
						<option value="5">May</option>
						<option value="6">June</option>
						<option value="7">July</option>
						<option value="8">August</option>
						<option value="9">September</option>
						<option value="10">October</option>
						<option value="11">November</option>
						<option value="12">December</option>
						<option value="*">Show all Months</option>
					</select>
					 
					<span class="filterby">Search Events >></span> 
					<form id="eventsSearch">
						<input type="text" id="esearch" placeholder="Search" />
						<input type="submit" id="esubmit" value="Search" />
					</form>
				</div>
			</div>

			<div id="contentSecondary">
				<div class="graybar"></div>
				<div class="gutter clearfix">
						<div id="postBox" class="clearfix">
								<div id="fader" class="clearfix scrollable events">
									<div id="loader-gif"> Loading more events</div>
									<div class="items">
										<?php AEH_EVENTS::get_events(); ?>
									</div>
								</div>
						</div>
				</div>
			</div>


		</div>
	</div>
</div>

<?php get_footer(); ?>
