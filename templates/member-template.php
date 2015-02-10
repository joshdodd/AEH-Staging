<?php 
/*
Template Name: Member Template Test
*/

get_header();

/* get user meta for current user*/
	$current_user= wp_get_current_user();
	$username    = $current_user->user_login;
	$useremail   = $current_user->user_email;
	$firstname   = $current_user->user_firstname;
	$lastname    = $current_user->user_lastname;
	$displayname = $current_user->display_name;
	$userID      = $current_user->ID;
	$metakey 	 = "custom_news_feed";
	$usermeta    = get_user_meta($userID, $metakey, TRUE);
	/* get the custom meta data containing the custom news feeds */
	if ($usermeta !=""){$customnewsfeed = unserialize($usermeta);}
?>

	<div id="contentM" class="clearfix">
	<h4 class="widgettitle">My Custom News Feed</h4>
	<?php 
	
	$cat = ""; 
	foreach($customnewsfeed as $category){if ($category){$cat .= ",$category";}else{$cat .= ",15";}}
	$cat = substr($cat,1); /* $cat now = comma separated list of categories to fetch data from */
	
	query_posts("posts_per_page=$n&orderby=cat&cat=$cat");
	$n = 0;
	if (have_posts()) : 
		while (have_posts()) : the_post(); 
			$catinfo = get_the_category();
			$catname = $catinfo[0]->cat_name;
			$cont = 'catcontainer2'; if ($n)$cont = 'catcontainer1';
?>
			<div class='<?php echo $cont; ?>'>
				<div class='catheader'><?php echo $catname; ?></div>
				<div class='cattitle'>
					<h5><a href='<?php echo get_permalink(); ?>'><?php echo get_the_title(); ?></a></h5>
					<img class='catimage' src='<?php if (has_post_thumbnail()){echo mdw_featured_img_url('cat-thumb');}else{echo "http://meshdevsite.com/aehph/wp-content/uploads/2013/06/placeholder300x200-120x80.jpg";} ?>' />
					<?php echo the_excerpt(); ?>
				</div>
				<div class='clear'></div>
			</div>
<?php
			$n++;
		endwhile;
	endif; 
	wp_reset_query(); 
?>

<ul>
<?php
echo $cat . "<br />";
global $post;

$args = array( 'posts_per_page' => 50, 'offset'=> 1, 'category__in' => (array($cat)) );

$myposts = get_posts( $args );

foreach( $myposts as $post ) : setup_postdata($post); ?>
	<li>
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </li>
<?php endforeach; ?>

</ul>

<?php
	
	/*
	//global $wpdb;
$myposts = $wpdb->get_results( 
	"
	SELECT ID, post_title 
	FROM wpph_posts
	WHERE post_status = 'publish'
	AND post_type = 'post'
	"
);
$n = 1;
foreach ($myposts as $test){
	echo "$n: " . $test->post_title . "<br />";
	$n++;
}
	*/
?>
	
		
	</div>
	<!-- /#content -->
<?php get_sidebar('A'); ?>
<?php get_sidebar('B'); ?>
	
<?php get_footer(); ?>