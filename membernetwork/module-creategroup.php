<?php
if (isset($_POST['newgroup'])){
	$groupName = $_POST['group_name'];
	$groupDesc = $_POST['group_desc'];
	$groupMem = $_POST['group_mem'];

	$groupArr = explode(",",$groupMem);
	$addArr = array();

	$sort = 0;
	foreach($groupArr as $group){
		$gUser = get_userdata($group);

		$array = array();
		$array['user_id'] = $gUser->ID;
		$array['user_title'] = 'N';
		$array['user_info'] = $gUser->description;
		$array['user_showinfo'] = true;
		$array['user_sortorder'] = $sort;
		$addArr[] = $array;
		$sort++;
	}

	$curid = get_current_user_id();

	$newDesc = $groupDesc;

	// Create post object
	$newpost = array(
	  'comment_status' => 'open',
	  'post_content'   => $newDesc,
	  'post_status'    => 'pending',
	  'post_title'     => $groupName,
	  'post_type'      => 'group',
	  'post_author'	   => $curid
	);

	// Insert the post into the database
	$newID = wp_insert_post( $newpost, true );
	add_post_meta($newID, 'autp', $addArr);
	add_post_meta($newID,'mod',get_current_user_id());
	add_post_meta($newID,'frontend',true);
	$parent_term = term_exists( 'group', 'discussions' ); // array is returned if taxonomy is given
	$parent_term_id = $parent_term['term_id']; // get numeric term id
	wp_insert_term(
	  'group-'.$newID, // the term
	  'discussions', // the taxonomy
	  array(
	    'slug' => $newID,
	    'parent'=> $parent_term_id
	  )
	);
	create_group($newID);?>
	<div id="createGroup" class="show">
			<div class="gutter">
				<p>An association staff member will review your request shortly and you will be notified of the status via email.</p>
			</div>
	</div>
<?php }else{ ?>
<div id="createGroup">
		<div class="gutter">
			<?php the_content(); ?>
			<form id="newgroup" name="newgroup" method="post">
				<input type="text" name="group_name" placeholder="Group Name">
				<textarea name="group_desc" placeholder="Group topic and purpose"></textarea>
				<div id="members-add">
					<input type="text" name="membersearch" placeholder="Requested members">
					<div id="userloader"></div>
					<div id="autp_fillcont"></div>
				</div>
				<div id="members-added">
					<h2 id="memtitle" style="display:none;">Group Members<br><span>Click an added member below to remove him/her from the group</span></h2>
				</div>
				<input type="hidden" name="group_mem">
				<input type="submit" value="Submit" name="newgroup">
			</form>

		</div>
</div>
<?php } ?>