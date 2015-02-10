<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
<?php $speakerIMG = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>
<div id="featured-img" class="action" style="background-image:url(<?php echo $speakerIMG; ?>);">
	<div class="container">
		<div id="featured-intro">

				<h3><span class="redd">ACTION</span><br><?php the_field('bannerTitle'); ?></h3>
			<?php endwhile; // end of the loop. ?>
		</div>
	</div>
</div>
<div id="contentWrap" class="action">
	<div id="prev" title="Show previous"> </div>
	<div id="next" title="Show more Articles"> </div>

	<a id="prevbtn" title="Show previous">  </a>
	<a id="nextbtn" title="Show more">  </a>
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

				<a href="<?php echo site_url('/feed/?post_type=policy'); ?>" target="_blank">
					<div id="rssFeedIcon" class="policy">
						Subscribe
					</div>
				</a>

			</div>
			<div id="contentPrimary">
				<div class="graybar"></div>
				<div class="gutter">
					<span class="filterby">Filter By Topic >></span><div class="going-mobile" id="page-filters">Filter By Topic &raquo;</div>
					<?php
						$args = array(
						    'orderby'       => 'name',
						    'order'         => 'ASC',
						    'hide_empty'    => false,
						    'exclude'       => array(),
						    'exclude_tree'  => array(),
						    'include'       => array(),
						    'number'        => '',
						    'fields'        => 'all',
						    'slug'          => '',
						    'parent'         => '',
						    'hierarchical'  => true,
						    'child_of'      => 0,
						    'get'           => '',
						    'name__like'    => '',
						    'pad_counts'    => false,
						    'offset'        => '',
						    'search'        => '',
						    'cache_domain'  => 'core'
						);
						$terms = get_terms( 'policytopics', $args );
						if(count($terms) > 0){ ?>
							<ul id="pagefilter" data-query="policy">
							<?php foreach($terms as $term){ ?>
								<li data-filter="<?php echo $term->slug; ?>"><a><?php echo $term->name; ?></a></li>
							<?php } ?>
								<li class="last"><a>Show All Topics</a></li>
							</ul>
						<?php } ?>
				</div>
			</div>
			<div id="contentSecondary" class="nostamp">
				<div class="graybar"></div>
				<div class="gutter">
					<?php get_template_part( 'partial/template', 'actionloop' ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	get_footer();
?>