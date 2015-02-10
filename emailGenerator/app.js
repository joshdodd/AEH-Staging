//jQuery UI
$("input#application-form-startdate, input#application-form-enddate").datepicker();
$('#sortable').sortable();
$('#sortable').disableSelection();

//Application
// prepare the form when the DOM is ready
$(document).ready(function() {
    var options = {
        target:        '#application-preview',
        beforeSubmit:  showRequest,
        success:       showResponse
    };
    // bind to the form's submit event
    $('#emailGenerator').submit(function() {
        $(this).ajaxSubmit(options);
        return false;
    });
    //remove article
    $(document).on('click','.deleteme',function(e){
    	$elem = $(this).parent();
    	$elem.fadeOut(200,function(){$elem.remove();});
    });

    //Editable fields
    $(document).on('click','.title-edit',function(e){
    	$('#editorDiv').remove();
    	$('*').removeClass('hidden');
    	$val = $(this).text();
    	$(this).addClass('hidden');
    	$('<div id="editorDiv"><input id="editorField" type="text" value="'+$val+'" /><button id="editorCancel">Cancel</button><button id="editorSave">Save</button></div>').insertAfter(this);
    });
    $(document).on('click','.excerpt-edit',function(e){
		$('#editorDiv').remove();
    	$('*').removeClass('hidden');
    	$val = $(this).text();
    	$(this).addClass('hidden');
    	$('<div id="editorDiv"><textarea id="editorField" value="'+$val+'" >'+$val+'</textarea><button id="editorCancel">Cancel</button><button id="editorSave">Save</button></div>').insertAfter(this);
    });

	//save fields
    $(document).on('click','#editorDiv #editorSave',function(e){
    	$elem = $(this).parent().prev();
    	$val = $('#editorField').val();
    	$elem.text($val).removeClass('hidden');
    	$(this).parent().remove();
    	$appRaw = $('#application-preview').html();
		$appRaw = '<html><body>'+$appRaw+'</body></html>';
		$('#application-raw').text($appRaw);
		$('#application-raw-copy').attr('data-clipboard-text',$appRaw);
    });
    $(document).on('click','#editorDiv #editorCancel',function(e){
		$elem = $(this).parent().prev();
    	$val = $('#editorField').val();
    	$elem.removeClass('hidden');
    	$(this).parent().remove();
    });


    //send ajax to get back posts based on what's been sorted
    $(document).on('click','#email-gen',function(e){
    	$arr = [];
    	$addir = $('#sortable #ad-dir').text();
    	$adloc = $('#sortable #ad-loc').text();
    	$adintro = $('#sortable #ad-intro').text();
    	$colorHead = $('#sortable #color-head').text();
    	$emailType = $('#sortable #email-type').text();
	    $('ul#sortable li').each(function(){
		    $arr.push($(this).attr('data-id'));
	    });
	    //Generate Email
	    var templateDir = 'http://essentialhospitals.org/wp-content/themes/EssentialHospitals';
		$ajaxurl = templateDir+'/emailGenerator/generate.php';
	    $.ajax({
	        url: $ajaxurl,
	        data: {
	            'adDir' : $addir,
	            'adLoc' : $adloc,
	            'ids'   : $arr,
	            'emailType' : $emailType,
	            'colorHead' : $colorHead,
	            'intro' : $adintro
	        },
	        success:function(data) {
	            $('#application-preview-loader').removeClass('active');
				$('#application-preview').html(data).fadeIn(300);

				$appRaw = $('#application-preview').html();
				$appRaw = '<html><body>'+$appRaw+'</body></html>';
				$('#application-raw').text($appRaw);
				$('#application-raw-copy').attr('data-clipboard-text',$appRaw);
	        },
	        error: function(errorThrown){
	            console.log(errorThrown);
	        }
	    });
    });
});
// pre-submit callback
function showRequest(formData, jqForm, options) {
    var queryString = $.param(formData);
    $('#application-sort-loader').addClass('active');
	$('#application-preview').fadeOut(300,function(){
		$(this).html("");
	});
    return true;
}
// post-submit callback
function showResponse(responseText, statusText, xhr, $form)  {
    $('#application-sort-loader').removeClass('active');
	$('#application-sort-window ul#sortable').html(responseText);
	$appRaw = $('#application-preview').html();
	$appRaw = '<html><body>'+$appRaw+'</body></html>';
	//console.log($appRaw);
	//$('#application-raw').text($appRaw);
	//$('#application-raw-copy').attr('data-clipboard-text',$appRaw);
}

//

//Clipboard
$('#application-raw-copy').clipboard({
        path: 'http://mlinson.staging.wpengine.com/wp-content/themes/EssentialHospitals/emailGenerator/jquery.clipboard.swf',
        copy: function() {
            return $('#application-raw').text();
        }
});
$('#application-raw-copy').click(function(){
	alert('Email copied to your clipboard. Head to Constant Contact to drop it in!');
});