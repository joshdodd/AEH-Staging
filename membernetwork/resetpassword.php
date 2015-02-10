 <?php
	$email = 'pat@meshfresh.com';
	if( email_exists( $email )) {
	  echo "it exists";
	}else{
	   echo "nope, doesn't exist";
	}
?> 