<?php
class AEH_Events{
	const VERSION = '1.0.0';
	protected $plugin_slug = 'aeh_events';
	protected static $instance = null;
	public function __construct(){
		//filters

		//actions
		add_action( 'init', array($this,'cpt_tax') );
		add_action( 'wp_enqueue_scripts', array($this, 'scripts_styles') );
		add_action( 'wp_ajax_getevents', array($this, 'getevents') );
		add_action( 'wp_ajax_nopriv_getevents', array($this, 'getevents') );
		add_action( 'wp_ajax_searchevents', array($this, 'searchevents') );
		add_action( 'wp_ajax_nopriv_searchevents', array($this, 'searchevents') );
		add_action( 'save_post',array($this,'updateDateMeta'));
	}
	public function get_plugin_slug(){
		return $this->plugin_slug;
	}
	public static function get_instance(){
		if(null == self::$instance){
			self::$instance = new self;
		}
		return self::$instance;
	}


	/**
	*
	* Post Types and Taxonomies (init)
	*
	*/
	public function cpt_tax(){
		// events cpt
		$labels = array(
			'name'               => _x( 'Events', 'post type general name', $plugin_slug ),
			'singular_name'      => _x( 'Event', 'post type singular name', $plugin_slug ),
			'menu_name'          => _x( 'Events', 'admin menu', $plugin_slug ),
			'name_admin_bar'     => _x( 'Event', 'add new on admin bar', $plugin_slug ),
			'add_new'            => _x( 'Add New', 'event', $plugin_slug ),
			'add_new_item'       => __( 'Add New Event', $plugin_slug ),
			'new_item'           => __( 'New Event', $plugin_slug ),
			'edit_item'          => __( 'Edit Event', $plugin_slug ),
			'view_item'          => __( 'View Event', $plugin_slug ),
			'all_items'          => __( 'All Events', $plugin_slug ),
			'search_items'       => __( 'Search Events', $plugin_slug ),
			'parent_item_colon'  => __( 'Parent Events:', $plugin_slug ),
			'not_found'          => __( 'No events found.', $plugin_slug ),
			'not_found_in_trash' => __( 'No events found in Trash.', $plugin_slug )
		);
		$args = array(
			'labels'             => $labels,
			'menu_icon'			 		 => 'dashicons-nametag',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'events' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		); register_post_type( 'events', $args );

		// audience ct
		$labels = array(
			'name'                       => _x( 'Audience', 'taxonomy general name' ),
			'singular_name'              => _x( 'Audience', 'taxonomy singular name' ),
			'search_items'               => __( 'Search Audiences' ),
			'popular_items'              => __( 'Popular Audiences' ),
			'all_items'                  => __( 'All Audiences' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Audience' ),
			'update_item'                => __( 'Update Audience' ),
			'add_new_item'               => __( 'Add New Audience' ),
			'new_item_name'              => __( 'New Audience Name' ),
			'separate_items_with_commas' => __( 'Separate audiences with commas' ),
			'add_or_remove_items'        => __( 'Add or remove audiences' ),
			'choose_from_most_used'      => __( 'Choose from the most used audiences' ),
			'not_found'                  => __( 'No audiences found.' ),
			'menu_name'                  => __( 'Audiences' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
		); register_taxonomy( 'audience', 'events', $args );


	}

	/**
	*
	* Scripts and Styles (wp_enqueue_script)
	*
	*/
	public function scripts_styles(){
		wp_enqueue_style( 'style-events', get_template_directory_uri().'/functions/events.css' );
		wp_enqueue_script( 'jquery-tools', get_template_directory_uri().'/js/jquery.tools.min.js', array(), '1.0', true );
		wp_enqueue_script( 'script-events', get_template_directory_uri().'/functions/events.js', array('jquery'), '1.0.0', true );
		wp_localize_script( 'script-events', 'AEH',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' )
			)
		);
	}

	/**
	*
	* Ajax Request
	*
	*/
	public function getevents(){
		// Get POST
		global $wpdb;
		$month = $_POST['month'];
		$time  = $_POST['time'];
		$section = $_POST['section'];
		// Determine timestamp
		if($time == 'future'){
			$time = '>';
		}elseif($time == 'past'){
			$time = '<';
		}
		else{
			$time = '*';
		}
		$timestamp = time();
		// Generate query situations
		if($month != "*"){
			$month_query = "AND month.meta_value = '$month'";
		}else{
			$month_query = "";
		}
		if($time != '*'){
			$time_query = "AND time.meta_value $time $timestamp";
		}else{
			$time_query = "";
		}
		if($section != '*'){
			$section_query = "AND section.meta_value = '$section'";
		}else{
			$section_query = "";
		}
		// Query Events
		$events = $wpdb->get_results("SELECT posts.ID
																FROM $wpdb->posts AS posts, $wpdb->postmeta AS time, $wpdb->postmeta AS section, $wpdb->postmeta AS month, $wpdb->postmeta AS hide
																WHERE posts.post_type = 'events'
																AND posts.post_status = 'publish'
																AND time.meta_key = 'date'
																AND section.meta_key = 'section'
																AND month.meta_key = 'month'
																$time_query
																$month_query
																$section_query
																AND hide.meta_key = 'hide_event'
																AND hide.meta_value = 0
																AND (time.post_id = posts.ID AND section.post_id = posts.ID AND month.post_id = posts.ID AND hide.post_id = posts.ID)
																ORDER BY time.meta_value ASC
																");
		// Render Markup
		$output = '<div class="item">';
		$i = 0;
		if( count($events != 0) ){
			foreach($events as $event){
				if($i%6 == 0 && $i != 0){
					$output .= '</div><div class="item">';
				}
				$output .= self::render_event($event);
				$i++;
			}
		}else{
			$output .= '<h3>No events found</h3>';
		}
		$output .= '</div>';
		// Gimme the output!
		echo $output;

		die();
	}



	/**
	*
	* Get Events (public function)
	*
	*/
	public static function get_events($number=25,$month='*',$time='*',$section='*'){
		// Determine timestamp
		if($time == 'future'){
			$time = '>';
		}elseif($time == 'past'){
			$time = '<';
		}
		else{
			$time = '!=';
		}
		$timestamp = time();
		// Generate query situations
		if($month != "*"){
			$month_query = "month.meta_value = '$month'";
		}else{
			$month_query = "";
		}
		if($time != '*'){
			$time_query = "time.meta_value = '$date'";
		}else{
			$time_query = "";
		}
		if($section != '*'){
			$section_query = "AND section.meta_value = '$section'";
		}else{
			$section_query = "";
		}
		// Query Events
		global $wpdb;
		$events = $wpdb->get_results("SELECT posts.ID
																FROM $wpdb->posts AS posts, $wpdb->postmeta AS time, $wpdb->postmeta AS section, $wpdb->postmeta AS month, $wpdb->postmeta AS hidden
																WHERE posts.post_type = 'events'
																AND posts.post_status = 'publish'
																AND time.meta_key = 'date'
																AND section.meta_key = 'section'
																AND month.meta_key = 'month'
																AND hidden.meta_key = 'hide_event'
																AND hidden.meta_value = '0'
																AND (time.post_id = posts.ID AND section.post_id = posts.ID AND month.post_id = posts.ID AND hidden.post_id = posts.ID)
																ORDER BY time.meta_value ASC
																");
		// Render Markup
		$output = '<div class="item">';
		$i = 0;
		if(count($events > 0)){
			foreach($events as $event){
				if($i%6 == 0 && $i != 0){
					$output .= '</div><div class="item">';
				}
				$output .= self::render_event($event);
				$i++;
			}
		}else{
			$output .= '<h3>No events found</h3>';
		}
		$output .= '</div>';
		// Gimme the output!
		echo $output;
	}


	public function searchevents(){
		$search = $_POST['search'];

		$args = array(
			'post_type' => 'events',
			'posts_per_page' => -1,
			'meta_key' => 'date',
			's' => $search,
		);
		$posts = get_posts($args);
		// Render Markup
		$output = '<div class="item">';
		$i = 0;
		if(count($posts > 0)){
			foreach($posts as $post){
				if($i%6 == 0 && $i != 0){
					$output .= '</div><div class="item">';
				}
				$output .= self::render_event($post);
				$i++;
			}
		}else{
			$output .= '<h3>No events found</h3>';
		}
		$output .= '</div>';
		// Gimme the output!
		echo $output;

		die();
	}


	private static function render_event($event){
		$post = get_post($event->ID);
		$link = get_permalink($event->ID);
		$title = $post->post_title;
		$meta = get_post_meta($event->ID);
		$date = $meta['date'][0];
		$time = date( 'g:i A T', $date );
		$date = date( 'F j, Y', $date );
		$intro = $meta['intro'][0];
		$intro = substr($intro,0,200);
		$section = $meta['section'][0];
		$location = $meta['location'][0];
		$audience = $meta['audience'][0];
		$minisite = $meta['minisite'][0];
		
		if($audience != NULL || $audience != ''){
			$audience = get_term_by('ID', $audience, 'audience')->name;
		}
		if($audience == 'Members Only'){
			$lockicon = "<span style='vertical-align:middle;border:none;' class='lock-icon'>
										<img src='".get_template_directory_uri()."/images/lockwebinar.png'>
									</span>";
		}else{
			$lockicon = '';
		}

		if($section == 'action'){
			$theme = 'redd';
		}elseif($section == 'quality'){
			$theme = 'greenn';
		}elseif($section == 'institute'){
			$theme = 'bluee';
		}else{
			$theme = 'grayy';
		}
		if($section == ''){
			$section = 'education';
		}

		if($minisite  != NULL || $$minisite  != ''){
			$link = $minisite;
			$minibg = "mini-".$theme;
		}
		$output = '<div class="post long columns '.$theme.' '.$post->post_type.' wide  '. $minibg.'">
									<div class="graybarright"></div>
									<div class="item-bar"></div>
									<div class="item-icon">
										<img src="http://mlinson.staging.wpengine.com/wp-content/themes/EssentialHospitals/images/icon-'.$section.'.png">
									</div>
									<div class="item-content">
										<div class="item-header">
											<h2><a href="'.$link.'">'.$lockicon.' '.$title.'</a></h2>
											<span class="item-date">'.$date.'</span>
											<span class="item-date">'.$location.'</span>
											<span class="item-date"><strong>'.$audience.'</strong></span>
										</div>
										<p>'.$intro.'<a href="'.$link.'" class="readmore read-more">Read More</a></p>
									</div>
									<div class="bot-border"></div>
						   </div>';
		return $output;
	}

	public function updateDateMeta($post_id){
		$type = get_post_type( $post_id );
		if ($type == 'events' ){
	    	//remove save post hook to avoid infinite loop
	    	remove_action('save_post',array($this,'updateDateMeta'));

	    	//Get post data and update meda
	    	//$post = get_post($post_id );
	    	$timestamp = get_post_meta($post_id, 'date', true);
	    	$month = date('n', $timestamp);
	    	$year = date('Y', $timestamp);
	    	update_post_meta($post_id, 'month', $month );
	    	update_post_meta($post_id, 'year', $year );

	    	add_action('save_post',array($this,'updateDateMeta'));	
		}
	}

}
global $aeh_events;
$aeh_events = new AEH_Events;
