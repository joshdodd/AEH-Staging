<?php 
/*
Template Name: Must Log In
*/
get_header();

?>
	<div id="content" class="clearfix">

		<?php while ( have_posts() ) : the_post(); ?>
						
			<h1 class="page-title"><?php the_title(); ?></h1>
			
	<?php if (is_user_logged_in()){ ?>

			<?php the_content(); ?>
			
			<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages:','themify').'</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
			
			<?php // edit_post_link(__('Edit','themify'), '[', ']'); ?>
			
			<?php // get comment template (comments.php) ?>
			<?php comments_template(); ?>
			
	<?php }else{echo "You have to log in to view this page!";} ?>
		
		<?php endwhile; ?>
		
	</div>
	<!-- /#content -->
		
<?php get_sidebar(); ?>

<?php get_footer(); ?>