<?php 
/*
Template Name: Full Width
*/

get_header(); ?>

	<div id="content" class="clearfix" style="width:100%">
	
		<?php while ( have_posts() ) : the_post(); ?>
						
			<h1 class="page-title"><?php the_title(); ?></h1>

			<?php the_content(); ?>
			
			<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages:','themify').'</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
			
			<?php // edit_post_link(__('Edit','themify'), '[', ']'); ?>
			
			<?php // get comment template (comments.php) ?>
			<?php comments_template(); ?>
		
		<?php endwhile; ?>
		
	</div>
	<!-- /#content -->
		
<?php get_footer(); ?>