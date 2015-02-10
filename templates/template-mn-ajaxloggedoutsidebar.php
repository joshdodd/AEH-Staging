<?php 
/*
Template Name: Member Network - AJAX Logged-out Sidebar
*/
// This is the sidebar for members who are logged out.

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') { // check if valid AJAX request

	include ("includes/aeh_config.php"); 
	include ("includes/aeh-functions.php"); // me = $userID from functions file
	?>

			<div class='gutter clearfix'>
				<h2 class='heading'>Logged Out!</h2>
				<div class="myfriends">
					<div class="membernotify"></div>
					<div class="membercontent">
						<p>Logged Out Content Would go Here</p>
					</div>
				</div>
			</div>
		<div class="clear10"></div>
<?php } ?>