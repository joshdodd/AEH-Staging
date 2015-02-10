jQuery(document).ready(function($){
	var templateDir = 'http://mlinson.wpengine.com/wp-content/themes/EssentialHospitals';
	var queryURL = templateDir+'/includes/infinitePosts.php';
	$page = 1;
	$type = 'institute';
	$center = $('#instititePostBox').attr('data-center');
	//Set Variables for masonry container and relayout
	$container = $('#institutePostBox');
	$container.masonry();
	$loading = false;

	//Check if we've scrolled to the bottom of the masonry container
	$(window).scroll(function(){
		var scrollOffset = $(window).scrollTop();
		var offset = $container.offset();
		var loadOffset = $container.height();

		if(scrollOffset >= loadOffset && $loading != true){
			$page++;
			$('#infinite-indicator').addClass('show');
			$loading = true;
			getPosts();
		}
	});

	function getPosts(){
		$.ajax({
	        type: 'POST',
	        url: queryURL,
	        data: {page : $page, posttype : $type, center : $center},
	        success: function(msg) {
	        	if(msg == 'end'){
		        	$('#infinite-indicator').html('<p>All posts loaded</p>');
		        	setTimeout(function(){
		        		$('#infinite-indicator').removeClass('show');
		        	},2000);
	        	}else{
		        	html = $.parseHTML(msg);
					$.each(html,function(i,val){
						$(val).appendTo($container);
					});
					$container.masonry( 'appended', html );
					$loading = false;
					$('#infinite-indicator').removeClass('show');
	        	}
	        }
	    });
	}
});