<?php get_header(); ?>


<div id="content">
	<?php $searchQuery = $_GET['post_type'];
		if(!$searchQuery){
			get_template_part('search','general');
		}else{
			get_template_part('search',$searchQuery);
		}
	?>

</div><!-- End of Content -->
<?php get_footer('sans'); ?>