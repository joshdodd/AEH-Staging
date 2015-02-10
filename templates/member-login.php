<?php /* Template Name: Member Network - Login */
include ("includes/aeh_config.php");
include ("includes/aeh-functions.php");
get_header(); ?>
<div id="membernetwork">
	<div class="container">
		<h1 class="title"><span class="grey">Essential Hospitals</span> Member Network | My Contacts</h1>
		<?php get_template_part('membernetwork/content','usernav'); ?>
		<div id="logincontent" class="group">
			<div class="gutter clearfix">
				<h2 class='heading'>Member Login</h1>
					<?php the_post();
						  the_content();
					?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var redir="<?php echo $_GET['redir']; ?>";
	var trigger=jQuery('#memberLogin > .gutter > p > a').first().text();
	if(trigger == "click here to logout" && redir == 'contact'){
		window.location = "http://essentialhospitals.org/membernetwork/connections/";
		window.navigate("http://essentialhospitals.org/membernetwork/connections/");
	}
</script>
<?php get_footer('sans'); ?>