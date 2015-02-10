jQuery(document).ready(function($){
	privToAscOnly();
	$('#submitpost a.save-post-visibility').click(function(){
		setTimeout(function(){
			$it = $('span#post-visibility-display').text().replace('Private','Association Members Only');
			$('span#post-visibility-display').text($it);
			//console.log($it);
		}, 0);
	});

	function privToAscOnly(){
		$('#submitpost label,span,li a').filter(":contains('Private')").each(function(){
			$(this).text($(this).text().replace('Private', 'Association Members Only'));
		});
	}

	$('a#content-html,a.switch-html').text('HTML');


	//Bio Limit
	$descField = $('.profile-php textarea#description');



	if($descField.length > 0){
		$('<span class="descLimit"><em>Limit your biography to 140 characters or less</em>  |  Number of Characters: <span id="numWords"></span><br></span>').insertBefore($descField.siblings('.description'));
		var str = $descField.val();
		count = str.length;
		$('#numWords').html(count);
		$descField.keyup(function(){
			var str = $descField.val();
			var count = str.length;

			if(count > 140){
				str = str.substring(0, 140);
				$descField.val(str);
				colorChange(count,'#numWords');
			}

			var str = $descField.val();
			count = str.length;
			$('#numWords').html(count);
		});
    }


	/*$('form#your-profile').submit(function(e){
		$val = $('select#hospital_name').val();
		if($val == ''){
			alert('Please choose a Company/Organization');
			return false;
		}
	});*/



});