<div id="myprofilecontent" class="group">
                    <div class="gutter clearfix">
                        <div id="my-profile-custom">
                            <div id="profile-left">
                                <div id="profile-avatar">
                                    <?php get_template_part( 'membernetwork/module', 'uploadavatar' );
$gravatar   = get_avatar($userID, 184);
?><?php
if($aeh_staff == 'Y'){
	echo '<div class="hospMem"></div>';
} echo $gravatar; ?>
                                </div>
                                <ul id="profile-mod">
                                    <li class="editprofile"><a href="?a=edit">Edit Profile</a></li>
                                    <li class="changepass"><a href="?a=pwdchange">Change Password</a></li>
                                    <li class="changeavatar"><a class="edit-avatar">Upload/Edit Profile Image</a></li>
                                </ul>
                            </div>
                            <div id="profile-right">
                                <h2 id="profile-name"><?php echo "$firstname $lastname"; ?></h2>
                                <span class="profile-position"><?php echo $jobtitle; ?></span>
                                <span class="profile-position"><?php echo $hospital_name; ?></span>
                                <span class="profile-employer"><?php echo $employer; ?></span>


                                <?php if ($twitter) { ?>
                                	<span class="profile-twitter"><span class="pre">twitter</span><a href="http://www.twitter.com/<?php echo $twitter; ?>">@<?php echo $twitter; ?></a></span>
                                <?php } ?>
                                <?php if ($facebook) { ?>
                                	<span class="profile-facebook"><span class="pre">facebook</span><a href="<?php echo $facebook; ?>">facebook</a></span> <?php } ?>
                                	<?php if ($linkedin) { ?> <span class="profile-linkedin"><span class="pre">linkedin</span><a href="<?php echo $linkedin; ?>">linkedin</a></span>
                                	<?php } ?>
                                <div class="profile-interests">
                                    <?php if($newsinterest){ ?><span class="pre">Interested in:</span> <?php foreach($newsinterest as $news){
		$result = $wpdb->get_row("SELECT `name` FROM `wp_terms` WHERE `slug` = '$news'");
?>
                                    <div class="interest">
                                        <?php echo $result->name; ?>
                                    </div><?php } } ?>
                                </div><?php if($description){ ?>
                                <div class="profile-description">
                                    <span class="pre">About:</span>
                                    <div class="interest">
                                        <?php echo $description; ?>
                                    </div>
                                </div><?php } ?>
                            </div>
                        </div><?php if ($aeh_staff=="Y")$output .=  "<div id='profile-staff'>Staff Member</div>";
echo $output; ?>
                        <div id="my-profile-wpm">
                            <?php the_post(); the_content(); ?>
                        </div>
                    </div>
                </div>