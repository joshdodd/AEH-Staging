<?php 
/*
Template Name: Forum
*/

get_header(); ?>

	<div id="forumcontent" class="clearfix" style="width:100%">
	
		<?php while ( have_posts() ) : the_post(); ?>
						
			<?php the_content(); ?>

		<?php endwhile; ?>
		
	</div>
	<!-- /#content -->
		
<?php get_footer(); ?>