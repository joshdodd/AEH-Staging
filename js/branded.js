jQuery(document).ready(function($){
	$('#brand-scrollable').smoothDivScroll({
		manualContinuousScrolling: false,
		/*scrolledToElementId: function($id) {
			$width = ($(window).width() - 754) / 2 ;
			$offset = $('#brand-scrollable').smoothDivScroll('getScrollerOffset');
			$('#brand-scrollable').smoothDivScroll("move", -$width);
			console.log($width);
			console.log($offset);
		}*/
		windowResized: function(){
			$width = ($(window).width() - 754) / 2 ;
			$('#rightSpacer, #leftSpacer').width($width);
			//$("#brand-scrollable").smoothDivScroll("recalculateScrollableArea");
		}
	});

	$('#brand-scrollable .scrollableArea').append('<div id="rightSpacer"></div>').prepend('<div id="leftSpacer"></div>');
	$width = ($(window).width() - 754) / 2 ;
	$('#rightSpacer, #leftSpacer').width($width);
	$("#brand-scrollable").smoothDivScroll("recalculateScrollableArea");


	$('#brand-nav .brand-nav-entry').click(function(){
		$id = $(this).attr('id');
		$id = $id.substr(4);
		$ind = $(this).index();
		$pos = $('.brand-focus-entry#'+$id).position();
		$width = ($(window).width() - 754) / 2 ;
		$scrollTo = $pos.left;
		$("#brand-scrollable").smoothDivScroll("scrollToElement", "id", $id);
		$("html, body").animate({ scrollTop: 100 }, 600);
	});


	$loc = GetURLParameter('loc');
	if($loc){
		$('#brand-scrollable').smoothDivScroll('scrollToElement','id','entry-'+$loc);
	}

	function GetURLParameter(sParam){
	    var sPageURL = window.location.search.substring(1);
	    var sURLVariables = sPageURL.split('&');
	    for (var i = 0; i < sURLVariables.length; i++){
	        var sParameterName = sURLVariables[i].split('=');
	        if (sParameterName[0] == sParam){ return sParameterName[1]; }
	    } }

	$('.brand-focus-info .readmore').click(function(){
		$elem = $(this).prev().prev();
		$(this).remove();
		$elem.fadeOut(200,function(){
			$elem.removeClass('active');
			$elem.next('.legacy-expand').fadeIn(200).addClass('active');
		});
	});
});