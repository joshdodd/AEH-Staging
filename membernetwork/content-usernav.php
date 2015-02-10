<?php
	$currentUser = get_current_user_id();
	$aeh_member = get_user_meta($currentUser,'aeh_member_type',true);
	$memberaccess = get_user_meta($currentUser,'MN_MemberAccess',true);
?>
<nav class="fullwidth membernav <?php if($aeh_member == 'hospital' || $memberaccess == true){echo 'hospital';} ?>">
	<div class="gutter clearfix">
		<?php
		if ( has_nav_menu( 'member-network' ) ) { ?>
		     	<?php
					$defaults = array(
						'theme_location'  => 'member-network',
						'menu'            => 'member-network',
						'container'       => '',
						'container_class' => '',
						'container_id'    => '',
						'menu_class'      => 'menu',
						'menu_id'         => 'membernav',
						'echo'            => true,
						'fallback_cb'     => 'wp_page_menu',
						'before'          => '',
						'after'           => '',
						'link_before'     => '',
						'link_after'      => '',
						'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						'depth'           => 0,
						'walker'          => ''
					);

				wp_nav_menu( $defaults ); ?>
		<?php } ?>

	</div>
</nav>

<?php
$args = array(
	'post_type' => 'alert',
	'posts_per_page' => 1,
	'category_name' => 'member-network',
);
$query = new WP_Query( $args );
if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post();
$excerpt = get_the_excerpt(); ?>
	<div id="alertCont">
		<div class="container">
			<div class="gutter">
				<?php echo $excerpt; ?>
			</div>
		</div>
	</div>
<?php }	} wp_reset_query(); ?>
