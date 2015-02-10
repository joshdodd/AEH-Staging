<?php/*Template Name: Member Activation*/include ('includes/aeh_config.php');include ('includes/aeh-functions.php');get_header();/* Retrieve the key */$confirmkey = (get_query_var('confirmed')) ? get_query_var('confirmed') : null;global $wpdb;$user_id = 0; //assume an invalid user because 0 is not a valid WP member ID numberif ($result = $wpdb->get_row("SELECT * FROM `wp_usermeta` WHERE `meta_key` = 'authcheck' AND `meta_value` = '$confirmkey'")){	$user_id = $result->user_id;	$memtype = get_user_meta($user_id, 'aeh_member_type', true);}if($_GET['memid']){	$user_id = $_GET['memid'];	$memtype = get_user_meta($user_id, 'aeh_member_type', true);} ?><div id="membernetwork">	<div class="container">		<h1 class="title"><span class="grey">Essential Hospitals</span> Member Network | Account Activation</h1>		<div id="connectioncontent" class="group">			<div class="gutter clearfix">				<h2 class='heading'>Membership Activation</h1>				<?php					if(!$user_id){						$user_id = get_current_user_id();						$memtype = get_user_meta($user_id, 'aeh_member_type', true);					}					if($user_id){						if($memtype == 'hospital'){							$user = get_userdata($user_id);							$email = $user->user_email;							$first   = $user->first_name;							$last    = $user->last_name;							$result = does_imis_user_exist($email);							$password = get_user_meta($user_id, 'aeh_password', true);								//Check if iMIS record exists								if($result === false){								//Fill form and create new iMIS record if no								if(isset($_POST['process'])){									update_user_meta( $user_id, 'middle_name', $_POST['m_initial']);									update_user_meta( $user_id, 'suffix', $_POST['suffix']);									update_user_meta( $user_id, 'designation', $_POST['designation']);									//update_user_meta( $user_id, 'salutation', $_POST['salutation']);									update_user_meta( $user_id, 'street_address', $_POST['street_address']);									update_user_meta( $user_id, 'city', $_POST['city']);									update_user_meta( $user_id, 'state', $_POST['state']);									update_user_meta( $user_id, 'zip_code', $_POST['zip_code']);									update_user_meta( $user_id, 'phone', $_POST['phone']);									update_user_meta( $user_id, 'fax', $_POST['fax']);									update_user_meta( $user_id, 'mobile_phone', $_POST['mobile']);									update_user_meta( $user_id, 'assistant_name', $_POST['assistant_name']);									update_user_meta( $user_id, 'assistant_email', $_POST['assistant_email']);									update_user_meta( $user_id, 'assistant_phone', $_POST['assistant_phone']);									update_user_meta( $user_id, 'hospital_name', $_POST['company']);									update_user_meta( $user_id, 'job_title', $_POST['job_title']);									update_user_meta( $user_id, 'CO_ID', $_POST['company_id']);									update_user_meta( $user_id, 'COMPANY_SORT', $_POST['company_sort']);									delete_user_meta( $user_id, 'confirmed');									//Display confirmation message									add_user_meta($user_id, 'verified', true, true);									//Attempt to create iMIS record									$imis_id = new_imis($first,$last,$email,$password);									if($imis_id === false){										//Create failed										update_user_meta($user_id,"aeh_imis_id","fail");									}else{										//Create didn't fail - set iMIS id and update iMIS record with WP meta										update_user_meta($user_id,"aeh_imis_id",$imis_id);										add_user_meta($user_id,'imis_verified',1,true);										$newusercheck = does_imis_user_exist($email);										$addressNum = $newusercheck['addressnum'];										if($addressNum){											add_user_meta($user_id,'address_number', $addressNum);											update_create_imis_from_wp($imis_id,$user,$user_id);										}									}									echo "<span class='imisFound'>Thank you! Your account has been activated, please login below.</span>";									echo "<div id='loginForm' class='verif'>";									echo do_shortcode('[wp-members page="login"]');									echo "</div>";								}else{ ?>									<span class="imisError">Please complete your profile to activate your account</span>								    <form method="post" id="newMem" onsubmit="return formValidate();">										<table class="floatleft onehalf">									<tr>										<td><label>Middle Initial</label></td>										<td><input name="m_initial" type="text" width="1" maxlength="20" /></td>									</tr>									<tr>										<td><label>Suffix</label></td>										<td><select id="suffix" name="suffix">												<option value="">Select a Suffix</option>												<option value="II">II</option>												<option value="III">III</option>												<option value="IV">IV</option>												<option value="Jr.">Jr</option>												<option value="Sr.">Sr</option>												<option value=""> </option>											</select></td>									</tr>									<tr>										<td><label>Credentials</label></td>										<td><input name="designation" type="text" maxlength="20"/></td>									</tr>									<tr>										<td><label>*Company/Organization</label></td>										<?php $org = get_site_option('company_list');											//var_dump($org); ?>										<td><select name="company" id="company">											<option value="">-- Select a Hospital --</option>											<?php $hq = $org['hq'];												  $company = $org['company'];												  $address = $org['address'];												  $city = $org['city'];												  $state = $org['state'];												  $id = $org['id'];												  $sort= $org['company_sort'];												  $zipc = $org['zip'];												  $wphone = $org['work_phone'];												  $wfax = $org['fax'];												  $len = count($hq);												  for($i = 1; $i <= $len; $i++){												  	if(count($company[$i]) > 0){ ?>												  	<option value="<?php echo $company[$i]; ?>">												  		<?php echo $company[$i]; ?>												  		<?php if(strlen($hq[$i]) > 1){													  		echo "(".$hq[$i]."), ";													  	}													  	if(strlen($city[$i]) > 0){													  		echo $city[$i].", ";													  	}													  	if(strlen($state[$i]) > 0){													  		echo $state[$i]." ";													  	}													  	if(strlen($id[$i]) > 0){													  		echo "[".$id[$i]." | ";													  	}													  	if(strlen($sort[$i]) > 0){													  		echo $sort[$i]."]";													  	} ?></option>												  <?php } } ?>											</select>										</td>									</tr>									<tr>										<td><label>*Street Address</label></td>										<td><input name="street_address" type="text" maxlength="40" /></td>									</tr>									<tr>										<td><label>*City</label></td>										<td><input name="city" type="text" maxlength="40" /></td>									</tr>									<tr>										<td><label>*State</label></td>										<td><select name="state">												<option value="" selected="selected">Select a State</option>												<option value="AL">Alabama</option>												<option value="AK">Alaska</option>												<option value="AZ">Arizona</option>												<option value="AR">Arkansas</option>												<option value="CA">California</option>												<option value="CO">Colorado</option>												<option value="CT">Connecticut</option>												<option value="DE">Delaware</option>												<option value="DC">District Of Columbia</option>												<option value="FL">Florida</option>												<option value="GA">Georgia</option>												<option value="HI">Hawaii</option>												<option value="ID">Idaho</option>												<option value="IL">Illinois</option>												<option value="IN">Indiana</option>												<option value="IA">Iowa</option>												<option value="KS">Kansas</option>												<option value="KY">Kentucky</option>												<option value="LA">Louisiana</option>												<option value="ME">Maine</option>												<option value="MD">Maryland</option>												<option value="MA">Massachusetts</option>												<option value="MI">Michigan</option>												<option value="MN">Minnesota</option>												<option value="MS">Mississippi</option>												<option value="MO">Missouri</option>												<option value="MT">Montana</option>												<option value="NE">Nebraska</option>												<option value="NV">Nevada</option>												<option value="NH">New Hampshire</option>												<option value="NJ">New Jersey</option>												<option value="NM">New Mexico</option>												<option value="NY">New York</option>												<option value="NC">North Carolina</option>												<option value="ND">North Dakota</option>												<option value="OH">Ohio</option>												<option value="OK">Oklahoma</option>												<option value="OR">Oregon</option>												<option value="PA">Pennsylvania</option>												<option value="RI">Rhode Island</option>												<option value="SC">South Carolina</option>												<option value="SD">South Dakota</option>												<option value="TN">Tennessee</option>												<option value="TX">Texas</option>												<option value="UT">Utah</option>												<option value="VT">Vermont</option>												<option value="VA">Virginia</option>												<option value="WA">Washington</option>												<option value="WV">West Virginia</option>												<option value="WI">Wisconsin</option>												<option value="WY">Wyoming</option>											</select></td>									</tr>									<tr>										<td><label>*ZIP Code</label></td>										<td><input name="zip_code" type="text" maxlength="10" /></td>									</tr>								</table>								<table class="floatleft onehalf">									<tr class="hidden">										<td><label>Company ID</label></td>										<td><select name="company_id" id="company_id">											<?php for($i = 1; $i <= $len; $i++){ ?>												<option value="<?php echo $id[$i]; ?>"><?php echo $id[$i]; ?></option>											<?php } ?>											</select>										</td>									</tr>									<tr class="hidden">										<td><label>Company Sort</label></td>										<td><select name="company_sort" id="company_sort">											<?php for($i = 1; $i <= $len; $i++){ ?>												<option value="<?php echo $sort[$i]; ?>"><?php echo $sort[$i]; ?></option>											<?php } ?>											</select>										</td>									</tr>									<tr class="hidden">										<td><label>Company address</label></td>										<td><select name="company_address" id="company_address">											<?php for($i = 1; $i <= $len; $i++){ ?>												<option value="<?php echo $address[$i]; ?>"><?php echo $address[$i]; ?></option>											<?php } ?>											</select>										</td>									</tr>									<tr class="hidden">										<td><label>Company city</label></td>										<td><select name="company_city" id="company_city">											<?php for($i = 1; $i <= $len; $i++){ ?>												<option value="<?php echo $city[$i]; ?>"><?php echo $city[$i]; ?></option>											<?php } ?>											</select>										</td>									</tr>									<tr class="hidden">										<td><label>Company state</label></td>										<td><select name="company_state" id="company_state">											<?php for($i = 1; $i <= $len; $i++){ ?>												<option value="<?php echo $state[$i]; ?>"><?php echo $state[$i]; ?></option>											<?php } ?>											</select>										</td>									</tr>									<tr class="hidden">										<td><label>Company zip</label></td>										<td><select name="company_zip" id="company_zip">											<?php for($i = 1; $i <= $len; $i++){ ?>												<option value="<?php echo $zipc[$i]; ?>"><?php echo $zipc[$i]; ?></option>											<?php } ?>											</select>										</td>									</tr>									<tr class="hidden">										<td><label>Company work phone</label></td>										<td><select name="company_workphone" id="company_workphone">											<?php for($i = 1; $i <= $len; $i++){ ?>												<option value="<?php echo $wphone[$i]; ?>"><?php echo $wphone[$i]; ?></option>											<?php } ?>											</select>										</td>									</tr>									<tr class="hidden">										<td><label>Company fax</label></td>										<td><select name="company_fax" id="company_fax">											<?php for($i = 1; $i <= $len; $i++){ ?>												<option value="<?php echo $wfax[$i]; ?>"><?php echo $wfax[$i]; ?></option>											<?php } ?>											</select>										</td>									</tr>									<tr>										<td><label>Job Title</label></td>										<td><input name="job_title" type="text" placeholder="Job Title" maxlength="80" /></td>									</tr>									<tr>										<td><label>*Phone</label></td>										<td><input name="phone" type="tel" placeholder="(xxx) xxx-xxxx" maxlength="25" /></td>									</tr>									<tr>										<td><label>Fax</label></td>										<td><input name="fax" type="tel" placeholder="(xxx) xxx-xxxx" maxlength="25" /></td>									</tr>									<tr>										<td><label>Mobile Phone</label></td>										<td><input name="mobile" type="tel" placeholder="(xxx) xxx-xxxx" maxlength="25" /></td>									</tr>									<tr>										<td><label>Assistant Name</label></td>										<td><input name="assistant_name" type="text" maxlength="30" /></td>									</tr>									<tr>										<td><label>Assistant Email</label></td>										<td><input name="assistant_email" type="text" maxlength="50" /></td>									</tr>									<tr>										<td><label>Assistant Phone</label></td>										<td><input name="assistant_phone" type="tel" placeholder="(xxx) xxx-xxxx" maxlength="25" /></td>									</tr>									</table>										<p class="required"><strong>* are required</strong></p>										<input type="submit" name="process" value="Submit"/>									</form>									<script type="text/javascript">										function formValidate(){											//Get field values											$m_initial = $('input[name="m_initial"]').val();											$suffix = $('select[id="suffix"]').val();											$designation = $('input[name="designation"]').val();											//$salutation = $('input[name="salutation"]').val();											$streetAddress = $('input[name="street_address"]').val();											$city = $('input[name="city"]').val();											$state = $('select[name="state"]').val();											$zipCode = $('input[name="zip_code"]').val();											$phone = $('input[name="phone"]').val();											$fax = $('input[name="fax"]').val();											$mobile = $('input[name="mobile"]').val();											$astName = $('input[name="assistant_name"]').val();											$astEmail = $('input[name="assistant_email"]').val();											$astPhone = $('input[name="assistant_phone"]').val();											$company = $('select[name="state"]').val();											//console.log($astPhone);											$('form#newMem *').removeClass('fail');											$validate = true;											/*if($m_initial == ''){												$validate = false;												$('input[name="m_initial"]').addClass('fail');											}*/											/*if($suffix == ''){												$validate = false;												$('select[id="suffix"]').parent().addClass('fail');											}*/											/*if($designation == ''){												$validate = false;												$('input[name="designation"]').addClass('fail');											}*/											/*if($salutation == ''){												$validate = false;												$('input[name="salutation"]').addClass('fail');											}*/											if($streetAddress == ''){												$validate = false;												$('input[name="street_address"]').addClass('fail');											}											if($city == ''){												$validate = false;												$('input[name="city"]').addClass('fail');											}											if($state == ''){												$validate = false;												$('select[name="state"]').parent().addClass('fail');											}											if($zipCode == ''){												$validate = false;												$('input[name="zip_code"]').addClass('fail');											}											if($phone == ''){												$validate = false;												$('input[name="phone"]').addClass('fail');											}											if($company == ''){												$validate = false;												$('select[name="company"]').parent().addClass('fail');											}											/*if($fax == ''){												$validate = false;												$('input[name="fax"]').addClass('fail');											}*/											/*if($mobile == ''){												$validate = false;												$('input[name="mobile"]').addClass('fail');											}*/											/*if($astName == ''){												$validate = false;												$('input[name="assistant_name"]').addClass('fail');											}*/											/*if($astEmail == ''){												$validate = false;												$('input[name="assistant_email"]').addClass('fail');											}*/											/*if($astPhone == ''){												$validate = false;												$('input[name="assistant_phone"]').addClass('fail');											}*/											if($validate == false){												return false;											}										}									</script>								<?php }								}else{								echo "<span class='imisFound'>We found your account! You can now log in to the Member Network existing account information.</span>";								echo "<div id='loginForm' class='verif'>";								echo do_shortcode('[wp-members page="login"]');								echo "</div>";								//Set user meta and verify if iMIS record exists								update_user_meta($user_id, 'first_name', $result['firstname']);								update_user_meta($user_id, 'last_name', $result['lastname']);								update_user_meta($user_id, 'middle_name', $result['middlename']);								update_user_meta($user_id, 'suffix', $result['suffix']);								update_user_meta($user_id, 'designation', $result['designation']);								update_user_meta($user_id, 'street_address', $result['address1']);								update_user_meta($user_id, 'city', $result['city']);								update_user_meta($user_id, 'state', $result['state']);								update_user_meta($user_id, 'zip_code', $result['zip']);								update_user_meta($user_id, 'country', $result['country']);								update_user_meta($user_id, 'phone', $result['workphone']);								update_user_meta($user_id, 'fax', $result['fax']);								update_user_meta($user_id, 'mobile_phone', $result['mobile']);								update_user_meta($user_id, 'assistant_name', $result['asst_name']);								update_user_meta($user_id, 'assistant_email', $result['asst_email']);								update_user_meta($user_id, 'assistant_phone', $result['asst_phone']);								update_user_meta($user_id, 'CO_ID', $result['companyID']);								if($result['mem_type'] == 'STAFF'){									update_user_meta($user_id, 'aeh_staff', 'Y');								}else{									update_user_meta($user_id, 'aeh_staff', 'N');								}								update_user_meta($user_id, 'aeh_imis_id', $result['ID']);								update_user_meta($user_id, 'hospital_name', $result['company']);								update_user_meta($user_id, 'job_title', $result['title']);								update_user_meta($user_id, 'website', $result['website']);								update_user_meta($user_id, 'informal', $result['informal']);								update_user_meta($user_id, 'address_number', $result['addressnum']);								update_user_meta($user_id, 'title', $result['prefix']);								delete_user_meta( $user_id, 'confirmed');								add_user_meta($user_id, 'imis_verified',1,true);								add_user_meta($user_id, 'verified', true, true);}						 }elseif($memtype != 'hospital'){						 	//Otherwise verify away							/* Display confirmation message*/							add_user_meta($user_id, 'verified', true, true);							echo "<span class='imisFound'>Thank you for verifying your account. Please login below. </span>";							echo "<div id='loginForm' class='verif'>";							echo do_shortcode('[wp-members page="login"]');							echo "</div>";						}				}else{					/* The key is incorrect or the user has already activated their account */					echo "<span class='imisError'>Unfortunately you registration key is not valid. This may be because you have already confirmed your registration. Please try logging in. Otherwise, contact support</span>";				} ?>			</div>		</div>	</div></div><?php get_footer('sans'); ?>