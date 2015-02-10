<?php  /* Template Name: Member Network - Registration */
get_header();
?>

<div id="membernetwork">
	<div class="container">
		<h1 class="title"><span class="grey">Essential Hospitals</span> Member Network</h1>
		<div id="registrationcontent" class="group">
			<div class="gutter clearfix">
				<h2 class='heading'>Registration</h2>
				<div id="loginregister" class="floatleft onehalf">
					<div id="memberReg">
						<div id="wpmem_reg">
							<div class="gutter">
								<?php echo do_shortcode('[wp-members page="register"]'); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="floatleft onehalf" id="reg-cont">
					<div class="gutter">
						<?php the_content(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function validateEmail(email) {
	    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    return re.test(email);
	}
	function valOutput(){
		$title = $('select#prefix').val();
		$first_name = $('input#first_name').val();
		$last_name = $('input#last_name').val();
		$email = $('input#user_email').val();
			$emailValidate = validateEmail($email);
		$password = $('input#password').val();
		$tos = $('input#tos');

		$('#wpmem_reg *').removeClass('failed');
		$('.failAlert').remove();



		$validate = true;
		$nonce = Math.floor((Math.random()*10000)+1);
		$('input#username').val($first_name+$last_name+$nonce);
		if($first_name == ''){
			$validate = false;
			$('input#first_name').addClass('failed');
			$('<div class="failAlert">first name required</div>').insertAfter('input#first_name');
		}
		if($last_name == ''){
			$validate = false;
			$('input#last_name').addClass('failed');
			$('<div class="failAlert">last name required</div>').insertAfter('input#last_name');
		}
		if($email == ''){
			$validate = false;
			$('input#user_email').addClass('failed');
			$('<div class="failAlert">email required</div>').insertAfter('input#user_email');
		}
		if($emailValidate != true){
			$validate = false;
			$('input#user_email').addClass('failed');
			$('<div class="failAlert">email is not valid</div>').insertAfter('input#user_email');
		}
		if($password == ''){
			$validate = false;
			$('input#password').addClass('failed');
			$('<div class="failAlert">password required</div>').insertAfter('input#password');
		}else if($password.length < 7){
			$validate = false;
			$('input#password').addClass('failed');
			$('<div class="failAlert">password must be more than 6 characters long</div>').insertAfter('input#password');
		}
		if(!($tos).is(':checked')){
			$validate = false;
			$('input#tos').parent().addClass('failed');
			$('<div class="failAlert">you must agree to the Terms of Service</div>').insertAfter('input#tos');
		}
		if($validate == false){
			return false;
		}

	}
</script>
<?php get_footer('sans'); ?>