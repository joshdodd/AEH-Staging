<?php /* Template Name: Member Network - Onboarding */ get_header(); ?>

<div id="ob-banner">
	<div class="container">
		<div class="container">
			<div class="gutter">
				<h3>Insider conversations and the latest news in your expertise.<br>Connect with peers on the Member Network.</h3>
				<a id="ob-reg" href="http://essentialhospitals.org/membernetwork/registration/">Register Now</a>
			</div>
		</div>
	</div>
</div>
<div id="ob-content">
	<div class="container">
		<div class="blog-social socialonboard">
			<!-- AddThis Button BEGIN -->
			<div class="addthis_toolbox addthis_32x32_style" style="">
			<a class="addthis_button_facebook"></a>
			<a class="addthis_button_twitter"></a>
			<a class="addthis_button_linkedin"></a>
			<a class="addthis_button_pinterest_share"></a>
			<a class="addthis_button_google_plusone_share"></a>
			<a class="addthis_button_email"></a>
			<a class="addthis_button_digg"></a>
			<a class="addthis_button_evernote"></a>
			<a class="addthis_button_compact"></a>
			</div>
			<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
			<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=naphsyscom"></script>
			<!-- AddThis Button END -->
		</div>
		<div id="contentPrimary">
			<div id="s-one" class="ob-section r">
				<div class="gutter clearfix">
					<div class="ob-section-content">
						<div class="gutter">
							<span class="ob-section-content-sub">Personal Profile</span>
							<h2>Let others know what you’re working on and learn about their work through personal profiles.</h2>
						</div>
					</div>
					<div class="ob-section-img">
						<div class="gutter">
							<img src="<?php bloginfo('template_directory'); ?>/images/onboarding/personalprofile.jpg" />
						</div>
					</div>
				</div>
			</div>
			<div id="s-two" class="ob-section l">
				<div class="gutter clearfix">
					<div class="ob-section-img">
						<div class="gutter">
							<img src="<?php bloginfo('template_directory'); ?>/images/onboarding/contacts.jpg" />
						</div>
					</div>
					<div class="ob-section-content">
						<div class="gutter">
							<span class="ob-section-content-sub">Contacts</span>
							<h2>Connect with existing contacts and seek out new ones.</h2>
						</div>
					</div>
				</div>
			</div>
			<div id="s-three" class="ob-section r">
				<div class="gutter clearfix">
					<div class="ob-section-content">
						<div class="gutter">
							<span class="ob-section-content-sub">Private Messaging</span>
							<h2>Send personal messages, seek advice, or follow up with new contacts via private messaging.</h2>
						</div>
					</div>
					<div class="ob-section-img">
						<div class="gutter">
							<img src="<?php bloginfo('template_directory'); ?>/images/onboarding/privatemessaging.jpg" />
						</div>
					</div>
				</div>
			</div>
			<div id="s-four" class="ob-section l">
				<div class="gutter clearfix">
					<div class="ob-section-img">
						<div class="gutter">
							<img src="<?php bloginfo('template_directory'); ?>/images/onboarding/communitydiscussions.jpg" />
						</div>
					</div>
					<div class="ob-section-content">
						<div class="gutter">
							<span class="ob-section-content-sub">Community Discussion</span>
							<h2>Engage in public conversations to share insights, learn from others, and collaborate on topics important to your work.</h2>
						</div>
					</div>
				</div>
			</div>
			<div id="s-five" class="ob-section r">
				<div class="gutter clearfix">
					<div class="ob-section-content">
						<div class="gutter">
							<span class="ob-section-content-sub">Topic-Based Group</span>
							<h2>Take advantage of this private platform open only to association members to discuss issues and share related resources.</h2>
						</div>
					</div>
					<div class="ob-section-img">
						<div class="gutter">
							<img src="<?php bloginfo('template_directory'); ?>/images/onboarding/topicbasedgroups.jpg" />
						</div>
					</div>
				</div>
			</div>
			<div id="s-six" class="ob-section l">
				<div class="gutter clearfix">
					<div class="ob-section-img">
						<div class="gutter">
							<img src="<?php bloginfo('template_directory'); ?>/images/onboarding/webinarbasedgroups.jpg" />
						</div>
					</div>
					<div class="ob-section-content">
						<div class="gutter">
							<span class="ob-section-content-sub">Webinar Group</span>
							<h2>Discuss webinar topics with fellow attendees and speakers to enhance your learning in this virtual classroom.</h2>
						</div>
					</div>
				</div>
			</div>
			<div id="s-seven" class="ob-section r">
				<div class="gutter clearfix">
					<div class="ob-section-content">
						<div class="gutter">
							<span class="ob-section-content-sub">Custom News</span>
							<h2>Personalize the content you receive from us—on your desktop or phone—for quicker access to the latest information most relevant to your work.</h2>
						</div>
					</div>
					<div class="ob-section-img">
						<div class="gutter">
							<img src="<?php bloginfo('template_directory'); ?>/images/onboarding/customnews.jpg" />
						</div>
					</div>
				</div>
			</div>
			<div id="s-eight" class="ob-section l last">
				<div class="gutter clearfix">
					<div class="ob-section-img">
						<div class="gutter">
							<img src="<?php bloginfo('template_directory'); ?>/images/onboarding/dashboard.jpg" />
						</div>
					</div>
					<div class="ob-section-content">
						<div class="gutter">
							<span class="ob-section-content-sub">Dashboard</span>
							<h2>See all your Member Network activity, including your custom news feed, in your dashboard— also viewable on your phone.</h2>
						</div>
					</div>
				</div>
			</div>
			<div class="floatleft fullwidth middlealign alignmiddle" id="touratouratoura">
				<a href="http://essentialhospitals.org/membernetwork/public-tour/">Take a tour of the Dashboard here</a>
			</div>
			<div id="loginregister" class="floatleft onehalf">
				<div id="memberReg">
					<div id="wpmem_reg">
						<div class="gutter">
							<?php echo do_shortcode('[wp-members page="register"]'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="contentSecondary">
			<div class="gutter">
				<ul>
					<li><a href="#s-one">Personal Profile</a></li>
					<li><a href="#s-two">Contacts</a></li>
					<li><a href="#s-three">Private Messaging</a></li>
					<li><a href="#s-four">Community Discussions</a></li>
					<li><a href="#s-five">Topic-Based Group</a></li>
					<li><a href="#s-six">Webinar Group</a></li>
					<li><a href="#s-seven">Custom News</a></li>
					<li><a href="#s-eight">Dashboard</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(window).ready(function(){
		if($('.wpmem_msg').length > 0){
			$('html, body').animate({
		    	scrollTop:$(".wpmem_msg").offset().top
		    },200);
		}
	});
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