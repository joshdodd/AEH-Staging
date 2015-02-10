jQuery(document).ready(function($){

	//----- All Connections
		//job function sort
		$('th.sortby-btn > select').change(function(){
			$value = $(this).val();
			$('#contactRender').fadeOut(200,function(){
				$(this).empty();
				$('#loadingstyle').remove();
			});
			$('#loader-gif').addClass('active');
			 $.ajax({
		        type: 'POST',
		        url: '/wp-content/themes/EssentialHospitals/membernetwork/contactquery.php',
		        data: {csort : 'job-function', csearch : $value},
		        success: function(msg) {
		        	$('#allContacts').removeClass().addClass('job-func');
		        	//$('#infinitescroll').remove();
		            $('#contactRender').html(msg).fadeIn(200);
		            $('#loader-gif').removeClass('active');
		            $('#queryholder').attr('data-sortby','job-function');
		        }
		    });
		});

		//staff only
		$('th.sortby-btn > div').on('click',function(){
			$('#contactRender').fadeOut(200,function(){
				$(this).empty();
				$('#loadingstyle').remove();
			});
			$('#loader-gif').addClass('active');
			 $.ajax({
		        type: 'POST',
		        url: '/wp-content/themes/EssentialHospitals/membernetwork/contactquery.php',
		        data: {csort : 'staff'},
		        success: function(msg) {
		        	$('#allContacts').removeClass().addClass('staff-only');
		        	//$('#infinitescroll').remove();
		            $('#contactRender').html(msg).fadeIn(200);
		            $('#loader-gif').removeClass('active');
		            $('#queryholder').attr('data-sortby','staff');
		        }
		    });
		});

		//search by
		$.xhrPool = [];
		$.xhrPool.abortAll = function() {
		    $(this).each(function(idx, jqXHR) {
		        jqXHR.abort();
		    });
		    $(this).each(function(idx, jqXHR) {
		        var index = $.inArray(jqXHR, $.xhrPool);
		        if (index > -1) {
		            $.xhrPool.splice(index, 1);
		        }
		    });
		};
		$.ajaxSetup({
		    beforeSend: function(jqXHR) {
		        $.xhrPool.push(jqXHR);
		    },
		    complete: function(jqXHR) {
		        var index = $.inArray(jqXHR, $.xhrPool);
		        if (index > -1) {
		            $.xhrPool.splice(index, 1);
		        }
		    }
		});
		$input = $('#contact-table .profilesearch input#profile-search');
		$input.bindWithDelay("keyup", function(){
			$search = $(this).val();
			$len = $search.length;
			if($len > 3){
				$('#contactRender').fadeOut(200,function(){
					$(this).empty();
					$('#loadingstyle').remove();
				});
				$('#loader-gif').addClass('active');
				 $.ajax({
			        type: 'POST',
			        url: '/wp-content/themes/EssentialHospitals/membernetwork/contactquery.php',
			        data: {csearch : $search, csort : 'search'},
			        success: function(msg) {
			        	$('#allContacts').removeClass().addClass('search-func');
			            $('#contactRender').html(msg).fadeIn(200);
			            $('#loader-gif').removeClass('active');
			            $('#queryholder').attr('data-sortby','search');
			        }
			    });
		    }
		}, 200);

		//Infinite Scroll
		function isScrolledIntoView(elem){
		    var docViewTop = $(window).scrollTop();
		    var docViewBottom = docViewTop + $(window).height();

		    var elemTop = $(elem).offset().top;
		    var elemBottom = elemTop + $(elem).height();

		    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
		}
		$('#infinitescroll #loadmore:not(.loading)').click(function(){
			infinitescroll();
		});
		function infinitescroll(){
			//if($('#infinitescroll').length > 0){
			//	scrollcheck = isScrolledIntoView('#infinitescroll');
			//}
			//if(scrollcheck){
				//Unbind the scroll event
				//$(window).unbind('scroll');
				$('#loadmore').addClass('loading');
				$offset = $('#queryholder').attr('data-offset');
				$search = $('#queryholder').attr('data-search');
				$sortby = $('#queryholder').attr('data-sortby');
				$action = $('#queryholder').attr('data-action');
				$page = $('#queryholder').attr('data-page');
					$page = parseInt($page);
					$page++;
				$.ajax({
			        type: 'POST',
			        url: '/wp-content/themes/EssentialHospitals/membernetwork/contactquery.php',
			        data: {coffset : $offset, cpage : $page, csort : $sortby, caction : $action},
			        success: function(msg) {
			        	console.log(msg);
			            $(msg).appendTo('#contactRender');
			            $('#queryholder').attr('data-search',$search).attr('data-offset',$offset).attr('data-sortby',$sortby).attr('data-page',$page);
			            //Rebind the scroll event
			            setTimeout(function(){
			            	//$(window).bind('scroll',function(){infinitescroll();});
			            }, 200);
						//removeload
						$('#loadmore').removeClass('loading');
					}
				});
			//}
		}
			//Check if trigger is in view
			//$(window).bind('scroll',function(){infinitescroll();});

		//Reset Filters
		$('.styled-reset #reset-btn').click(function(){
			$sortby = '';
			$offset = 20;
			$search = '';
			$page = 0;
			$('#contactRender').fadeOut(200,function(){
				$(this).empty();
				$('#loadingstyle').remove();
			});
			$('#loader-gif').addClass('active');
			 $.ajax({
		        type: 'POST',
		        url: '/wp-content/themes/EssentialHospitals/membernetwork/contactquery.php',
		        data: {coffset : $offset, csearch : $search, csort : $sortby, caction : 'reset'},
		        success: function(msg) {
		        	$('#allContacts').removeClass();
		        	//$('#infinitescroll').remove();
		            $('#contactRender').html(msg).fadeIn(200);
		            $('#loader-gif').removeClass('active');
		            $('#queryholder').attr('data-search',$search).attr('data-offset',$offset).attr('data-sortby',$sortby).attr('data-page',$page);
		        }
		    });
		});




		//----- Add Contact
		$(document).on('click','.add-button.contact-add',function(){
			$elem = $(this);
			$uid = $(this).attr('data-uid');
			$curid = $(this).attr('data-curid');
			$.ajax({
		        type: 'POST',
		        url: '/wp-content/themes/EssentialHospitals/membernetwork/contactprocquery.php',
		        data: {curid : $curid , uid : $uid , action : 'add'},
		        success: function(msg) {
					$elem.removeClass('add-button').addClass('added-button').text(msg);
					$('#pending-contacts').empty().load('/wp-content/themes/EssentialHospitals/membernetwork/contactpendingquery.php');
		        }
		    });
		});

		//----- Approve Contact

		$(document).on('click','.appdeny .approve',function(){
			$uid = $(this).attr('data-uid');
			$curid = $(this).attr('data-curid');
			$.ajax({
		        type: 'POST',
		        url: '/wp-content/themes/EssentialHospitals/membernetwork/contactprocquery.php',
		        data: {curid : $curid , uid : $uid , action : 'approve'},
		        success: function(msg) {
					$('#request-contacts').empty().load('/wp-content/themes/EssentialHospitals/membernetwork/contactrequestquery.php');
					$('#MyConnections #my-contacts').empty().load('/wp-content/themes/EssentialHospitals/membernetwork/contactmyquery.php');
		        }
		    });
		});

		//----- Deny Contact
		$(document).on('click','.appdeny .deny',function(){
			$uid = $(this).attr('data-uid');
			$curid = $(this).attr('data-curid');
			$.ajax({
		        type: 'POST',
		        url: '/wp-content/themes/EssentialHospitals/membernetwork/contactprocquery.php',
		        data: {curid : $curid , uid : $uid , action : 'deny'},
		        success: function(msg) {
					$('#request-contacts').empty().load('/wp-content/themes/EssentialHospitals/membernetwork/contactrequestquery.php');
					$('#MyConnections #my-contacts').empty().load('/wp-content/themes/EssentialHospitals/membernetwork/contactmyquery.php');
		        }
		    });
		});
});

//Bind with delay - for keyup on AJAX input fields
(function($) {
$.fn.bindWithDelay = function( type, data, fn, timeout, throttle ) {
    if ( $.isFunction( data ) ) {
        throttle = timeout;
        timeout = fn;
        fn = data;
        data = undefined;
    }
    // Allow delayed function to be removed with fn in unbind function
    fn.guid = fn.guid || ($.guid && $.guid++);
    // Bind each separately so that each element has its own delay
    return this.each(function() {
        var wait = null;
        function cb() {
            var e = $.extend(true, { }, arguments[0]);
            var ctx = this;
            var throttler = function() {
                wait = null;
                fn.apply(ctx, [e]);
            };
            if (!throttle) { clearTimeout(wait); wait = null; }
            if (!wait) { wait = setTimeout(throttler, timeout); }
        }
        cb.guid = fn.guid;
        $(this).bind(type, data, cb);
    });
};
})(jQuery);