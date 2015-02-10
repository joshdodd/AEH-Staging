<?php get_header(); ?>

<?php
	  //RANDOM GENERAL PAGE BANNERS
	  $rand = rand(1,9);
      $speakerIMG = "http://mlinson.staging.wpengine.com/wp-content/uploads/2013/11/AEH_generalbanner" .$rand . "_222.jpg"; ?>

<div id="featured-img" class="archive tag" style="background-image:url(<?php echo $speakerIMG; ?>);">
	<div class="container">
		<div id="featured-intro">
			<h3 class="tag"><?php single_tag_title(); ?></h3>
		</div>
	</div>
</div>
<div id="postFeatured">
	<div class="container fullborder">
 		<div class="eightteen columns filters">
 			<span> FILTER WITHIN THIS TAG &rsaquo;&rsaquo;</span>
 			<?php $curArch = get_term_by('name', single_tag_title('',FALSE) , 'post_tag'); ?>
 			<div id="red_btn" data-archive="<?php echo $curArch->slug; ?>" data-filter="policy" class="filter_btn ">
 				<img src="<?php bloginfo('template_directory'); ?>/images/policy.png"> <span>Action</span>
 			</div>
 			<div id="green_btn" data-archive="<?php echo $curArch->slug; ?>" data-filter="quality" class="filter_btn ">
 				<img src="<?php bloginfo('template_directory'); ?>/images/quality.png"> <span>Quality</span>
 			</div>
 			<div id="gray_btn" data-archive="<?php echo $curArch->slug; ?>" data-filter="education" class="filter_btn ">
 				<img src="<?php bloginfo('template_directory'); ?>/images/edu.png"> <span>Education</span>
 			</div>
 			<div id="blue_btn" data-archive="<?php echo $curArch->slug; ?>" data-filter="institute" class="filter_btn ">
 				<img src="<?php bloginfo('template_directory'); ?>/images/inst.png"> <span>Institute</span>
 			</div>
 			<div id="all" data-archive="<?php echo $curArch->slug; ?>" data-filter="any" class="filter_btn all"><span>Reset</a></div>

 			<a href="<?php echo site_url('/feed/'); ?>?tag=<?php $term_id = get_query_var('tag_id');
																 $taxonomy = 'post_tag';
																 $args ='include=' . $term_id;
																 $terms = get_terms( $taxonomy, $args ); echo $terms[0]->slug; ?>" target="_blank">
				<div id="rssFeedIcon" class="tag">
					Subscribe
				</div>
			</a>

 		</div>
 	</div>
    <div class ="grayblock"></div>

	<div class="container twelve columns content">


		<div id="contentWrap" class="action">
			<div class="gutter">
				<div class="container">
					<div id="contentPrimary">
						<div class="graybar"></div>
						<div class="gutter">
							<?php get_template_part( 'partial/template', 'tagloop' ); ?>
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