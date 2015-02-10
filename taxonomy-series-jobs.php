<?php get_header(); ?>

<?php 
$rand = rand(1,9);
$bannerImg = "http://mlinson.staging.wpengine.com/wp-content/uploads/2013/11/AEH_generalbanner" .$rand . "_222.jpg"; ?>
<div id="featured-img" class="archive series policy jobs" style="background-image:url(<?php echo $bannerImg; ?>);">
	<div class="container">
		<div id="featured-intro">
			<h3><span>ABOUT</span><br /><?php single_tag_title(); ?></h3>
		</div>
	</div>
</div>
<div id="postFeatured">

    <div class ="grayblock"></div>

	<div class="container twelve columns content">
		<div id="pagefilter" data-query="jobs"></div>
		<div id="jobFilter">
				<div id="filterContRight">
					<span class="timePhrase">Jobs at >></span>
					<div data-time="all" class="timeButton">
						<a>All</a>
					</div>
					<div data-time="at-americas-essential-hospitals" class="timeButton">
						<a>America's Essential Hospitals</a>
					</div>
					<div data-time="at-our-member-hospitals" class="timeButton">
						<a>Our Member Hospitals</a>
					</div>
				</div>
			</div>

		<div id="contentWrap" class="action">
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
					</div>

					<div id="contentPrimary">
						<div class="graybar"></div>
						<div class="gutter">
							<?php get_template_part( 'partial/template', 'jobloop' ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>


	</div><!-- End of Content -->
	<div id="prev" title="Show previous"> </div>
	<div id="next" title="Show more Articles"> </div>

	<a id="prevbtn" title="Show previous">  </a>
	<a id="nextbtn" title="Show more">  </a>
</div>

<?php get_footer(); ?>