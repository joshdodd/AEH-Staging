<?php
	// Our include  
	define('WP_USE_THEMES', false);
	require_once('../../../../wp-load.php');
	$currentUser = $_POST['currentUser'];
	add_user_meta( $currentUser, 'firstrun', 'Y', true );
?>