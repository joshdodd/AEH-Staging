<?php $queryObject = get_queried_object();
	$user = get_userdata($queryObject->ID);
	$authAva = get_avatar($queryObject->ID, 160);
	$userPosition = get_user_meta($queryObject->ID, 'job_title', true);
	$userEmployer = get_user_meta($queryObject->ID, 'employer', true);
?>
<div id="postBox" class="clearfix">
	<div id="fader" class="clearfix scrollable">
	<div id="loader-gif"> Loading more posts</div>
			<div class="items">
			<div class="post long columns redd policy bio">
				<div id="author-article-info">
					<div class="gutter clearfix">
						<div class="author-article-ava">
							<a href="<?php echo get_permalink(276); ?>?member=<?php echo $user->ID; ?>"><?php echo $authAva; ?></a>
						</div>
						<div class="author-article-data">
							<h2 id="profile-name"><?php echo $user->first_name.' '.$user->last_name; ?></h2>
							<?php if($userPosition){ ?>
								<span class="profile-position"><?php echo $userPosition; ?></span>
							<?php } ?>
							<?php if($userEmployer){ ?>
								<span class="profile-employer"><?php echo $userEmployer; ?></span>
							<?php } ?>
							<span class="proflie-about"><?php echo $user->description; ?></span>
						</div>
					</div>
				</div>
	    		<div class="bot-border"></div>
	  		</div>

			<?php
				$args = array(
					'post_type' => array('policy','quality','institute'),
					'author' => $queryObject->ID
				);
				$query = new WP_Query($args);
			if ( $query->have_posts() ) while ( $query->have_posts() ) : $query->the_post();
				$postType = get_post_type( get_the_ID() );

				//check post type and apply a color
				if($postType == 'policy'){
					$postColor = 'redd';
				}else if($postType == 'quality'){
					$postColor = 'greenn';
				}else if($postType == 'education'){
					$postColor = 'grayy';
				}else if($postType == 'institute'){
					$postColor = 'bluee';
				}else{
					$postColor = 'bluee';
				}
			?>
			<div class="post long columns <?php echo $postColor; ?>  <?php echo $postType; ?> ">
				<div class="graybarright"></div>
	  			<div class="item-bar"></div>
    			<div class="item-icon">
    				<?php $terms = wp_get_post_terms(get_the_ID(), 'series');
    					if($terms){
	    					$termLink = get_term_link($terms[0], 'series');
		    				echo "<a href='".$termLink."'>".$terms[0]->name."</a>";
	    				}
    				?>
					<?php if($postType != 'post'){ ?>
	    				<img src="<?php bloginfo('template_directory'); ?>/images/icon-<?php echo $postType; ?>.png" />
    				<?php } ?>
    			</div>
    			<div class="item-content">
	    			<div class="item-header">
	    				<h2><a href="<?php if(get_field('link_to_media')){the_field('uploaded_file');}else{the_permalink();} ?>"><?php the_title(); ?></a></h2>
	    				<span class="item-date"><?php the_time('M j, Y'); ?> ||</span>
	    				<span class="item-author"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>/?prof=article"><?php the_author(); ?></a></span>
	    			</div>
	    			<?php if(get_field('link_to_media')){ ?>
						<a href="<?php the_field('uploaded_file'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/<?php echo $postType; ?>-doc.png" /></a>
					<?php }else{ ?>
						<p><?php
						$exc = get_the_excerpt();
						$line=$exc;
						if (preg_match('/^.{1,100}\b/s', $exc, $match))
						{
						    $line=$match[0];
						}
						echo $exc; ?></p><a class="more" href="<?php the_permalink(); ?>"> view more » </a>
					<?php } ?>
	    			<div class="item-tags">
	    				<?php $tags = get_the_terms(get_the_ID(),'post_tag');
			    					if($tags){
			    						$cnt = 0;
			    						foreach($tags as $tag)
			    						{
				    						$tagLink = get_term_link($tag->term_id,'post_tag');
				    						$tagSlug = $tag->slug;
				    						$tagSlug = str_replace('-',' ', $tagSlug);
				    						if ($cnt != 0) echo ", ";
					    					echo "<a href='".$tagLink."'>".$tagSlug."</a>";
					    					$cnt++;
					    				}
				    				}?>
	    			</div>
	    		</div>
	    		<div class="bot-border"></div>
	  		</div>


		<?php endwhile; wp_reset_query();?>
			</div>
	</div>
</div>