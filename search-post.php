
<div id="featured-img" class="blog" >
	<div class="container">
		<div id="featured-intro">
			<h3>Essential Insights</h3>
			 <h4><?php echo get_field('bannerTitle',562); ?></h4>
		</div>
	</div>
</div>
<?php $speakerIMG = wp_get_attachment_url( get_post_thumbnail_id(562) ); ?>
<div id="content" class="page-single blog" style="background-image:url(<?php if($speakerIMG){echo $speakerIMG; } ?>);">
	<div class="container">
		<div id="contentColumnWrap">
			<div id="contentPrimary" class="heightcol">
				<div class="gutter">
				<h2 id="blog-search"><?php printf( __( 'Search Results for: %s' ), '<span>' . get_search_query() . '</span>' ); ?></h2>
				<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<div class="blog-post">
						<div class="gutter">
							<h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
							<p class="postinfo">By <?php the_author(); ?> | <?php comments_popup_link(); ?></p>
							<?php the_excerpt(); ?>
						</div>
					</div>

				<?php endwhile; ?>

				<?php else : ?>
					<div class="blog-post">
						<div class="gutter">
							<h1>No Posts Found</h1>
							<p>Nothing matched your search criteria. Please try again with some different keywords.</p>
							<?php get_template_part( 'searchform', 'blog' ); ?>
						</div>
					</div>
				<?php endif; ?>
				</div>
			</div>
			<div id="contentSecondary" class="heightcol">
				<div class="gutter">

					<div class="blog-panel">
						<h4>Categories</h4>
						<div class="gutter">
							<?php $args = array(
								    'orderby'       => 'name',
								    'order'         => 'ASC',
								    'hide_empty'    => true,
								    'exclude'       => array(227),
								    'exclude_tree'  => array(94,1,102),
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
								$terms = get_terms('category',$args);
								if($terms){
									echo "<ul>";
									foreach($terms as $term){
										echo "<li><a href='".$term->guid."'>".$term->name."</a></li>";
									}
									echo "</ul>";
								}
							?>
						</div>
					</div>

					<div class="blog-panel authors">
						<h4>Authors</h4>
						<div class="gutter">
							<?php $authors = get_all_authors();
							foreach($authors as $author){
								$authName = $author['name'];
								$authID = $author['ID'];
								$authAva = get_avatar( $authID, 37 );
								$authDesc = $author['desc'];
								$pos = strpos($authDesc, '.');
								$authDesc = substr($authDesc, 0, $pos+1);
								echo "<div class='blog-author'>
										<div class='blog-author-avatar'>
											$authAva
										</div>
										<div class='blog-author-details'>
											<span class='blog-author-name'>
												$authName
											</span>
											<span class='blog-author-bio'>
												$authDesc
											</span>
										</div>
									  </div>";
							} ?>
						</div>
					</div>

				</div>
			</div>
			<div id="contentTertiary" class="heightcol">
				<div class="gutter">
					<div class="blog-btn rss">
						<a href="<?php bloginfo('rss2_url'); ?>">Subscribe Now</a>
					</div>
					<div class="blog-search">
						<?php get_template_part( 'searchform', 'blog' ); ?>
					</div>
					<div class="blog-panel">
						<h4>Share</h4>
						<div class="gutter">
							<div class="blog-social">
								<div>
								<span class='st_facebook_large left' displayText='Facebook'></span>
								<span class='st_twitter_large center' displayText='Tweet'></span>
								<span class='st_googleplus_large right' displayText='Google +'></span>
								</div>
								<div>
								<span class='st_linkedin_large left' displayText='LinkedIn'></span>
								<span class='st_email_large center' displayText='Email'></span>
								<span class='st_sharethis_large right' displayText='Share This'></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>