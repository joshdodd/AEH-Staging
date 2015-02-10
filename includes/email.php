<?php

// ----- Email alerts (groups/webinars/messages located in their plugins)
	//-New comment alert
	add_action( 'wp_insert_comment', 'comment_message', 10, 1 );
	function comment_message($comment_id, $comment_object){
		$comment = get_comment($comment_id);
		$commentDate = strtotime($comment->comment_date);
		$commentDate = date("M jS", $commentDate);
		$commentContent = $comment->comment_content;
		$commentID = $comment->comment_ID;
		$commentAuthor = $comment->comment_author;

		$post = get_post($comment->comment_post_ID);
		$postTitle = $post->post_title;
		if($post->post_type != 'post'){
			$terms = wp_get_post_terms($post->ID, 'series');
			if($terms){
				$postType = $terms[0]->name;
			}
		}else{
			$postType = 'Blog';
		}
		$postURL = get_permalink($post->ID);

		$author = get_user_by('email', $comment->comment_author_email);
		$authorID = $author->ID;
		$authorStaff = get_user_meta($authorID, 'aeh_staff', true);
		$authorEmail = $author->user_email;

		$adminURL = 'http://essentialhospitals.org/wp-admin';


		//- Notification of user comments to Comms team/site admins (Maya and Carl)
		$adArr = array(get_option('admin_email'),'cgraziano@essentialhospitals.org');

		$subject = "Please address: new comment(s) on $postTitle";
		$message = "A user has commented on the post $postTitle, published $commentDate<br><br>
					$commentContent<br><br>
					<a href='$adminURL/comment.php?action=editcomment&c=$commentID'>Approve or delete this comment</a>
					<br><br>
					<a href='$adminURL/edit-comments.php'>View all recent comments</a>
					<br><br>
					The above comment may be hidden until you or another web admin approves it. If this user previously posted comments that were approved, this comment will have been automatically approved and is already visible. Review this comment regardless, as action may be necessary. ";
		$headers = "From: $postType <info@essentialhospitals.org>";

		wp_mail($adArr, $subject, $message, $headers);
		//- End Admin notification


		//-Community discussion post/article comment reply
		if($comment->comment_parent>0){
			$comParent = get_comment($comment->comment_parent);
				$comParentEmail = $comParent->comment_author_email;

			$subject = "$commentAuthor commented on $postTitle";
			$message = "Another Member Network user has just responded to a comment you posted on $postTitle<br><br>
						$commentAuthor: $commentContent<br><br>
						<a href='$postURL/?replytocom=$commentID#respondRevisit the discussion and post an additional response.</a><br><br>
						Thank you for being part of the conversation!";
			$headers = "From: America's Essential Hospitals <info@essentialhospitals.org>";

			wp_mail($comParentEmail, $subject, $message, $headers);

		//-end comment reply notification
		}else{
			//- Notification of user comments to staff member (author)
			if($authorStaff == 'Y'){

				$subject = "New comment(s) on your article $postTitle";
				$message = "There is a new user comment on $postTitle, published $commentDate<br><br>
							$commentContent<br><br>
							<a href='$postURL/?replytocom=$commentID#respond'>Respond to this comment</a><br><br>
							You may wish to answer questions, thank commenters for their thoughts, and judiciously respond to criticism. If you’re unsure how to respond to a comment, read the suggestions below. If you’re still unsure, consult a member of the communications team.<br><br>
							<strong>Guidance on user comments:</strong><br><br>
							<em>Comments and Discussions</em><br><br>
							By default, when a user leaves a comment on our website for the first time, the content will not appear until we approve it within the content management system. Comments from users who have previously left a comment will appear immediately. As a general practice, authors will respond to comments on their own content. If you are unsure how to respond to a particular comment, ask the communications team.<br><br>
							In general, active, ongoing discussions in comment sections or the Member Network do not require input from staff. However, staff input will be valuable in certain instances, such as providing a link to pertinent resources or answering questions related to the association itself.<br><br>
							<em>Comment Controversies</em><br><br>
							Do not delete (or decide not to approve) comments simply because they offer an opposing or critical view. It’s better to respond to fair criticism directly. However, some comments on the website or social media channels can be malicious, offensive, or overly negative. It is an accepted risk of maintaining a transparent, open, and active online community and can be handled easily. If such a case arises, the author and communications team should collaborate to plan a course of action.";
				$headers = "From: $postType <info@essentialhospitals.org>";

				wp_mail($authorEmail, $subject, $message, $headers);
			//- End staff author notification
			}else{
			//- Notification of new comments to nonstaff blog author
				$subject = "New comment(s) on your article $postTitle";
				$message = "There is a new user comment on $postTitle, published $commentDate<br><br>
							$commentContent<br><br>
							<a href='$postURL/?replytocom=$commentID#respond'>Respond to this comment</a><br><br>
							You may wish to answer questions, thank commenters for their thoughts, and judiciously respond to criticism. If you’re unsure how to respond to a comment, consult the communications team at America’s Essential Hospitals at <a href='mailto:help@essentialhospitals.org'>help@essentialhospitals.org</a>.";
				$headers = "From: $postType <info@essentialhospitals.org>";

				wp_mail($authorEmail, $subject, $message, $headers);
			//- End non-staff author notification
			}
		}
	}


	//-New User Registration
	// ----- see wp-registration-by-email plugin


	//-Reset Password
	add_filter( 'wpmem_login_form', 'my_resetpwd_inputs' );
	function my_resetpwd_inputs( $form ) {
		global $wpmem_a;
		if( $wpmem_a == 'pwdreset' ) {
			$username_field = '<label for="username">Username</label><div class="div_text"><input name="user" type="text" id="user" value="" class="username" /></div>';
			$old = array( "\n", "\r", "\t", $username_field );
			$new = array( '', '', '', '' );
			$form = str_replace( $old, $new, $form );
		}
		return $form;
	}
	add_filter( 'wpmem_pwdreset_args', 'my_pwd_reset_args' );
	function my_pwd_reset_args( $args ) {
		if( isset( $_POST['email'] ) ) {
			$user = get_user_by( 'email', trim( $_POST['email'] ) );
		} else {
			$user = false;
		}
		if( $user ) {
			return array('user'=>$user->user_login,'email'=>$_POST['email']);
		} else {
			return array( 'user' => '', 'email' => '' );
		}
		return $args;
	}


	//Email created through Admin panel
	if ( !function_exists('wp_new_user_notification') ) {
		function wp_new_user_notification( $user_id, $plaintext_pass) {

			$user = new WP_User( $user_id );

			$user_login = stripslashes( $user->user_login );
			$user_email = stripslashes( $user->user_email );

			if(empty($plaintext_pass)){return;}

			$message  = __('Hi there,') . "\r\n\r\n";
			$message .= sprintf( __("Welcome to %s! Here's how to log in:"), get_option('blogname')) . "\r\n\r\n";
			$message .= wp_login_url() . "\r\n";
			$message .= sprintf( __('Username: %s'), $user_login ) . "\r\n";
			$message .= sprintf( __('Password: %s'), $plaintext_pass ) . "\r\n\r\n";
			$message .= sprintf( __('If you have any problems, please contact me at %s.'), get_option('admin_email') ) . "\r\n\r\n";
			$message .= __('Adios!');

			wp_mail(
				$user_email,
				sprintf( __('Hello there'), get_option('blogname') ),
				$message
			);
		}
	}


	//-User request to create private group (email to admin only)
	function create_group($post_id){
		$post = get_post($post_id);
		$postType = $post->post_type;
		$postTitle = $post->post_title;
		$postAuthor = get_userdata($post->post_author);
			$paFname = $postAuthor->first_name;
			$paLname = $postAuthor->last_name;
		$postDescription = $post->post_content;
		$members = get_post_meta($post_id, 'autp', true);
		foreach($members as $user){
			$id = $user['user_id'];
			$user = get_userdata($id);
			$fname = $user->first_name;
			$lname = $user->last_name;
			$membership .= "<li>$fname $lname</li>";
		}

		$admins = get_users('role=administrator');
		$adArr = array();
		foreach($admins as $user){
			array_push($adArr, $user->user_email);
		}

		$adminURL = get_edit_post_link($post_id);

		$subject = "Please review a new private group request - $postTitle";
		$message = "Group Name: <em>$postTitle</em><br>
					Requestor Name: <em>$paFname $paLname</em><br>
					Topic/Description: <em>$postDescription</em><br>
					Requested Members: <ul>$membership</ul><br><br>
					<a href='$adminURL'>Approve or decline this request</a>.";
		$headers = "From: Member Network <info@essentialhospitals.org>";

		wp_mail($adArr, $subject, $message, $headers);
	}

	//- User notified of private group creation
	add_action('pending_to_publish','publish_group',99);
	function publish_group($post){
		$postType = $post->post_type;

		if($postType == 'group'){
			$postTitle = $post->post_title;
			$postLink = get_permalink($post->ID);
			$members = get_post_meta($post->ID,'autp',true);
			$mod = get_post_meta($post->ID,'mod',true);
			$frontend = get_post_meta($post->ID,'frontend',true);

			$moderator = get_userdata($mod);
			$modfname = $moderator->first_name;
			$modlname = $moderator->last_name;
			$modmail = $moderator->user_email;

			$author = get_userdata($post->post_author);
				$authorEmail = $author->user_email;

			$emailArr = array();
			foreach($members as $member){
				$user = get_userdata($member['user_id']);
				$fname = $user->first_name;
				$lname = $user->last_name;
				$names .= '<li>'.$fname." ".$lname.'</li>';
				array_push($emailArr, $user->user_email);
			}

			$adminEmail = get_bloginfo('admin_email');

			// only send if this was created using the frontend form, omit dashboard created groups
			if($frontend){
				$subject = "Access to $postTitle, the private group you requested";
				$message = "We approved your request to initiate a private group on the Member Network. Thank you for your initiative! The group members you requested will receive invitations to participate. <a href='$postLink'>Visit your private group page</a> to kick off the conversation. We recommend you announce your intentions and goals for the group and share why you have invited your participants.<br><br>
				A staff member from America’s Essential Hospitals has also been added to your group as a moderator. Only you, the moderator, and those Member Network users you invited (listed below) can see or access this group area. Should the group want to invite additional people, please contact your staff moderator: $modfname $modlname at $modmail.<br><br>

				<ul>$names</ul>

				Again, thank you for engaging your fellow members, and good luck reaching your goals. As the group leader, you can guide the conversation how you choose, but we are happy to help if you have questions. When the group is no longer necessary, please contact your staff moderator or <a href='mailto:$adminEmail'>general web admin</a> to close it.";
				$headers = "From: America's Essential Hospitals <info@essentialhospitals.org>";

				wp_mail($authorEmail, $subject, $message, $headers);
			}
		}
	}

	//-User added to webinar or group
	//------ Check add-users-to-posttype plugin


	//-Initiator notified on community discussion creation
	add_action('pending_to_publish','publish_discussion',99);
	function publish_discussion($post){
		$postType = $post->post_type;
		if($postType == 'discussion'){
			$postTitle = $post->post_title;
			$postLink = get_permalink($post->ID);
			$frontend = get_post_meta($post->ID,'frontend',true);

			$author = get_userdata($post->post_author);
				$authorEmail = $author->user_email;

			if($frontend){
				$subject = "We have initiated the community discussion you requested: $postTitle";
				$message = "Thank you for starting a new conversation on America’s Essential Hospitals’ Member Network! We believe the topic you requested is unique and the discussion will benefit your fellow members.<br><br>
				<a href='$postLink'>Visit the discussion page to start posting.</a><br><br>
				<strong>Some tips to start off the conversation:</strong><br><br>
				<ul>
					<li>Explain why you’re starting this discussion. Does it relate to your personal experiences? Help people identify with the issue.</li>
					<li>Try to keep your initial post short to give others a chance to guide the discussion:
						<ul>
					      <li>It may be more interesting to ask for input, opinions, and experiences on the topic rather than posting a fully developed idea and asking for feedback.</li>
					      <li>Make sure your Member Network profile is complete rather than using space to provide details on your background.</li>
					    </ul>
					</li>
					<li>Put the topic in context:
						<ul>
					     	<li>Is the topic especially relevant because of a recent or ongoing issue or event?</li>
							<li>Is there a related common practice or popular opinion right now that you agree/disagree with?</li>
							<li>Was new research on the topic published? Or, conversely, is there a troublesome lack of new information?</li>
							<li>Does the issue affect a community, a population segment, or even an individual you can talk about?</li>
						</ul>
					</li>
				</ul>
				Contact the communications team at help@essentialhospitals.org if you have any questions about using the Member Network and community discussions.";
				$headers = "From: Member Network <info@essentialhospitals.org>";

				wp_mail($authorEmail, $subject, $message, $headers);
			}
		}
	}


	//-Contact request from association member
	function contact_request($toID, $fromID){
		$toUser = get_userdata($toID);
			$toFname = $toUser->first_name;
			$toLname = $toUser->last_name;
			$toTitle = get_user_meta($toID, 'title', true);
			$toEmail = $toUser->user_email;

		$fromUser = get_userdata($fromID);
			$fromFname = $fromUser->first_name;
			$fromLname = $fromUser->last_name;
			$fromHosp = get_user_meta($fromID, 'hospital_name', true);
			if($fromHosp){
				$fromHosp = "at $fromHosp";
			}

		$dashboardURL = get_permalink(278);

		$subject = "$fromFname $fromLname would like to add you as a Member Network contact";
		$message = "Dear $toFname $toLname,<br><br>
					$fromFname $fromLname from $fromHosp, a member of America’s Essential Hospitals, would like to add you as a contact. If you don’t already know this person, he or she may be interested in your work. You may accept this request, and connect with other users, by following this link:<br><br>

					<a href=\"http://essentialhospitals.org/membernetwork/connections/\">http://essentialhospitals.org/membernetwork/connections/</a><br><br>

					You might first need to sign in. Then, you will see this request (and possibly others) under \"Requests Received\" on the right hand side of your Contacts page; select each request to approve it or deny it. Note: If, after signing in, you land on your Member Network dashboard rather than your contacts page, choose \"Contacts\" from the menu bar.<br><br>

					If you have questions or comments, contact the America’s Essential Hospitals communications team at <a href=\"mailto:help@essentialhospitals.org\">help@essentialhospitals.org</a>.";
		$headers = "From: America's Essential Hospitals <info@essentialhospitals.org>";

		wp_mail($toEmail, $subject, $message, $headers);
	}

// ----- Subscribed Discussions ----- //
add_action( 'wp_insert_comment', 'subscribed_comment', 10, 1 );
function subscribed_comment($comment_id, $comment_object){
	$comment = get_comment($comment_id);
	$commentDate = strtotime($comment->comment_date);
	$commentDate = date("M jS", $commentDate);
	$commentContent = $comment->comment_content;
	$commentID = $comment->comment_ID;
	$commentAuthor = $comment->comment_author;

	$post = get_post($comment->comment_post_ID);
	$postTitle = $post->post_title;
	$postLink = get_permalink($post->ID);
	$args = array(
					'meta_key' 	 => 'subscribe-'.$post->ID,
					'meta_value' => true
				);
				$users = get_users($args);
	if(count($users) > 0){
		foreach($users as $user){
			$toEmail = $user->user_email;
			$subject = "There has been a new comment on a discussion you subscribed to";
			$message = "There has been an update to the topic <em>$postTitle</em> you subscribed to. You can access this <a href='$postLink'>discussion here</a>.";
			$headers = "From: America's Essential Hospitals <info@essentialhospitals.org>";

			wp_mail($toEmail, $subject, $message, $headers);
		}
	}

}






// ----- Email template constructors and settings
	//HTML content type
add_filter ("wp_mail_content_type", "email_content_type");
function email_content_type() {
	return "text/html";
}
	//Email from name set
add_filter ("wp_mail_from_name", "email_from_name");
function email_from_name($original_email_from) {
	if($original_email_from == 'WordPress'){
		return "America's Essential Hospitals <info@essentialhospitals.org>";
	}else{
		return $original_email_from;
	}
}
	//wp_mail filter to use HTML theme
add_filter ('wp_mail','email_theme');
function email_theme($vars){
	//check if $vars['message'] is an array
	if(is_array($vars['message'])){
		if($vars['message']['theme'] == true){
			//send with membernetwork template
			$vars['message'] = MN_email_construct($vars['subject'],$vars['message']['content']);
		}else{
			//send with standard template
			$vars['message'] = email_construct($vars['subject'],$vars['message']['content']);
		}
	}else{
		//send with standard template
		$vars['message'] = email_construct($vars['subject'],$vars['message']);
	}

	return $vars;
}
	//Function that constructs the HTML theme
function email_construct($title,$content){
	$header = file_get_contents('email-header.php',true);
	$mid = file_get_contents('email-mid.php',true);
	$footer = file_get_contents('email-footer.php',true);

	$output = $header.$title.$mid.$content.$footer;
	return $output;
}
	//Function that constructs Member Network HTML theme
function MN_email_construct($title,$content){
	$header = file_get_contents('MNemail-header.php',true);
	$mid = file_get_contents('MNemail-mid.php',true);
	$footer = file_get_contents('MNemail-footer.php',true);

	$output = $header.$title.$mid.$content.$footer;
	return $output;
}
