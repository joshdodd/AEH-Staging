<?php
	// Our include
	define('WP_USE_THEMES', false);
	require_once('../../../../wp-load.php');

	global $wpdb;
	$curID = get_current_user_id();

	$action = $_POST['action'];

	$curID = $_POST['curid'];
	$uid = $_POST['uid'];
	$user = get_userdata($uid);
		$firstname = $user->first_name;
	$now = time();
	//check if $action exists
	if(isset($action) && isset($curID) && isset($uid)){
		//what action are we doing?
		if($action == 'add'){
			//check if contact hasn't already been added
			$contacts = $wpdb->get_results("SELECT user_id, friend_id
											FROM wp_aeh_connections
											WHERE user_ID = $curID
											AND consent_date > 0
											OR friend_id = $curID
											AND consent_date > 0");
			$contactarr = array();
			foreach($contacts as $contact){
				if($contact->user_id == $curID){
					array_push($contactarr, $contact->friend_id);
				}else{
					array_push($contactarr,$contact->user_id);
				}
			}

			$pendings = $wpdb->get_results("SELECT user_id, friend_id
											FROM wp_aeh_connections
											WHERE user_ID = $curID
											AND consent_date = 0
											OR friend_id = $curID
											AND consent_date = 0");
			$pendingarr = array();
			foreach($pendings as $pending){
				if($pending->user_id == $curID){
					array_push($pendingarr, $pending->friend_id);
				}else{
					array_push($pendingarr,$pending->user_id);
				}
			}
			//contact or pending?
			if(in_array($uid, $contactarr)){
				echo 'This person is already a contact';
			}elseif(in_array($uid, $pendingarr)){
				echo 'This person is already a pending contact';
			}else{
				//Add a row for the new connection
				$wpdb->insert('wp_aeh_connections',array(
														'connection_ID' => $insert_id,
														'user_ID' => $curID,
														'friend_ID' => $uid,
														'request_date' => $now,
														'consent_date' => '0',
														'interactions' => '0',
														'last_contact' => '0'
													));
				echo "Request Sent to $firstname";
				contact_request($uid,$curID);
			}

		}elseif($action == 'remove'){


		}elseif($action == 'deny'){
			//Find the right row
			$appme = $wpdb->get_row("SELECT connection_id
										 FROM wp_aeh_connections
										 WHERE consent_date = 0
										 AND (friend_id = $uid AND user_id = $curID)
										 OR (friend_id = $curID AND user_id = $uid)");
			//Update that row
			$wpdb->delete('wp_aeh_connections',array('connection_id' => $appme->connection_id));
			echo $appme->connection_id;

		}elseif($action == 'approve'){
			//Find the right row
			$appme = $wpdb->get_row("SELECT connection_id
										 FROM wp_aeh_connections
										 WHERE consent_date = 0
										 AND friend_id = $uid
										 AND user_id = $curID
										 OR friend_id = $curID
										 AND user_id = $uid");
			//Update that row
			$wpdb->update('wp_aeh_connections',
						  array('consent_date' => $now),
						  array('connection_id' => $appme->connection_id));
			echo "Success!";

		}else{
			echo 'Invalid action';
		}
	}else{
		echo 'Something went horribly wrong!';
	}
?>