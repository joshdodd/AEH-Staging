<?php 
/*
Template Name: Member Network - News Feed
*/

if (is_user_logged_in()){
get_header();
include ("includes/aeh_config.php"); 
include ("includes/aeh-functions.php");
$metakey 	 = "custom_news_feed";
$usermeta    = get_user_meta($userID, $metakey, TRUE);
if ($usermeta !=""){$customnewsfeed = unserialize($usermeta);}

?>
<div id="membernetwork">
	<div class="container">
		<h1 class="title"><span class="grey">Essential Hospitals</span> Member Network | My News Feed</h1>
		
<?php

	get_template_part('membernetwork/content','usernav');

			/* If the POST variable 'catcount' is set then presume a user submitted the form to make changes */
			$catcount = 0; $values = array();
			if (isset($_POST['catcount'])){
				$catcount = $_POST['catcount'];
				$n = 1;
				while ($n <= $catcount){
					if (isset($_POST["option$n"])){$values[$n] = $_POST["option$n"];}else{$values[$n] = 0;}
					$n++;
				}
			update_user_meta($userID, $metakey, serialize($values));
			$usermeta  = get_user_meta($userID, $metakey, TRUE);
			if ($usermeta !=""){$customnewsfeed = unserialize($usermeta);}
			}
?>
	<div id="connectioncontent" class="group">
		<div class="gutter clearfix">
			<h2 class='heading'>My Custom News Feed</h1>
				<p>Customize your news feed by selecting categories you would like to receive articles on</p> 
	
				<form action="#list" method="POST" name="customnews" />
<?php
			/* Create a WP categories object so we can display all categories */
			$args=array(
				'orderby' => 'name',
				'hide_empty' => 0,
				'order' => 'ASC'
			);
			$categories = get_categories($args);
			
			$n=1; 
				foreach($categories as $category){
					if ($shade == "eee"){$shade = "ddd";}else{$shade = 'eee';}
					
					$catval = $category->cat_ID;
					/* if no usermeta then make checkbox unchecked otherwise set its state accordingly */
					$ckval = "";
					if ($usermeta != ""){if ($customnewsfeed[$n])$ckval = " checked";}
					$shadesel = $shade;
					if ($ckval)$shadesel{1}='f';
					echo "
					<div style='width:100%;height:63px;padding:2px 0px 3px;margin:0px;background-color:#$shadesel'>";
						echo "
						<div style='margin:8px 10px 0px;height:20px;width:20px;float:left;background-color:#$shadesel'>
						<input style='margin:5px;padding:5px;' type='checkbox' name='option$n' value='$catval'$ckval />
						</div>";
						echo "
						<div style='float:left;width:80%;background-color:#$shadesel'>";
						echo '
						<a href="' . get_category_link($category->term_id) . '" title="' . sprintf( __("View all posts in %s"), $category->name) . '" ' . '><h4 style="margin:0;padding:0">' . $category->name.'</h4></a>';
						echo $category->description . "
						</div>";
					echo '
					</div><div style="margin:2px;clear:both"></div>';
					$n++;
				}
			$n--;
			echo "<input type='hidden' name='catcount' value='$n' />";
?>
			<span style="padding:8px 10px 0 0;margin:5px 0 0"><a href="javascript:void();" onclick="javascript:checkAll('customnews', true);">Check All</a></span>
			<span style="padding:3px 10px 0 0"><a href="javascript:void();" onclick="javascript:checkAll('customnews', false);">Uncheck All</a><br /></span><br />
			<input type="submit" value="Save" />
		</form>
		
		</div>
	</div>
<?php
			}else{
				header('Location: http://meshdevsite.com/membercenter/member-login/');
		}
?>

	</div>
</div>
	
<?php get_footer('sans'); ?>