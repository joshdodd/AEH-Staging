<?php
/*
Template Name: Import iMIS Users
*/
 

$test = 0;
$import = 1;

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
				<h2 class='heading'>Import iMIS Users</h1>

<?php
$user_ID = get_current_user_id();
if ($user_ID == 321){ // check for Super Admin ID

	if ($import){
	
	$params = array(
		'securityPassword' => 'F46DB250-B294-4B3D-BC95-45B7DDFEE334',
		'name' => 'importUsers',
		'parameters' => ''
	);

	// Send a POST request to ibridge
	$result = post_request('http://isgweb.naph.org/ibridge/DataAccess.asmx/ExecuteDatasetStoredProcedure', $params);
	
	if ($result['status'] == 'ok'){
	 
		// Print headers 
		echo $result['header']; echo "<br><br>";
		
		//$thexml = html_entity_decode($result['content']);				// convert the xml into real characters instead of entities
		$entries = simplexml_load_string($result['content']);
		echo $result['content'];exit;
		    $entries->registerXPathNamespace('c', 'urn:schemas-microsoft-com:xml-diffgram-v1');
    $result = $entries->xpath("//c:diffgr");
	
	
var_dump($entries);exit;


if(count($entries)):
    //echo "<pre>";print_r($entries);die;
    //alternate way other than registring NameSpace
    //$asin = $asins->xpath("//*[local-name() = 'ASIN']");

    $entries->registerXPathNamespace('c', 'urn:schemas-microsoft-com:xml-diffgram-v1');
    $result = $entries->xpath("//c:diffgr");
	
	
print_r($result);exit;

foreach ($result as $title) {
  echo $title . "\n";
}
    //echo count($asin);
    //echo "<pre>";print_r($result);die;
    foreach ($result as $entry):
        //echo "<pre>";print_r($entry);die;
        $dc = $entry->children('urn:schemas-microsoft-com:xml-msdata');
        echo $dc->ID."<br/>";
        echo $dc->FIRST_NAME."<br/>";
        echo "<hr>";
    endforeach;
endif;

		
		EXIT;
		//$start = strpos($thexml, '<NewDataSet');
		//$e = '</NewDataSet>';
		//$end = strpos($thexml, $e) + strlen($e);
		//$length = $end - $start;
		//$xml = simplexml_load_string(substr ($thexml, $start, $length));
		
		//	  $namespaces = $xml->getNameSpaces(true);
		//	  $ns = $xml->children($namespaces['diffgr']);
		
		if (1){
			foreach ($thexml->NewDataSet as $entry){

			  $namespaces = $entry->getNameSpaces(true);
			  //Now we don't have the URL hard-coded
			  $dc = $entry->children($namespaces['diffgr']); 
			  echo $dc->ID;echo ", ";
			  echo $dc->FIRST_NAME;
			  echo "<br />";
			}
		}
		exit;
		
		
		
		//$thexml = substr($thexml, strpos($thexml, '<NewDataSet',-25));
		//file_put_contents('testxml.txt', $thexml);exit;
		
		//echo "ZZZZZZZZZZZZZZZZZZZZZZZZ<br>$start, $end, $length"; exit;

		//$thexml = substr($thexml, strpos($thexml, '<Rows>'),-19); 		// reduce the xml to <Rows> as the root element

		//$xml    = simplexml_load_string($thexml);						// create an xml object containing all the fields
		//echo $xml->asXML(); exit;
		
		//echo $xml->getName() . "<br>";

foreach($xml->NewDataSet as $child)
  {
  echo $child->ID . ": " . $child . "<br><br>";
  }
   exit;
		
		echo "<br />";
		echo "<br />";
		echo "<br />";

		$n = 1; $i = 0; $err = 0;
		
		foreach($xml->newdataset as $user){
			echo $user->id;
		
			echo "<br />";
		}	
	}
		
		exit;
	
	
	

	
	
	
	
	

		/*************************** start of post import code ******************************/
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
		/*************************** end of import code ******************************/
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
?>