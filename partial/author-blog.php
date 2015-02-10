 
<div id="featured-img" class="blog" >
	<div class="container">
		<div id="featured-intro">
			<h3>Essential Insights</h3>
			<h4><?php echo get_field('bannerTitle',562); ?></h4>
		</div>
	</div>
</div>
<?php $speakerIMG = wp_get_attachment_url( get_post_thumbnail_id(562) ); ?>
<div id="content" class="page-single blog" style="background-image:url(<?php if($speakerIMG){echo $speakerIMG;} ?>);">
	<div class="container">
		<div id="contentColumnWrap">
			<div id="contentPrimary" class="heightcol">
				<div class="gutter">
					<div id="author-archive-info">
						<div class="gutter">
							<div id="author-data">
								<div class="gutter">
								<?php $author = get_userdata( get_query_var('author') );?>
									<?php $authorAva = get_avatar($author->ID, 100 );
										$authorDesc = get_the_author_meta('description',$author->ID); ?>
									<div id="author-data-img">
										<?php echo $authorAva; ?>
									</div>
								</div>
							</div>
							<h2 id="blog-search"> All Posts by: <?php echo $author->display_name;?>
								<a href="?prof=article">View bio and articles written by <?php echo $author->display_name; ?></a></h2>
						</div>
					</div>
					<?php if ( have_posts() ) { while ( have_posts() ) { the_post();
						$authorID = get_the_author_id(); ?>
						<div class="blog-post">
							<div class="gutter">
								<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<div class="blog-postmeta"><span class="item-date"><?php the_time('M j, Y'); ?> || </span><span class="item-comments"><?php comments_number('(0) comments', '(1) comment', '(%) comments')?></span></div>

								<div class="blog-excerpt">
									<?php the_excerpt(); ?>
									<a class="readmore" href="<?php the_permalink(); ?>">view more &raquo;</a>
								</div>
								<div class="blog-tax">
									<span class="blog-cat">
									<?php $categories = get_the_category();
										if($categories){
										$catName = $categories[0]->name;
										$catLink = get_term_link($categories[0]->slug, 'category');
											if($catName != 'Blog'){
												echo "<a href='$catLink'>$catName</a>";
											}
										}
									?>
									</span>
									<span class="blog-tag"><?php the_tags('',','); ?></span>
								</div>
							</div>
						</div>
					<?php } } ?>
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
										$termLink = get_term_link($term->slug,'category');
										echo "<li><a href='".$termLink."'>".$term->name."</a></li>";
									}
									echo "</ul>";
								}
							?>
						</div>
					</div>

					<div class="blog-panel authors">
						<h4>Authors</h4>
						<div class="gutter clearfix">
							<?php $authors = get_all_authors(10);
							if($authors){
							foreach($authors as $author){
								$authName = $author['name'];
								$authID = $author['ID'];
								$authAva = get_avatar( $authID, 37 );
								$authDesc = $author['desc'];
								$authURL = get_author_posts_url($authID);
								$pos = strpos($authDesc, '.');
								$authDesc = substr($authDesc, 0, $pos+1);
								echo "<div class='blog-author'>
										<a href='$authURL'>
										<div class='blog-author-avatar'>
											$authAva
										</div>
										<div class='blog-author-details'>
											<span class='blog-author-name'>
												$authName
											</span>
										</div>
										</a>
									  </div>";
							} } ?>
						<span class="author-data-archive"><a href="<?php echo get_permalink(13808); ?>">View all authors</a></span>
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
								<!-- AddThis Button BEGIN -->
								<div class="addthis_toolbox addthis_32x32_style" style="">
								<a class="addthis_button_facebook"></a>
								<a class="addthis_button_twitter"></a>
								<a class="addthis_button_linkedin"></a>
								<a class="addthis_button_pinterest_share"></a>
								<a class="addthis_button_google_plusone_share"></a>
								<a class="addthis_button_email"></a>
								<a class="addthis_button_digg"></a>
								<a class="addthis_button_evernote"></a>
								<a class="addthis_button_compact"></a>
								</div>
								<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
								<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=naphsyscom"></script>
								<!-- AddThis Button END -->
							</div>
						</div>
					</div>
					<div class="blog-panel tweets">
						<h4>Twitter</h4>
						<div class="gutter clearfix">
							<div class="blog-twitter">
								<?php display_user_tweets('OurHospitals',3); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>