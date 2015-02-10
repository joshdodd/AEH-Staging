<?php /* Template Name: test stuff */ get_header(); ?>


<div class="container">
	<p></p>
	<p></p>
<p></p>
v
<p></p>
<p></p>
v
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
	<?php
		//Attempt to create iMIS record
		$imis_id = 'johndoe11@meshfresh.com';

			//Create didn't fail - set iMIS id and update iMIS record with WP meta
			//update_user_meta($user_id,"aeh_imis_id",$imis_id);
			//sleep(3);
			$newusercheck = does_imis_user_exist($imis_id);
			var_dump($newusercheck);
			$addressNum = $newusercheck['addressnum'];
	?>
</div>

<?php get_footer(); ?>