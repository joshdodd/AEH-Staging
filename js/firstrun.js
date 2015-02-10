function firstRun(currentUser){
	queryURL = templateDir+'/membernetwork/firstrun.php';
	  $.ajax({
        type: 'POST',
        url: queryURL,
        data: {currentUser : currentUser},
        success: function(msg) {
        }
    });
	var intro = introJs();
          intro.setOptions({
            steps: [
              {
                element: '#membernetwork h1.title',
                intro: "<span style='font-size:24px; line-height:30px;' >Welcome to the Member Network</span><br>Take a quick walkthrough of your new accountaccount's features (click \"next\")"
              },
              {
                element: '#membernetwork',
                intro: "<span style='font-size:24px; line-height:30px;' >This is your dashboard</span><br>Use the black navigation bar to access all of your account features. Build your profile, customize content, have discussions, access your program groups, and make connections."
              },
              {
                element: '#run-news',
                intro: "<span style='font-size:24px; line-height:30px;' >Your custom content feed</span><br>Customize your dashboard to show the latest headlines from your interest areas. Content can include critical updates, learning opportunities, feature articles, and more."
              },
              {
                element: '#run-groups',
                intro: "<span style='font-size:24px; line-height:30px;' >Your programs and groups</span><br>Access the virtual classrooms for your programs here. You can also request to initiate your own private group: explore a topic, plan an effort, or share resources with only the people you invite."
              },
              {
                element: '#run-disccomm',
                intro: "<span style='font-size:24px; line-height:30px;' >Community discussions & content comments</span><br>Revisit a conversation you started or joined right from your dashboard. Just below, see the latest comments readers have posted on sitewide articles and media."

              },
              {
                element: '#userProfile',
                intro: "<span style='font-size:24px; line-height:30px;' >View and edit your profile.</span><br>This is a preview of your profile. After this tour, click on the image or the \"edit profile\" button to add or change your photo, edit details, and publish a bio."
              },
              {
                element: '#run-webinars',
                intro: "<span style='font-size:24px; line-height:30px;' >Your upcoming webinars</span><br>If you’re registered for one or more webinars, quick links appear here."
              },
              {
                element: '#run-connect',
                intro: "<span style='font-size:24px; line-height:30px;' >Private messages</span><br>The heart of the Member Network is in connecting with other professionals like you from across the U.S. Access your Member Network private messages inbox here.
"
              },
              {
                element: '#membernetwork',
                intro: "America's Essential Hospitals is here to support and bring together people dedicated to higher quality, more accessible health care. Make good use of your new community! You can always access dashboard in the upper right corner from anywhere on the site.  For any questions or comments, contact help@essentialhospitals.org"
              },

            ]
          });

          intro.start();
          $('.introjs-skipbutton').on('click',function(){
	         $('body > .introjs-tooltip').remove();
          });
		  $('.introjs-tooltip').clone().prependTo('body');
		  $( "body > .introjs-tooltip .introjs-prevbutton" ).one( "click", function() {
	      		console.log('hey');
			  $('.introjs-helperLayer .introjs-tooltip .introjs-prevbutton').click();
			});
			$( "body > .introjs-tooltip .introjs-nextbutton" ).one( "click", function() {
				console.log('hey');
			  $('.introjs-helperLayer .introjs-tooltip .introjs-nextbutton').click();
			});
			$( "body > .introjs-tooltip .introjs-skipbutton" ).one( "click", function() {
				console.log('hey');
			  $('.introjs-helperLayer .introjs-tooltip .introjs-skipbutton').click();
			});
		  intro.onbeforechange(function(){
		  	$( "body > .introjs-tooltip").remove();
		  });
          intro.onchange(function(){
	          	setTimeout(function(){
	          		$('.introjs-tooltip').clone().prependTo('body');
	          		if($('body > .introjs-tooltip .introjs-skipbutton').text() == 'Done'){
				  		$('body > .introjs-tooltip .introjs-skipbutton').addClass('done');
				  		$('body > .introjs-tooltip .introjs-prevbutton, body > .introjs-tooltip .introjs-nextbutton').remove();
				  	};
	          		$( "body > .introjs-tooltip .introjs-prevbutton" ).one( "click", function() {
		          		console.log('hey');
					  $('.introjs-helperLayer .introjs-tooltip .introjs-prevbutton').click();
					});
					$( "body > .introjs-tooltip .introjs-nextbutton" ).one( "click", function() {
						console.log('hey');
					  $('.introjs-helperLayer .introjs-tooltip .introjs-nextbutton').click();
					});
					$( "body > .introjs-tooltip .introjs-skipbutton" ).one( "click", function() {
						console.log('hey');
					  $('.introjs-helperLayer .introjs-tooltip .introjs-skipbutton').click();
					});
	          	}, 500);

          	});
}
