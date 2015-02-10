<?php function email_footer($color){
$output = "<table style='background: transparent;' border='0' width='100%' cellspacing='0' cellpadding='0'>
			<tbody>
			<tr>
			<td><div style='font-size: 5pt; padding: 0px 12px 0px 12px;' valign='top' width='100%'>&nbsp;</div></td>
			</tr>
			</tbody>
			</table>
			<table width='100%' cellpadding='0' cellspacing='0'>
			<tbody>
			<tr>
			<td valign='top' bgcolor='$color' style='padding-top: 12px; padding-bottom: 10px; padding-left: 5px; background-color: $color;'><a href='http://www.facebook.com/essentialhospitals' title='Like our Facebook page' target='_blank'><img src='http://essentialhospitals.org/wp-content/uploads/2014/06/facebook-icon-email.png' width='16' height='14' border='0' /></a><a href='http://twitter.com/ourhospitals' title='Follow us on Twitter' target='_blank'><img src='http://essentialhospitals.org/wp-content/uploads/2014/06/twitter-icon-email.png' width='16' height='14' border='0' hspace='3' /></a><a href='mailto:info@essentialhospitals.org'><img src='http://essentialhospitals.org/wp-content/uploads/2014/06/email-icon-email.png' width='16' height='14' border='0' hspace='2' /></a><a href='http://essentialhospitals.org/feed/?post_type=policy' title='Subscribe to our RSS feed' target='_blank'><img src='http://essentialhospitals.org/wp-content/uploads/2014/06/rss-icon-email.png' width='16' height='14' border='0' hspace='2' /></a></td>
			<td valign='top' bgcolor='$color' style='background-color: $color; padding-top: 12px; padding-right: 10px; padding-bottom: 10px; padding-left: 20px; text-align: right;'><span style='font-family: Georgia, Times, serif; font-size: 8pt; color: #FFFFFF; line-height: 150%;'>1301 Pennsylvania Ave NW, Suite 950  Washington, D.C. 20004<br>202.585.0100  ||  essentialhospitals.org</span>
			</td><td valign='middle' bgcolor='$color' style='background-color: $color; padding-top: 12px; padding-bottom: 10px;'><a href='http://www.essentialhospitals.org/' title='Visit EssentialHospitals.org' target='_blank'><img src='http://essentialhospitals.org/wp-content/uploads/2014/06/diamond-icon-email.png' width='29' height='30' border='0' hspace='5' /></a></td>
			</tr>
			</tbody>
			</table>
		</td>
		</tr>
		</tbody>
		</table>
	</td>
	</tr>
	</tbody>
	</table>
</td>
</tr>
</tbody>
</table>";
return $output; }
?>