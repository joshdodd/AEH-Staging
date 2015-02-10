<?php get_header(); ?>
<?php
	$queried_object = get_queried_object();
	$term_id = $queried_object->term_id;
	$term_slug = $queried_object->slug;
    $term_meta = get_option( "taxonomy_term_$term_id" );
    $postType = $term_meta['section'];

    if($postType == 'policy'){
		$bannerImg  = wp_get_attachment_url( get_post_thumbnail_id(62) );
		$bannerTitle = "Action";
	}elseif($postType == 'quality'){
		$bannerImg  = wp_get_attachment_url( get_post_thumbnail_id(64) );
		$bannerTitle = "Quality";
	}elseif($postType == 'institute'){
		$bannerImg  = wp_get_attachment_url( get_post_thumbnail_id(621) );
		$bannerTitle = "Essential Hospitals Institute" ;
	}
	elseif($postType == 'education'){
		$bannerImg  = wp_get_attachment_url( get_post_thumbnail_id(475) );
		$bannerTitle = "Education" ;
	}else{
		$postType = 'policy'; //About/general
		$bannerTitle = "About" ;
		$bannerArr = get_field('banners',29);

		$randArr = array_rand($bannerArr);
		$speakerIMG = $bannerArr[$randArr]['image'];
	}




?>

<?php //$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>
<div id="featured-img" class="archive series <?php echo $postType ?>" style="background-image:url(<?php echo $bannerImg; ?>);">
	<div class="container">
		<div id="featured-intro" class="<?php echo $postType ?>">
			<h3><span><?php echo $bannerTitle; ?></span><br /> <?php single_tag_title(); ?></h3>
		</div>
	</div>
</div>
<div id="postFeatured">

    <div class ="grayblock"></div>

	<div class="container twelve columns content">


		<div id="contentWrap" class="action default-<?php echo $term_meta['section']; ?>">
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

						<a href="<?php echo site_url('/feed/'); ?>?series=<?php echo $term_slug; ?>" target="_blank">
							<div id="rssFeedIcon" class="<?php echo $term_meta['section']; ?>">
								Subscribe
							</div>
						</a>

					</div>


					<div id="contentPrimary" class="stream-<?php echo $term_meta['section']; ?>">
						<div class="graybar"></div>
						<div class="gutter">
						<?php
							$cUser = wp_get_current_user();
							$cUserMeta = get_user_meta($cUser->ID, 'aeh_member_type', true);
							$term_slug = get_queried_object()->slug;
							if($term_slug == 'action-alerts' || $term_slug == 'action-updates'){
								if($cUserMeta == 'hospital'){
									include(locate_template('partial/template-tagloop.php'));
								}else{ ?>
									<h1>Restricted Content</h1>
									<p><?php single_tag_title(); ?> entries are restricted to Association Members only.</p>
								<?php }
							}else{
								include(locate_template('partial/template-tagloop.php'));
							} ?>
						</div>
					</div>
				</div>
			</div>
		</div>


	</div><!-- End of Content -->

	<?php
	$cUser = wp_get_current_user();
	$cUserMeta = get_user_meta($cUser->ID, 'aeh_member_type', true);
	$term_slug = get_queried_object()->slug;
	if($term_slug == 'action-alerts' || $term_slug == 'action-updates'){
		if($cUserMeta == 'hospital'){ ?>
			<div id="prev" title="Show previous"> </div>
			<div id="next" title="Show more Articles"> </div>

			<a id="prevbtn" title="Show previous">  </a>
			<a id="nextbtn" title="Show more">  </a>
		<?php }
	}else{ ?>
		<div id="prev" title="Show previous"> </div>
		<div id="next" title="Show more Articles"> </div>

		<a id="prevbtn" title="Show previous">  </a>
		<a id="nextbtn" title="Show more">  </a>
	<?php } ?>



</div>

<?php get_footer(); ?>