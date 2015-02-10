<?php
/* ----- Scripts for improving how the Dashboard works ----- */

//Stylesheet
function admin_styles() {
    wp_register_style( 'admin_stylesheet', get_template_directory_uri().'css/admin.css' );
    wp_enqueue_style( 'admin_stylesheet' );
}
add_action( 'admin_enqueue_scripts', 'admin_styles' );

?>