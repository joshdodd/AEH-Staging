jQuery(window).ready(function($){
	if($('#post_status').val() == 'publish'){
		$('#post_status').val("pending");
		$('a.save-post-status').click();
		$("input#publish").val('Submit for Review');
		$('#preview-action').remove();
	}
});