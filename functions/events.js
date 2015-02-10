jQuery(document).ready(function($){
	console.log(AEH);
	// Events Masonry
	var api = $('#fader').data('scrollable');


	// events-time
	$(document).on('click','#timeFilter-events .timeButton-events',function(){
		// get vars
		var monthFilter = $('#monthFilter option:selected').val();
		var sectionFilter = $('.filter_btn-events.active').attr('data-filter');
		var timeFilter = $(this).attr('data-filter');
		// set for undefined
		if(monthFilter == undefined){
			monthFilter = '*';
		}
		if(sectionFilter == undefined){
			sectionFilter = '*';
		}
		// set active
		$('#timeFilter-events .timeButton-events').removeClass('active');
		$(this).addClass('active');
		// scrollable go back
		$('#postBox .post').addClass('close');
		api.seekTo(0,1);
		$('#loader-gif').addClass('active');
		var request = $.ajax({
			url: AEH.ajaxurl,
			type: "POST",
			data: {
				action : 'getevents',
				month  : monthFilter,
				section: sectionFilter,
				time   : timeFilter,
			},
		});
		request.done(function(response){
			 
			setTimeout(function(){
				$('#loader-gif').removeClass('active');
				$('#postBox #fader .item').remove();
				$('#postBox #fader .items').html(response);
				$('#postBox .post.close').removeClass('close');
				masonryslides();
			},300);
		});
		request.fail(function(jqXHR,textStatus){
			console.log(textStatus);
		});
	});

	//events-month
	$(document).on('change','#monthFilter',function(){
		// get vars
		var monthFilter = $('#monthFilter option:selected').val();
		console.log(monthFilter);
		var sectionFilter = $('.filter_btn-events.active').attr('data-filter');
		var timeFilter = $('#timeFilter-events .timeButton-events.active').attr('data-filter');
		// set for undefined
		if(timeFilter == undefined){
			timeFilter = '*';
		}
		if(sectionFilter == undefined){
			sectionFilter = '*';
		}
		// set active
		$(this).addClass('active');
		// scrollable go back
		$('#postBox .post').addClass('close');
		api.seekTo(0,1);
		$('#loader-gif').addClass('active');
		var request = $.ajax({
			url: AEH.ajaxurl,
			type: "POST",
			data: {
				action : 'getevents',
				month  : monthFilter,
				section: sectionFilter,
				time   : timeFilter,
			},
		});
		request.done(function(response){
			//console.log(response);
			setTimeout(function(){
				$('#loader-gif').removeClass('active');
				$('#postBox #fader .item').remove();
				$('#postBox #fader .items').html(response);
				masonryslides();
				$('#postBox .post.close').removeClass('close');
			},300);
		});
		request.fail(function(jqXHR,textStatus){
			console.log(textStatus);
		});
	});


	//events-section
	$(document).on('click','.filter_btn-events',function(){
		// get vars
		var monthFilter = $('#monthFilter option:selected').val();
		var sectionFilter = $(this).attr('data-filter');
		var timeFilter = $('#timeFilter-events .timeButton-events.active').attr('data-filter');
		// set for undefined
		if(timeFilter == undefined){
			timeFilter = '*';
		}
		if(monthFilter == undefined){
			monthFilter = '*';
		}
		// set active
		$('.filter_btn-events').removeClass('active');
		$(this).addClass('active');
		// scrollable go back
		$('#postBox .post').addClass('close');
		api.seekTo(0,1);
		$('#loader-gif').addClass('active');
		var request = $.ajax({
			url: AEH.ajaxurl,
			type: "POST",
			data: {
				action : 'getevents',
				month  : monthFilter,
				section: sectionFilter,
				time   : timeFilter,
			},
		});
		request.done(function(response){
			setTimeout(function(){
				$('#loader-gif').removeClass('active');
				$('#postBox #fader .item').remove();
				$('#postBox #fader .items').html(response);
				masonryslides();
				$('#postBox .post.close').removeClass('close');
			},300);
		});
		request.fail(function(jqXHR,textStatus){
			console.log(textStatus);
		});
	});


	// events search
	$(document).on('submit', '#eventsSearch',function(e){
		e.preventDefault();
		var search = $('#eventsSearch input[type="text"]#esearch').val();
		$('#timeFilter-events .timeButton-events').removeClass('active');
		$('.filter_btn-events').removeClass('active');
		// scrollable go back
		$('#postBox .post').addClass('close');
		api.seekTo(0,1);
		$('#loader-gif').addClass('active');
		var request = $.ajax({
			url: AEH.ajaxurl,
			type: "POST",
			data: {
				action : 'searchevents',
				search : search,
			},
		});
		request.done(function(response){
			setTimeout(function(){
				$('#loader-gif').removeClass('active');
				$('#postBox #fader .item').remove();
				$('#postBox #fader .items').html(response);
				masonryslides();
				$('#postBox .post.close').removeClass('close');
			},300);
		});
		request.fail(function(jqXHR,textStatus){
			console.log(textStatus);
		});
	});


	// presentation search
	$(document).on('submit','#presentationSearch',function(e){
		e.preventDefault();
		var search = $('#presentationSearch input[type="text"]#psearch').val();
		// scrollable go back
		$('#postBox .post').addClass('close');
		api.seekTo(0,1);
		$('#loader-gif').addClass('active');
		var request = $.ajax({
			url: AEH.ajaxurl,
			type: "POST",
			data: {
				action : 'getpresentations',
				search : search,
			},
		});
		request.done(function(response){
			setTimeout(function(){
				$('#loader-gif').removeClass('active');
				$('#postBox #fader .item').remove();
				$('#postBox #fader .items').html(response);
				masonryslides();
				$('#postBox .post.close').removeClass('close');
			},300);
		});
		request.fail(function(jqXHR,textStatus){
			console.log(textStatus);
		});
	});



	// masonry and slides
	function masonryslides(){
		$masonrycont = $('#fader .items .item');
		$masonrycont.masonry({
			columnWidth: 280,
			itemSelector: '.post',
			stamp:		'.stamp',
		});
		var msnry = $masonrycont.data('masonry');
	}
});
