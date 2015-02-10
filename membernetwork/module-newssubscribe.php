<?php
	$userID		 = get_current_user_id();
	$metakey 	 = "custom_news_feed";
	$usermeta    = get_user_meta($userID, $metakey, TRUE);
	if($usermeta){
		$usermetaOLD = get_user_meta($userID, $metakey, TRUE);
	}else{
		$usermetaOLD = array();
	}

	$newsArray = array();
	if ($_SERVER['REQUEST_METHOD'] == "POST"){
		foreach ($_POST as $param_name => $param_val) {
			$newsVal = substr($param_val, 1);
		    array_push($newsArray,$newsVal);
		}
		update_user_meta($userID, $metakey, $newsArray);
	}

	$usermeta  = get_user_meta($userID, $metakey, TRUE);

?>
	<h2 class='heading'>Customize your content feed</h1>
		<span class="termgroup">Interest areas:</span>
		<div id="curr-news">
		<?php
		// no default values. using these as examples
		$taxonomies = array('educationtopics', 'qualitytopics', 'policytopics', 'institutetopics','webinartopics' );

		$args = array(
		    'orderby'       => 'term_group',
		    'order'         => 'ASC',
		    'hide_empty'    => false,
		    'exclude'       => array(),
		    'exclude_tree'  => array(),
		    'include'       => array(),
		    'number'        => '',
		    'fields'        => 'all',
		    'slug'          => '',
		    'parent'         => '',
		    'hierarchical'  => true,
		    'child_of'      => 0,
		    'get'           => '',
		    'name__like'    => '',
		    'pad_counts'    => false,
		    'offset'        => '',
		    'search'        => '',
		    'cache_domain'  => 'core'
		);
		$taxs = get_terms( $taxonomies, $args );
		if($usermeta){
		foreach($taxs as $tax){
			if(in_array($tax->slug, $usermeta)) {
		?>
			<div class="newsSelection <?php echo substr($tax->taxonomy,0,-6)?>">
				<div class="gutter clearfix">
					<label><?php echo $tax->name; ?> (<?php echo $tax->count; ?>)</label>
				</div>
			</div>
		<?php } }
		}else{
			echo "Select topics of interest to create a feed.";
		} echo "</div><div id='change-news'>";
		if ($_SERVER['REQUEST_METHOD'] == "POST"){
			// no default values. using these as examples
				$taxonomies = array('educationtopics', 'qualitytopics', 'policytopics', 'institutetopics','webinartopics' );

				$args = array(
				    'orderby'       => 'term_group',
				    'order'         => 'ASC',
				    'hide_empty'    => false,
				    'exclude'       => array(),
				    'exclude_tree'  => array(),
				    'include'       => array(),
				    'number'        => '',
				    'fields'        => 'all',
				    'slug'          => '',
				    'parent'         => '',
				    'hierarchical'  => true,
				    'child_of'      => 0,
				    'get'           => '',
				    'name__like'    => '',
				    'pad_counts'    => false,
				    'offset'        => '',
				    'search'        => '',
				    'cache_domain'  => 'core'
				);
				$taxs = get_terms( $taxonomies, $args );
				$termgroup = '';


				if(count($usermeta) > count($usermetaOLD)){
					foreach($taxs as $tax){
					    if(in_array($tax->slug, $usermeta) && !in_array($tax->slug, $usermetaOLD)) {
							echo '<span class="new-sub">'.$tax->name.'</span>';
						}
					}
					echo 'Have been added to your news subscriptions';
				}else{
					if(!in_array($tax->slug, $usermeta) && in_array($tax->slug, $usermetaOLD)){
						echo '<span class="new-sub">'.$tax->name.'</span>';
					}
					echo 'Have been removed from your news subscriptions';
				}


			}
		?>
		</div>
		<p>Customize your news feed by selecting categories you would like to receive articles on</p>

		<?php if($usermeta){
			echo '<button id="showmore">Edit Preferences</button>';
		} ?>

		<form id="addNews" method="post" name="customnews" <?php if($usermeta){ echo 'data-filter="meta"';} ?>>
			<?php
				// no default values. using these as examples
				$taxonomies = array('educationtopics', 'qualitytopics', 'policytopics', 'institutetopics','webinartopics' );

				$args = array(
				    'orderby'       => 'term_group',
				    'order'         => 'ASC',
				    'hide_empty'    => false,
				    'exclude'       => array(),
				    'exclude_tree'  => array(),
				    'include'       => array(),
				    'number'        => '',
				    'fields'        => 'all',
				    'slug'          => '',
				    'parent'         => '',
				    'hierarchical'  => true,
				    'child_of'      => 0,
				    'get'           => '',
				    'name__like'    => '',
				    'pad_counts'    => false,
				    'offset'        => '',
				    'search'        => '',
				    'cache_domain'  => 'core'
				);
				$taxs = get_terms( $taxonomies, $args );
				$termgroup = '';
				$countit = 0;
				foreach($taxs as $tax){
				?>
					<div class="newsSelection <?php echo substr($tax->taxonomy,0,-6)?>">
						<?php if($termgroup != $tax->taxonomy){
							$termgroup = $tax->taxonomy;
							if($termgroup == 'educationtopics'){
								$termname = 'Education';
							}elseif($termgroup == 'qualitytopics'){
								$termname = 'Quality';
							}elseif($termgroup == 'policytopics'){
								$termname = 'Policy';
							}elseif($termgroup == 'institutetopics'){
								$termname = 'Institute';
							}elseif($termgroup == 'webinartopics'){
								$termname = 'Webinar';
							}
							echo "<span class='termgroup'>".$termname."</span>";
						} ?>
						<div class="gutter clearfix">
							<input name="X<?php echo $tax->slug; ?>" type="checkbox" value="X<?php echo $tax->slug; ?>" <?php if($usermeta){if(in_array($tax->slug, $usermeta)) {  echo "checked='checked'"; }else{ echo ""; }} ?>>
							<label for="<?php $tax->slug; ?>"><?php echo $tax->name; ?> (<?php echo $tax->count; ?>)</label>
							<span class="newsDesc"><?php echo $tax->description; ?></span>
						</div>
					</div>
				<?php } ?>
	<span style="padding:8px 10px 0 0;margin:5px 0 0"><a href="javascript:void();" onclick="javascript:checkAll('customnews', true);">Check All</a></span>
	<span style="padding:3px 10px 0 0"><a href="javascript:void();" onclick="javascript:checkAll('customnews', false);">Uncheck All</a><br /></span><br />
	<input type="submit" value="Save" />
</form>