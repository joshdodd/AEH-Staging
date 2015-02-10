<div class="panel">
		<?php
			 $discuss = $_GET['discuss'];

			 $userID = get_current_user_id();
			 $isPrivate = get_user_meta($userID, 'aeh_member_type', true);
			 if (!empty($_POST)){
			 	$newTitle = $_POST['title'];
			 	$newDesc  = $_POST['description'];
			 	$tagPost  = explode(',',$_POST['tagPost']);
			 	if(isset($_POST['catPost'])){
				 	$catPost  = $_POST['catPost'];
			 	}else{
			 		$catPost  = 'public';
			 	}

			 	$newID    = $_POST['userID'];

			 	if($isPrivate == 'hospital'){
			 		$postStatus = 'publish';
			 	}else{
				 	$postStatus = 'pending';
			 	}

			 	// Create post object
				$newpost = array(
				  'comment_status' => 'open',
				  'post_author'    => $userID,
				  'post_content'   => $newDesc,
				  'post_status'    => $postStatus,
				  'post_title'     => $newTitle,
				  'post_type'      => 'discussion',
				  'tax_input'      => array( 'discussions' => array( $catPost ) ),
				);

				// Insert the post into the database
				$newID = wp_insert_post( $newpost, true );
				add_post_meta($newID, 'frontend', true);
				wp_set_object_terms( $newID, $catPost, 'discussions' );
				wp_set_object_terms( $newID, $tagPost, 'discussion_tags' );

			 	if($isPrivate == 'hospital'){
			 		echo '<span class="discsuccess">Thank you for your submission, your discussion has been added below.</span>';
			 	}else{
				 	echo '<span class="discsuccess">Thank you for your submission, it will be reviewed and posted here.</span>';
			 	}

			 } else{ ?>
			 	<div id="newDiscussion" class="clearfix" <?php if($discuss){ echo 'style="display:block;"'; }?>>
			 	 <h2 class="heading">Start a New Discussion</h2>
				 <form id="addPost" method="post">
					<input name="title" type="text" placeholder="Discussion Title" />
					<textarea name="description" type="textarea" placeholder="Start Discussion here"></textarea>
					<?php echo '<input name="userID" type="hidden" value="'.$userID.'" />'; ?>
					<?php if($isPrivate == 'hospital'){ ?>
						<label for="public">Public</label>
						<input type="radio" value="public" name="catPost" id="catPost" checked="checked"/>
						<label for="private">Hospital Members Only</label>
						<input type="radio" value="private" name="catPost" id="catPost" />
						<label for="staff">Staff Only</label>
						<input type="radio" value="staff" name="catPost" id="catPost" />
					<?php } ?>
					<input type="text" name="tagPost" placeholder="tags separated by commas. ie: this,is,the,tags" />
					<input name="submit" type="submit" value="Submit" />
				</form>
			 	</div>
			 <?php } ?>
</div>