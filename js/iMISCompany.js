jQuery(document).ready(function($){
	//Hackery
	$('label:contains(Company ID)').parent().parent().addClass('hiddenVal');
	$('label:contains(Company Sort)').parent().parent().addClass('hiddenVal');

	//Copy data to select lists
	$('select#hospital_name').html($('#company_list').html());
	$('select#CO_ID').html($('#company_id').html());
	$('select#COMPANY_SORT').html($('#company_sort').html());

	//Set current dropdown
	$val = $('#company_current').text();
	$('select#hospital_name').val($val);
	$index = $("select#hospital_name option:selected").index();
	$index=$index-1;
	$('select#CO_ID').prop('selectedIndex', $index);
	$('select#COMPANY_SORT').prop('selectedIndex', $index);

	//Change function
	$('select#hospital_name').change(function(){
		$index = $("select#hospital_name option:selected").index();
		$index=$index-1;
		$('select#CO_ID').prop('selectedIndex', $index);
		$('select#COMPANY_SORT').prop('selectedIndex', $index);
	});

	//Edit field
	$('.form-table select#hospital_name').change(function(){
		$index = $(".form-table select#hospital_name option:selected").index();
		$index=$index-1;
		$('.form-table select#CO_ID').prop('selectedIndex', $index);
		$('.form-table select#COMPANY_SORT').prop('selectedIndex', $index);

		$('.form-table input[name="street_address"]').val($('select[name="ISFcompany_address"] option:eq('+$index+')').val());
		$('.form-table input[name="city"]').val($('select[name="ISFcompany_city"] option:eq('+$index+')').val());
		$state = $('select[name="ISFcompany_state"] option:eq('+$index+')').val();
		$('.form-table select[name="state"] option').removeAttr('selected').filter('[value='+$state+']').attr('selected', true);
		$('.form-table input[name="zip_code"]').val($('select[name="ISFcompany_zip"] option:eq('+$index+')').val());
		$('.form-table input[name="phone"]').val($('select[name="ISFcompany_workphone"] option:eq('+$index+')').val());
		$('.form-table input[name="fax"]').val($('select[name="ISFcompany_fax"] option:eq('+$index+')').val());
	});
});