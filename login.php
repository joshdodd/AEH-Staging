<?php 
/*
Template Name: Login
*/

if (is_user_logged_in()){header('Location: '.get_bloginfo('url').'/login/');}

get_header(); ?>

	<div id="content" class="clearfix">
	
		<?php while ( have_posts() ) : the_post(); ?>
						
			<h1 class="page-title"><?php echo "Login"; ?></h1>

			<?php the_content(); ?>
			
			<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages:','themify').'</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
			
			<?php // edit_post_link(__('Edit','themify'), '[', ']'); ?>
			
			<?php // get comment template (comments.php) ?>
			<?php comments_template(); ?>
		
		<?php endwhile; ?>
		
	</div>
	<!-- /#content -->
		
<?php get_sidebar(); ?>
	
<?php get_footer(); ?>