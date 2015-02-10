<h2 class='heading'>Contacts</h2>
                <div class='gutter clearfix'>
                	<?php $members = output_connections("",$userID,'friends',8);
						if ($members!=""){ $blocks++; ?>
                    <div class="myfriends">
                        <div class="pendingnotify"></div>
                        <div class="membercontent">
                            <?php echo $members; ?>
                        </div>
                    </div>
                    <?php }else{
	                    echo '<p>No contacts yet - <a href='.get_permalink(278).'>meet your fellow members</a>.</p>';
                    } ?>
                </div>