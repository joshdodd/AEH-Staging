<?php
/*
Template Name: Test Template
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
				<h2 class='heading'>Diagnostic Page</h1>

<?php
$user_ID = get_current_user_id();

if ($user_ID == 321){ //check for Super Admin ID
echo "<h1>Welcome SuperAdmin</h1>";
exit;
/****************************************************************************************************************/
$n = 1; $i = 0;
$results = $wpdb->get_results("SELECT * FROM `wp_usermeta` WHERE meta_key = 'aeh_member_type' and meta_value = 'hospital' LIMIT 2200,50");
foreach($results as $result){

	$ID = $result->user_id;
	echo "$n: $ID - ";
	$imis_id  = get_usermeta($ID, 'aeh_imis_id'); 
	$imd = $imis_id;
	$user     = get_userdata($ID);
	$first    = $user->first_name;
	$last     = $user->last_name;
	echo "$imis_id - $first $last ";
	update_imis_from_wp($imis_id,$user,$ID);
	
	$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Got to: $n - $ID', '$imd')");
	
	$n++; //if ($n>1)exit;
	echo "<br />";
}
/*

	$email    = $user->user_email;
	$first    = $user->first_name;
	$last     = $user->last_name;
	$result   = does_imis_user_exist($email);
	$password = get_user_meta($user_id, 'aeh_password', true);
	
	//Attempt to create iMIS record
	$imis_id = new_imis($first,$last,$email,$password);
	if($imis_id === false){
		//Create failed
		//update_user_meta($user_id,"aeh_imis_id","fail");
	}else{
		//Create didn't fail - set iMIS id and update iMIS record with WP meta
		update_user_meta($user_id,"aeh_imis_id",$imis_id);
		$newusercheck = does_imis_user_exist($email);
		$addressNum = $newusercheck['addressnum'];
		if($addressNum){
			add_user_meta($user_id,'address_number', $addressNum);
			
			$user     = get_userdata($user_id);
			update_imis_from_wp($imis_id,$user,$user_id);
			*/
exit;
/****************************************************************************************************************/

	$n = 1; $i = 0;
	$results = $wpdb->get_results("SELECT `ID` FROM `wp_users`");
	foreach($results as $result){
		$ID = $result->ID;
		echo "$n: $ID - ";
		$pass = $wpdb->get_row("SELECT * FROM wp_usermeta WHERE meta_key = 'aeh_password' AND user_id = $ID");

		$user     = get_userdata($ID);
		$email    = $user->user_email;
		$first    = $user->first_name;
		$last     = $user->last_name;
		echo "$first $last $email ";
		if ($pass){$pwd = $pass->meta_value; echo " Password: $pwd";}else{
			echo " NO PASSWORD!!!!!!";
			update_usermeta($ID, 'aeh_password', 'XXXXXXzzzzzz');
			}
		$n++;
		echo "<br />";
	}

exit;
/****************************************************************************************************************/
if (0){
	$n = 1; $i = 0;
	$results = $wpdb->get_results("SELECT `ID` FROM `wp_users`");
	foreach($results as $result){
		$ID = $result->ID;
		echo "$n: $ID - ";
		$user     = get_userdata($ID);
		$email    = $user->user_email;
		$first    = $user->first_name;
		$last     = $user->last_name;
		echo "$first $last $email ";
		$imis = $wpdb->get_row("SELECT * FROM `wp_usermeta` WHERE `meta_key` = 'aeh_imis_id' AND `user_id` = $ID");
		$imis_id = $imis->meta_value;
		if ($imis_id == ''){
			$member = $wpdb->get_row("SELECT * FROM `wp_usermeta` WHERE `meta_key` = 'aeh_member_type' AND `user_id` = $ID");
			$membertype = $member->meta_value;
			if ($membertype == 'hospital'){
				echo "[$membertype] No iMIS ID";$i++; $imis_id = upload_user_fix($ID); if ($imis_id){echo "<span style='background-color:green'> New iMIS ID added: $imis_id</span>";}else{echo " FAILED ADD!";}
				if ($i == 10)exit;
			}else{
				echo "[$membertype] Type - no need to add";
			}
		}else{
			echo "iMIS ID: $imis_id";
		}	
		$n++;
		echo "<br />";
	}
	echo "<h1>Total WP accounts without iMIS IDs = $i</h1>";
	//$user = 25970;

	//$return = add_one_imis_user($user);
	//if ($return){echo "Sent value: $user, returned value: $return";}else{echo "ERROR!!!";}
	exit;
}
/****************************************************************************************************************/
//echo update_wp_users_temp();

//exit;


/****************************************************************************************************************/
if (0){
	$results = $wpdb->get_results("SELECT * FROM `wp_usermeta` WHERE `meta_key` = 'aeh_password'");
	$n = 1;
	foreach($results as $result){
		
		$ptpwd   = $result->meta_value;
		$user_id = $result->user_id;
		
		$wp_user = $wpdb->get_row("SELECT * FROM `wp_users` WHERE `ID`='$user_id'");
		$wp_hash = $wp_user->user_pass;
		echo "$n: $user_id - $ptpwd = $wp_hash - ";
		
		$wp_hasher = new PasswordHash(8, TRUE);
		
		if($wp_hasher->CheckPassword($ptpwd, $wp_hash)){
			echo "YES, Matched";
		}else{
			echo "No, Wrong Password";
		}
		echo "<br />";
		$n++;
		//unset ($wp_hasher);
	}

	exit;
}
/****************************************************************************************************************/

//$print = print_constants();echo $print;

$results = $wpdb->get_results("SELECT * FROM `wp_aeh_import_full`");
$n=1;$i=0;
echo "<table>
<tr><th>No:</th><th>iMIS ID</th><th>iMIS email</th><th>WP ID == iMIS ID</th><th>WP ID == email</th><th>Email</th><th>Display Name</th></tr>
";
foreach ($results as $result){

	$ID         = $result->ID;
	$email      = $result->email;
	$umeta      = $wpdb->get_row("SELECT * FROM `wp_usermeta` WHERE `meta_key`='aeh_imis_id' AND `meta_value`='$ID'");
	$users      = $wpdb->get_row("SELECT ID,user_email,display_name FROM `wp_users` WHERE `user_email`='$email'");
	$wpuserID   = $users->ID;
	$wpuseremail= $users->user_email; if ($wpuseremail=="")$wpuseremail = "NO MATCHING EMAIL!";
	$wpusername = $users->display_name;
	$user_id    = $umeta->user_id; if($user_id==""){$uid = "<span style='font:larger;background:red'>NO WP ID matching iMIS ID</span>";$i++;}else{$uid = "iMIS ID matches WP ID: $user_id";}
	echo "<tr><td>$n:</td><td>$ID</td><td>$email</td><td>$uid</td><td>$wpuserID</td><td>$wpuseremail</td><td>$wpusername</td></tr>";
	$n++;

}
echo "</table>";
echo "There were $i entries from iMIS that were not in WP";



exit;

/****************************************************************************************************************/
$user = "johndoe6@meshfresh.com";

print_r(does_imis_user_exist($user));
echo "\r\n";

if (0){
	$email = "rscott@gmh.edu"; 
	$result = does_imis_user_exist($email);
	if ($result === false){
		echo "USER $email NOT FOUND!";
	}else{
		echo "SUCCESS!<pre>";
		print_r($result);
		echo "</pre>";
	}
}

	if ($create){
		//$result = check_aeh_email("test@essentialhospitals.org");
		//print_r($result);
		/******************************************************** CREATE NEW IMIS ACCOUNT ****************************************************************/
		$first   = "Fred";
		$last    = "Flintlock";
		$email   = "fred.flintlock@nymc.edu";
		$password= "bambam";
		$result  = new_imis($first,$last,$email,$password); // if false on return then new account not created
		if ($result === false){
		
		}else{
		
			
		
		}

		$time    = time();
		$wpdb->query("UPDATE wp_usermeta SET meta_value = '$time - $result' WHERE umeta_id=22");
		echo "Result: $result";
			/*************************** end of create code ******************************/
	}else{
		echo "Importing is turned off!";
	}
}else{
echo "You do not have permission to view this page.";
}
?>

			</div><!-- #gutter clearfix -->
		</div><!-- #registrationcontent -->
	</div><!-- #container -->
</div><!-- #membernetwork -->

<?php 

get_footer('sans'); 

/************************************ functions **************************************************/

function number_pad($number,$n) {return str_pad((int) $number,$n,"0",STR_PAD_LEFT);} 

function strip_title($string,$limit=-1){
   $string=preg_replace('~<h4>[\s\S]*?</h4>~', '', $string,$limit);
   return $string;
} 

if (0){
function post_request($url, $data, $referer='') {
 
    // Convert the data array into URL Parameters like a=b&foo=bar etc.
    $data = http_build_query($data);
 
    // parse the given URL
    $url = parse_url($url);
 
    if ($url['scheme'] != 'http') { 
        die('Error: Only HTTP request are supported !');
    }
 
    // extract host and path:
    $host = $url['host'];
    $path = $url['path'];
 
    // open a socket connection on port 80 - timeout: 30 sec
    $fp = fsockopen($host, 80, $errno, $errstr, 30);
 
    if ($fp){
 
        // send the request headers:
        fputs($fp, "POST $path HTTP/1.1\r\n");
        fputs($fp, "Host: $host\r\n");
 
        if ($referer != '')
            fputs($fp, "Referer: $referer\r\n");
 
        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-length: ". strlen($data) ."\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $data);
 
        $result = ''; 
        while(!feof($fp)) {
            // receive the results of the request
            $result .= fgets($fp, 4096);
        }
    }
    else { 
        return array(
            'status' => 'err', 
            'error' => "$errstr ($errno)"
        );
    }
 
    // close the socket connection:
    fclose($fp);
 
    // split the result header from the content
    $result = explode("\r\n\r\n", $result, 2);
 
    $header = isset($result[0]) ? $result[0] : '';
    $content = isset($result[1]) ? $result[1] : '';
 
    // return as structured array:
    return array(
        'status' => 'ok',
        'header' => $header,
        'content' => $content
    );
}

/**
 * XML2Array: A class to convert XML to array in PHP
 * It returns the array which can be converted back to XML using the Array2XML script
 * It takes an XML string or a DOMDocument object as an input.
 *
 * See Array2XML: http://www.lalit.org/lab/convert-php-array-to-xml-with-attributes
 *
 * Author : Lalit Patel
 * Website: http://www.lalit.org/lab/convert-xml-to-array-in-php-xml2array
 * License: Apache License 2.0
 *          http://www.apache.org/licenses/LICENSE-2.0
 * Version: 0.1 (07 Dec 2011)
 * Version: 0.2 (04 Mar 2012)
 * 			Fixed typo 'DomDocument' to 'DOMDocument'
 *
 * Usage:
 *       $array = XML2Array::createArray($xml);
 */

class XML2Array {

    private static $xml = null;
	private static $encoding = 'UTF-8';

    /**
     * Initialize the root XML node [optional]
     * @param $version
     * @param $encoding
     * @param $format_output
     */
    public static function init($version = '1.0', $encoding = 'UTF-8', $format_output = true) {
        self::$xml = new DOMDocument($version, $encoding);
        self::$xml->formatOutput = $format_output;
		self::$encoding = $encoding;
    }

    /**
     * Convert an XML to Array
     * @param string $node_name - name of the root node to be converted
     * @param array $arr - aray to be converterd
     * @return DOMDocument
     */
    public static function &createArray($input_xml) {
        $xml = self::getXMLRoot();
		if(is_string($input_xml)) {
			$parsed = $xml->loadXML($input_xml);
			if(!$parsed) {
				throw new Exception('[XML2Array] Error parsing the XML string.');
			}
		} else {
			if(get_class($input_xml) != 'DOMDocument') {
				throw new Exception('[XML2Array] The input XML object should be of type: DOMDocument.');
			}
			$xml = self::$xml = $input_xml;
		}
		$array[$xml->documentElement->tagName] = self::convert($xml->documentElement);
        self::$xml = null;    // clear the xml node in the class for 2nd time use.
        return $array;
    }

    /**
     * Convert an Array to XML
     * @param mixed $node - XML as a string or as an object of DOMDocument
     * @return mixed
     */
    private static function &convert($node) {
		$output = array();

		switch ($node->nodeType) {
			case XML_CDATA_SECTION_NODE:
				$output['@cdata'] = trim($node->textContent);
				break;

			case XML_TEXT_NODE:
				$output = trim($node->textContent);
				break;

			case XML_ELEMENT_NODE:

				// for each child node, call the covert function recursively
				for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
					$child = $node->childNodes->item($i);
					$v = self::convert($child);
					if(isset($child->tagName)) {
						$t = $child->tagName;

						// assume more nodes of same kind are coming
						if(!isset($output[$t])) {
							$output[$t] = array();
						}
						$output[$t][] = $v;
					} else {
						//check if it is not an empty text node
						if($v !== '') {
							$output = $v;
						}
					}
				}

				if(is_array($output)) {
					// if only one node of its kind, assign it directly instead if array($value);
					foreach ($output as $t => $v) {
						if(is_array($v) && count($v)==1) {
							$output[$t] = $v[0];
						}
					}
					if(empty($output)) {
						//for empty nodes
						$output = '';
					}
				}

				// loop through the attributes and collect them
				if($node->attributes->length) {
					$a = array();
					foreach($node->attributes as $attrName => $attrNode) {
						$a[$attrName] = (string) $attrNode->value;
					}
					// if its an leaf node, store the value in @value instead of directly storing it.
					if(!is_array($output)) {
						$output = array('@value' => $output);
					}
					$output['@attributes'] = $a;
				}
				break;
		}
		return $output;
    }

    /*
     * Get the root XML node, if there isn't one, create it.
     */
    private static function getXMLRoot(){
        if(empty(self::$xml)) {
            self::init();
        }
        return self::$xml;
    }
}

/**
Validate an email address.
Provide email address (raw input)
Returns true if the email address has the email 
address format and the domain exists.
*/
function validEmail($email)
{
   $isValid = true;
   
   
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if
(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            ///$isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || 
 checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}
}

/**********************************************************************************************************/

function update_wp_users_temp() { 
	global $wpdb;

	$sql = "
	SELECT
	`rownum`,
	`t1`.`ID`,
	`firstname`,
	`middlename`,
	`lastname`,
	`suffix`,
	`nickname`,
	`addressnum`,
	`address1`,
	`city`,
	`state`,
	`zip`,
	`country`,
	`workphone`,
	`fax`,
	`mobile`,
	`email`,
	`password`,
	`mem_type`,
	`company`,
	`title`,
	`prefix`,
	`designation`,
	`website`,
	`asst_name`,
	`asst_phone`,
	`asst_email`,
	`webinterest`,
	`t2`.`ID` AS user_id
	FROM `wp_aeh_import_full_temp` AS t1
	JOIN `wp_users` AS t2 ON `user_email` = t1.email
	WHERE `WP_post_ID` = ''
	ORDER BY `t1`.`rownum` ASC
	LIMIT " . MAX_WP_USERS_UPDATED;
	$results = $wpdb->get_results($sql);

	$string = ""; $n = 0; $t = 0;

	foreach ($results as $row){
		$rownum      = $row->rownum;
		$imis_id     = $row->ID;
		$firstname   = $row->firstname; //
		$middlename  = $row->middlename;//
		$lastname    = $row->lastname;  //
		$suffix      = $row->suffix;	//
		$nickname    = $row->nickname;  //
		$addressnum  = $row->addressnum;//
		$address1    = $row->address1;	//
		$city        = $row->city;		//
		$state       = $row->state;		//
		$zip         = $row->zip;		//
		$country     = $row->country;	//
		$workphone   = $row->workphone;	//
		$fax         = $row->fax;		//
		$mobile      = $row->mobile;	//
		$email       = $row->email;		//
		//$password    = $row->password;	//
		$mem_type    = $row->mem_type; if ($mem_type == "STAFF"){$mem_type = 'Y';}else{$mem_type = 'N';}
		$company     = $row->company;	//
		$title       = $row->title;		//
		$prefix      = $row->prefix;	//
		$designation = $row->designation;
		$website     = $row->website;	//
		$asst_name   = $row->asst_name;	//
		$asst_phone  = $row->asst_phone;//
		$asst_email  = $row->asst_email;//
		$webinterest = explode(',',$row->webinterest);//
		$fullname    = $row->fullname;  //
		$wp_id       = $row->user_id;

		
		$imisID = get_user_meta($wp_id, 'aeh_imis_id',true);
		
		if ($imisID==''){
			update_user_meta($wp_id, 'aeh_imis_id', $imis_id);
			update_user_meta($wp_id, 'first_name', $firstname);
			update_user_meta($wp_id, 'middle_name', $middlename);
			update_user_meta($wp_id, 'last_name', $lastname);
			update_user_meta($wp_id, 'nickname', $nickname);
			update_user_meta($wp_id, 'address_number', $addressnum);
			update_user_meta($wp_id, 'street_address', $address1);
			update_user_meta($wp_id, 'city', $city);
			update_user_meta($wp_id, 'state', $state);
			update_user_meta($wp_id, 'zip_code', $zip);
			update_user_meta($wp_id, 'country', $country);
			update_user_meta($wp_id, 'phone', $workphone);
			update_user_meta($wp_id, 'fax', $fax);
			update_user_meta($wp_id, 'designation', $designation);
			update_user_meta($wp_id, 'mobile_phone', $mobile);
			update_user_meta($wp_id, 'aeh_staff', $mem_type);
			update_user_meta($wp_id, 'hospital_name', $company);
			update_user_meta($wp_id, 'job_title', $title);
			update_user_meta($wp_id, 'assistant_name', $asst_name);
			update_user_meta($wp_id, 'assistant_phone', $asst_phone);
			update_user_meta($wp_id, 'assistant_email', $asst_email);
			update_user_meta($wp_id, 'imisWebInterests', $webinterest);
			
			update_user_meta($wp_id, 'aeh_member_type', 'hospital');
			update_user_meta($wp_id, 'verified', '1');
			update_user_meta($wp_id, 'role', 'member');
			
			update_user_meta($wp_id, 'suffix', $suffix);
			update_user_meta($wp_id, 'title', $prefix);
			//update_user_meta($wp_id, 'aeh_password', $password);
			
			$email = addslashes($email);
			//wp_update_user(array('ID' => $wp_id, 'user_email' => $email));
			$wpdb->query("UPDATE `wp_users` SET `user_email` = '$email' WHERE `ID` = $wp_id");
			echo "$n: $wp_id, $imis_id....$imisID<br />";
			$wpdb->query("UPDATE `wp_aeh_import_full_temp` SET `WP_post_ID` = $wp_id WHERE `ID` = $imis_id");
		}
		

		$n++; //$string .= "$rownum: $imis_id - $fullname\r\n";
	}
	return $n;
}

/*****************************************************************************************************************/
function update_wp_users_xxx() {  // matching imis IDs
	global $wpdb;

	$sql = "
	SELECT
	`rownum`,
	`ID`,
	`firstname`,
	`middlename`,
	`lastname`,
	`suffix`,
	`nickname`,
	`addressnum`,
	`address1`,
	`city`,
	`state`,
	`zip`,
	`country`,
	`workphone`,
	`fax`,
	`mobile`,
	`email`,
	`password`,
	`mem_type`,
	`company`,
	`title`,
	`prefix`,
	`designation`,
	`website`,
	`asst_name`,
	`asst_phone`,
	`asst_email`,
	`webinterest`,
	`user_id`
	FROM `wp_aeh_import_full_temp` AS t1
	JOIN `wp_usermeta` AS t2 ON `meta_key` = 'aeh_imis_id' AND `meta_value` = t1.ID
	WHERE `WP_post_ID` = ''
	ORDER BY `t1`.`rownum` ASC
	LIMIT " . MAX_WP_USERS_UPDATED;
	$results = $wpdb->get_results($sql);

	$string = ""; $n = 0; $t = 0;

	foreach ($results as $row){
		$rownum      = $row->rownum;
		$imis_id     = $row->ID;
		$firstname   = $row->firstname; //
		$middlename  = $row->middlename;//
		$lastname    = $row->lastname;  //
		$suffix      = $row->suffix;	//
		$nickname    = $row->nickname;  //
		$addressnum  = $row->addressnum;//
		$address1    = $row->address1;	//
		$city        = $row->city;		//
		$state       = $row->state;		//
		$zip         = $row->zip;		//
		$country     = $row->country;	//
		$workphone   = $row->workphone;	//
		$fax         = $row->fax;		//
		$mobile      = $row->mobile;	//
		$email       = $row->email;		//
		//$password    = $row->password;	//
		$mem_type    = $row->mem_type; if ($mem_type == "STAFF"){$mem_type = 'Y';}else{$mem_type = 'N';}
		$company     = $row->company;	//
		$title       = $row->title;		//
		$prefix      = $row->prefix;	//
		$designation = $row->designation;
		$website     = $row->website;	//
		$asst_name   = $row->asst_name;	//
		$asst_phone  = $row->asst_phone;//
		$asst_email  = $row->asst_email;//
		$webinterest = explode(',',$row->webinterest);//
		$fullname    = $row->fullname;  //
		$wp_id       = $row->user_id;

		if (1){
			update_user_meta($wp_id, 'first_name', $firstname);
			update_user_meta($wp_id, 'middle_name', $middlename);
			update_user_meta($wp_id, 'last_name', $lastname);
			update_user_meta($wp_id, 'nickname', $nickname);
			update_user_meta($wp_id, 'address_number', $addressnum);
			update_user_meta($wp_id, 'street_address', $address1);
			update_user_meta($wp_id, 'city', $city);
			update_user_meta($wp_id, 'state', $state);
			update_user_meta($wp_id, 'zip_code', $zip);
			update_user_meta($wp_id, 'country', $country);
			update_user_meta($wp_id, 'phone', $workphone);
			update_user_meta($wp_id, 'fax', $fax);
			update_user_meta($wp_id, 'designation', $designation);
			update_user_meta($wp_id, 'mobile_phone', $mobile);
			update_user_meta($wp_id, 'aeh_staff', $mem_type);
			update_user_meta($wp_id, 'hospital_name', $company);
			update_user_meta($wp_id, 'job_title', $title);
			update_user_meta($wp_id, 'assistant_name', $asst_name);
			update_user_meta($wp_id, 'assistant_phone', $asst_phone);
			update_user_meta($wp_id, 'assistant_email', $asst_email);
			update_user_meta($wp_id, 'imisWebInterests', $webinterest);
			
			update_user_meta($wp_id, 'aeh_member_type', 'hospital');
			update_user_meta($wp_id, 'verified', '1');
			update_user_meta($wp_id, 'role', 'member');
			
			update_user_meta($wp_id, 'suffix', $suffix);
			update_user_meta($wp_id, 'title', $prefix);
			//update_user_meta($wp_id, 'aeh_password', $password);
			
			$email = addslashes($email);
			//wp_update_user(array('ID' => $wp_id, 'user_email' => $email));
			$wpdb->query("UPDATE `wp_users` SET `user_email` = '$email' WHERE `ID` = $wp_id");
		}
		$wpdb->query("UPDATE `wp_aeh_import_full_temp` SET `WP_post_ID` = $wp_id WHERE `ID` = $imis_id");
		echo "$n: $wp_id, $imis_id<br />";
		$n++; //$string .= "$rownum: $imis_id - $fullname\r\n";
	}
	return $n;
}
/****************************************************************************************************************/
$n=1; $d = 0;
$results = $wpdb->get_results("SELECT distinct user_id FROM `wp_usermeta_bak`");
foreach ($results as $result){
	$ID = $result->user_id;
	
	$test = $wpdb->query("DELETE FROM wp_usermeta WHERE `user_id` = $ID");
	
	if ($test){$d+= $test;}else{$test = 0;}
	
	echo "$n: removing $ID...removed $test usermeta entries<br />";
	
	$n++;

}
echo "$d total deleted";
exit;



/****************************************************************************************************************/
$n=1; $d = 0;
$results = $wpdb->get_results("SELECT `ID` FROM `wp_users`");
foreach ($results as $result){
	$ID = $result->ID;
	
	$test = $wpdb->query("DELETE FROM wp_usermeta_bak WHERE `user_id` = $ID");
	
	if ($test){$d+= $test;}else{$test = 0;}
	
	echo "$n: removing $ID...removed $test usermeta entries<br />";
	
	$n++;

}
echo "$d total deleted";
exit;
/**********************************************************************/

function upload_user_fix($user_id){

	$user     = get_userdata($user_id);
	$email    = $user->user_email;
	$first    = $user->first_name;
	$last     = $user->last_name;
	$result   = does_imis_user_exist($email);
	$password = get_user_meta($user_id, 'aeh_password', true);
	
	//Attempt to create iMIS record
	$imis_id = new_imis($first,$last,$email,$password);
	if($imis_id === false){
		//Create failed
		//update_user_meta($user_id,"aeh_imis_id","fail");
	}else{
		//Create didn't fail - set iMIS id and update iMIS record with WP meta
		update_user_meta($user_id,"aeh_imis_id",$imis_id);
		$newusercheck = does_imis_user_exist($email);
		$addressNum = $newusercheck['addressnum'];
		if($addressNum){
			add_user_meta($user_id,'address_number', $addressNum);
			update_imis_from_wp($imis_id,$user,$user_id);
		}
	}
	return $imis_id;
}
?>