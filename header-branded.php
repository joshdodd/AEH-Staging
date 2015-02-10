<!DOCTYPE html>

<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->

<head>
	<meta charset="utf-8">
	<title><?php wp_title( '|', true, 'right' );  ?>   America's Essential Hospitals</title>

	<link rel="shortcut icon" type="image/x-icon" href="<?php bloginfo('template_directory'); ?>/favicon.ico">

	<!-- Meta / og: tags -->
	<?php if (have_posts()):while(have_posts()):the_post(); endwhile; endif;?>

	<!-- if page is content page -->
	<?php if (is_single()) { ?>
	<meta property="og:url" content="<?php the_permalink() ?>"/>
	<meta property="og:title" content="<?php single_post_title(''); ?>" />
	<meta property="og:description" content="<?php echo strip_tags(get_the_excerpt($post->ID)); ?>" />
	<meta property="og:type" content="article" />
	<meta property="og:image" content="<?php bloginfo('template_url' );?>/images/logo.png" />

	<!-- if page is others -->
	<?php } else { ?>
	<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
	<meta property="og:description" content="<?php bloginfo('description'); ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="<?php bloginfo('template_url' );?>/images/logo.png" /> <?php } ?>


	<script type='text/javascript'>
		var templateDir = "<?php bloginfo('template_directory') ?>";
		var siteDir = "<?php bloginfo('url') ?>";
	</script>

	<!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0">


	<!-- FONTS
	================================================== -->
	  <link href='http://fonts.googleapis.com/css?family=Cabin:400,600,700' rel='stylesheet' type='text/css'>

	<!-- CSS (* with Edge Inspect Fix)
    ================================================== -->
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_uri(); ?>" />

	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="<?php bloginfo('url' );?>/favicon.ico">
	<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">

	<script type="text/javascript">var switchTo5x=true;</script>
	<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
	<script type="text/javascript">stLight.options({publisher: "457e6d5d-5f44-4df8-9c1d-ec560a221bb7", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
	<?php wp_head(); ?>

	<script src="<?php bloginfo('template_directory'); ?>/js/branded.js"></script>
	<!--[if lt IE 9]>
		<script src="<?php bloginfo('template_directory'); ?>/js/html5shiv.js"></script>
	<![endif]-->

</head>
<body <?php body_class();?>>
<?php get_template_part( 'membernetwork/module', 'memberdash' ); ?>
<div id="siteWrap" class="clearfix">
<?php $currentUser = get_current_user_id(); ?>

	<!-- Utilities Navigataion -->
	<div id="utility-nav" class="fullwidth">
		<div class="container">
			 <div class="sixteen columns utils">
			 	<div id="TopMenu">
				 	<?php
			 		if(has_nav_menu('utility-menu')){
				 		wp_nav_menu( array( 'menu_id' => 'utils-nav', 'theme_location' => 'utility-menu', 'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>'));
			 		} ?>
			 	</div>

			 	<div id="searchlogin">
			 		<div id="search" class="search">
			 			<div id="searchclose">x</div>
			 			<img src="<?php bloginfo('template_directory'); ?>/images/search.png" />
			 			<?php get_template_part( 'membernetwork/module', 'searchform' ); ?>
			 		</div>

			 		<?php if(is_user_logged_in()){ ?>
				 		<div id="login" class="login"><a id="memNetwork" href="#">My Dashboard</a></div>
				 	<?php }else{ ?>
						<div id="login" class="login"><a href="<?php bloginfo('url');?>/membernetwork/registration"><img src="<?php bloginfo('template_directory'); ?>/images/signup.png" /></a><img id="loginButton" src="<?php bloginfo('template_directory'); ?>/images/login.png" /></div>
				 	<?php } ?>
				 </div>
			 </div>
		</div>
	</div>
	<?php
		if(!is_user_logged_in()){
			get_template_part( 'membernetwork/module', 'loginreg' );
		}
	?>
	<!-- END Utilities Navigataion -->
	<!-- Logo/Main Navigataion -->
	<div id="header" class="fullwidth">
		<div class="container">
			 <div class="logo">
			 	<?php
			 	while ( have_posts() ) : the_post();
			 	$pageTheme = get_field('theme');
			 	endwhile;
			 	if(is_page_template('templates/template-institute.php') || is_post_type_archive('institute') || is_tax('centers') || is_singular('institute') || $pageTheme == 'institute'){ ?>
			 		<a href="<?php bloginfo( 'url' );?>/institute" title="<?php bloginfo( 'name' );?>"><img src="<?php bloginfo('template_url' );?>/images/instituteLogo.png" title="<?php bloginfo( 'name' );?>"></a>
				<?php }else{ ?>
					<a href="<?php bloginfo( 'url' );?>" title="<?php bloginfo( 'name' );?>"><img src="<?php bloginfo('template_url' );?>/images/logo.png" title="<?php bloginfo( 'name' );?>"></a>
				<?php } ?>
			</div>
			<div class="twelve columns navigation">
				<?php $walker = new Menu_With_Description;
				$defaults = array(
					'theme_location'  => 'primary-menu',
					'menu'            => 'primary-menu',
					'container'       => '',
					'container_class' => '',
					'container_id'    => '',
					'menu_class'      => '',
					'menu_id'         => 'menu-primary-menu',
					'echo'            => true,
					'fallback_cb'     => 'wp_page_menu',
					'before'          => '',
					'after'           => '',
					'link_before'     => '',
					'link_after'      => '',
					'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'depth'           => 1,
					'walker'          => $walker
				);
				wp_nav_menu( $defaults ); ?>
			</div>
      	    <div class="clear"></div>
		</div><!-- End of Container -->
		<div class="clear"></div>
	</div>
	<!-- Logo/Main Navigataion -->








