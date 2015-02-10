<?php while ( have_posts() ) : the_post();
	$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	$postID     = get_the_ID();
	$postType   = get_post_type($postID);
	$general = false;
	if($postType == 'policy'){
		$bannerTitle = '<span class="redd">ACTION</span><br>';
		//$bannerDesc  = get_field('bannerTitle',62);
		//$bannerImg   = wp_get_attachment_url( get_post_thumbnail_id(62) );
		$bannerImg  = get_field('small_banner', 62);
	}elseif($postType == 'quality'){
		$bannerTitle = '<span class="greenn">QUALITY</span><br>';
		//$bannerDesc  = get_field('bannerTitle',64);
		//$bannerImg   = wp_get_attachment_url( get_post_thumbnail_id(64) );
		$bannerImg  = get_field('small_banner', 64);
	}elseif($postType == 'institute'){
		$bannerTitle = '<span class="bluee">INSTITUTE</span><br>';
		//$bannerDesc  = get_field('bannerTitle',621);
		//$bannerImg = wp_get_attachment_url( get_post_thumbnail_id(621) );
		$bannerImg  = get_field('small_banner', 621);
	}else{
		$bannerTitle = '<span class="redd">ABOUT</span><br>';
		$rand = rand(1,9);
		$bannerImg = "http://mlinson.staging.wpengine.com/wp-content/uploads/2013/11/AEH_generalbanner" .$rand . "_222.jpg";
		$postType  == 'policy';
		$bannerDesc  = '';
	} ?>
<div id="featured-img-small" class="<?php echo get_post_type($postID); ?>" style="background-image:url(<?php echo $bannerImg; ?>);background-size:cover;background-position:center center;">
	<div class="container">
		<div id="featured-intro">
			<h3><?php echo $bannerTitle; ?></h3>
			<?php endwhile; // end of the loop. ?>
		</div>
	</div>
</div>
<div id="content" class="single <?php echo "$postType" ?> <?php if(!page_in_menu('primary-menu')){ if($general) echo 'default-utility'; else  echo 'default-'.$postType;   } ?>">
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
		<div id="columnBalance">
			<div id="contentPrimary">
			<div class="graybar"></div>
			<div class="gutter">
			<?php if ( have_posts() ) while ( have_posts() ) : the_post();
				$auth = get_the_author_meta('ID');
			?>
				<h1><?php the_title(); ?></h1>
				<div id="singlestream">
				<?php $postType = get_post_type( get_the_ID() );
				if($postType == 'policy'){
					$postColor = 'redd';
				}else if($postType == 'quality'){
					$postColor = 'greenn';
				}else if($postType == 'education'){
					$postColor = 'grayy';
				}else if($postType == 'institute'){
					$postColor = 'bluee';
				}else{
					$postColor = 'bluee';
				}
				?>
				<img src="<?php bloginfo('template_directory'); ?>/images/icon-<?php echo $postType; ?>.png" />
				<?php $terms = wp_get_post_terms(get_the_ID(), 'series');
    					if($terms){
	    					$termLink = get_term_link($terms[0], 'series');
		    				echo "<a href='".$termLink."'>".$terms[0]->name."</a>";
	    				}
    				?></div>
				<div id="postmeta">
					<span class="postmeta"><?php the_time('M j, Y'); ?> || <em><a href="<?php echo get_author_posts_url($auth); ?>?prof=article"><?php the_author(); ?></a></em></span><span class="postcomments"><?php comments_number( ' || (0) comments', ' || (1) comment', ' || (%) comments' ); ?></span>
				<div id="topics">
					<div class="item-tags">
	    				<?php the_tags(' ',' ',' '); ?>
	    			</div>
				</div>
				</div>
				<?php the_post_thumbnail(); ?>
				<div class="postcontent">
					<?php the_excerpt(); ?>
					<p id="login-lock">You must be an association member to access this article</p>
				</div>
			</div>
		</div>
			<div id="contentSecondary">
				<div class="gutter clearfix">

				</div>
			</div>
			<div id="contentTertiary">
				<div class="graybar"></div>
				<div class="gutter clearfix">

				</div>
			</div>
		</div>
	</div>
<?php endwhile; ?>
</div><!-- End of Content -->