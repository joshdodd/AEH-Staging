<?php /*
 * Template Name: Policy Template
 */
?>
 
<?php get_header(); ?>

<?php 
$args = array( 'post_type' => 'policy', 'posts_per_page' => 10 );
$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post();?>

<h2><?php the_title(); ?></h2>
<?php 
	the_content();
	the_category();
	the_tags( $before = null, $sep = ', ', $after = '' );
 
endwhile;

//wp_list_categories_for_post_type('policy');
$args = wp_list_categories_for_post_type('policy');
$categories = get_categories( $args );
foreach ( $categories as $category ) {
	echo '<a href="' . get_category_link( $category->term_id ) . '">...' . $category->name . '</a><br/>';
}
?>

?>

<?php get_footer(); ?>