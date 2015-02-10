<?php while ( have_posts() ) : the_post();
	$speakerIMG = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	$postID     = get_the_ID();
	$postType   = get_post_type($postID);
	$general = false;
	if($postType == 'policy'){
		$bannerTitle = '<span class="redd">ACTION</span><br>';
		//$bannerDesc  = get_field('bannerTitle',62);
		//$bannerImg   = wp_get_attachment_url( get_post_thumbnail_id(62) );
		$bannerImg  = get_field('small_banner', 62);
	}elseif($postType == 'quality'){
		$bannerTitle = '<span class="greenn">QUALITY</span><br>';
		//$bannerDesc  = get_field('bannerTitle',64);
		//$bannerImg   = wp_get_attachment_url( get_post_thumbnail_id(64) );
		$bannerImg  = get_field('small_banner', 64);
	}elseif($postType == 'institute'){
		$bannerTitle = '<span class="bluee">INSTITUTE</span><br>';
		//$bannerDesc  = get_field('bannerTitle',621);
		//$bannerImg = wp_get_attachment_url( get_post_thumbnail_id(621) );
		$bannerImg  = get_field('small_banner', 621);
	}else{
		$bannerTitle = '<span class="redd">ABOUT</span><br>';
		$rand = rand(1,9);
		$bannerImg = "http://mlinson.staging.wpengine.com/wp-content/uploads/2013/11/AEH_generalbanner" .$rand . "_222.jpg";
		$postType  == 'policy';
		$bannerDesc  = '';
	} ?>
<div id="featured-img-small" class="<?php echo get_post_type($postID); ?>" style="background-image:url(<?php echo $bannerImg; ?>);background-size:cover;background-position:center center;">
	<div class="container">
		<div id="featured-intro">
			<h3><?php echo $bannerTitle;  ?></h3>
			<?php endwhile; // end of the loop. ?>
		</div>
	</div>
</div>
<div id="content" class="single <?php echo "$postType" ?> <?php if(!page_in_menu('primary-menu')){ if($general) echo 'default-utility'; else  echo 'default-'.$postType;   } ?>">
	<div class="container">
		<?php if(has_nav_menu('primary-menu')){
		$defaults = array(
			'theme_location'  => 'primary-menu',
			'menu'            => 'primary-menu',
			'container'       => 'div',
			'container_class' => '',
			'container_id'    => 'pageNav',
			'menu_class'      => 'fallback',
			'menu_id'         => '',
			'echo'            => true,
			'fallback_cb'     => 'wp_page_menu',
			'before'          => '',
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'depth'           => 0,
			'walker'          => ''
		);
		wp_nav_menu( $defaults ); } ?>
		<div id="columnBalance">
			<div id="contentPrimary">
			<div class="graybar"></div>
			<div class="gutter">
			<?php if ( have_posts() ) while ( have_posts() ) : the_post();
				$auth = get_the_author_meta('ID');
			?>
				<h1><?php the_title(); ?></h1>
				<div id="singlestream">
				<?php $postType = get_post_type( get_the_ID() );
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
				<img src="<?php bloginfo('template_directory'); ?>/images/icon-<?php echo $postType; ?>.png" />
				<?php $terms = wp_get_post_terms(get_the_ID(), 'series');
    					if($terms){
	    					$termLink = get_term_link($terms[0], 'series');
		    				echo "<a href='".$termLink."'>".$terms[0]->name."</a>";
	    				}
    				?></div>

				<div id="postmeta">
					<span class="postmeta"><?php the_time('M j, Y'); ?> || <em><a href="<?php echo get_author_posts_url($auth); ?>?prof=article"><?php the_author(); ?></a></em></span><span class="postcomments"><?php comments_number( ' || (0) comments', ' || (1) comment', ' || (%) comments' ); ?></span>
				<div id="topics">
					<div class="item-tags">
	    				<?php the_tags(' ',' ',' '); ?>
	    			</div>
				</div>
				</div>
				<?php the_post_thumbnail(); ?>
				<div class="postcontent">
					<?php the_content(); ?>


				</div>
				<div id="printbtn"><a href="javascript:print();"><em>Print</em></a></div>

				<?php
					$args = array(
					  'id_form'           => 'commentform',
					  'id_submit'         => 'submit',
					  'title_reply'       => __( 'Leave a Reply' ),
					  'title_reply_to'    => __( 'Leave a Reply to %s' ),
					  'cancel_reply_link' => __( 'Cancel Reply' ),
					  'label_submit'      => __( 'Post Comment' ),

					  'comment_field' =>  '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) .
					    '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true">' .
					    '</textarea></p>',

					  'must_log_in' => '<p class="must-log-in">' .
					    sprintf(
					      __( 'You must be <a href="%s">logged in</a> to post a comment.' ),
					      wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
					    ) . '</p>',

					  'logged_in_as' => '<p class="logged-in-as">' .
					    sprintf(
					    __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ),
					      admin_url( 'profile.php' ),
					      $user_identity,
					      wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
					    ) . '</p>',

					  'comment_notes_before' => '<p class="comment-notes">' .
					    __( 'Your email address will not be published.' ) . ( $req ? $required_text : '' ) .
					    '</p>',

					  'comment_notes_after' => '<p class="form-allowed-tags">' .
					    sprintf(
					      __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ),
					      ' <code>' . allowed_tags() . '</code>'
					    ) . '</p>',

					  'fields' => apply_filters( 'comment_form_default_fields', array(

					    'author' =>
					      '<p class="comment-form-author">' .
					      '<label for="author">' . __( 'Name', 'domainreference' ) . '</label> ' .
					      ( $req ? '<span class="required">*</span>' : '' ) .
					      '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
					      '" size="30"' . $aria_req . ' /></p>',

					    'email' =>
					      '<p class="comment-form-email"><label for="email">' . __( 'Email', 'domainreference' ) . '</label> ' .
					      ( $req ? '<span class="required">*</span>' : '' ) .
					      '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
					      '" size="30"' . $aria_req . ' /></p>',

					    'url' =>
					      '<p class="comment-form-url"><label for="url">' .
					      __( 'Website', 'domainreference' ) . '</label>' .
					      '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
					      '" size="30" /></p>'
					    )
					  ),
					);
					comment_form($args);
				?>


			</div>
		</div>
			<div id="contentSecondary">
				<div class="gutter clearfix">
					<div class="panel">
					<?php
					$orig_post = $post;
					global $post;
					$tags = wp_get_post_tags($post->ID);

					if ($tags) {
					$tag_ids = array();
					foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
					$args = array(
						'tag__in'              => $tag_ids,
						'post__not_in'         => array($post->ID),
						'post_count'           => 3,
						'ignore_sticky_posts'  => 1,
						'post_type'            => array('post','quality','institute','policy'),
						'posts_per_page'	   => 3
					);

					$my_query = new wp_query( $args );

					if($my_query->have_posts()){ ?>
					<h3>Related Articles</h3>
					<?php while( $my_query->have_posts() ) {
					$my_query->the_post();
					?>
						<div class="post <?php echo get_post_type(get_the_id()); ?>">
							<h4 class="<?php echo get_post_type(get_the_id()); ?>"><a class="<?php echo get_post_type(get_the_id()); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
							<span class="author"><?php the_author(); ?></span>
							<!-- <a class="more" href="<?php the_permalink(); ?>">View More &raquo;</a> -->
						</div>
					<? }  } }
					$post = $orig_post;
					wp_reset_query(); ?>

				</div>
					<?php if(get_field('links')){ ?>

				<?php $links = get_field('links');
					if($links){ ?>
					<div class="panel">
					<h3>External Links</h3>
					<?php foreach($links as $link){ ?>
							<div class="post">
								<h4><a href="<?php echo $link['link']; ?>"><?php echo $link['heading']; ?></a></h4>
								<span class="author"><?php echo $link['source']; ?></span>
								<a class="more" href="<?php echo $link['link']; ?>"><?php echo $link['label']; ?></a>
							</div>
				<?php } } ?>
					</div>
				<?php } ?>
				</div>
			</div>
			<div id="contentTertiary">
			<div class="graybar"></div>
			<div class="gutter clearfix">
				<div class="panel">
					<h3 id="sharetitle">Share</h3>
					<div id="share">
						<!-- AddThis Button BEGIN -->
						<div class="addthis_toolbox addthis_32x32_style" style="">
						<a class="addthis_button_facebook"></a>
						<a class="addthis_button_twitter"></a>
						<a class="addthis_button_linkedin"></a>
						<a class="addthis_button_pinterest_share"></a>
						<a class="addthis_button_google_plusone_share"></a>
						<a class="addthis_button_email"></a>
						<a class="addthis_button_digg"></a>
						<a class="addthis_button_evernote"></a>
						<a class="addthis_button_compact"></a>
						</div>
						<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
						<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=naphsyscom"></script>
						<!-- AddThis Button END -->
					</div>
				</div>
				<div class="panel">
					<h3>About the Author</h3>
					<p><?php the_author_description(); ?></p>
				</div>
				<?php if(strlen(get_field('notes')) > 0){ ?>
				<div class="panel">
					<h3>Notes</h3>
					<p><?php echo get_field('notes'); ?></p>
				</div>
				<?php } ?>
			</div>
		</div>
		</div>
	</div>
<?php endwhile; ?>
</div><!-- End of Content -->