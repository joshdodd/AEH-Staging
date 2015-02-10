<?php
	 $terms = get_terms('webinartopics', 'orderby=count&hide_empty=0');
	 $webinars = array();
	 foreach($terms as $term){
	 	$webinars[] = $term->slug;
	 }
	 $webinararray = array(implode(',',$webinars));
	 if(isset($_GET['timeFilter'])){
		$time = $_GET['timeFilter'];
	 }else{
		 $time = 'all';
	 }

	 if($time == 'future'){
		 $sortCompare = '>=';
		 $sortOrder = 'asc';
	 }elseif($time == 'publish'){
		 $sortCompare = '<=';
		 $sortOrder = 'desc';
	 }else{
		 $sortCompare = '>=';
		 $sortOrder = 'asc';
	 }

	 $uid = get_current_user_id();
	 $mem = get_user_meta($uid, 'aeh_member_type', true);
?>
<div id="postBox" class="clearfix">
	<div id="fader" class="clearfix scrollable">
	<div id="loader-gif"> Loading more webinars</div>
			<div class="items">
			<?php
				$today = mktime(0, 0, 0, date('n'), date('j'));
				$args = array(
					'post_type' => 'webinar',
					'orderby' => 'meta_value',
					'meta_key' => 'webinar_date',
					'order' => $sortOrder,
					'post_status' => 'all',
					'meta_query'  => array(
						array(
							'key' => 'webinar_date',
							'value' => $today,
							'compare' => $sortCompare
						)
					)
				);
				$query = new WP_Query($args);
				if ( $query->have_posts() ) while ( $query->have_posts() ) : $query->the_post();
					$postType = get_post_type( get_the_ID() );

					$postTerms = wp_get_post_terms( get_the_ID(), 'webinartopics' );
					$newTerm = array();
					foreach($postTerms as $term){
						array_push($newTerm, $term->slug);
					}
					//check post type and apply a color
					if(in_array('policy',$newTerm) || in_array('advocacy',$newTerm)){
						$postColor = 'redd';
						$postType  = 'policy';
					}else if(in_array('quality',$newTerm)){
						$postColor = 'greenn';
						$postType  = 'quality';
					}else if(in_array('education',$newTerm)){
						$postColor = 'grayy';
						$postType  = 'education';
					}else if(in_array('institute',$newTerm)){
						$postColor = 'bluee';
						$postType  = 'institute';
					}else{
						$postColor = 'grayy';
						$postType  = 'education';
					}
				?>
				<div class="post long columns <?php echo $postColor; ?>  <?php echo get_post_type( get_the_ID() ); ?> ">
					<div class="graybarright"></div>
		  			<div class="item-bar"></div>
	    			<div class="item-icon"><img src="<?php bloginfo('template_directory'); ?>/images/icon-<?php echo $postType; ?>.png" /></div>
	    			<div class="item-content">
		    			<div class="item-header">
		    				<h2>
		    				<?php $isPrivate = get_field('private_webinar');
							if($isPrivate){ ?>
								<div class="private-webinar <?php echo $postColor; ?>"></div>
							<?php } ?>
		    				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		    				<span class="item-date"><?php echo date('M j, Y', get_field('webinar_date')); ?> at <?php echo date('g:i A T',get_field('webinar_date')); ?></span>
		    			</div>

		    			<?php
			    			if ( get_post_meta($post->ID, 'webinar_date', true) > $today  && $mem == 'hospital') {
								echo get_the_excerpt().'<span class="reserve button '.$postType.'"><a href="'.get_field('registration_link').'">Reserve Your Spot</a></span>';
							}elseif(get_post_meta($post->ID, 'webinar_date', true) > $today  && $mem != 'hospital'){
								echo get_the_excerpt().'<span class="reserve button '.$postType.'"><a href="'.get_permalink().'">Reserve Your Spot</a></span>';
							}else{
								echo get_the_excerpt().'<span class="readmore"><a href="'.get_permalink().'">read more &raquo;</a></span>';
							} ?>

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


			<?php endwhile; wp_reset_query(); ?>



			</div>
	</div>
</div>