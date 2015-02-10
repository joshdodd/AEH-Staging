<?php

// Import iMIS Users

include ('includes/aeh_config.php'); 
include ("includes/aeh-functions.php");
get_header();
global $wpdb;	
	$params = array(
		'securityPassword' => 'F46DB250-B294-4B3D-BC95-45B7DDFEE334',
		'name' => 'importUsers',
		'parameters' => ''
	);

	// Send a POST request to ibridge
	$result = post_request('http://isgweb.naph.org/ibridge/DataAccess.asmx/ExecuteDatasetStoredProcedure', $params);
	$startunix = time();
	if ($result['status'] == 'ok'){
	 
		// Print headers 
		echo $result['header']; echo "<br><br>";
		
		//$thexml = html_entity_decode($result['content']);				// convert the xml into real characters instead of entities
		$xml = simplexml_load_string($result['content']);
		
		//file_put_contents('test-text.txt', $result['content']);
		//echo $result['content'];exit;
		
		if ($xml === false) {
			echo 'Error while parsing the document';
			exit;
		}
		$xml = dom_import_simplexml($xml);
		
		if (!$xml) {
			echo 'Error while converting XML';
			exit;
		}
		
		$wpdb->query('TRUNCATE TABLE `wp_aeh_import`'); // clear out the import table before importing all users from iMIS
		
		$nodelist = $xml->getElementsByTagName('Table');  
		
		for($i = 0; $i < $nodelist->length; $i++) {
			
			$unix        = time();
			$ID          = $nodelist->item($i)->getElementsByTagName('ID');
			$firstname   = $nodelist->item($i)->getElementsByTagName('FIRST_NAME');
			$lastname    = $nodelist->item($i)->getElementsByTagName('LAST_NAME');
			$email       = $nodelist->item($i)->getElementsByTagName('EMAIL');
			$password    = $nodelist->item($i)->getElementsByTagName('WEB_PASSWORD');
			$mem_type    = $nodelist->item($i)->getElementsByTagName('MEMBER_TYPE');
			$company     = $nodelist->item($i)->getElementsByTagName('COMPANY');
			$title       = $nodelist->item($i)->getElementsByTagName('TITLE');
			$prefix      = $nodelist->item($i)->getElementsByTagName('PREFIX');
			$designation = $nodelist->item($i)->getElementsByTagName('DESIGNATION');
			$fullname    = $nodelist->item($i)->getElementsByTagName('FULL_NAME');
			$website     = $nodelist->item($i)->getElementsByTagName('WEBSITE');
			
			$ID          =   $ID->item(0)->nodeValue;          
			$firstname   =   addslashes($firstname->item(0)->nodeValue);  
			$lastname    =   addslashes($lastname->item(0)->nodeValue);    
			$email       =   addslashes($email->item(0)->nodeValue);       
			$password    =   addslashes($password->item(0)->nodeValue);   
			$mem_type    =   addslashes($mem_type->item(0)->nodeValue);     
			$company     =   addslashes($company->item(0)->nodeValue);     
			$title       =   addslashes($title->item(0)->nodeValue);       
			$prefix      =   addslashes($prefix->item(0)->nodeValue);      
			$designation =   addslashes($designation->item(0)->nodeValue); 
			$fullname    =   addslashes($fullname->item(0)->nodeValue);    
			$website     =   addslashes($website->item(0)->nodeValue);
			
			//echo "$i: $ID $firstname $lastname $email $password $memtype $company $title $prefix $designation $fullname $website<br/><br />";
			
			$sql = "
			INSERT INTO `wp_aeh_import` (
				`ID`,
				`unixtime`,
				`firstname`,
				`lastname`,
				`email`,
				`password`,
				`mem_type`,
				`company`,
				`title`,
				`prefix`,
				`designation`,
				`fullname`,
				`website`
			) VALUES (
				'$ID',
				'$unix',
				'$firstname',
				'$lastname',
				'$email',
				'$password',
				'$mem_type',
				'$company',
				'$title',
				'$prefix',
				'$designation',
				'$fullname',
				'$website'			
				)";
			//echo $sql; exit;
			$wpdb->query($sql);
		}
		$elapsed = time() - $startunix;
		echo "Added $i records to temp import table taking $elapsed seconds";
	}
		/*************************** start of post import code ******************************/
		
		if (0){
		$n = 1; $output = '';

		$post_data = array(
			'securityPassword' => 'F46DB250-B294-4B3D-BC95-45B7DDFEE334',
			'sqlStatement' => "SELECT t1.ID, FIRST_NAME, LAST_NAME, EMAIL, WEB_PASSWORD, MEMBER_TYPE, COMPANY, TITLE, PREFIX, DESIGNATION, FULL_NAME, WEBSITE FROM PRODIMIS.dbo.Name t1 INNER JOIN dbo.UD_SECURITY t2 ON t1.ID = t2.ID WHERE (MEMBER_TYPE = 'MIND' OR MEMBER_TYPE= 'STAFF') AND (t1.EMAIL = t2.WEB_LOGIN) AND t1.EMAIL != '' AND WEB_PASSWORD !='password' AND WEB_PASSWORD !=''"
		);

		// Send a POST request to ibridge
		$result = post_request('http://isgweb.naph.org/ibridge/DataAccess.asmx/ExecuteSelectSQLStatement', $post_data);
		 
		if ($result['status'] == 'ok'){
		 
			// Print headers 
			//echo $result['header']; 
			
			$thexml = html_entity_decode($result['content']);				// convert the xml into real characters instead of entities
			$thexml = substr($thexml, strpos($thexml, '<Rows>'),-19); 		// reduce the xml to <Rows> as the root element
			$xml    = simplexml_load_string($thexml);						// create an xml object containing all the fields
			
			$n = 1; $i = 0; $err = 0;
			
			foreach($xml as $name){
				set_time_limit(300); 										// increase time for the script to run in each iteration
				ob_implicit_flush(TRUE);
				ob_end_flush();
				
				$ID          = (string)$name->ID;
				$firstname   = addslashes((string)$name->FIRST_NAME);
				$lastname    = addslashes((string)$name->LAST_NAME);
				$email       = (string)$name->EMAIL; $ed = explode('@',$email); //$email = addslashes($email);
				$emaildomain = $ed[1]; 										//email domain
				$password    = (string)$name->WEB_PASSWORD; 
				$epassword   = base64_encode(gzcompress($password)); 		//encrypted version of the password
				$hpassword   = wp_hash_password($password);					//WP hashed version of the password
				$membertype  = (string)$name->MEMBER_TYPE; 
				if ($membertype=="STAFF"){$mt = 'Y';}else{$mt = 'N';}		//$mt = Y or N
				$employer    = addslashes((string)$name->COMPANY);
				$jobtitle    = addslashes((string)$name->TITLE);
				$title       = addslashes((string)$name->PREFIX);
				$jobfunction = addslashes((string)$name->DESIGNATION);
				$nickname    = addslashes((string)$name->FULL_NAME);
				$website     = addslashes((string)$name->WEBSITE);
				$WPusername  = str_replace("'","",$email);
				
				$output = "<strong>$n</strong> ID: $ID, Name: $firstname $lastname, Email: $email, Job Title: $jobtitle ";

				//check to see if user already exists before doing import operation so you update existing users and create the others.
				if (validEmail($email)){
					$check = $wpdb->get_col("SELECT ID FROM wp_users WHERE user_email = '" . addslashes($email) . "'");
					$user_id = $check[0];
				}else{
					$user_id = 0; //zero is a non-existent WP user (used as a flag)
					$err++;
				}
				
				if ($user_id === ''){ //if blank then user does not exist in WP so they need to be created

					$userdata = array(
						'user_login'    => $WPusername,
						'user_email'    => addslashes($email),
						'display_name'  => "$firstname $lastname",
						'user_nicename' => "$firstname $lastname",
						'user_url'  	=> $website
					);

					$user_id = wp_insert_user($userdata);

					//On success update the user metadata
					if(is_wp_error($user_id)){
						echo "Could not add user <span style='font-weight:bold;color:red'>$output</span> [$sql]";
					}else{
						echo "Added user <span style='font-weight:bold;color:yellow'>$output</span>";
						
						$wpdb->query("UPDATE `wp_users` SET `user_pass`='$hpassword' WHERE `ID`= $user_id"); //set the password
						$wpdb->query("INSERT INTO `wp_usermeta` (user_id,meta_key,meta_value) VALUES ($user_id,'aeh_member_type','hospital')");
						$wpdb->query("INSERT INTO `wp_usermeta` (user_id,meta_key,meta_value) VALUES ($user_id,'job_title','$jobtitle')");
						$wpdb->query("INSERT INTO `wp_usermeta` (user_id,meta_key,meta_value) VALUES ($user_id,'aeh_staff','$mt')");
						$wpdb->query("INSERT INTO `wp_usermeta` (user_id,meta_key,meta_value) VALUES ($user_id,'job_function','$jobfunction')");
						$wpdb->query("INSERT INTO `wp_usermeta` (user_id,meta_key,meta_value) VALUES ($user_id,'title','$title')");
						$wpdb->query("INSERT INTO `wp_usermeta` (user_id,meta_key,meta_value) VALUES ($user_id,'aeh_password','$epassword')");
						$wpdb->query("INSERT INTO `wp_usermeta` (user_id,meta_key,meta_value) VALUES ($user_id,'aeh_imis_id','$ID')");
						$wpdb->query("INSERT INTO `wp_usermeta` (user_id,meta_key,meta_value) VALUES ($user_id,'email_domain','$emaildomain')");
						$wpdb->query("INSERT INTO `wp_usermeta` (user_id,meta_key,meta_value) VALUES ($user_id,'employer','$employer')");
						$wpdb->query("INSERT INTO `wp_usermeta` (user_id,meta_key,meta_value) VALUES ($user_id,'first_name','$firstname')");
						$wpdb->query("INSERT INTO `wp_usermeta` (user_id,meta_key,meta_value) VALUES ($user_id,'last_name','$lastname')");
						$wpdb->query("INSERT INTO `wp_usermeta` (user_id,meta_key,meta_value) VALUES ($user_id,'nickname','$nickname')");
					}

				}else{
					if ($user_id === 0){
						echo "<span style='font-weight:bold;color:blue'>iMIS DB user [$ID] has an invalid email address [$email]. Account not updated or added</span>";
					}else{
						$i++;		//increment the already imported counter because this user already exists in WP
						echo "<span style='font-weight:bold;color:green'>$output already imported!</span>"; 
									// update existing user
						if (0){
							wp_set_password($password, $user_id);
							update_user_meta($user_id, 'aeh_member_type', 'hospital');
							update_user_meta($user_id, 'job_title', $jobtitle);
							if ($membertype=="STAFF"){
								update_user_meta($user_id, 'aeh_staff', 'Y');
							}else{
								update_user_meta($user_id, 'aeh_staff', 'N');
							}
							update_user_meta($user_id, 'job_function', $jobfunction);
							update_user_meta($user_id, 'title', $title);
							update_user_meta($user_id, 'aeh_password', $epassword);
							update_user_meta($user_id, 'aeh_imis_id', $ID);
							update_user_meta($user_id, 'email_domain', $emaildomain);
							update_user_meta($user_id, 'employer', $employer);
							update_user_meta($user_id, 'user_url', $website);
							update_user_meta($user_id, 'first_name', $firstname);
							update_user_meta($user_id, 'last_name', $lastname);
							update_user_meta($user_id, 'nickname', $nickname);
						}
					}
				}
				echo "<br /><br />";
				$n++; if ($n==2000)break;
				}

			}	 

				/*
				META VALUE = VALUE
				aeh_member_type = hospital
				job_title = $jobtitle
				aeh_staff = Y if $membertype = STAFF, N if $membertype = anything else)
				job_function = $jobfunction
				nickname = $nickname
				title = $title
				aeh_password = encrypted $password
				aeh_imis_id = $ID
				aeh_visibility = Yes
				tos = agree
				employer = $employer
				first_name = $firstname
				last_name = $lastname
				website = $website
				<?php wp_set_password( $password, $user_id ) ?> 
				<?php get_user_meta($user_id, $key, $single);  ?> 
				<?php update_user_meta( $user_id, $meta_key, $meta_value, $prev_value ) ?> 
				*/
				
				//public viz default = on
				//$encryptedpw = base64_encode(gzcompress($name->WEB_PASSWORD));
				//$decryptedpw = gzuncompress(base64_decode($encryptedpw));
				//echo "EncryptedPW: " . $encryptedpw . "<br />";
				//echo "DecryptedPW: " . $decryptedpw . "<br /><br />";


				//if ($n == 200)exit;

			$n--;
			$output = "<div>There were $i Members already in the system and a total of $n members processed from the iMIS database. There were [$err] errors.</div>";
			echo $output;
		}
		/*************************** end of import code ******************************/