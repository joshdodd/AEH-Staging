<?php get_header();
	if(isset($_GET['prof']) && $_GET['prof'] == 'article'){
		get_template_part( 'partial/author', 'article' );
	}else{
		get_template_part( 'partial/author', 'blog' );
	}
get_footer('sans'); ?>