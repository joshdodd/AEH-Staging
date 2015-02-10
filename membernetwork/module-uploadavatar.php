<div id="uploadAvatar">
	<?php
	global $user_ID;
	if ($user_ID) {
	$user_info = get_userdata($user_ID);
	$id = $user_info->ID;
	}
	 ?>
	
	<?php if(isset($_POST['user_avatar_edit_submit']))
	      {
	           do_action('edit_user_profile_update', $id);
	      }
	?>
	
	<form id="your-profile" action="" method="post" enctype="multipart/form-data">
	    <?php
	    $myAv = new simple_local_avatars();
	    $myAv->edit_user_profile($user_info);
	    ?>
	    <input type="submit" name="user_avatar_edit_submit" value="OK"/>
	</form>
</div>