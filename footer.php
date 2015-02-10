<div id="orginfo">
<div id="base" class="container" >
	<div class="graybarleft"></div>
	<div class="graybarright"></div>
	<div class="gutter">
	    <div class="eight columns about" id="col1">
	        <?php dynamic_sidebar('left-footer'); ?>
	    </div>

	    <div class="four columns" id="col2">
	        <div class="section">
	        <?php dynamic_sidebar('center-footer'); ?>
	        </div>

	    </div>

	    <div class="four columns sidebar" id="col3">
	        <div class="sidesection top">
	          <?php dynamic_sidebar('righttop-footer'); ?>
	        </div>

	        <div class="sidesection bottom">
	          <?php dynamic_sidebar('rightbottom-footer'); ?>
	        </div>
	    </div>

	    <div class="clear"></div>
	  </div>
  </div>
</div>








<div id="footer" class="fullwidth g4">
	<div class="container">
		<div id="footer_address" class="four columns element">
			<div class="footer-address">
				<?php dynamic_sidebar('ffooter-contact'); ?>
			</div>
			<div class="newsletter">
				 <?php dynamic_sidebar('Constant Contact'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<div id="footer_contact" class="four columns element"><div class="gutter">
			<?php dynamic_sidebar('ffooter-departments'); ?>
		</div></div>


		<div class="eight columns element" id="footer_columns"><div class="gutter">
			<?php dynamic_sidebar('ffooter-sections'); ?>


		</div>
		<div id="footer-brandSocial">
			<div id="footer_logo">
				<a href="<?php echo site_url(); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/footer_logo.png" /></a>
			</div>
			<?php
			$defaults = array(
				'theme_location'  => 'social-nav',
				'menu'            => 'social-nav',
				'container'       => 'div',
				'container_class' => '',
				'container_id'    => 'social',
				'menu_class'      => '',
				'menu_id'         => '',
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

		</div>
		<div class="clear"></div>
		<!-- END CONTAINER -->
	</div>
	<div class="clear"></div>

	<!-- END FOOTER -->
</div>
	</div>



	<?php wp_footer(); ?>

	<script src="<?php bloginfo('template_url'); ?>/js/jquery-ui-1.8.23.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_url'); ?>/js/jquery.mousewheel.min.js"></script>
	<script src="<?php bloginfo('template_url'); ?>/js/smoothdivscroll.js"></script>

	<script src="<?php bloginfo('template_url'); ?>/js/scripts-josh.js"></script>

	​<script type="text/javascript">
		var addthis_config = addthis_config||{};
		addthis_config.data_track_addressbar = false;
		addthis_config.data_track_clickback = false;
	</script>

	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-47673413-1', 'essentialhospitals.org');
  ga('send', 'pageview');

</script>



</body>
</html>
