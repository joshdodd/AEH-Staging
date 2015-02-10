<?php get_header(); ?>

<?php $speakerIMG = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); 

$pageTheme = get_post_type();


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
			$rand = rand(1,9);
			$speakerIMG = "http://mlinson.staging.wpengine.com/wp-content/uploads/2013/11/AEH_generalbanner" .$rand . "_222.jpg";
			$pageTheme == 'policy';
			$bannerSize = "";
			$parents = get_post_ancestors( $post->ID );
			$chck_id = ($parents) ? $parents[count($parents)-1]: $parent_id;
			$pageTitle = "ABOUT"; 
			$pageTheme = 'policy';

			if($chck_id == 645)
				{$bannerSize = ""; $pageTitle = "ABOUT"; $pageTheme = 'policy';}
		}

?>


<div id="featured-img" class="archive tag <?php echo $pageTheme; ?>" style="background-image:url(<?php echo $speakerIMG; ?>);">
	<div class="container">
		<div id="featured-intro">
			<h3><span><?php echo $pageTitle; ?> </span> <br /><?php $tax = $wp_query->get_queried_object();echo $tax->name; ?> </h3>
		</div>
	</div>
</div>
<div id="postFeatured">
	 
   

	<div class="container twelve columns content">


		<div id="contentWrap" class="action archivecss default-<?php echo $pageTheme; ?>topics">
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

					<!--
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
					-->
					<div id="contentPrimary">
						<div class="graybar"></div>
						<div class="gutter">
							<?php get_template_part( 'partial/template', 'archive' ); ?>
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