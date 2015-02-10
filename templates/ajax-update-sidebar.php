<?php
/*
Template Name: Member Network - AJAX Update Sidebar
*/
// This is the sidebar for members who are logged in.

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') { // check if valid AJAX request

	include ("includes/aeh_config.php");
	include ("includes/aeh-functions.php"); // me = $userID from functions file

		// output those who friended you if they exist
		$members = output_connections("",$userID,'friended',8);
			if ($members!=""){
?>
				<div class='gutter clearfix'>
					<h2 class='heading'>Requested Contacts</h2>
					<div class="myfriends">
						<div class="friendednotify">
							<p>Click to confirm contact request</p>
						</div>
						<div class="membercontent">
							<?php echo $members; ?>
						</div>
					</div>
				</div>
				<div class="clear10"></div>
			<?php } ?>

<?php	// output pending friends if any
		$members = output_connections("",$userID,'pending',8);
			if ($members!=""){
?>
				<div class='gutter clearfix'>
					<h2 class='heading'>Pending Contacts</h2>
					<div class="myfriends">
						<div class="pendingnotify"></div>
						<div class="membercontent">
							<?php echo $members; ?>
						</div>
					</div>
				</div>
				<div class="clear10"></div>
			<?php } ?>

<?php	// output your friends if any
		$members = output_connections("",$userID,'friends',8);

?>
				<div class='gutter clearfix'>
					<h2 class='heading'>My Contacts</h2>
					<div class="myfriends">
						<div class="pendingnotify"></div>
						<div class="membercontent">
<?php 					if ($members!=""){
							echo $members;
						}else{
							echo "<p>Browse and add people to your network of contacts</p>";
						}
 ?>
						</div>
					</div>
				</div>
				<div class="clear10"></div>
<?php

}
?>
