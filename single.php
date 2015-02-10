<?php get_header();
	global $post;
	$postSt = $post->post_status;
	$current_user = wp_get_current_user();
	$cUID = $current_user->ID;
	$cUStaff = get_user_meta($cUID,'aeh_member_type',true);
	if($postSt == 'private'){
		if($cUStaff == 'hospital'){
			get_template_part('partial/single','loggedin');
		}else{
			get_template_part('partial/single','loggedout');
		}
	}else{
		get_template_part('partial/single','public');
	} ?>


<?php get_footer(); ?>