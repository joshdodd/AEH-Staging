<?php
function email_header($color, $subHead, $emailType){
if($emailType == 'action'){
		//Action email query
		$leftHead = 'http://essentialhospitals.org/wp-content/uploads/2014/05/email-header-action.png';
		$color = '#F05135';
		$icon = 'http://essentialhospitals.org/wp-content/uploads/2014/05/email-header-bar-action.png';
	}elseif($emailType == 'quality'){
		//Quality email query
		$leftHead = 'http://essentialhospitals.org/wp-content/uploads/2014/05/email-header-quality.png';
		$color = '#28BDB3';
		$icon = 'http://essentialhospitals.org/wp-content/uploads/2014/05/email-header-bar-quality.png';
	}elseif($emailType == 'institute'){
		//Institute email query
		$leftHead = 'http://essentialhospitals.org/wp-content/uploads/2014/08/institute-header-logo.png';
		$color = '#00AEEF';
		$icon = 'http://essentialhospitals.org/wp-content/uploads/2014/08/institute-bar-384.png';
	}elseif($emailType == 'education'){
		//Education email query - webinars
		$leftHead = 'http://essentialhospitals.org/wp-content/uploads/2014/05/email-header-education.png';
		$color = '#565656';
		$icon = 'http://essentialhospitals.org/wp-content/uploads/2014/05/email-header-bar-education.png';
	}elseif($emailType == 'ehen'){
		//EHEN email query
		$leftHead = 'http://essentialhospitals.org/wp-content/uploads/2014/08/institute-header-logo.png';
		$color = '#00AEEF';
		$icon = 'http://essentialhospitals.org/wp-content/uploads/2014/08/institute-bar-384.png';
	}elseif($emailType == 'full'){
		//Full email query
		$leftHead = 'http://essentialhospitals.org/wp-content/uploads/2014/05/email-header-bestof.png';
		$color = '#f05135';
		$icon = 'http://essentialhospitals.org/wp-content/uploads/2014/05/email-header-bar-bestof.png';
	}else{
		return false;
	}

$output = "<body topmargin='0' leftmargin='0' rightmargin='0'>
<div>
<div style='width: 100%; margin: auto auto;'>
<table style='background-color: #d6d6d6;' bgcolor='#d6d6d6' border='0' width='100%' cellspacing='0' cellpadding='0'>
<tbody>
<tr>
<td style='padding: 14px 14px 14px 14px;' valign='top'>
	<table style='margin: auto auto; width: 600px;' border='0' width='600' cellspacing='0' cellpadding='0'>
	<tbody>
	<tr>
	<td style='background-color: #5a5a5a; padding: 1px 1px 1px 1px;' valign='top'>
		<table style='background-color: #ffffff;' bgcolor='#ffffff' border='0' width='100%' cellspacing='0' cellpadding='0'>
		<tbody>
		<tr>
		<td valign='top'>
<!-- HEADER SECTION -->
			<table width='100%' cellspacing='0' cellpadding='0'>
			<tbody>
			<tr>
			<td width='213' valign='top' rowspan='2'><img width='213' height='92' src='$leftHead' border='0'></td><td height='26' style='margin: 0px; text-align: right; max-height: 26px;'><img width='385' height='26' src='$icon' border='0'></td>
			</tr>
			<tr>
			<td align='right' valign='top' style='margin: 0px; padding: 8px 12px 28px 10px; text-align: right; vertical-align: top;'><span style='color: rgb(86, 86, 86); font-family: Georgia, Times, serif; font-size: 30px; font-style: italic;'>$subHead</span></td>
			</tr>
			</tbody>
			</table>";
		return $output;
} ?>