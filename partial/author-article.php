<?php $queryObject = get_queried_object();
 
$pageTitle = "ABOUT"; 
$pageTheme = 'policy';
$rand = rand(1,9);
$speakerIMG = "http://mlinson.staging.wpengine.com/wp-content/uploads/2013/11/AEH_generalbanner" .$rand . "_222.jpg";
 
?>
<div id="featured-img" class="archive tag <?php echo $pageTheme; ?>" style="background-image:url(<?php echo $speakerIMG; ?>); ">
	<div class="container">
		<div id="featured-intro">
			<h3> <span><?php echo $pageTitle; ?> </span> <br/><?php echo $queryObject->display_name; ?></h3>
		</div>
	</div>
</div>
<div id="postFeatured" class="<?php echo $queryObject->taxonomy; ?>">
    <div class ="grayblock"></div>

	<div class="container twelve columns content">


		<div id="contentWrap" class="relative archivecss <?php if(!page_in_menu('primary-menu')){echo 'default-utility';} ?>">
			<div class="gutter">
				<div class="container">
					<?php if(has_nav_menu('primary-menu')){
					$defaults = array(
						'theme_location'  => 'primary-menu',
						'menu'            => 'primary-menu',
						'container'       => 'div',
						'container_class' => '',
						'container_id'    => 'pageNav',
						'menu_class'      => 'fallback',
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
					wp_nav_menu( $defaults ); } ?>
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
						<div class="graybarX"></div>
						<div class="gutter">
							<?php get_template_part( 'partial/template', 'author' ); ?>
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