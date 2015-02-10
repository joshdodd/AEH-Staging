<?php

/*

Template Name: Member Network - Profile

*/

// this page displays another member's profile - not yours


include ("includes/aeh_config.php");
include ("includes/aeh-functions.php");
if (is_user_logged_in()){
get_header();
$currentUser = get_current_user_id();
$user_info = get_userdata($currentUser);
$user_avatar = get_avatar($currentUser);

if($_GET['member'] == get_current_user_id()){
	header('Location: '.site_url().'/membernetwork/my-profile/');
}

$memberID = 0; $member = "No Member Specified";
if (isset($_GET['member'])){
	if ($_GET['member']!=""){
		$get_var = $_GET['member'];
		if (is_int($get_var + 0)){ // check if variable is number or string
			$memberID = $get_var;
		}
	}
}
// at this stage, either memberID = valid member integer or memberID = 0
// in both cases member = the actual get variable
// now check the DB to see if that person exists if memberID is not 0
if ($memberID){
	if ($usermeta = get_user_meta($memberID)){
		$firstname  = $usermeta['first_name'][0];
		$lastname   = $usermeta['last_name'][0];
		$nickname   = $usermeta['nickname'][0];
		$description= $usermeta['description'][0];
		$user_email = $usermeta['user_email'][0];
		$membersince= $usermeta['user_registered'];
		$twitter    = $usermeta['twitter'][0];
		$linkedin   = $usermeta['linkedin'][0];
		$jobfunction= $usermeta['job_function'][0];
		$employer   = $usermeta['employer'][0];
		$title      = $usermeta['title'][0];
		$jobtitle   = $usermeta['job_title'][0];
		$facebook   = $usermeta['facebook'][0];
		$hospital_name   = $usermeta['hospital_name'][0];
		$visibility = $usermeta['aeh_visibility'][0];
		$aeh_staff  = $usermeta['aeh_staff'][0];
		$gravatar   = get_avatar($memberID, 184);
		$userdata	= get_userdata($memberID);
		$newsinterest = get_user_meta($memberID, 'custom_news_feed', true);
	}else{
		$memberID = 0; // if false from num rows then user doesn't exist in meta table so make memberID = 0
	}
}
// if memberID = 0 then no user was in the DB. If memberID > 0 then usermeta is an array of the profile metadata

?>


<script src="<?php bloginfo('template_directory'); ?>/js/connections.js"></script>
<div id="membernetwork">
	<div class="container">
		<h1 class="title"><span class="grey">Essential Hospitals</span> Member Network | <?php echo $firstname; ?> <?php echo $lastname; ?>'s Profile</h1>

<?php

	get_template_part('membernetwork/content','usernav');

		if ($hospitalmember){
			$output = "";
?>
<div class="group-details groupcol clearfix">
			<div id="myprofilecontent" class="group">
				<div class="gutter clearfix">

<?php
					the_post();
					the_content();
					if ($memberID){ // a memberID = 0 means an invalid GET variable
?>
<?php
						 
						if ($visibility == ""){ ?>

							<div id="my-profile-custom">
								<div id="profile-left">
									<div id="profile-avatar"><?php
										if($aeh_staff == 'Y'){
											echo '<div class="hospMem"></div>';
										} echo $gravatar; ?></div>
									<?php
										// now check this person's status compared to yours i.e. are they a 'connection', 'pending connection', or nothing at all.
										$sql = mysql_query("
											SELECT *
											FROM `wp_aeh_connections`
											WHERE (`user_ID`=$userID AND `friend_ID`=$memberID) OR (`user_ID`=$memberID AND `friend_ID`=$userID)
										");

										if (mysql_num_rows($sql)){ // if row(s) then you have friended this person or vice versa
											$result = mysql_fetch_array($sql);
											if ($result['consent_date']>0){
											$token = strrev(md5($result['consent_date'] - $result['request_date']));

											$output .=  "Connection since " . date('l jS \of F Y', $result['consent_date']) . " at " . date('h:i:s a', $result['request_date']);

											$output .= "

											<div id='removalbuttons'><button type='button' id='remove'>Remove Connection</button>

											<div id='removebuttons' style='display:none'>

												<h4>Are you sure you want to remove this connection?</h4>

												<button type='button' id='remove-yes' name='$token$memberID'>Yes Please</button>

												<button type='button' id='remove-no'>Cancel</button>

											</div>

											</div>";
											}else{
												if ($result['user_ID']==$userID){
													$outut .=  "You sent a friend request on " . date('l jS \of F Y', $result['request_date']) . " at " . date('h:i:s a', $result['request_date']);
												}else{
													$outut .=  "Your friend requested a connection on " . date('l jS \of F Y', $result['request_date']) . " at " . date('h:i:s a', $result['request_date']);
												}
											}
										}else{
											$output .=  "This person is not a Connection.";


											//Get pending/requested/my contacts
											$contacts = $wpdb->get_results("SELECT user_id, friend_id
																					FROM wp_aeh_connections
																					WHERE user_ID = $curID
																					AND consent_date > 0
																					OR friend_id = $curID
																					AND consent_date > 0");
											$myContacts = array();
											foreach($contacts as $contact){
												if($contact->user_id = $curID){
													array_push($myContacts, $contact->friend_id);
												}else{
													array_push($myContacts,$contact->user_id);
												}
											}
											$contacts = $wpdb->get_results("SELECT user_id, friend_id
																					FROM wp_aeh_connections
																					WHERE friend_ID = $curID
																					AND consent_date = 0");
											$pendingContacts = array();
											foreach($contacts as $contact){
												array_push($pendingContacts, $contact->user_id);
											}
											$curID = get_current_user_id();
													//echo $curID;
													//Get contacts for current user
													$contacts = $wpdb->get_results("SELECT user_id, friend_id
																					FROM wp_aeh_connections
																					WHERE user_ID = $curID
																					AND consent_date = 0");
											$requestedContacts = array();
											foreach($contacts as $contact){
												array_push($requestedContacts, $contact->friend_id);
											}
											if(in_array($user->ID, $myContacts)){
												$output .= "<div class='my-connection'>
													<button class='added-button'>$firstname is a Contact</button>
												</div>";
											}elseif(in_array($user->ID, $pendingContacts)){
												$output .= "<div class='pending-connection'>
													<button class='added-button'>$firstname has added you</button>
												</div>";
											}elseif(in_array($user->ID, $requestedContacts)){
												$output .= "<div class='requested-connection'>
													<button class='added-button'>You have requested $firstname</button>
												</div>";
											}else{
												$output .= "<div class='add-connection'>
													<button data-curid='$userID' data-uid='$memberID' title='add $firstname to your connections' class='add-button contact-add'>Add $firstname to Contacts</button>
												</div>";
											}
										}

										echo $output; // output all the optional profile info from the output string.
									?>
								</div>
								<div id="profile-right">
									<a id="sendmessage" href="<?php bloginfo('url'); ?>/membernetwork/messages/?pmaction=newmessage&username=<?php echo $userdata->user_login; ?>">Send Message</a>
									<h2 id="profile-name"><?php echo "$title $firstname $lastname"; ?></h2>
									<span class="profile-position"><?php echo $jobtitle; ?></span>
									<span class="profile-position"><?php echo $hospital_name; ?></span>
									<span class="profile-employer"><?php echo $employer; ?></span>

									<?php if ($twitter) { ?>
										<span class="profile-twitter"><span class="pre">twitter</span><a href="http://www.twitter.com/<?php echo $twitter; ?>">twitter</a></span>
									<?php } ?>
									<?php if ($facebook) { ?>
										<span class="profile-facebook"><span class="pre">facebook</span><a href="<?php echo $facebook; ?>">facebook</a></span>
									<?php } ?>
									<?php if ($linkedin) { ?>
										<span class="profile-linkedin"><span class="pre">linkedin</span><a href="<?php echo $linkedin; ?>">linkedin</a></span>
									<?php } ?>
									<div class="profile-interests">

										<?php
											if($newsinterest){ ?>
											<span class="pre">Interested in:</span>
											<?php
											foreach($newsinterest as $news){
											$result = $wpdb->get_row("SELECT `name` FROM `wp_terms` WHERE `slug` = '$news'");
										?>
											<div class="interest">
												<?php echo $result->name; ?>
											</div>
										<?php } }?>
									</div>
								</div>

							<?php if ($aeh_staff=="Y")$output .=  "<div id='profile-staff'>Staff Member</div>";

						}else{ ?>
							<div id="my-profile-custom">
								<div id="profile-left">
									<div id="profile-avatar"><?php
										if($aeh_staff == 'Y'){
											echo '<div class="hospMem"></div>';
										} echo $gravatar; ?></div>
								</div>
								<div id="profile-right">
									<a id="sendmessage" href="<?php bloginfo('url'); ?>/membernetwork/messages/?pmaction=newmessage&username=<?php echo $userdata->user_login; ?>">Send Message</a>
									<h2 id="profile-name"><?php echo "$title $firstname $lastname"; ?></h2>
									<p><em>This user has chosen to keep their information private</em></p>
								</div>
						<?php }



					}else{
						echo "Oops, sorry, you seem to have come to this page in error!";
					}
?>
				</div>
			</div>
</div>
</div>
<div class="group-members groupcol">
<h2 class='heading'>Connections</h2>
<?php
	$members = output_connections("",$memberID,'friends',8);
	if ($members!=""){ $blocks++; ?>

		<div class='gutter clearfix'>
			<div class="myfriends">

				<div class="pendingnotify"></div>

				<div class="membercontent">

					<?php echo $members; ?>

				</div>

			</div>

		</div>
	<?php } ?>
</div>




<div class="group-resources groupcol">
	<div class="panel">
			<h2 class="heading">Discussions</h2>
				<?php $comments = get_comments(array('user_id' => $memberID));
					$commentArray = array();
					foreach($comments as $comment){
						array_push($commentArray,$comment->comment_post_ID);
					}
					$commentArray = array_unique($commentArray);

					if(sizeof($commentArray) > 0){
						$commentArray = array_slice($commentArray,0,3);
						$query = new WP_Query( array( 'post_type' => 'discussion', 'post__in' => $commentArray ) );
						if ( $query->have_posts() ) {
							while ( $query->have_posts() ) {
								$query->the_post(); ?>
								<div class="grouplist">
									<div class="gutter">
										<span class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>
										<span class="desc"><?php the_excerpt(); ?></span>
									</div>
								</div>
						<?php } } else { ?>
							<p>You haven't joined any discussions.</p>
					<?php } wp_reset_postdata(); }else{
						echo "<p>You haven't joined any discussions</p>";
					} ?>
	</div>
	<div class="panel">
		<h2 class="heading">Private Groups</h2>
		<?php $groups = get_user_meta($memberID, 'groupMem', true);
			if($groups){
			foreach($groups as $group){
				$post = get_post($group);
				$title = $post->post_title;
				$desc = $post->post_excerpt;
				$link = get_permalink($group);
				$type = get_post_type($post->ID);
				if($type == 'group'){ ?>
				<div class="grouplist">
					<div class="gutter">
						<span class="title"><a href="<?php echo $link; ?>"><?php echo $title; ?></a></span>
						<span class="desc"><?php echo $desc; ?></span>
					</div>
				</div>
			<?php } } } ?>
	</div>
</div>

	</div>
<?php
		}else{
			echo "You are a public member so you cannot access this page.";
		}
	}else{
		header('Location: '.get_bloginfo('url').'/membernetwork/member-login/');
	}
?>
	</div>
</div>
<?php get_footer('sans'); ?>