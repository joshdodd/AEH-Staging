<?php
	$args = array(
		'post_type' => array('policy','quality','institute','post','general'),
		'meta_key' => 'sticky_topic',
		'meta_value' => 'home',
		'meta_compare' => '='
	);
	$query = new WP_Query($args);
	if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post();
		$postType = get_post_type( get_the_ID() );

			//check post type and apply a color
			if($postType == 'policy' || $postType == 'general'){
			$postColor = 'redd';
		}else if($postType == 'quality'){
			$postColor = 'greenn';
		}else if($postType == 'education' || $postType == 'webinar'){
			$postColor = 'grayy';
		}else if($postType == 'institute'){
			$postColor = 'bluee';
		}else if($postType == 'post'){
			$postColor = 'blog';
			$postType = 'blog';
		}else{
			$postColor = 'bluee';
		}

		if($postType == 'general'){$postType = 'policy';} ?>
	<div class="post long columns <?php echo $postColor; ?>  <?php echo get_post_type( get_the_ID() ); ?> ">
				<div class="graybarright"></div>
	  			<div class="item-bar"></div>
    			<div class="item-icon"><img src="<?php bloginfo('template_directory'); ?>/images/icon-<?php echo $postType; ?>.png" /></div>
    			<div class="item-content">
	    			<div class="item-header">
	    				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	    				<span class="item-date"><?php the_time('M j, Y'); ?> ||</span>
	    				<span class="item-author"><?php the_author(); ?></span>
	    			</div>
	    			<?php if(get_field('link_to_media') != 0){ ?>
						<a href="<?php the_field('uploaded_file'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/<?php echo $postType; ?>-doc.png" /></a>
						<p><?php $exc = get_the_excerpt();
						$line=$exc;
						if(preg_match('/^.{1,100}\b/s', $exc, $match)){$line=$match[0];}
						$long_exc = get_field('long_excerpt');
						if($long_exc){echo $long_exc;}else{echo $exc;} ?></p>
						<a class="more" href="<?php the_permalink(); ?>"> view more » </a>

					<?php }else{ ?>

						<p><?php $exc = get_the_excerpt();
						$line=$exc;
						if (preg_match('/^.{1,100}\b/s', $exc, $match)){$line=$match[0];}
						$long_exc = get_field('long_excerpt');
						if($long_exc){echo $long_exc;}else{echo $exc;} ?></p>
						<a class="more" href="<?php the_permalink(); ?>"> view more » </a>

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
<?php } } wp_reset_query(); ?>