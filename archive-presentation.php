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

				<a href="<?php echo site_url('/feed/?post_type=presentation'); ?>" target="_blank">
					<div id="rssFeedIcon" class="education">
						Subscribe
					</div>
				</a>

			</div>

			<div id="postFeatured">

			</div>


			<div id="contentPrimary">
				<div class="graybar"></div>
				<div class="gutter clearfix">
					<form id="presentationSearch">
						<input type="text" id="psearch" placeholder="search" />
						<input type="submit" id="psubmit" value="Search" />
					</form>
				</div>
			</div>

			<div id="contentSecondary">
				<div class="graybar"></div>
				<div class="gutter clearfix">
						<div id="postBox" class="clearfix">
								<div id="fader" class="clearfix scrollable events">
									<div id="loader-gif"> Loading more presentations</div>
									<div class="items">
										<?php AEH_Presentations::get_presentations(); ?>
									</div>
								</div>
						</div>
				</div>
			</div>


		</div>
	</div>
</div>

<?php get_footer(); ?>
