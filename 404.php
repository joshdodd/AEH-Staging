<?php /*
 * 404 Page Template
 */
?>

<?php get_header(); ?>

<div id="featured-img" class="page-single">
	<div class="container">
		<div id="featured-intro">
			<h3>404 - Page Not Found</h3>
		</div>
	</div>
</div>
<div id="content" class="page-single default">
	<div class="container">
		<div id="contentColumnWrap">
			<div class="graybarright"></div>
			<div class="graybarleft"></div>
			<div id="contentPrimary" class="heightcol">
				<div class="gutter">
					<h1>Oops!</h1>
					<p>The page you're looking for wasn't found! Check your URL and try again, or you could try searching for what you were looking for below.</p>
					<?php get_search_form(); ?>
				</div>
			</div>
		</div>
	</div>
</div><!-- End of Content -->
<?php get_footer(); ?>