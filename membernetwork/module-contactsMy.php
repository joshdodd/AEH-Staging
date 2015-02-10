<div class="panel" id="MyConnections">
	<h2 class="heading">My Contacts</h2>
	<div class="gutter">
		<div id="my-contacts">
		<?php global $wpdb;
			$curID = get_current_user_id();
			//echo $curID;
			//Get contacts for current user
			$contacts = $wpdb->get_results("SELECT user_id, friend_id
											FROM wp_aeh_connections
											WHERE user_ID = $curID
											AND consent_date > 0
											OR friend_id = $curID
											AND consent_date > 0");
			//print_r($contacts);
			//Create ID array from contacts; exclude currentuser ID
			$myCont = array();
			foreach($contacts as $contact){
				if($contact->user_id == $curID){
					array_push($myCont, $contact->friend_id);
				}else{
					array_push($myCont,$contact->user_id);
				}
			}
			//print_r($myCont);
			//Loop through IDs
			if(count($myCont) > 0){
			foreach($myCont as $user){
				$uData = get_userdata($user);
				$fName = $uData->first_name;
				$lName = $uData->last_name;
				$staff = get_usermeta($user,'aeh_staff');
				$ava = get_avatar($user, 96); ?>
				<a class="member-icon" href="<?php echo get_permalink(276); ?>?member=<?php echo $user; ?>">
					<div class="group-memberavatar friendedmeicon">
						<?php if($staff == 'Y'){echo '<div class="hospMem"></div>';}?>
						<span class="group-membername"><?php echo $fName.' '.$lName;?></span>
						<?php echo $ava; ?>
					</div>
				</a>
		<?php } }else{echo 'No contacts yet';} ?>
		</div>
	</div>
</div>