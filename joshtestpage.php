<?php
/*
Template Name: Test Pass Template
*/
 

$test = 0;
$create = 0; 

//include "simple_html_dom.php";

include ('includes/aeh_config.php'); 
include ("includes/aeh-functions.php");
get_header();
global $wpdb;

?>

<div id="membernetwork">
	<div class="container">
		<h1 class="title"><span class="grey">Essential Hospitals</span> Maintenance Page</h1>	
		<div id="registrationcontent" class="group">
			<div class="gutter clearfix">
				<h2 class='heading'>Diagnostic Pages</h1>

<?php
//get_imis_tables();

//$headers = "From: America's Essential Hospitals <info@essentialhospitals.org>\r\n";  
//if(wp_mail('mcampbell@essentialhospitals.org', 'Test Email from website', "This is a test", $headers))
//	echo 'The test message was sent! Check your email inbox.';
//else  
 //   echo 'The message was not sent!';
 




/*
	$imisdata = does_imis_user_exist('');
	if($imisdata){
		$prefix        = $imisdata['prefix'];
		$firstname     = $imisdata['firstname'];
		$middlename    = $imisdata['middlename'];
		$lastname      = $imisdata['lastname'];
		$suffix        = $imisdata['suffix'];
		$designation   = $imisdata['designation'];
		$workphone     = $imisdata['workphone'];
		$fax           = $imisdata['fax'];
		$addressnum    = $imisdata['addressnum'];
		$address1      = $imisdata['address1'];
		$city          = $imisdata['city'];
		$state         = $imisdata['state'];
		$zip           = $imisdata['zip'];
		$country       = $imisdata['country'];
		$company       = $imisdata['company'];
		$co_id         = $imisdata['companyID'];
		$title         = $imisdata['title'];
		$mobile        = $imisdata['mobile'];
		$asst_name     = $imisdata['asst_name'];
		$asst_phone    = $imisdata['asst_phone'];
		$asst_email    = $imisdata['asst_email'];
		$webinterest   = $imisdata['webinterest'];


		echo $prefix        . "<br>" ; 
		echo $firstname     . "<br>" ; 
		echo $middlename    . "<br>" ; 
		echo $lastname      . "<br>" ; 
		echo $suffix        . "<br>" ; 
		echo $designation   . "<br>" ; 
		echo $workphone     . "<br>" ; 
		echo $fax           . "<br>" ; 
		echo $addressnum    . "<br>" ; 
		echo $address1      . "<br>" ; 
		echo $city          . "<br>" ; 
		echo $state         . "<br>" ; 
		echo $zip           . "<br>" ; 
		echo $country       . "<br>" ; 
		echo $company       . "<br>" ; 
		echo $co_id         . "<br>" ; 
		echo $title         . "<br>" ; 
		echo $mobile        . "<br>" ; 
		echo $asst_name     . "<br>" ; 
		echo $asst_phone    . "<br>" ; 
		echo $asst_email    . "<br>" ; 
		echo $webinterest   . "<br>" ; 

	} */
 	
	
	//$email = "pateason@pateason.com";
	//$id = 2;
	//$execut= $wpdb->query("UPDATE $wpdb->users SET user_email = '$email' WHERE ID =  '$id' ");
	//var_dump($execut);
	//$page = "http://essentialhospitals.org/testing-pwd/";
 	//$sec = "1";
 	//header("Refresh: $sec; url=$page");

 






	//GET USERS THAT ARE NOT VERIFIED (NEED TO BE ADDED TO IMIS...)
 	$all_hosp = $wpdb->get_col("SELECT user_id FROM `wp_usermeta` where meta_key = 'aeh_member_type' and meta_value = 'hospital'");
 	$verified = $wpdb->get_col("SELECT user_id FROM `wp_usermeta` where meta_key = 'imis_verified' and meta_value = 1");

 	$diffs = array_diff($all_hosp,$verified);
 	//print_r($diffs);

 	$n = 1;
 	echo "<br><br>  USERS NOT IMIS VERIFIED (WP ID)<br><br>";
 	foreach ($diffs as $diff){
		 
		//$imis = array();
		$imisid  = get_usermeta( $diff, $meta_key = 'aeh_imis_id' );
		//delete_user_meta( $diff , 'aeh_imis_id' );
		//delete_user_meta( $diff , 'aeh_password' );
		//delete_user_meta( $diff , 'address_number' );
		 

		echo $n . ":  " .$diff .  "<br>";
		//array_push($imis, $imisid);


		$n++;
	}

	 
	 




	//print_r($diff);
	//print_r($verified);






/**************************************************************************************************************** 

	$results = $wpdb->get_results("SELECT * FROM `wp_users` where ID IN (SELECT DISTINCT user_id FROM `wp_usermeta` WHERE `meta_key` = 'aeh_member_type'  AND `meta_value` = 'hospital' AND user_id NOT IN (SELECT user_id FROM `wp_usermeta` WHERE`meta_key` = 'aeh_password'))
 ");
	 $n = 1;

	foreach($results as $result){

		$pwd_hash   = $result->user_pass;
		$user_id = $result->ID;
		$user_login = $result->user_login;
		$user_email = $result->user_email;
		 
 		require_once( ABSPATH . 'wp-includes/class-phpass.php');
		
		$wp_hasher = new PasswordHash(8, TRUE);
 
		if($wp_hasher->CheckPassword($tester, $pwd_hash)){
			echo "<span style='color:#00ff00'> YES, Matched </span>: " . $user_id . " - " .$user_login ;
			add_user_meta( $user_id, "aeh_password", $tester );
		}else{
			echo  $user_email;
		}
		echo "<br /> ";
		$n++;
		//unset ($wp_hasher);
	}
	$n = $n - 1;
	echo "<br /> TOTAL: $n";

	exit;
 
/****************************************************************************************************************/
?>  