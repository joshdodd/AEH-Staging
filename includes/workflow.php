<?php
//Onsave moderation & add editor as coauthor
function onsave_moderation($post_id){
	global $coauthors_plus;
	$thisPost = get_post($post_id);
	//Check for revision
	if ($parent_id = wp_is_post_revision($post_id)){
		$post_id = $parent_id;
	}
	//Check post type
	$postType = get_post_type($post_id);
	$typeArray = array('policy','quality','institute','post','page','webinar','group');
	$editorID = $_POST['editor'];
	$curEditor = get_post_meta($post_id,'curEdit',true);

	$modID = $_POST["fields"]['field_524d8c19819bb'];
	$curMod = get_post_meta($post_id,'curMod',true);

	$userArray = array();
	$curAuthors = get_coauthors($post_id);
	//add coauthors to $userArray
	foreach($curAuthors as $author){
		array_push($userArray, $author->user_login);
		$authout .= $author->user_login.' | ';

	}
	if(in_array($postType, $typeArray) /*&& get_post_status($post_id) != 'publish'*/){
		//add editor to $userArray
		if($editorID != $curEditor){
			//Get new editor
			$thisEditor = get_userdata($editorID);
			$editorEmail = $thisEditor->user_email;
			$postTitle = $thisPost->post_title;
			$postLink =  "http://essentialhospitals.org/wp-admin/post.php?post=" . $post_id ."&action=edit";
			//Send to new moderator
			$headers[] = 'Content-type: text/html';
			$subject = 'Article for review';
			$message = "You have been flagged as an editor for $postTitle.<br>
						Go <a href='$postLink'>here</a> to review the publication.";
			wp_mail($editorEmail, $subject, $message, $headers);

			//Add meta for current editor & save as coauthor
			add_post_meta($post_id,'curEdit',$editorID,true) || update_post_meta($post_id,'curEdit',$editorID);

			$user = get_userdata($editorID);
			array_push($userArray, $user->user_login);
			$authout .= $user->user_login.' | ';
		}

		//add moderator to $userArray
		if($modID != $curMod){
			add_post_meta($post_id,'curMod',$modID,true) || update_post_meta($post_id,'curMod',$modID);

			$user = get_userdata($modID);
			array_push($userArray, $user->user_login);
			$authout .= $user->user_login.' | ';
		}

		//set the coauthors
		$coauthors_plus->add_coauthors($post_id,$userArray);
		add_post_meta($post_id,'authout',$authout,true) || update_post_meta($post_id,'authout',$authout);

	}
}
add_action('save_post','onsave_moderation');

//Remove Editor meta when post/page is published
function removeEditor($post){
    delete_post_meta($post->ID,'curEdit');
}
add_action('new_to_publish', 'removeEditor');
add_action('draft_to_publish', 'removeEditor');
add_action('pending_to_publish', 'removeEditor');


//Editor field
add_action( 'post_submitbox_misc_actions', 'next_editor',90 );
function next_editor(){
	global $current_user;
	global $post;
	get_currentuserinfo();
	$userRole = implode(', ',$current_user->roles);
	$postType = $post->post_type;
	$curEdit = get_post_meta($post->ID,'curEdit',true);
	$typeArray = array('policy','quality','institute','post','page','webinar','group');
	if(in_array($postType, $typeArray)){
		$output .= "<div class='misc-pub-section'><label for='editor'>Editor: </label>";
			$editors = get_users('role=content_creator');
			$admins = get_users('role=administrator');
			$output .= '<select id="editor" name="editor">';
			$output .= '<optgroup label="Admins">';
			foreach($admins as $admin){
				$output .= '<option ';
					if($admin->ID == $curEdit){
						$output .= 'selected ';
					}
				$output .= 'value="'.$admin->ID.'">'.$admin->display_name.'</option>';
			}
			$output .= '</optgroup>';
			if(count($editors) > 0){
				$output .= '<optgroup label="Content Creators">';
				foreach($editors as $editor){
					$uMeta = get_user_meta($editor->ID, 'staffPermissions', true);
					if($uMeta){
						if(in_array($postType, $uMeta)){
							$output .= '<option ';
								if($editor->ID == $curEdit){
									$output .= 'selected ';
								}
							$output .= 'value="'.$editor->ID.'">'.$editor->display_name.'</option>';
						}
					}
				}
				$output .= '</optgroup>';
			}
			$output .= '</select>';
		$output .= "</div>";
		echo $output;
	}
}