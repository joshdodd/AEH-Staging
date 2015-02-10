<?php

/********************************************* SWITCHES/VARIABLE SETUP ***********************************************/

$test = false;														// true = TEST DATABASE, false = PRODUCTION DATABASE
define ("EMAILCRON", FALSE);										// true = send email at import cron, false = do not send email
define ("IMPORT_PER_CRON", 1000);									// maximum number of rows to import per cron job - tweak to make sure script doesn't timeout
define ("SP_SECURITY_PWD", "F46DB250-B294-4B3D-BC95-45B7DDFEE334"); // Stored Procedure Security Password
define ("SOAP_ACCOUNT_PWD", "300A6E01-5DB9-4217-A2DE-CDB2F08FE1F7");// SOAP web services security password
define ("MAX_WP_USERS_UPDATED", '400');								// How many users updated in WP from the wp_aeh_import_full table

/****************************************** PROD or TEST? IMIS STORED PROCS ETC. *************************************/
if ($test){
	define ("SP_IMPORT_USERS",   "test_importUsers");				// main import users cron job
	define ("SP_GET_IMIS_USER",  "test_GetImisUser");				// retrieve info on one iMIS user
	define ("SP_GET_ROW_COUNT",  "test_GetRowCount");				// get total row count for main import users table from iMIS
	define ("SP_LOGIN_TIME",     "test_MESH_UD_Security");			// update last login time in iMIS
	define ("SP_DOES_USER_EXIST","test_DoesUserExist");				// check if user already exists in iMIS from their email address
	define ("SP_GET_TITLES",     "test_GetTitles");					// cron to get user TITLES and set serialized value in WP Options table
	define ("SP_WEB_INTERESTS",  "test_GetWebInterests");			// cron to get WEB_INTEREST and set serialized value in WP Options table
	define ("SP_COMPANY_LIST",  "test_GetCompanyList");				// cron to get COMPANY and HQ address info and set serialized value in WP Options table
	define ("IMIS_SOAP_URL",'http://isgweb.naph.org/ibridge_test/Account.asmx?wsdl'); 								// URL for test SOAP Client comms with iMIS
	define ("IMIS_POST_URL",'http://isgweb.naph.org/ibridge_test/DataAccess.asmx/ExecuteDatasetStoredProcedure');	// URL for test POST comms (read) with iMIS
	define ("SP_POST_UPDATE_URL", 'http://isgweb.naph.org/ibridge_test/DataAccess.asmx/ExecuteStoredProcedure');	// URL for POST execute SP on Account Updates
	define ("SOAP_DEMOG_UPDATE_URL",'http://isgweb.naph.org/ibridge_test/Demographics.asmx?wsdl');					// URL for POST execute SP on Demographic Updates
}else{
	define ("SP_IMPORT_USERS",   "importUsers");					// main import users cron job
	define ("SP_GET_IMIS_USER",  "GetImisUser");					// retrieve info on one iMIS user
	define ("SP_GET_ROW_COUNT",  "GetRowCount");					// get total row count for main import users table from iMIS
	define ("SP_LOGIN_TIME",     "MESH_UD_Security");				// update last login time in iMIS
	define ("SP_DOES_USER_EXIST","DoesUserExist");					// check if user already exists in iMIS from their email address
	define ("SP_GET_TITLES",     "GetTitles");						// cron to get user TITLES and set serialized value in WP Options table
	define ("SP_WEB_INTERESTS",  "GetWebInterests");				// cron to get WEB_INTEREST and set serialized value in WP Options table
	define ("SP_COMPANY_LIST",  "GetCompanyList");					// cron to get COMPANY and HQ address info and set serialized value in WP Options table
	define ("SP_EMAIL_LIST",  "GetEmailList");				     	// cron to get verified email domains and update wp_aeh_email table
	define ("IMIS_SOAP_URL",'http://isgweb.naph.org/ibridge/Account.asmx?wsdl'); 									// URL for SOAP Client comms with iMIS
	define ("IMIS_POST_URL",'http://isgweb.naph.org/ibridge/DataAccess.asmx/ExecuteDatasetStoredProcedure');		// URL for POST comms (read) with iMIS
	define ("SP_POST_UPDATE_URL", 'http://isgweb.naph.org/ibridge/DataAccess.asmx/ExecuteStoredProcedure');			// URL for POST execute SP on Account Updates
	define ("SOAP_DEMOG_UPDATE_URL",'http://isgweb.naph.org/ibridge/Demographics.asmx?wsdl');					// URL for POST execute SP on Demographic Updates
}

/*
iMIS passwords
Account        = 300A6E01-5DB9-4217-A2DE-CDB2F08FE1F7
Activities     = 300A6E01-5DB9-4217-A2DE-CDB2F08FE1F7
Authentication = 27D5F4B5-57B2-4A67-BC82-AA2E1756DED3
DataAccess     = F46DB250-B294-4B3D-BC95-45B7DDFEE334
Demographics   = 300A6E01-5DB9-4217-A2DE-CDB2F08FE1F7
Purchase       = 300A6E01-5DB9-4217-A2DE-CDB2F08FE1F7
Relationships  = 300A6E01-5DB9-4217-A2DE-CDB2F08FE1F7
*/
/*********************************************************************************************************************/

function print_constants(){
	$string = '';
	$string .= "SP_IMPORT_USERS: " . SP_IMPORT_USERS . "<br />";
	$string .= "SP_GET_IMIS_USER: " . SP_GET_IMIS_USER . "<br />";
	$string .= "SP_GET_ROW_COUNT: " . SP_GET_ROW_COUNT . "<br />";
	$string .= "SP_LOGIN_TIME: " . SP_LOGIN_TIME . "<br />";
	$string .= "SP_DOES_USER_EXIST: " . SP_DOES_USER_EXIST . "<br />";
	$string .= "SP_GET_TITLES: " . SP_GET_TITLES . "<br />";
	$string .= "SP_WEB_INTERESTS: " . SP_WEB_INTERESTS . "<br />";
	$string .= "IMIS_SOAP_URL: " . IMIS_SOAP_URL . "<br />";
	$string .= "IMIS_POST_URL: " . IMIS_POST_URL . "<br />";
	$string .= "SP_POST_UPDATE_URL: " . SP_POST_UPDATE_URL . "<br />";
	$string .= "EMAILCRON: " . EMAILCRON . "<br />";
	$string .= "IMPORT_PER_CRON: " . IMPORT_PER_CRON . "<br />";
	$string .= "SP_SECURITY_PWD: " . SP_SECURITY_PWD . "<br />";
	$string .= "SOAP_ACCOUNT_PWD: " . SOAP_ACCOUNT_PWD . "<br />";
	$string .= "MAX_WP_USERS_UPDATED: " . MAX_WP_USERS_UPDATED . "<br />";
	return $string;
}


//Roles and Workflow
include('includes/roles.php');
include('includes/workflow.php');
include('includes/email.php');
include('includes/twitter.php');

//Extra User Fields when creating through the dashboard
function extraUserFields() {
  echo '<table><tbody>
  			<tr class="form-field">
				<th scope="row"><label for="url">Middle Initial</label></th>
				<td><input name="m_initial" type="text" width="1"/></td>
			</tr>

			<tr class="form-field">
				<th scope="row"><label for="url">Suffix</label></th>
				<td><select id="suffix">
					<option value="">Select a Suffix</option>
					<option value="II">II</option>
					<option value="III">III</option>
					<option value="IV">IV</option>
					<option value="Jr">Jr</option>
					<option value="Sr">Sr</option>
				</select></td>
			</tr>

			<tr class="form-field">
				<th scope="row"><label for="url">Designation</label></th>
				<td><input name="designation" type="text"/></td>
			</tr>

			<tr class="form-field">
				<th scope="row"><label for="url">Salutation</label></th>
				<td><input name="salutation" type="text"/></td>
			</tr>

			<tr class="form-field">
				<th scope="row"><label for="url">Street Address</label></th>
				<td><input name="street_address" type="text" /></td>
			</tr>

			<tr class="form-field">
				<th scope="row"><label for="url">City</label></th>
				<td><input name="city" type="text" /></td>
			</tr>

			<tr class="form-field">
				<th scope="row"><label for="url">State</label></th>
				<td><select name="state">
					<option value="" selected="selected">Select a State</option>
					<option value="AL">Alabama</option>
					<option value="AK">Alaska</option>
					<option value="AZ">Arizona</option>
					<option value="AR">Arkansas</option>
					<option value="CA">California</option>
					<option value="CO">Colorado</option>
					<option value="CT">Connecticut</option>
					<option value="DE">Delaware</option>
					<option value="DC">District Of Columbia</option>
					<option value="FL">Florida</option>
					<option value="GA">Georgia</option>
					<option value="HI">Hawaii</option>
					<option value="ID">Idaho</option>
					<option value="IL">Illinois</option>
					<option value="IN">Indiana</option>
					<option value="IA">Iowa</option>
					<option value="KS">Kansas</option>
					<option value="KY">Kentucky</option>
					<option value="LA">Louisiana</option>
					<option value="ME">Maine</option>
					<option value="MD">Maryland</option>
					<option value="MA">Massachusetts</option>
					<option value="MI">Michigan</option>
					<option value="MN">Minnesota</option>
					<option value="MS">Mississippi</option>
					<option value="MO">Missouri</option>
					<option value="MT">Montana</option>
					<option value="NE">Nebraska</option>
					<option value="NV">Nevada</option>
					<option value="NH">New Hampshire</option>
					<option value="NJ">New Jersey</option>
					<option value="NM">New Mexico</option>
					<option value="NY">New York</option>
					<option value="NC">North Carolina</option>
					<option value="ND">North Dakota</option>
					<option value="OH">Ohio</option>
					<option value="OK">Oklahoma</option>
					<option value="OR">Oregon</option>
					<option value="PA">Pennsylvania</option>
					<option value="RI">Rhode Island</option>
					<option value="SC">South Carolina</option>
					<option value="SD">South Dakota</option>
					<option value="TN">Tennessee</option>
					<option value="TX">Texas</option>
					<option value="UT">Utah</option>
					<option value="VT">Vermont</option>
					<option value="VA">Virginia</option>
					<option value="WA">Washington</option>
					<option value="WV">West Virginia</option>
					<option value="WI">Wisconsin</option>
					<option value="WY">Wyoming</option>
				</select></td>
			</tr>

			<tr class="form-field">
				<th scope="row"><label for="url">Zip Code</label></th>
				<td><input name="zip_code" type="text" /></td>
			</tr>

			<tr class="form-field">
				<th scope="row"><label for="url">Country</label></th>
				<td><input name="country" type="text" /></td>
			</tr>

			<tr class="form-field">
				<th scope="row"><label for="url">Phone</label></th>
				<td><input name="phone" type="tel" placeholder="(xxx) xxx-xxxx" /></td>
			</tr>

			<tr class="form-field">
				<th scope="row"><label for="url">Fax</label></th>
				<td><input name="fax" type="tel" placeholder="(xxx) xxx-xxxx" /></td>
			</tr>

			<tr class="form-field">
				<th scope="row"><label for="url">Mobile Phone</label></th>
				<td><input name="mobile" type="tel" placeholder="(xxx) xxx-xxxx" /></td>
			</tr>

			<tr class="form-field">
				<th scope="row"><label for="url">Assistant Name</label></th>
				<td><input name="assistant_name" type="text" /></td>
			</tr>

			<tr class="form-field">
				<th scope="row"><label for="url">Assistant Email</label></th>
				<td><input name="assistant_email" type="text" /></td>
			</tr>

			<tr class="form-field">
				<th scope="row"><label for="url">Assistant Phone</label></th>
				<td><input name="assistant_phone" type="tel" placeholder="(xxx) xxx-xxxx"/></td>
			</tr>
			<input type="hidden" value="extraImisFields" name="extraImisFields" /></tbody></table>';
}
add_action('user_new_form', 'extraUserFields');

function extraUserFieldsSave($user_id){
	if(isset($_POST['extraImisFields'])){
		add_user_meta( $user_id, 'middle_initial', $_POST['m_initial'], true );
		add_user_meta( $user_id, 'suffix', $_POST['suffix'], true );
		add_user_meta( $user_id, 'designation', $_POST['designation'], true );
		add_user_meta( $user_id, 'salutation', $_POST['salutation'], true );
		add_user_meta( $user_id, 'street_address', $_POST['street_address'], true );
		add_user_meta( $user_id, 'city', $_POST['city'], true );
		add_user_meta( $user_id, 'state', $_POST['state'], true );
		add_user_meta( $user_id, 'zip_code', $_POST['zip_code'], true );
		add_user_meta( $user_id, 'country', $_POST['country'], true );
		add_user_meta( $user_id, 'phone', $_POST['phone'], true );
		add_user_meta( $user_id, 'fax', $_POST['fax'], true );
		add_user_meta( $user_id, 'mobile_phone', $_POST['mobile'], true );
		add_user_meta( $user_id, 'assistant_name', $_POST['assistant_name'], true );
		add_user_meta( $user_id, 'assistant_email', $_POST['assistant_email'], true );
		add_user_meta( $user_id, 'assistant_phone', $_POST['assistant_phone'], true );
		delete_user_meta( $user_id, 'confirmed');
		/* Display confirmation message*/
		add_user_meta($user_id, 'verified', true, true);
	}
}
add_action( 'user_register', 'extraUserFieldsSave', 10, 1 );



//Authentication check
add_filter('wp_authenticate_user', 'check_login', 10, 2);
function check_login($user, $password) {
	$auth = get_user_meta($user->ID, 'verified', true);
	$verif = get_user_meta($user->ID,'imis_verified',true);
	$memberType = get_user_meta($user->ID,'aeh_member_type',true);
	$loginurl = site_url().'/membernetwork/member-activation/?memid='.$user->ID;
	if($verif != 1 && $memberType == 'hospital'){
		wp_redirect( $loginurl,302);
		exit;
	}
    if(!$auth) {
    	//wp_mail('pat@meshfresh.com','login attempt','you tried to login, Admin');
    	add_filter( 'wpmem_login_failed', 'my_login_failed_msg' );
    	return null;
    }
    return $user;
}
function my_login_failed_msg( $str ){
	$str = "Your account has not been verified";
	return $str;
}


//Check if current page exists in nav
function page_in_menu( $menu = null, $object_id = null ) {
    $menu_object = wp_get_nav_menu_items( esc_attr( $menu ) );
    if( ! $menu_object )
        return false;
    $menu_items = wp_list_pluck( $menu_object, 'object_id' );
    if( !$object_id ) {
        global $post;
        $object_id = get_queried_object_id();
    }
    return in_array( (int) $object_id, $menu_items );
}

//Stylesheet
function admin_styles() {
    wp_register_style( 'admin_stylesheet', get_template_directory_uri().'/css/admin.css' );
    wp_enqueue_style( 'admin_stylesheet' );
}
add_action( 'admin_enqueue_scripts', 'admin_styles' );

//At a Glance
add_action('dashboard_glance_items', 'add_custom_post_counts');
function add_custom_post_counts() {
   $post_types = array('policy','quality','institute','webinar','story','group','discussion','general'); // array of custom post types to add to 'At A Glance' widget
   foreach ($post_types as $pt) :
      $pt_info = get_post_type_object($pt); // get a specific CPT's details
      $num_posts = wp_count_posts($pt); // retrieve number of posts associated with this CPT
      $num = number_format_i18n($num_posts->publish); // number of published posts for this CPT
      $text = _n( $pt_info->labels->singular_name, $pt_info->labels->name, intval($num_posts->publish) ); // singular/plural text label for CPT
      echo '<li class="page-count '.$pt_info->name.'-count"><a href="edit.php?post_type='.$pt.'">'.$num.' '.$text.'</li>';
   endforeach;
}

//Constant Contact Sidebar Widget
register_sidebar(array(
  'name' => __( 'Constant Contact' ),
  'id' => 'email-reg',
  'description' => __( 'Used for Constant Contact widget only' ),
  'before_title' => '',
  'after_title' => ''
));


//RSS Feeds
add_action('init','newslineRSS');
function newslineRSS(){
	add_feed('newsline','newslineRSSFunc');
}
function newslineRSSFunc(){
	get_template_part('partial/feed','newsline');
}




//Private post - adds lock icon next to title if post is private
function private_lock($title){
	$pT = get_post_type();
	if(get_post_status() == 'private'){
		// Might aswell make use of this function to escape attributes
		$title = attribute_escape($title);
		// What to find in the title
		$findthese = array(
			'#Protected:#', // # is just the delimeter
			'#Private:#'
		);
		// What to replace it with
		$replacewith = array(
			'<span style="vertical-align:middle;border:none;" class="lock-icon"><img src="http://essentialhospitals.org/wp-content/themes/EssentialHospitals/images/lock'.$pT.'.png"></span>', // What to replace protected with
			'<span style="vertical-align:middle;border:none;" class="lock-icon"><img src="http://essentialhospitals.org/wp-content/themes/EssentialHospitals/images/lock'.$pT.'.png"></span>' // What to replace private with
		);
		// Items replace by array key
		$title = preg_replace($findthese, $replacewith, $title);
		return $title;
	}else{
		return $title;
	}
}
add_filter('the_title','private_lock');




//Cycle function
function cycle($first_value, $values = '*') {
  static $count = array();
  $values = func_get_args();
  $name = 'default';
  $last_item = end($values);
  if( substr($last_item, 0, 1) === ':' ) {
    $name = substr($last_item, 1);
    array_pop($values);
  }
  if( !isset($count[$name]) )
    $count[$name] = 0;
  $index = $count[$name] % count($values);
  $count[$name]++;
  return $values[$index];
}

//Truncate and close function
function html_cut($text, $max_length)
{
    $tags   = array();
    $result = "";

    $is_open   = false;
    $grab_open = false;
    $is_close  = false;
    $in_double_quotes = false;
    $in_single_quotes = false;
    $tag = "";

    $i = 0;
    $stripped = 0;

    $stripped_text = strip_tags($text);

    while ($i < strlen($text) && $stripped < strlen($stripped_text) && $stripped < $max_length)
    {
        $symbol  = $text{$i};
        $result .= $symbol;

        switch ($symbol)
        {
           case '<':
                $is_open   = true;
                $grab_open = true;
                break;

           case '"':
               if ($in_double_quotes)
                   $in_double_quotes = false;
               else
                   $in_double_quotes = true;

            break;

            case "'":
              if ($in_single_quotes)
                  $in_single_quotes = false;
              else
                  $in_single_quotes = true;

            break;

            case '/':
                if ($is_open && !$in_double_quotes && !$in_single_quotes)
                {
                    $is_close  = true;
                    $is_open   = false;
                    $grab_open = false;
                }

                break;

            case ' ':
                if ($is_open)
                    $grab_open = false;
                else
                    $stripped++;

                break;

            case '>':
                if ($is_open)
                {
                    $is_open   = false;
                    $grab_open = false;
                    array_push($tags, $tag);
                    $tag = "";
                }
                else if ($is_close)
                {
                    $is_close = false;
                    array_pop($tags);
                    $tag = "";
                }

                break;

            default:
                if ($grab_open || $is_close)
                    $tag .= $symbol;

                if (!$is_open && !$is_close)
                    $stripped++;
        }

        $i++;
    }

    while ($tags)
        $result .= "</".array_pop($tags).">";

    return $result;
}

//Cat and Tags for Media Library
function register_mediaCat_tax() {
  $labels = array(
    'name'          => _x( 'Media Category', 'taxonomy general name' ),
    'singular_name'     => _x( 'Media Category', 'taxonomy singular name' ),
    'add_new'         => 'Add New Media Category',
    'add_new_item'      => __( 'Add New Media Category' ),
    'edit_item'       => __( 'Edit Media Category' ),
    'new_item'        => __( 'New Media Category' ),
    'view_item'       => __( 'View Media Category' ),
    'search_items'      => __( 'Search Media Category' ),
    'not_found'       => __( 'No Media Categories found' ),
    'not_found_in_trash'  => __( 'No Media Categories found in Trash' ),
  );
  $pages = array('attachment');
  $args = array(
    'labels'      => $labels,
    'singular_label'  => __('Media Category'),
    'public'      => false,
    'show_ui'       => true,
    'hierarchical'    => true,
    'show_tagcloud'   => false,
    'show_in_nav_menus' => false,
    'rewrite'       => array('slug' => 'mediaCat', 'with_front' => false ),
   );
  register_taxonomy('mediaCat', $pages, $args);
}
add_action('init', 'register_mediaCat_tax');
add_filter( 'manage_taxonomies_for_attachment_columns', 'activity_type_columns' );
function activity_type_columns( $taxonomies ) {
    $taxonomies[] = 'mediaCat';
    return $taxonomies;
}
function wptp_add_tags_to_attachments() {
    register_taxonomy_for_object_type( 'post_tag', 'attachment' );
}
add_action( 'init' , 'wptp_add_tags_to_attachments' );

//Hide Member Network Pages
add_action( 'pre_get_posts', 'hide_member_network' );
function hide_member_network( $query ) {
	global $post_type, $current_user;
	$userRole = get_current_user_role();
    if ( is_admin() && $query->is_main_query() && $post_type == 'page' && $userRole != 'Administrator' || $query->is_search){
        $query->set('post__not_in', array(271,301,297,295,299,308,330,278,392,244,257,287,248,260,274,280,310,276,290,547));
    }
}
//Sort based on whether author is editor



//Get Current User Role
function get_current_user_role() {
	global $wp_roles;
	$current_user = wp_get_current_user();
	$roles = $current_user->roles;
	$role = array_shift($roles);
	return isset($wp_roles->role_names[$role]) ? translate_user_role($wp_roles->role_names[$role] ) : false;
}

//Show Future Posts
function show_future_posts($posts)
{
   global $wp_query, $wpdb;
   if(is_single() && $wp_query->post_count == 0)
   {
      $posts = $wpdb->get_results($wp_query->request);
   }
   return $posts;
}
add_filter('the_posts', 'show_future_posts');

//Get All Authors
function get_all_authors($authCount) {
	global $wpdb;
	$i = 0;
	foreach ( $wpdb->get_results("SELECT DISTINCT post_author, COUNT(ID) AS count FROM $wpdb->posts WHERE post_type = 'post' AND " . get_private_posts_cap_sql( 'post' ) . " GROUP BY post_author") as $row ){

		if($authCount && $i >= $authCount){ break; }

	    $author = get_userdata( $row->post_author );
	    $authors[$row->post_author]['name'] = $author->display_name;
	    $authors[$row->post_author]['post_count'] = $row->count;
	    $authors[$row->post_author]['ID'] = $author->ID;
	    $authors[$row->post_author]['desc'] = $author->user_description;
	    $authors[$row->post_author]['posts_url'] = get_author_posts_url( $author->ID, $author->user_nicename );
	    $authors[$row->post_author]['nice_name'] = $author->first_name.' '.$author->last_name;
	    $i++;
	}
	return $authors;
}

//Page Columns
function page_columns($columns)
{
	$columns = array(
		'cb'	 	=>  '<input type="checkbox" />',
		'title' 	=>  'Title',
		'author'	=>	'Author',
		'theme'		=>  'Theme',
		'date'		=>	'Date',
	);
	return $columns;
}

function custom_columns($column)
{
	global $post;
	if($column == 'theme')
	{
		echo get_field('theme', $post->ID);
	}
}
function column_register_sortable( $columns )
{
	$columns['theme'] = 'theme';
	return $columns;
}

add_filter("manage_edit-page_sortable_columns", "column_register_sortable" );
add_action("manage_pages_custom_column", "custom_columns");
add_filter("manage_edit-page_columns", "page_columns");

//Branding
function AEH_branding() {
    wp_enqueue_style('AEH-theme', get_template_directory_uri() . '/css/login.css');
}
add_action('login_enqueue_scripts', 'AEH_branding');

function AEH_editor_styles() {
    add_editor_style( 'css/editor.css' );
}
add_action( 'init', 'AEH_editor_styles' );

//Global Variables

add_action('init', 'register_my_menus');
add_action('init', 'loadup_scripts'); // Add Custom Scripts
function register_my_menus() {
	register_nav_menus(
		array(
			'primary-menu'   => __('Primary Menu'),
			'utility-menu'   => __('Utility Navigation'),
			'footer-menu'    => __('Footer Menu'),
			/*'action-nav'     => __('Action Menu'),
			'quality-nav'    => __('Quality Menu'),
			'institute-nav'  => __('Institute Menu'),
			'education-nav'  => __('Education Menu'),
			'member-network' => __('Member Network'),
			'ehu'            => __('Essential Hospitals U'),*/
			'general-nav'    => __('General Navigation - used on default page template'),
			'social-nav'	 => __('Social Navigation in footer')
		)
	);
}

//Nav Walker
class Menu_With_Description extends Walker_Nav_Menu {
	function start_el(&$output, $item, $depth, $args) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

		$attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) .'"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) .'"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) .'"' : '';

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '<br /><span class="sub">' . $item->description . '</span>';
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}


//Widget Sections
register_sidebar(array(
  'name' => __( 'Left Footer' ),
  'id' => 'left-footer',
  'description' => __( 'Footer - About' ),
  'before_title' => '<h2>',
  'after_title' => '</h2>',
  'before_widget' => '',
  'after_widget'  => '',
));
register_sidebar(array(
  'name' => __( 'Center Footer' ),
  'id' => 'center-footer',
  'description' => __( 'Footer - Center' ),
  'before_widget' => '',
  'after_widget'  => '',
));
register_sidebar(array(
  'name' => __( 'Right-Top Footer' ),
  'id' => 'righttop-footer',
  'description' => __( 'Footer - Right Top' ),
  'before_widget' => '',
  'after_widget'  => '',
));
register_sidebar(array(
  'name' => __( 'Right-Bottom Footer' ),
  'id' => 'rightbottom-footer',
  'description' => __( 'Footer - Right Bottom' ),
  'before_widget' => '',
  'after_widget'  => '',
));


register_sidebar(array(
  'name' => __( 'Footer - Contact Info' ),
  'id' => 'ffooter-contact',
  'description' => __( 'Contact info for America\'s Essential Hospitals'),
  'before_widget' => '',
  'after_widget'  => '',
));
register_sidebar(array(
  'name' => __( 'Footer - Department Contact' ),
  'id' => 'ffooter-departments',
  'description' => __( 'Contact info for specific departments of America\'s Essential Hospitals'),
  'before_widget' => '<div class="contact-section">',
  'after_widget'  => '</div>',
));
register_sidebar(array(
  'name' => __( 'Footer - Section Descriptions' ),
  'id' => 'ffooter-sections',
  'description' => __( 'Descriptions for the sections'),
  'before_widget' => '<div id="%1$s" class="desc-col %2$s">',
  'after_widget'  => '</div>',
  'before_title'  => '<h2 class="hidden">',
  'after_title'   => '</h2>'
));




if (!current_user_can('administrator')):
	show_admin_bar(false);
endif;


function loadup_scripts()
{
    if (!is_admin()) {
        wp_deregister_script('jquery'); // Deregister WordPress jQuery
        wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js' ); // Google CDN jQuery
        wp_enqueue_script('jquery'); // Enqueue it!


		//Pat Script - registered
		wp_register_script('kinetic', get_template_directory_uri() . '/js/jquery.kinetic.min.js');
		wp_register_script('hammer', get_template_directory_uri() . '/js/jquery.hammer.min.js');
        wp_register_script('masonry', get_template_directory_uri() . '/js/masonry.pkgd.min.js');
		wp_register_script('jquerytools', get_template_directory_uri() . '/js/jquery.tools.min.js');
		wp_register_script('themetools', get_template_directory_uri() . '/js/script-pat.js');
		wp_register_script('membernetwork', get_template_directory_uri() . '/js/theme.script.js');

        //Pat Script - queued
        wp_enqueue_script('kinetic');
        wp_enqueue_script('hammer');
        wp_enqueue_script('masonry');
        wp_enqueue_script('jquerytools');
        wp_enqueue_script('themetools');
        wp_enqueue_script('membernetwork');
    }
}

// Add Thumbnail Theme Support
add_theme_support('post-thumbnails');
add_image_size('large', 700, '', true); // Large Thumbnail
add_image_size('medium', 250, '', true); // Medium Thumbnail
add_image_size('small', 120, '', true); // Small Thumbnail
add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');

add_image_size('story-home',300,300,true);
add_image_size('story-focus',754,754,true);
add_image_size('story-nav',362,362,true);

// Register Widget Area for the Sidebar
register_sidebar( array(
	'name' => __( 'Primary Widget Area', 'Sidebar' ),
	'id' => 'primary-widget-area',
	'description' => __( 'The primary widget area', 'Sidebar' ),
	'before_widget' => '<div class="box">',
	'after_widget' => '</div>',
	'before_title' => '<h1>',
	'after_title' => '</h1>',
) );

// Load Optimised Google Analytics in the footer
// Change the UA-XXXXXXXX-X to your Account ID
function add_google_analytics()
{
    $google = "<!-- Google Analytics -->";
    $google .= "<script>";
    $google .= "var _gaq=[['_setAccount','UA-XXXXXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));";
    $google .= "</script>";
    echo $google;
}

//add_action('wp_footer', 'add_google_analytics'); // Google Analytics optimised in footer


/*** CLEAN UP FUNCTIONS ----------------------------------------*/

  /* admin part cleanups */
  add_action('admin_menu','remove_dashboard_widgets'); // cleaning dashboard widgets
  add_action('admin_menu', 'delete_menu_items'); // deleting menu items from admin area
  add_action('admin_menu','customize_meta_boxes'); // remove some meta boxes from pages and posts edition page
  add_filter('manage_posts_columns', 'custom_post_columns'); // remove column entries from list of posts
  add_filter('manage_pages_columns', 'custom_pages_columns'); // remove column entries from list of page
  add_action('wp_before_admin_bar_render', 'wce_admin_bar_render' ); // clean up the admin bar
  add_action('widgets_init', 'unregister_default_widgets', 11); // remove widgets from the widget page

  /* selfish frshstart plugins code parts*/
  add_action('admin_notices','rynonuke_update_notification_nonadmins',1); // remove notification for enayone but admin
  add_action('pre_ping','rynonuke_self_pings'); // disable self-trackbacking


  /***************** Security + header clean-ups ************************/

  /** remove the wlmanifest (useless !!) */
  remove_action('wp_head', 'wlwmanifest_link');
  remove_action('wp_head', 'rsd_link');
  remove_action( 'wp_head', 'index_rel_link' ); // index link
  remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
  remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
  remove_action('wp_head','start_post_rel_link');
  remove_action('wp_head','adjacent_posts_rel_link_wp_head');
  remove_action('wp_head', 'wp_generator'); // remove WP version from header
  remove_action('wp_head','wp_shortlink_wp_head');
  remove_filter( 'the_content', 'capital_P_dangit' ); // Get outta my Wordpress codez dangit!
  remove_filter( 'the_title', 'capital_P_dangit' );
  remove_filter( 'comment_text', 'capital_P_dangit' );


  // removes detailed login error information for security
  add_filter('login_errors',create_function('$a', "return null;"));

/*** cleaning up the dashboard- ----------------------------------------*/
function remove_dashboard_widgets(){

  //remove_meta_box('dashboard_right_now','dashboard','core'); // right now overview box
  remove_meta_box('dashboard_incoming_links','dashboard','core'); // incoming links box
  remove_meta_box('dashboard_quick_press','dashboard','core'); // quick press box
  remove_meta_box('dashboard_plugins','dashboard','core'); // new plugins box
  remove_meta_box('dashboard_recent_drafts','dashboard','core'); // recent drafts box
  remove_meta_box('dashboard_primary','dashboard','core'); // wordpress development blog box
  remove_meta_box('dashboard_secondary','dashboard','core'); // other wordpress news box


}

/* Remove some menus froms the admin area*/
function delete_menu_items() {
}

/* remove some meta boxes from pages and posts -------------------------
feel free to comment / uncomment  */

function customize_meta_boxes() {
  /* Removes meta boxes from pages */
  remove_meta_box('postcustom','page','normal'); // custom fields metabox
  remove_meta_box('trackbacksdiv','page','normal'); // trackbacks metabox
}

/** removing parts from column ------------------------------------------*/
/* use the column id, if you need to hide more of them
syntaxe : unset($defaults['columnID']);   */

/** remove column entries from posts **/
function custom_post_columns($defaults) {
  return $defaults;
}


/** remove column entries from pages **/
function custom_pages_columns($defaults) {
  return $defaults;
}

/** remove widgets from the widget page ------------------------------------*/
/* Credits : http://wpmu.org/how-to-remove-default-wordpress-widgets-and-clean-up-your-widgets-page/
uncomment what you want to remove  */
 function unregister_default_widgets() {
 }

/****** removings items froms admin bars
use the last part of the ID after "wp-admin-bar-" to add some menu to the list  exemple for comments : id="wp-admin-bar-comments" so the id to use is "comments"  ***********/
function wce_admin_bar_render() {
global $wp_admin_bar;
  $wp_admin_bar->remove_menu('wp-logo');
  $wp_admin_bar->remove_menu('updates');
  $wp_admin_bar->remove_menu('comments');
  $wp_admin_bar->remove_menu('new-content');

}
/*-----------------------------------------------------------------------**/




/**  Other usefull cleanups from selfish fresh start plugin http://wordpress.org/extend/plugins/selfish-fresh-start/ --------------------*/

// remove update notifications for everybody except admin users
function rynonuke_update_notification_nonadmins() {
  if (!current_user_can('administrator'))
    remove_action('admin_notices','update_nag',3);
}

// disable self-trackbacking
function rynonuke_self_pings( &$links ) {
    foreach ( $links as $l => $link )
        if ( 0 === strpos( $link, home_url() ) )
            unset($links[$l]);
}

/** WordPress user profil cleanups  ------------------------------------*/

/* remove the color scheme options */
  function admin_color_scheme() {
   global $_wp_admin_css_colors;
   $_wp_admin_css_colors = 0;
}

// add_action('admin_head', 'admin_color_scheme');

// rem/add user profile fields
function rynonuke_contactmethods($contactmethods) {
  unset($contactmethods['yim']);
  unset($contactmethods['aim']);
  unset($contactmethods['jabber']);
  $contactmethods['rynonuke_twitter']='Twitter';
  $contactmethods['rynonuke_facebook']='Facebook';
  return $contactmethods;
}


/*----------------------------------------------------------------------- **/

/*** Add a login stylesheet and a wordpress specific stylesheet------------
stylesheets are in the plugin directory, you can change the content to make it suite your needs. You'll also find a logo.png file, to brand the login form using your personnal logo
-----------*/

function style_my_login_please() {
}

/** stylesheet link for admin **/
function style_my_admin_please() {
}


/*-------------------FUNCTIONS--------------------- */
function wp_list_categories_for_post_type($post_type, $args = '') {
    $exclude = array();

    // Check ALL categories for posts of given post type
    foreach (get_categories() as $category) {
        $posts = get_posts(array('post_type' => $post_type, 'category' => $category->cat_ID));

        // If no posts found, ...
        if (empty($posts))
            // ...add category to exclude list
            $exclude[] = $category->cat_ID;
    }

    // Set up args
    if (! empty($exclude)) {
        $args .= ('' === $args) ? '' : '&';
        $args .= 'exclude='.implode(',', $exclude);
    }

    // List categories
    //wp_get_categories($args);
    return $args;
}



/*---------------------------CUSTOM POST TYPES----------------------------- **/

$themeDIR = get_bloginfo('template_directory');
  //Stories CPT
  function register_stories_posttype() {
    $labels = array(
      'name'        => _x( 'Stories', 'post type general name' ),
      'singular_name'   => _x( 'Story', 'post type singular name' ),
      'add_new'       => __( 'Add New' ),
      'add_new_item'    => __( 'Story' ),
      'edit_item'     => __( 'Edit Story' ),
      'new_item'      => __( 'New Story' ),
      'view_item'     => __( 'View Story' ),
      'search_items'    => __( 'Search Stories' ),
      'not_found'     => __( 'No Stories Found' ),
      'not_found_in_trash'=> __( 'No Stories in Trash' ),
      'parent_item_colon' => __( 'Story' ),
      'menu_name'     => __( 'Stories' )
    );

    $taxonomies = array();

    $supports = array('title');

    $post_type_args = array(
      'labels'      => $labels,
      'singular_label'  => __('Story'),
      'public'      => true,
      'show_ui'       => true,
      'publicly_queryable'=> true,
      'query_var'     => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus' => false,
      'capability_type'   => 'post',
      'has_archive'     => true,
      'hierarchical'    => false,
      'rewrite'       => array('slug' => 'stories', 'with_front' => false ),
      'supports'      => $supports,
      'menu_position'   => 5,
      'taxonomies'    => $taxonomies
     );
     register_post_type('story',$post_type_args);
  }
  add_action('init', 'register_stories_posttype');


  // registration code for general post type
  function register_general_posttype() {
    $labels = array(
      'name'        => _x( 'General', 'post type general name' ),
      'singular_name'   => _x( 'General', 'post type singular name' ),
      'add_new'       => __( 'Add New' ),
      'add_new_item'    => __( 'Add new General' ),
      'edit_item'     => __( 'Edit General' ),
      'new_item'      => __( 'New General' ),
      'view_item'     => __( 'View General article' ),
      'search_items'    => __( 'Search General' ),
      'not_found'     => __( 'No General articles found' ),
      'not_found_in_trash'=> __( 'No General articles found' ),
      'parent_item_colon' => __( 'General' ),
      'menu_name'     => __( 'General' )
    );

    $taxonomies = array('series','category','post_tag');

    $supports = array('title','editor','thumbnail','excerpt','custom-fields','comments','revisions','author');

    $post_type_args = array(
      'labels'      => $labels,
      'singular_label'  => __('General'),
      'public'      => true,
      'show_ui'       => true,
      'publicly_queryable'=> true,
      'query_var'     => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus' => false,
      'capability_type'   => 'post',
      'has_archive'     => true,
      'hierarchical'    => true,
      'rewrite'       => array('slug' => 'general', 'with_front' => false ),
      'supports'      => $supports,
      'menu_position'   => 5,
      'taxonomies'    => $taxonomies
     );
     register_post_type('general',$post_type_args);
  }
  add_action('init', 'register_general_posttype');


  // registration code for webinars post type
  function register_webinar_posttype() {
    $labels = array(
      'name'        => _x( 'Webinars', 'post type general name' ),
      'singular_name'   => _x( 'Webinar', 'post type singular name' ),
      'add_new'       => __( 'Add New' ),
      'add_new_item'    => __( 'Add new Webinar' ),
      'edit_item'     => __( 'Edit Webinar' ),
      'new_item'      => __( 'New Webinar' ),
      'view_item'     => __( 'View Webinar' ),
      'search_items'    => __( 'Search Webinars' ),
      'not_found'     => __( 'No Webinars found' ),
      'not_found_in_trash'=> __( 'No Webinars found' ),
      'parent_item_colon' => __( 'Webinar' ),
      'menu_name'     => __( 'Webinars' )
    );

    $taxonomies = array('post_tag', 'policytopics', 'educationtopics', 'qualitytopics', 'institutetopics','webinartopics');

    $supports = array('title','editor','thumbnail','excerpt','custom-fields','comments','revisions','author');

    $post_type_args = array(
      'labels'      => $labels,
      'singular_label'  => __('Webinar'),
      'public'      => true,
      'show_ui'       => true,
      'publicly_queryable'=> true,
      'query_var'     => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus' => false,
      'capability_type'   => 'post',
      'has_archive'     => true,
      'hierarchical'    => true,
      'rewrite'       => array('slug' => 'webinar', 'with_front' => false ),
      'supports'      => $supports,
      'menu_position'   => 5,
      'menu_icon'     => get_bloginfo('template_directory').'/images/education-menu.png',
      'taxonomies'    => $taxonomies
     );
     register_post_type('webinar',$post_type_args);
  }
  add_action('init', 'register_webinar_posttype');

  // registration code for alerts post type
  function register_alerts_posttype() {
    $labels = array(
      'name'        => _x( 'Alerts', 'post type general name' ),
      'singular_name'   => _x( 'Alert', 'post type singular name' ),
      'add_new'       => __( 'Add New' ),
      'add_new_item'    => __( 'Add new Alert' ),
      'edit_item'     => __( 'Edit Alert' ),
      'new_item'      => __( 'New Alert' ),
      'view_item'     => __( 'View Alert' ),
      'search_items'    => __( 'Search Alerts' ),
      'not_found'     => __( 'No Alerts found' ),
      'not_found_in_trash'=> __( 'No Alerts found' ),
      'parent_item_colon' => __( 'No Alerts found' ),
      'menu_name'     => __( 'Announcements' )
    );

    $taxonomies = array('post_tag', 'policytopics', 'educationtopics', 'qualitytopics', 'institutetopics','category');

    $supports = array('title','excerpt');

    $post_type_args = array(
      'labels'      => $labels,
      'singular_label'  => __('Alert'),
      'public'      => true,
      'show_ui'       => true,
      'publicly_queryable'=> true,
      'query_var'     => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus' => false,
      'capability_type'   => 'post',
      'has_archive'     => true,
      'hierarchical'    => true,
      'rewrite'       => array('slug' => 'alert', 'with_front' => false ),
      'supports'      => $supports,
      'menu_position'   => 5,
      'taxonomies'    => $taxonomies
     );
     register_post_type('alert',$post_type_args);
  }
  add_action('init', 'register_alerts_posttype');

  // registration code for policy post type
  function register_policy_posttype() {
    $labels = array(
      'name'        => _x( 'Action', 'post type general name' ),
      'singular_name'   => _x( 'Action', 'post type singular name' ),
      'add_new'       => __( 'Add New' ),
      'add_new_item'    => __( 'Add new Action article' ),
      'edit_item'     => __( 'Edit Action' ),
      'new_item'      => __( 'New Action article' ),
      'view_item'     => __( 'View Action article' ),
      'search_items'    => __( 'Search Action articles' ),
      'not_found'     => __( 'No Action articles found' ),
      'not_found_in_trash'=> __( 'No Action articles found' ),
      'parent_item_colon' => __( 'Action' ),
      'menu_name'     => __( 'Action' )
    );

    $taxonomies = array('post_tag', 'policytopics', 'educationtopics', 'qualitytopics', 'institutetopics');

    $supports = array('title','editor','author','thumbnail','excerpt','custom-fields','comments','revisions');

    $post_type_args = array(
      'labels'      => $labels,
      'singular_label'  => __('Policy'),
      'public'      => true,
      'show_ui'       => true,
      'publicly_queryable'=> true,
      'query_var'     => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus' => false,
      'capability_type'   => 'post',
      'has_archive'     => true,
      'hierarchical'    => true,
      'rewrite'       => array('slug' => 'policy', 'with_front' => false ),
      'supports'      => $supports,
      'menu_position'   => 2,
      'menu_icon'     => get_bloginfo('template_directory').'/images/policy-menu.png',
      'taxonomies'    => $taxonomies
     );
     register_post_type('policy',$post_type_args);
  }
  add_action('init', 'register_policy_posttype');

  // registration code for quality post type
  function register_quality_posttype() {
    $labels = array(
      'name'        => _x( 'Quality', 'post type general name' ),
      'singular_name'   => _x( 'Quality', 'post type singular name' ),
      'add_new'       => __( 'Add New' ),
      'add_new_item'    => __( 'Add new Quality article' ),
      'edit_item'     => __( 'Edit Quality' ),
      'new_item'      => __( 'New Quality article' ),
      'view_item'     => __( 'View Quality articles' ),
      'search_items'    => __( 'Search Quality articles' ),
      'not_found'     => __( 'No Quality articles found' ),
      'not_found_in_trash'=> __( 'No Quality articles found' ),
      'parent_item_colon' => __( 'Quality' ),
      'menu_name'     => __( 'Quality' )
    );

     $taxonomies = array('post_tag', 'policytopics', 'educationtopics', 'qualitytopics', 'institutetopics');

    $supports = array('title','editor','author','thumbnail','excerpt','custom-fields','comments','revisions');

    $post_type_args = array(
      'labels'      => $labels,
      'singular_label'  => __('Quality'),
      'public'      => true,
      'show_ui'       => true,
      'publicly_queryable'=> true,
      'query_var'     => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus' => true,
      'capability_type'   => 'post',
      'has_archive'     => false,
      'hierarchical'    => false,
      'rewrite'       => array('slug' => 'quality', 'with_front' => false ),
      'supports'      => $supports,
      'menu_position'   => 3,
      'menu_icon'     => get_bloginfo('template_directory').'//images/quality-menu.png',
      'taxonomies'    => $taxonomies
     );
     register_post_type('quality',$post_type_args);
  }
  add_action('init', 'register_quality_posttype');

  // registration code for institute post type
  function register_institute_posttype() {
    $labels = array(
      'name'        => _x( 'Institute', 'post type general name' ),
      'singular_name'   => _x( 'Institute', 'post type singular name' ),
      'add_new'       => __( 'Add New' ),
      'add_new_item'    => __( 'Add new Institute article' ),
      'edit_item'     => __( 'Edit Institute' ),
      'new_item'      => __( 'New Institute article' ),
      'view_item'     => __( 'View Institute article' ),
      'search_items'    => __( 'Search Institute articles' ),
      'not_found'     => __( 'No Institute articles found' ),
      'not_found_in_trash'=> __( 'No Institute articles found' ),
      'parent_item_colon' => __( 'Institute' ),
      'menu_name'     => __( 'Institute' )
    );

    $taxonomies = array('post_tag', 'policytopics', 'educationtopics', 'qualitytopics', 'institutetopics');

    $supports = array('title','editor','author','thumbnail','excerpt','custom-fields','comments','revisions');

    $post_type_args = array(
      'labels'      => $labels,
      'singular_label'  => __('Institute'),
      'public'      => true,
      'show_ui'       => true,
      'publicly_queryable'=> true,
      'query_var'     => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus' => true,
      'capability_type'   => 'post',
      'has_archive'     => false,
      'hierarchical'    => false,
      'rewrite'       => array('slug' => 'institute', 'with_front' => false ),
      'supports'      => $supports,
      'menu_position'   => 4,
      'menu_icon'     => get_bloginfo('template_directory').'/images/institute-menu.png',
      'taxonomies'    => $taxonomies
     );
     register_post_type('institute',$post_type_args);
  }
  add_action('init', 'register_institute_posttype');

  // registration code for discussion post type
  function register_discussion_posttype() {
    $labels = array(
      'name'        => _x( 'Discussions', 'post type general name' ),
      'singular_name'   => _x( 'Discussion', 'post type singular name' ),
      'add_new'       => __( 'Add New' ),
      'add_new_item'    => __( 'Add new Discussion' ),
      'edit_item'     => __( 'Edit Discussion' ),
      'new_item'      => __( 'New Discussion' ),
      'view_item'     => __( 'View Discussion' ),
      'search_items'    => __( 'Search Discussions' ),
      'not_found'     => __( 'No Discussions found' ),
      'not_found_in_trash'=> __( 'No Discussions found' ),
      'parent_item_colon' => __( 'Discussion' ),
      'menu_name'     => __( 'Discussions' )
    );

     $taxonomies = array('discussions','discussion_tags');

    $supports = array('title','editor','author','comments','custom-fields');

    $post_type_args = array(
      'labels'      => $labels,
      'singular_label'  => __('Discussion'),
      'public'      => true,
      'show_ui'       => true,
      'publicly_queryable'=> true,
      'query_var'     => true,
      'exclude_from_search'=> true,
      'show_in_nav_menus' => true,
      'capability_type'   => 'post',
      'has_archive'     => true,
      'rewrite'       => array('slug' => 'discussions', 'with_front' => false ),
      'supports'      => $supports,
      'menu_position'   => 5,
      'taxonomies'    => $taxonomies
     );
     register_post_type('discussion',$post_type_args);
  }
  add_action('init', 'register_discussion_posttype');


  // registration code for discussion post type
  function register_group_posttype() {
    $labels = array(
      'name'        => _x( 'Groups', 'post type general name' ),
      'singular_name'   => _x( 'Group', 'post type singular name' ),
      'add_new'       => __( 'Add New' ),
      'add_new_item'    => __( 'Add new Group' ),
      'edit_item'     => __( 'Edit Group' ),
      'new_item'      => __( 'New Group' ),
      'view_item'     => __( 'View Group' ),
      'search_items'    => __( 'Search Groups' ),
      'not_found'     => __( 'No Groups found' ),
      'not_found_in_trash'=> __( 'No Group found' ),
      'parent_item_colon' => __( 'Group' ),
      'menu_name'     => __( 'Groups' )
    );

     $taxonomies = array('post_tag', 'groups');

    $supports = array('title','editor','author','comments','page-attributes','custom-fields','revisions');

    $post_type_args = array(
      'labels'      => $labels,
      'singular_label'  => __('Group'),
      'public'      => true,
      'show_ui'       => true,
      'publicly_queryable'=> true,
      'query_var'     => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus' => true,
      'capability_type'   => 'post',
      'has_archive'     => true,
      'hierarchical'    => true,
      'rewrite'       => array('slug' => 'groups', 'with_front' => false ),
      'supports'      => $supports,
      'menu_position'   => 5,
      //'menu_icon'     => get_bloginfo('template_directory').'//images/quality-menu.png',
      'taxonomies'    => $taxonomies,
      'exclude_from_search' => true
     );
     register_post_type('group',$post_type_args);
  }
  add_action('init', 'register_group_posttype');

  //Menu Order
  function custom_menu_order($menu_ord) {
	    if (!$menu_ord) return true;

	    return array(
	        'index.php', 							// Dashboard
	        'edit.php?post_type=policy', 			// Policy
	        'edit.php?post_type=quality', 			// Quality
	        'edit.php?post_type=institute',			// Insitute
	        'edit.php?post_type=webinar',			// Webinars
					'edit.php?post_type=events',
					'edit.php?post_type=presentation',
	        'edit.php', 							// Posts
	        'edit.php?post_type=page', 				// Pages
	        'separator1', 							// First separator
	        'edit.php?post_type=group',				// Groups
	        'edit.php?post_type=discussion',		// Discussions
	        'edit.php?post_type=story', 			// Stories
	        'edit.php?post_type=alert',				// Alerts
	        'edit.php?post_type=general',			// General
	        'edit-comments.php', 					// Comments
	        'upload.php', 							// Media
	        'separator2', 							// Second separator
	        'themes.php', 							// Appearance
	        'plugins.php', 							// Plugins
	        'users.php', 							// Users
	        'tools.php', 							// Tools
	        'options-general.php',					// Settings
	        'separator-last', 						// Last separator
	    );
	}
	add_filter('custom_menu_order', 'custom_menu_order'); // Activate custom_menu_order
	add_filter('menu_order', 'custom_menu_order');

  //Remove preview from posttype
  function posttype_admin_css() {
	    global $post_type;
	    $post_types = array('group','webinar');
	    if(in_array($post_type, $post_types))
	    echo '<style type="text/css">li#wp-admin-bar-view,#post-preview, #view-post-btn{display: none;}</style>';
	}
	//add_action( 'admin_head-post-new.php', 'posttype_admin_css' );
	//add_action( 'admin_head-post.php', 'posttype_admin_css' );


  //-------------CUSTOM TAXONOMIES----------------------------------------------------------------------------//

  	// registration code for series taxonomy
    function register_series_tax() {
      $labels = array(
        'name'          => _x( 'Stream', 'taxonomy general name' ),
        'singular_name'     => _x( 'Stream', 'taxonomy singular name' ),
        'add_new'         => _x( 'Add New Stream', 'Stream'),
        'add_new_item'      => __( 'Add New Stream' ),
        'edit_item'       => __( 'Edit Stream' ),
        'new_item'        => __( 'New Stream' ),
        'view_item'       => __( 'View Stream' ),
        'search_items'      => __( 'Search Streams' ),
        'not_found'       => __( 'No Streams found' ),
        'not_found_in_trash'  => __( 'No Streams found in Trash' ),
      );

      $pages = array('policy','quality','externallinks','institute','general');

      $args = array(
        'labels'      => $labels,
        'singular_label'  => __('Stream'),
        'public'      => true,
        'show_ui'       => true,
        'hierarchical'    => true,
        'show_tagcloud'   => false,
        'show_in_nav_menus' => true,
        'rewrite'       => array('slug' => 'series', 'with_front' => false ),
       );
      register_taxonomy('series', $pages, $args);
    }
    add_action('init', 'register_series_tax');

    // registration code for educationtopics taxonomy
    function register_educationtopics_tax() {
      $labels = array(
        'name'          => _x( 'Education Topics', 'taxonomy general name' ),
        'singular_name'     => _x( 'Education Topic', 'taxonomy singular name' ),
        'add_new'         => _x( 'Add New Education Topic', 'Education Topic'),
        'add_new_item'      => __( 'Add New Education Topic' ),
        'edit_item'       => __( 'Edit Education Topic' ),
        'new_item'        => __( 'New Education Topic' ),
        'view_item'       => __( 'View Education Topic' ),
        'search_items'      => __( 'Search Education Topics' ),
        'not_found'       => __( 'No Education Topic found' ),
        'not_found_in_trash'  => __( 'No Education Topic found in Trash' ),
      );

      $pages = array('policy','quality','institute','externallinks');

      $args = array(
        'labels'      => $labels,
        'singular_label'  => __('Education Topic'),
        'public'      => true,
        'show_ui'       => true,
        'hierarchical'    => true,
        'show_tagcloud'   => false,
        'show_in_nav_menus' => true,
        'rewrite'       => array('slug' => 'educationtopics', 'with_front' => false ),
       );
      register_taxonomy('educationtopics', $pages, $args);
    }
    add_action('init', 'register_educationtopics_tax');

    // registration code for educationtopics taxonomy
    function register_qualitytopics_tax() {
      $labels = array(
        'name'          => _x( 'Quality Topics', 'taxonomy general name' ),
        'singular_name'     => _x( 'Quality Topic', 'taxonomy singular name' ),
        'add_new'         => _x( 'Add New Quality Topic', 'Quality Topic'),
        'add_new_item'      => __( 'Add New Quality Topic' ),
        'edit_item'       => __( 'Edit Quality Topic' ),
        'new_item'        => __( 'New Quality Topic' ),
        'view_item'       => __( 'View Quality Topic' ),
        'search_items'      => __( 'Search Quality Topics' ),
        'not_found'       => __( 'No Quality Topic found' ),
        'not_found_in_trash'  => __( 'No Quality Topic found in Trash' ),
      );

      $pages = array('policy','quality','institute','externallinks','post','webinar');

      $args = array(
        'labels'      => $labels,
        'singular_label'  => __('Quality Topic'),
        'public'      => true,
        'show_ui'       => true,
        'hierarchical'    => true,
        'show_tagcloud'   => false,
        'show_in_nav_menus' => true,
        'rewrite'       => array('slug' => 'qualitytopics', 'with_front' => false ),
       );
      register_taxonomy('qualitytopics', $pages, $args);
    }
    add_action('init', 'register_qualitytopics_tax');


    // registration code for educationtopics taxonomy
    function register_policytopics_tax() {
      $labels = array(
        'name'          => _x( 'Action Topics', 'taxonomy general name' ),
        'singular_name'     => _x( 'Action Topic', 'taxonomy singular name' ),
        'add_new'         => _x( 'Add New Action Topic', 'Policy Topic'),
        'add_new_item'      => __( 'Add New Action Topic' ),
        'edit_item'       => __( 'Edit Action Topic' ),
        'new_item'        => __( 'New Action Topic' ),
        'view_item'       => __( 'View Action Topic' ),
        'search_items'      => __( 'Search Action Topics' ),
        'not_found'       => __( 'No Action Topic found' ),
        'not_found_in_trash'  => __( 'No Action Topic found in Trash' ),
      );

      $pages = array('policy','quality','institute','externallinks','post','webinar');

      $args = array(
        'labels'      => $labels,
        'singular_label'  => __('Action Topic'),
        'public'      => true,
        'show_ui'       => true,
        'hierarchical'    => true,
        'show_tagcloud'   => false,
        'show_in_nav_menus' => true,
        'rewrite'       => array('slug' => 'policytopics', 'with_front' => false ),
       );
      register_taxonomy('policytopics', $pages, $args);
    }
    add_action('init', 'register_policytopics_tax');


    // registration code for educationtopics taxonomy
    function register_institutetopics_tax() {
      $labels = array(
        'name'          => _x( 'Institute Topics', 'taxonomy general name' ),
        'singular_name'     => _x( 'Institute Topic', 'taxonomy singular name' ),
        'add_new'         => _x( 'Add New Institute Topic', 'Institute Topic'),
        'add_new_item'      => __( 'Add New Institute Topic' ),
        'edit_item'       => __( 'Edit Institute Topic' ),
        'new_item'        => __( 'New Institute Topic' ),
        'view_item'       => __( 'View Institute Topic' ),
        'search_items'      => __( 'Search Institute Topics' ),
        'not_found'       => __( 'No Institute Topic found' ),
        'not_found_in_trash'  => __( 'No Institute Topic found in Trash' ),
      );

      $pages = array('policy','quality','institute','externallinks');

      $args = array(
        'labels'      => $labels,
        'singular_label'  => __('Institute Topic'),
        'public'      => true,
        'show_ui'       => true,
        'hierarchical'    => true,
        'show_tagcloud'   => false,
        'show_in_nav_menus' => true,
        'rewrite'       => array('slug' => 'institutetopics', 'with_front' => false ),
       );
      register_taxonomy('institutetopics', $pages, $args);
    }
    add_action('init', 'register_institutetopics_tax');

    // registration code for institute centers taxonomy
    function register_institutecenters_tax() {
      $labels = array(
        'name'          => _x( 'Institute Centers', 'taxonomy general name' ),
        'singular_name'     => _x( 'Institute Center', 'taxonomy singular name' ),
        'add_new'         => _x( 'Add New Institute Center', 'Institute Center'),
        'add_new_item'      => __( 'Add New Institute Center' ),
        'edit_item'       => __( 'Edit Institute Center' ),
        'new_item'        => __( 'New Institute Center' ),
        'view_item'       => __( 'View Institute Center' ),
        'search_items'      => __( 'Search Institute Centers' ),
        'not_found'       => __( 'No Institute Center found' ),
        'not_found_in_trash'  => __( 'No Institute Center found in Trash' ),
      );

      $pages = array('institute','externallinks');

      $args = array(
        'labels'      => $labels,
        'singular_label'  => __('Institute Center'),
        'public'      => true,
        'show_ui'       => true,
        'hierarchical'    => true,
        'show_tagcloud'   => false,
        'show_in_nav_menus' => true,
        'has_archive' => true,
        'rewrite'       => array('slug' => 'center', 'with_front' => false ),
       );
      register_taxonomy('centers', $pages, $args);
    }
    add_action('init', 'register_institutecenters_tax');

    //Description Field for Centers
    function centers_tax_fields($tag){
	    // Check for existing taxonomy meta for the term you're editing
	    $t_id = $tag->term_id; // Get the ID of the term you're editing
	    $term_meta = get_option( "taxonomy_term_$t_id" ); // Do the check
	?>

	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="presenter_id">About this Center</label>
		</th>
		<td>
			<textarea type="text" name="term_meta[about_center]" id="term_meta[about_center]" rows="5" style="width:95%;"><?php echo $term_meta['about_center'] ? $term_meta['about_center'] : ''; ?></textarea><br />
			<span class="description">About paragraph that will display on the archive page</span>
		</td>
	</tr>

	<?php
    }
    function centers_save_tax_fields($term_id){
	    if ( isset( $_POST['term_meta'] ) ) {
		        $t_id = $term_id;
		        $term_meta = get_option( "taxonomy_term_$t_id" );
		        $cat_keys = array_keys( $_POST['term_meta'] );
		            foreach ( $cat_keys as $key ){
		            if ( isset( $_POST['term_meta'][$key] ) ){
		                $term_meta[$key] = $_POST['term_meta'][$key];
		            }
		        }
		        //save the option array
		        update_option( "taxonomy_term_$t_id", $term_meta );
		    }
		}
    add_action( 'centers_edit_form_fields', 'centers_tax_fields', 10, 2 );
    add_action( 'edited_centers', 'centers_save_tax_fields', 10, 2 );

	// registration code for webinar topics taxonomy
    function register_webinartopics_tax() {
      $labels = array(
        'name'          => _x( 'Webinar Topics', 'taxonomy general name' ),
        'singular_name'     => _x( 'Webinar Topic', 'taxonomy singular name' ),
        'add_new'         => _x( 'Add New Webinar Topic', 'Institute Center'),
        'add_new_item'      => __( 'Add New Webinar Topic' ),
        'edit_item'       => __( 'Edit Webinar Topic' ),
        'new_item'        => __( 'New Webinar Topic' ),
        'view_item'       => __( 'View Webinar Topic' ),
        'search_items'      => __( 'Search Webinar Topics' ),
        'not_found'       => __( 'No Webinar Topics found' ),
        'not_found_in_trash'  => __( 'No Webinar Topics found in Trash' ),
      );

      $pages = array('webinar');

      $args = array(
        'labels'      => $labels,
        'singular_label'  => __('Webinar Topic'),
        'public'      => true,
        'show_ui'       => true,
        'hierarchical'    => true,
        'show_tagcloud'   => false,
        'show_in_nav_menus' => true,
        'has_archive' => true,
        'rewrite'       => array('slug' => 'webinars', 'with_front' => false ),
       );
      register_taxonomy('webinartopics', $pages, $args);
    }
    add_action('init', 'register_webinartopics_tax');

    // registration code for discussion taxonomy
    function register_discussion_tax() {
      $labels = array(
        'name'          => _x( 'Discussions', 'taxonomy general name' ),
        'singular_name'     => _x( 'Discussion', 'taxonomy singular name' ),
        'add_new'         => _x( 'Add New Discussion', 'Institute Center'),
        'add_new_item'      => __( 'Add New Discussion' ),
        'edit_item'       => __( 'Edit Discussion' ),
        'new_item'        => __( 'New Discussion' ),
        'view_item'       => __( 'View Discussions' ),
        'search_items'      => __( 'Search Discussions' ),
        'not_found'       => __( 'No Discussions found' ),
        'not_found_in_trash'  => __( 'No Discussions found in Trash' ),
      );

      $pages = array('discussion');

      $args = array(
        'labels'      => $labels,
        'singular_label'  => __('Discussion'),
        'public'      => true,
        'show_ui'       => true,
        'hierarchical'    => true,
        'show_tagcloud'   => false,
        'show_in_nav_menus' => false,
        'rewrite'       => array('slug' => 'discussions', 'with_front' => false ),
       );
      register_taxonomy('discussions', $pages, $args);
    }
    add_action('init', 'register_discussion_tax');

     // registration code for discussion tags taxonomy
    function register_discussionTags_tax() {
      $labels = array(
        'name'          => _x( 'Discussion Tags', 'taxonomy general name' ),
        'singular_name'     => _x( 'Discussion Tag', 'taxonomy singular name' ),
        'add_new'         => _x( 'Add New Discussion Tag', 'Institute Center'),
        'add_new_item'      => __( 'Add New Discussion Tag' ),
        'edit_item'       => __( 'Edit Discussion Tag' ),
        'new_item'        => __( 'New Discussion Tag' ),
        'view_item'       => __( 'View Discussion Tags' ),
        'search_items'      => __( 'Search Discussion Tags' ),
        'not_found'       => __( 'No Discussion Tags found' ),
        'not_found_in_trash'  => __( 'No Discussion Tags found in Trash' ),
      );

      $pages = array('discussion');

      $args = array(
        'labels'      => $labels,
        'singular_label'  => __('Discussion Tags'),
        'public'      => true,
        'show_ui'       => true,
        'hierarchical'    => true,
        'show_tagcloud'   => true,
        'show_in_nav_menus' => false,
        'has_archive' => true,
        'rewrite'       => array('slug' => 'discussion_tags', 'with_front' => false ),
       );
      register_taxonomy('discussion_tags', $pages, $args);
    }
    add_action('init', 'register_discussionTags_tax');



    function new_excerpt_more( $more ) {
        return ' ';
    }
    add_filter('excerpt_more', 'new_excerpt_more');

    add_filter('the_excerpt', 'my_excerpts');

    function my_excerpts($content = false) {
            global $post;
            $mycontent = $post->post_excerpt;

            $mycontent = $post->post_content;
            $mycontent = strip_shortcodes($mycontent);
            $mycontent = str_replace(']]>', ']]&gt;', $mycontent);
            $mycontent = strip_tags($mycontent);
            $excerpt_length = 30;
            $words = explode(' ', $mycontent, $excerpt_length + 1);
            if(count($words) > $excerpt_length) :
                array_pop($words);
                array_push($words, '...');
                $mycontent = implode(' ', $words);
            endif;
            $mycontent = '<p>' . $mycontent . '</p>';
    // Make sure to return the content
    return $mycontent;
}

function mytheme_enqueue_comment_reply() {
    // on single blog post pages with comments open and threaded comments
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        // enqueue the javascript that performs in-link comment reply fanciness
        wp_enqueue_script( 'comment-reply' );
    }
}
// Hook into wp_enqueue_scripts
add_action( 'wp_enqueue_scripts', 'mytheme_enqueue_comment_reply' );

function wpa_cpt_tags( $query ) {
    if ( $query->is_tag() && $query->is_main_query() ) {
        $query->set( 'post_type', array( 'policy', 'quality', 'institute' ,'webinar','education') );
    }
}
add_action( 'pre_get_posts', 'wpa_cpt_tags' );

	///////////////////////////////////////
	// Register Custom Menu Function
	///////////////////////////////////////
	/*if (function_exists('register_nav_menus')) {
		register_nav_menus( array(
			'main-nav' => __( 'Main Navigation', 'themify' ),
			'alt-nav' => __( 'Logged-in Navigation', 'themify' ),
			'footer-nav' => __( 'Footer Navigation', 'themify' ),
		) );
	}*/

    ///////////////////////////////////////
	// Default Alternative Nav Function (When Already Logged in)
	///////////////////////////////////////
	function default_alt_nav() {
		echo '<ul id="alt-nav" class="alt-nav clearfix">';
		wp_list_pages('title_li=');
		echo '</ul>';
	}


    ///////////////////////////////////////
	// Content for the Member Profile page
	///////////////////////////////////////

add_filter( 'wpmem_member_links', 'my_member_links' );
function my_member_links( $links )
{
// get the current_user object
global $current_user;
get_currentuserinfo();

     // format the date they registered
$regdate = strtotime( $current_user->user_registered );

// and the user info
$str = '<div id="theuser">
<h3 id="userlogin"><span style="color: white">' . $current_user->user_login . '</span></h3>
        <div id="useravatar">' . get_avatar( $current_user->ID, '82' ) . '</div>
          <dl id="userinfo">
			  <dt>Member Since</dt>
			  <dd>' . date( 'M d, Y', $regdate ) . '</dd>
			  <dt>Website</dt>
			  <dd><a class="url" href="' . $current_user->user_url . '" rel="nofollow">' . $current_user->user_url . '</a></dd>
			  <dt>Location</dt>
			  <dd>'
				. get_user_meta( $current_user->ID, 'city', true )
				. ', '
				. get_user_meta( $current_user->ID, 'thestate', true )
				. '</dd>
          </dl>
        </div>
        <hr />';

     // tag the original links on to the end
     $string = $str . $links;

     // send back our content
     return $string;
}

	///////////////////////////////////////
	// Register Sidebar A and B
	///////////////////////////////////////
	if ( function_exists('register_sidebar') ) {
		register_sidebar(array(
			'name' => 'Sidebar A',
			'id' => 'sidebara',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
	}
	if ( function_exists('register_sidebar') ) {
		register_sidebar(array(
			'name' => 'Sidebar B',
			'id' => 'sidebarb',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
	}

	/* get featured image url function */
	function mdw_featured_img_url( $mdw_featured_img_size ) {
		$mdw_image_id = get_post_thumbnail_id();
		$mdw_image_url = wp_get_attachment_image_src( $mdw_image_id, $mdw_featured_img_size );
		$mdw_image_url = $mdw_image_url[0];
		return $mdw_image_url;
	}

add_image_size('cat-thumb', 120, 90);

/***************************************************************************************************/
function aeh_member($user_id) {
	$user_info = get_userdata($user_id);
	$result = check_aeh_email($user_info->user_email);          // key 0 => member_type, 1 => domain, 2 => staff(Y/N), 3 => organization
	update_user_meta( $user_id, 'email_domain', $domain );	    // save the domain in a new user_meta value
	update_user_meta( $user_id, 'aeh_member_type', $result[0]); // initialize the member type in meta table
	update_user_meta( $user_id, 'aeh_staff', $result[2]);	    // initialize the staff type in meta table
	update_user_meta( $user_id, 'title', "");
	update_user_meta( $user_id, 'job_title', "");
	update_user_meta( $user_id, 'job_function', "");
	update_user_meta( $user_id, 'employer', "");
	update_user_meta( $user_id, 'tos', 'agree');
	delete_user_meta( $user_id, 'password'); 					// remove the plaintext password from the DB
}

add_action('user_register', 'aeh_member', 10, 2);
//add_action('bp_core_signup_user', 'aeh_member', 10, 2);

define ("CUSTOM_NEWS_URL", home_url("custom-news-feed/"));

function get_member_type(){
	$member = array();
	$current_user= wp_get_current_user();
	$member[0]   = $current_user->ID;
	$member[1]   = get_user_meta($member[0], 'aeh_member_type', TRUE);
	$member[2]   = get_user_meta($member[0], 'email_domain', TRUE);
	return $member;
}

function alertbox_check($userID){
	$return = ""; //presume no need for alerts at first
	if (get_user_meta($userID, 'custom_news_feed', TRUE) == ""){
		$return = "You have not set up your custom news feed yet.<br /><a href=" . CUSTOM_NEWS_URL . ">Click here to set up your custom news settings.</a>"; //user has no custom news feed so signal an alert
	}
	return $return;
}

function get_excerpt_by_id($post_id, $words){
	$the_post = get_post($post_id); //Gets post ID
	$the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt
	$excerpt_length = $words; //Sets excerpt length by word count
	$the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
	$words = explode(' ', $the_excerpt, $excerpt_length + 1);
	if(count($words) > $excerpt_length) :
		array_pop($words);
		array_push($words, '…');
		$the_excerpt = implode(' ', $words);
	endif;
	//$the_excerpt = '<p>' . $the_excerpt . '</p>';
	return $the_excerpt;
}



//Create new Group/Webinar cat on publication
function new_cat_group( $new_status, $old_status, $post ) {
    if ( $old_status != 'publish' && $new_status == 'publish' ) {
        $postType = $_POST['post_type'];
        if($postType == 'group' || $postType == 'webinar'){
	        $postID = $_POST['ID'];
			$parent_term = term_exists( $postType, 'discussions' ); // array is returned if taxonomy is given
			$parent_term_id = $parent_term['term_id']; // get numeric term id
			wp_insert_term(
			  $postType.'-'.$postID, // the term
			  'discussions', // the taxonomy
			  array(
			    'slug' => $postID,
			    'parent'=> $parent_term_id
			  )
			);
        }
    }
}
add_action( 'transition_post_status', 'new_cat_group', 10, 3 );



//When a Webinar is created add the author as a member

/*--- Check the add-to-postype plugin ---*/

//When a Webinar is updated add the mod to the group unless that mod already exists in which case do nothing.





// ----- Member Network -----

class myUsers {
	static function init() {
		// Change the user's display name after insertion
		add_action( 'user_register', array( __CLASS__, 'change_display_name' ) );
	}

	static function change_display_name( $user_id ) {
		$info = get_userdata( $user_id );

		$args = array(
			'ID' => $user_id,
			'display_name' => $info->first_name . ' ' . $info->last_name
		);

		wp_update_user( $args ) ;
	}
}

myUsers::init();


add_filter( 'wpmem_login_redirect', 'my_login_redirect' );
function my_login_redirect(){
	// return the url that the login should redirect to
	$location = $_SERVER['HTTP_REFERER'];
	if($location == 'http://essentialhospitals.org/membernetwork/registration/'){
		$location = 'http://essentialhospitals.org/membernetwork/dashboard/';
	}
    wp_safe_redirect($location);
    exit();
}




function remove_from_db( $user_id ) {
	global $wpdb;
	$wpdb->query( "DELETE FROM `wp_aeh_connections` WHERE `user_ID` = $user_id OR `friend_ID` = $user_id" );
}
add_action( 'delete_user', 'remove_from_db' );


//Change Private to Association Members Only
function custom_admin_js() {
    $url = get_option('siteurl');
    $url = get_bloginfo('template_directory') . '/js/wp-admin.js';
    echo '"<script type="text/javascript" src="'. $url . '"></script>"';
}
add_action('admin_footer', 'custom_admin_js');


//Staff Settings Admin Panel & Email Verification
add_filter('admin_init', 'my_general_settings_register_fields');

function my_general_settings_register_fields()
{
    register_setting('general', 'email_ver', 'esc_attr');
    add_settings_field('email_ver', '<label for="email_ver">'.__('Verified Email Addresses' , 'my_field' ).'</label>' , 'my_general_settings_fields_html', 'general');
}

function my_general_settings_fields_html()
{
    $value = get_option( 'email_ver', '' );

}

add_action('user_register', 'email_verification');
function email_verification($user_id) {
    if ( isset( $_POST['user_email'] ) ){
        $uEmail = $_POST['user_email'];
		if(strpos($uEmail,'essentialhospitals.org') !== false){
			update_user_meta($user_id,'staff_mem','Y');
		}else{
			update_user_meta($user_id,'staff_mem','N');
		}
    }
}


//Comments Walker
function commentWalker($comment, $args, $depth){
		$GLOBALS['comment'] = $comment;
		extract($args, EXTR_SKIP);

		if ( 'div' == $args['style'] ) {
			$tag = 'div';
			$add_below = 'comment';
		} else {
			$tag = 'li';
			$add_below = 'div-comment';
		}
?>
		<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
			<?php if ( 'div' != $args['style'] ) : ?>
				<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
			<?php endif; ?>
			<div class="comment-author vcard">
				<?php if ($args['avatar_size'] != 0) echo '<a href="'.get_permalink(276).'?member='.$comment->user_id.'">'.get_avatar( $comment, $args['avatar_size'] ).'</a>'; ?>
				<?php  echo '<cite class="fn"><a href="'.get_permalink(276).'?member='.$comment->user_id.'">' .get_comment_author() .'</a></cite> '; ?>
			</div>

			<?php if ($comment->comment_approved == '0') : ?>
				<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
				<br />
			<?php endif; ?>

			<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
				<?php printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' ); ?>
			</div>

			<div class="comment-contentbox">
				<?php comment_text() ?>
			</div>
			<div class="reply">
				<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			</div>
			<div class="cancelreply">
				<?php cancel_comment_reply_link('Cancel Reply'); ?>
			</div>

		</li>
<?php
        }

//Description Field for Centers
    function series_tax_fields($tag){
	    // Check for existing taxonomy meta for the term you're editing
	    $t_id = $tag->term_id; // Get the ID of the term you're editing
	    $term_meta = get_option( "taxonomy_term_$t_id" ); // Do the check
	?>

	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="presenter_id">Section</label>
		</th>
		<td>
			<select type="text" name="term_meta[section]" id="term_meta[section]">
				<option <?php if($term_meta['section'] == 'policy'){ echo 'selected="checked"'; } ?> value="policy">Action</option>
				<option <?php if($term_meta['section'] == 'quality'){ echo 'selected="checked"'; } ?>value="quality">Quality</option>
				<option <?php if($term_meta['section'] == 'education'){ echo 'selected="checked"'; } ?>value="education">Education</option>
				<option <?php if($term_meta['section'] == 'institute'){ echo 'selected="checked"'; } ?>value="institute">Institute</option>
        <option <?php if($term_meta['section'] == 'utility'){ echo 'selected="checked"'; } ?>value="utility">General</option>
			</select><br />
			<span class="description">Which section is this Stream/Series a part of?</span>
		</td>
	</tr>

	<?php
    }
    function series_save_tax_fields($term_id){
	    if ( isset( $_POST['term_meta'] ) ) {
		        $t_id = $term_id;
		        $term_meta = get_option( "taxonomy_term_$t_id" );
		        $cat_keys = array_keys( $_POST['term_meta'] );
		            foreach ( $cat_keys as $key ){
		            if ( isset( $_POST['term_meta'][$key] ) ){
		                $term_meta[$key] = $_POST['term_meta'][$key];
		            }
		        }
		        //save the option array
		        update_option( "taxonomy_term_$t_id", $term_meta );
		    }
		}
    add_action( 'series_edit_form_fields', 'series_tax_fields', 10, 2 );
    add_action( 'edited_series', 'series_save_tax_fields', 10, 2 );

//In taxonomy?
function tax_check($tax, $term, $_post = NULL) {
	// if neither tax nor term are specified, return false
	if ( !$tax || !$term ) { return FALSE; }
	// if post parameter is given, get it, otherwise use $GLOBALS to get post
	if ( $_post ) {
	$_post = get_post( $_post );
	} else {
	$_post =& $GLOBALS['post'];
	}
	// if no post return false
	if ( !$_post ) { return FALSE; }
	// check whether post matches term belongin to tax
	$return = is_object_in_term( $_post->ID, $tax, $term );
	// if error returned, then return false
	if ( is_wp_error( $return ) ) { return FALSE; }
	return $return;
}







/* ************************************************************************************************************************************************/
// update profile hook to send updated data to iMIS DB

add_action('profile_update', 'update_imis', 12, 2);
add_action( 'wpmem_post_update_data', 'prof_update_hook', 99999 , 1 );

function prof_update_hook( $fields )
{
  $user_id = $fields['ID'];
  update_imis($user_id);
}

function update_imis($user_id){
	global $wpdb;
	$user        = (string)$user_id;

  //get user ID of the profile being updated (not necessarily who is logged in!)
	$userdata    = get_userdata($user_id);              	//use this object for data in wp_users
	$imis_id   	 = $userdata->aeh_imis_id;


	if ($imis_id != ""){									//only do this update code if imis user id is present
		$update = update_imis_from_wp($imis_id, $userdata, $user);
			//$wpdb->update('wp_aeh_import', array('WP_post_ID' => '$user'), array( 'ID' => $imis_id ),array('%s'));
			//$wpdb->update('wp_aeh_import_full', array('WP_post_ID' => '$user'), array( 'ID' => $imis_id ),array('%s'));
		if ($update === false){}else{


		}
	}
}
/******************************************************** UPDATE IMIS FROM WP USERMETA ***********************************************************/

function update_imis_from_wp($imis_id, $userdata, $user){

		global $wpdb;
		$check = time(); //used for test purposes


		$mem_type    = $userdata->aeh_staff; if ($mem_type=='Y' OR $mem_type=='y'){$mem_type= "STAFF";}else{$mem_type= "MIND";}
		$prefix      = $userdata->prefix;
		$firstname   = $userdata->first_name;
		$middlename  = $userdata->middle_name;
		$lastname    = $userdata->last_name;
		$suffix      = $userdata->suffix;
		$jobtitle    = $userdata->job_title;
		$nickname    = $userdata->nickname;
		$addressnum  = $userdata->address_number;
		$streetaddr  = $userdata->street_address;
		$city        = $userdata->city;
		$state       = $userdata->state;
		$zip         = $userdata->zip_code;
		$ptpwd       = $userdata->aeh_password;
		$country     = $userdata->country;
		$workphone   = $userdata->phone;
		$fax         = $userdata->fax;
		$designation = $userdata->designation;
		$mobile      = $userdata->mobile_phone;
		$employer    = $userdata->hospital_name;
        $co_id       = $userdata->CO_ID;
		$email       = $userdata->user_email;
		$website     = $userdata->user_url;
		$asst_name   = $userdata->assistant_name;
		$asst_phone  = $userdata->assistant_phone;
		$asst_email  = $userdata->assistant_email;
		$webinterest = $userdata->imisWebInterests;
		if (empty($webinterest)){
			$webinterest = "";
		}else{
			$temp = "";
			foreach ($webinterest as $each){$temp .= $each . ", ";}
			$webinterest = substr($temp,0,-2);
		}




		$params = array(
			'securityPassword' => SOAP_ACCOUNT_PWD,
			'account' => array(
				'Id'          => (string)$imis_id,
				'MemberType'  => (string)$mem_type,
				'Prefix'      => (string)$prefix,
				'Email'       => (string)$email,
				'WebLogin'    => (string)$email,
				'Company'     => (string)$employer,
        		'CompanyID'   => (string)$co_id,
				'FirstName'   => (string)$firstname,
				'MiddleName'  => (string)$middlename,
				'LastName'    => (string)$lastname,
				'InformalName'=> (string)$firstname,
				'Suffix'      => (string)$suffix,
				'Title'       => (string)$jobtitle,
				'Password'    => (string)$ptpwd,
				'Designation' => (string)$designation,
				'WorkPhone'   => (string)$workphone,
				'Fax'         => (string)$fax,
				'WebSite'     => (string)$website

			)
		);
		$q = serialize($params);


		$client     = new SoapClient(IMIS_SOAP_URL);
		$response   = $client->Update($params);
		$result     = $response->UpdateResult;
		$z = serialize($result);
		$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('profile_update_response: $check', '$result : $email')");



	if (1){
		$params = array(
			'securityPassword'=> SOAP_ACCOUNT_PWD,
			'id'              => (string)$imis_id,
			'windowName'      => 'Name-Assistant',
			'fieldName'       => 'ASSISTANT_NAME',
			'fieldValue'      => (string)$asst_name
		);
		$demographic = new SoapClient(SOAP_DEMOG_UPDATE_URL);
		$response    = $demographic->Update($params);
		$result      = $response->UpdateResult;
    $wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Demographic1 response: $check', '$result')");

		unset ($demographic);
		$params = array(
			'securityPassword'=> SOAP_ACCOUNT_PWD,
			'id'              => (string)$imis_id,
			'windowName'      => 'Name-Assistant',
			'fieldName'       => 'ASSISTANT_PHONE',
			'fieldValue'      => (string)$asst_phone
		);
		$demographic = new SoapClient(SOAP_DEMOG_UPDATE_URL);
		$response    = $demographic->Update($params);
		$result      = $response->UpdateResult;
    $wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Demographic2 response: $check', '$result')");
		unset ($demographic);
		$params = array(
			'securityPassword'=> SOAP_ACCOUNT_PWD,
			'id'              => (string)$imis_id,
			'windowName'      => 'Name-Assistant',
			'fieldName'       => 'ASSISTANT_EMAIL',
			'fieldValue'      => (string)$asst_email
		);
		$demographic = new SoapClient(SOAP_DEMOG_UPDATE_URL);
		$response    = $demographic->Update($params);
		$result      = $response->UpdateResult;
    $wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Demographic3 response: $check', '$result')");
		unset ($demographic);
		$params = array(
			'securityPassword'=> SOAP_ACCOUNT_PWD,
			'id'              => (string)$imis_id,
			'windowName'      => 'Name-Iweb_create_account',
			'fieldName'       => 'WEB_INTERESTS',
			'fieldValue'      => (string)$webinterest
		);
		$demographic = new SoapClient(SOAP_DEMOG_UPDATE_URL);
		$response    = $demographic->Update($params);
		$result      = $response->UpdateResult;
    $wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Demographic4 response: $check', '$result')");
}
		$params = array(
			'securityPassword' => SOAP_ACCOUNT_PWD,
			'address' => array(
				'Id'           => (string)$imis_id,
				'Address1'     => (string)$streetaddr,
				'City'         => (string)$city,
				'StateProvince'=> (string)$state,
				'Zip'          => (string)$zip,
				'Country'      => (string)'',
				'Number' 	   => (string)$addressnum
			)
		);
		$addrclient = new SoapClient(IMIS_SOAP_URL);
		$response   = $addrclient->UpdateAddress($params);
		$result     = $response->UpdateAddressResult;
         $wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Address response: $check', '$result')");
		//$z = serialize($params);
		//$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Address $check', '$result, $z')");
		if ($result == $imis_id){
			$return = $imis_id;
		}else{
			return false;
		}


}


/******************************************************** UPDATE IMIS ON CREATE NEW IMIS ACCOUNT ****************************************************************/


function update_create_imis_from_wp($imis_id, $userdata, $user){


		global $wpdb;
		$check = time(); //used for test purposes


		$mem_type    = $userdata->aeh_staff; if ($mem_type=='Y' OR $mem_type=='y'){$mem_type= "STAFF";}else{$mem_type= "MIND";}
		$prefix      = $userdata->prefix;
		$firstname   = $userdata->first_name;
		$middlename  = $userdata->middle_name;
		$lastname    = $userdata->last_name;
		$suffix      = $userdata->suffix;
		$jobtitle    = $userdata->job_title;
		$nickname    = $userdata->nickname;
		$addressnum  = $userdata->address_number;
		$streetaddr  = $userdata->street_address;
		$city        = $userdata->city;
		$state       = $userdata->state;
		$zip         = $userdata->zip_code;
		$ptpwd       = $userdata->aeh_password;
		$country     = $userdata->country;
		$workphone   = $userdata->phone;
		$fax         = $userdata->fax;
		$designation = $userdata->designation;
		$mobile      = $userdata->mobile_phone;
		$employer    = $userdata->hospital_name;
        $co_id       = $userdata->CO_ID;
		$email       = $userdata->user_email;
		$website     = $userdata->user_url;
		$asst_name   = $userdata->assistant_name;
		$asst_phone  = $userdata->assistant_phone;
		$asst_email  = $userdata->assistant_email;
		$webinterest = $userdata->imisWebInterests;
		if (empty($webinterest)){
			$webinterest = "";
		}else{
			$temp = "";
			foreach ($webinterest as $each){$temp .= $each . ", ";}
			$webinterest = substr($temp,0,-2);
		}


		$params = array(
			'securityPassword' => SOAP_ACCOUNT_PWD,
			'account' => array(
				'Id'          => (string)$imis_id,
				'MemberType'  => (string)$mem_type,
				'Prefix'      => (string)$prefix,
				'Email'       => (string)$email,
				'WebLogin'    => (string)$email,
				'Company'     => (string)$employer,
        		'CompanyID'   => (string)$co_id,
				'FirstName'   => (string)$firstname,
				'MiddleName'  => (string)$middlename,
				'LastName'    => (string)$lastname,
				'InformalName'=> (string)$firstname,
				'Suffix'      => (string)$suffix,
				'Title'       => (string)$jobtitle,
				'Password'    => (string)$ptpwd,
				'Designation' => (string)$designation,
				'WorkPhone'   => (string)$workphone,
				'Fax'         => (string)$fax,
				'WebSite'     => (string)$website

			)
		);
		$q = serialize($params);


		$client     = new SoapClient(IMIS_SOAP_URL);
		$response   = $client->Update($params);
		$result     = $response->UpdateResult;
		$z = serialize($result);
		$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('update_on_create_response: $check', '$result')");



	if (1){
		$params = array(
			'securityPassword'=> SOAP_ACCOUNT_PWD,
			'id'              => (string)$imis_id,
			'windowName'      => 'Name-Assistant',
			'fieldName'       => 'ASSISTANT_NAME',
			'fieldValue'      => (string)$asst_name
		);
		$demographic = new SoapClient(SOAP_DEMOG_UPDATE_URL);
		$response    = $demographic->Update($params);
		$result      = $response->UpdateResult;
    $wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Demographic1 response: $check', '$result')");

		unset ($demographic);
		$params = array(
			'securityPassword'=> SOAP_ACCOUNT_PWD,
			'id'              => (string)$imis_id,
			'windowName'      => 'Name-Assistant',
			'fieldName'       => 'ASSISTANT_PHONE',
			'fieldValue'      => (string)$asst_phone
		);
		$demographic = new SoapClient(SOAP_DEMOG_UPDATE_URL);
		$response    = $demographic->Update($params);
		$result      = $response->UpdateResult;
    $wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Demographic2 response: $check', '$result')");
		unset ($demographic);
		$params = array(
			'securityPassword'=> SOAP_ACCOUNT_PWD,
			'id'              => (string)$imis_id,
			'windowName'      => 'Name-Assistant',
			'fieldName'       => 'ASSISTANT_EMAIL',
			'fieldValue'      => (string)$asst_email
		);
		$demographic = new SoapClient(SOAP_DEMOG_UPDATE_URL);
		$response    = $demographic->Update($params);
		$result      = $response->UpdateResult;
    $wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Demographic3 response: $check', '$result')");
		unset ($demographic);
		$params = array(
			'securityPassword'=> SOAP_ACCOUNT_PWD,
			'id'              => (string)$imis_id,
			'windowName'      => 'Name-Iweb_create_account',
			'fieldName'       => 'WEB_INTERESTS',
			'fieldValue'      => (string)$webinterest
		);
		$demographic = new SoapClient(SOAP_DEMOG_UPDATE_URL);
		$response    = $demographic->Update($params);
		$result      = $response->UpdateResult;
    $wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Demographic4 response: $check', '$result')");
}
		$params = array(
			'securityPassword' => SOAP_ACCOUNT_PWD,
			'address' => array(
				'Id'           => (string)$imis_id,
				'Address1'     => (string)$streetaddr,
				'City'         => (string)$city,
				'StateProvince'=> (string)$state,
				'Zip'          => (string)$zip,
				'Country'      => (string)'',
				'Number' 	   => (string)$addressnum
			)
		);
		$addrclient = new SoapClient(IMIS_SOAP_URL);
		$response   = $addrclient->UpdateAddress($params);
		$result     = $response->UpdateAddressResult;
         $wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Address response: $check', '$result')");
		//$z = serialize($params);
		//$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Address $check', '$result, $z')");
		if ($result == $imis_id){
			$return = $imis_id;
		}else{
			return false;
		}

}

/******************************************************** CREATE NEW IMIS ACCOUNT ****************************************************************/

function new_imis($first,$last,$email,$password){

	global $wpdb;
	$check = time(); //used for test purposes
	$expiration = date("Y") + 30; 					//add 30 years to the present year
	$check_email = check_aeh_email($email); 		//test the email address to see what to set other values to
	if ($check_email[0] == "public")return false; 	//no public users get added to the iMIS DB!
	if ($check_email[2] == 'Y'){$staff = 'Y'; $memtype = "STAFF";}else{$staff = 'N'; $memtype = "MIND";}
	$params = array(
		'securityPassword' => SOAP_ACCOUNT_PWD,
		'account' => array(
			'Status'              => 'A',
			'MemberType'          => $memtype,
			'JoinDate'            => date ("m/d/Y"),
			'FirstName'           => $first,
			'LastName'            => $last,
			'SourceCode'          => 'WEB',
			'Email'               => $email,
			'WebLogin'            => $email,
			'Password'            => $password,
			'ExpirationDate'      => "01/01/$expiration",
			'StaffUser'           => $staff,
		)
	);

	$client     = new SoapClient(IMIS_SOAP_URL);
	$response   = $client->Create($params);
	$result     = $response->CreateResult;
	$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('New User Time: $check', '$result')");

	if (is_numeric($result + 0))return $result; // add 0 to result. If it was a number in the first place it'll still be a number (will be numeric).
	return false;

}

/************************************************** CHECK TO GET STATUS OF EMAIL ADDRESS *********************************************************/

function check_aeh_email($email){ // this is the email address to check and return its status
	global $wpdb;
	$return       = explode('@', $email);
	$domain       = $return[1];
	$result       = $wpdb->get_row("SELECT * FROM `wp_aeh_email` WHERE `domain` = '$domain'");
	$aeh_staff    = "N";
	$organization = "";
	if ($result->domain == $domain){
		$member_type    = 'hospital';
		$aeh_staff      = $result->staff;
		$organization   = $result->organization;
	}else{
		$member_type    = 'public';
	}
	$result = array();
	array_push($result, $member_type, $domain, $aeh_staff, $organization);
	return $result; // key 0 => member_type, 1 => domain, 2 => staff(Y/N), 3 => organization
}
/*************************************************************************************************************************************************/



/******************************************************** CRON Jobs & Misc Functions *************************************************************/

add_action('aeh_import_imis', 'import_imis');

function import_imis() { 	 // fill up the wp_aeh_import & wp_aeh_import_full tables from iMIS

	global $wpdb;

	$rowcount = get_imis_row_count();
	$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('rowcount', '$rowcount')");


	$results  = $wpdb->get_row('SELECT `rownum` FROM `wp_aeh_import` ORDER BY `rownum` DESC LIMIT 0, 1');
	$import   = $results->rownum;
	$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('import', '$import')");


	if ($import === null){
		$start = '0';
	}else{
		$start = $import;
	}

	if ($start >= $rowcount){
		$start = '0';
		$wpdb->query('TRUNCATE TABLE `wp_aeh_import_full`');						//trash the full table before copying over the latest full contents
		$wpdb->query('INSERT `wp_aeh_import_full` SELECT * FROM `wp_aeh_import`');	//copy over the import table to the full table so WP can use it anytime
		$wpdb->query('TRUNCATE TABLE `wp_aeh_import`');								//truncate the import table to start over doing new imports
	}

	$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('start', '$start')");


	$sql = "";
	$error = 0;																		// assume no errors at first
	$crontime = date("Y-m-d H:i:s");												// get the current datetime stamp for the cron log
	$variables = "@start=$start, @limit=" . IMPORT_PER_CRON;

	$params = array(
		'securityPassword'=> SP_SECURITY_PWD,
		'name'            => SP_IMPORT_USERS,
		'parameters'      => $variables
	);

	// Send a POST request to ibridge
	$result = post_request(IMIS_POST_URL, $params);
	$result_stat = $result['status'];




	$startunix = time();
	if ($result['status'] == 'ok'){ //if no status then an error occurred.

		// Print headers
		$header = $result['header'];

		//$thexml = html_entity_decode($result['content']);				// convert the xml into real characters instead of entities
		$xml = simplexml_load_string($result['content']);

		//file_put_contents("import_table.txt",$result['content']);		// test save file to check xml output

		if ($xml === false){
			//echo 'Error while parsing the document';
			$error++;													// make error non zero
		}
		$xml = dom_import_simplexml($xml);

		if (!$xml) {
			//echo 'Error while converting XML';
			$error++;													// make error non zero
		}

		if ($error==0){													// proceed with table import if no errors so far

			$nodelist = $xml->getElementsByTagName('Table');

			for($i = 0; $i < $nodelist->length; $i++) {

				$unix        = time(); if ($i == 0){$first_time = $unix;}
				$ID          = $nodelist->item($i)->getElementsByTagName('ID');
				$rownum      = $nodelist->item($i)->getElementsByTagName('RowNum');
				$prefix      = $nodelist->item($i)->getElementsByTagName('PREFIX');
				$firstname   = $nodelist->item($i)->getElementsByTagName('FIRST_NAME');
				$middlename  = $nodelist->item($i)->getElementsByTagName('MIDDLE_NAME');
				$lastname    = $nodelist->item($i)->getElementsByTagName('LAST_NAME');
				$designation = $nodelist->item($i)->getElementsByTagName('DESIGNATION');
				$informal    = $nodelist->item($i)->getElementsByTagName('INFORMAL');
				$workphone   = $nodelist->item($i)->getElementsByTagName('WORK_PHONE');
				$fax         = $nodelist->item($i)->getElementsByTagName('FAX');
				$suffix      = $nodelist->item($i)->getElementsByTagName('SUFFIX');
				$addressnum	 = $nodelist->item($i)->getElementsByTagName('ADDRESS_NUM');
				$address1	 = $nodelist->item($i)->getElementsByTagName('ADDRESS_1');
				$city        = $nodelist->item($i)->getElementsByTagName('CITY');
				$state       = $nodelist->item($i)->getElementsByTagName('STATE_PROVINCE');
				$zip         = $nodelist->item($i)->getElementsByTagName('ZIP');
				$country     = $nodelist->item($i)->getElementsByTagName('COUNTRY');
				$email       = $nodelist->item($i)->getElementsByTagName('EMAIL');
				$password    = $nodelist->item($i)->getElementsByTagName('WEB_PASSWORD');
				$mem_type    = $nodelist->item($i)->getElementsByTagName('MEMBER_TYPE');
				$company     = $nodelist->item($i)->getElementsByTagName('COMPANY');
				$co_id 		 = $nodelist->item($i)->getElementsByTagName('CO_ID');
				$title       = $nodelist->item($i)->getElementsByTagName('TITLE');
				$website     = $nodelist->item($i)->getElementsByTagName('WEBSITE');
				$mobile      = $nodelist->item($i)->getElementsByTagName('MOBILE_PHONE');
				$asst_name   = $nodelist->item($i)->getElementsByTagName('ASSISTANT_NAME');
				$asst_phone  = $nodelist->item($i)->getElementsByTagName('ASSISTANT_PHONE');
				$asst_email  = $nodelist->item($i)->getElementsByTagName('ASSISTANT_EMAIL');
				$webinterest = $nodelist->item($i)->getElementsByTagName('WEB_INTERESTS');


				$ID          =   $ID->item(0)->nodeValue;
				$rownum      =   $rownum->item(0)->nodeValue;
				$prefix      =   addslashes($prefix->item(0)->nodeValue);
				$firstname   =   addslashes($firstname->item(0)->nodeValue);
				$middlename  =   addslashes($middlename->item(0)->nodeValue);
				$lastname    =   addslashes($lastname->item(0)->nodeValue);
				$suffix      =   addslashes($suffix->item(0)->nodeValue);
				$designation =   addslashes($designation->item(0)->nodeValue);
				$fullname    =   trim (str_replace("  ", " ", "$prefix $firstname $middlename $lastname $suffix $designation"));
				$informal    =   addslashes($informal->item(0)->nodeValue);
				$workphone   =   addslashes($workphone->item(0)->nodeValue);
				$fax         =   addslashes($fax->item(0)->nodeValue);
				$addressnum  =   addslashes($addressnum->item(0)->nodeValue);
				$address1    =   addslashes($address1->item(0)->nodeValue);
				$city        =   addslashes($city->item(0)->nodeValue);
				$state       =   addslashes($state->item(0)->nodeValue);
				$zip         =   addslashes($zip->item(0)->nodeValue);
				$country     =   addslashes($country->item(0)->nodeValue);
				$email       =   addslashes($email->item(0)->nodeValue);
				$password    =   addslashes($password->item(0)->nodeValue);
				$mem_type    =   addslashes($mem_type->item(0)->nodeValue);
				$company     =   addslashes($company->item(0)->nodeValue);
				$co_id       =   addslashes($co_id->item(0)->nodeValue);
				$title       =   addslashes($title->item(0)->nodeValue);
				$website     =   addslashes($website->item(0)->nodeValue);
				$mobile      =   addslashes($mobile->item(0)->nodeValue);
				$asst_name   =   addslashes($asst_name->item(0)->nodeValue);
				$asst_phone  =   addslashes($asst_phone->item(0)->nodeValue);
				$asst_email  =   addslashes($asst_email->item(0)->nodeValue);
				$webinterest =   addslashes($webinterest->item(0)->nodeValue);
				$username 	 =   "$ID-" . preg_replace("/[^a-z0-9]+/i", "", "$firstname$middlename$lastname");




				$sql = "
				INSERT INTO `wp_aeh_import` (
					`rownum`,
					`ID`,
					`unixtime`,
					`username`,
					`firstname`,
					`middlename`,
					`lastname`,
					`suffix`,
					`fullname`,
					`nickname`,
					`email`,
					`password`,
					`mem_type`,
					`company`,
					`CompanyID`,
					`title`,
					`prefix`,
					`designation`,
					`website`,
					`addressnum`,
					`address1`,
					`city`,
					`zip`,
					`state`,
					`country`,
					`workphone`,
					`fax`,
					`mobile`,
					`asst_name`,
					`asst_phone`,
					`asst_email`,
					`webinterest`
				) VALUES (
					'$rownum',
					'$ID',
					'$unix',
					'$username',
					'$firstname',
					'$middlename',
					'$lastname',
					'$suffix',
					'$fullname',
					'$informal',
					'$email',
					'$password',
					'$mem_type',
					'$company',
					'$co_id',
					'$title',
					'$prefix',
					'$designation',
					'$website',
					'$addressnum',
					'$address1',
					'$city',
					'$zip',
					'$state',
					'$country',
					'$workphone',
					'$fax',
					'$mobile',
					'$asst_name',
					'$asst_phone',
					'$asst_email',
					'$webinterest'

				)";

				$wpdb->query($sql);
			}
			$elapsed = time() - $startunix;
			$now    = date("Y-m-d H:i:s");
			$header = substr($header, strpos($header, 'Content-Length:'));
			$message =  "Added $i records to temp import table at $now taking $elapsed seconds.\r\n\r\nUNIX time range: $first_time - $unix\r\n\r\n$header\r\n";
			$sql = "
			INSERT INTO `wp_aeh_import_meta` (
				`date`,
				`header`,
				`elapsed`,
				`records`,
				`first`,
				`last`
			) VALUES (
				'$now',
				'$header',
				$elapsed,
				$i,
				$first_time,
				$unix
				)";
			$wpdb->query($sql);
		}
	}
		/*************************** confirmation email & update cron log file ******************************/

		if ($error){$message = "The table import failed in Cron Job. See administrator for details.";}
		$cronlogtext = "Cron fired at: $crontime\r\n$message\r\n**************************************************************\r\n\r\n";
		file_put_contents("cronlog.txt", $cronlogtext, FILE_APPEND);

		if (EMAILCRON){
			$headers = "From: Cron Job <cron@essentialhospitals.org>\r\n";
			wp_mail('steve@meshfresh.com', 'iMIS data imported', $cronlogtext, $headers);
		}

}

/******************************************* CRON TO TAKE iMIS VALUES AND UPDATE WP USERS *************************************************/

add_action('aeh_update_wp_users', 'update_wp_users');
function update_wp_users() { // fill up the wp_aeh_import & wp_aeh_import_full tables from iMIS
	global $wpdb;

	$sql = "
	SELECT
	`rownum`,
	`ID`,
	`firstname`,
	`middlename`,
	`lastname`,
	`suffix`,
	`nickname`,
	`addressnum`,
	`address1`,
	`city`,
	`state`,
	`zip`,
	`country`,
	`workphone`,
	`fax`,
	`mobile`,
	`email`,
	`password`,
	`mem_type`,
	`company`,
	`CompanyID`,
	`title`,
	`prefix`,
	`designation`,
	`website`,
	`asst_name`,
	`asst_phone`,
	`asst_email`,
	`webinterest`,
	`user_id`
	FROM `wp_aeh_import_full` AS t1
	JOIN `wp_usermeta` AS t2 ON `meta_key` = 'aeh_imis_id' AND `meta_value` = t1.ID
	WHERE `WP_post_ID` = ''
	ORDER BY `t1`.`rownum` ASC
	LIMIT " . MAX_WP_USERS_UPDATED;
	$results = $wpdb->get_results($sql);



	$string = ""; $n = 0; $t = 0;

	foreach ($results as $row){
		$rownum      = $row->rownum;
		$imis_id     = $row->ID;
		$firstname   = $row->firstname; //
		$middlename  = $row->middlename;//
		$lastname    = $row->lastname;  //
		$suffix      = $row->suffix;	//
		$nickname    = $row->nickname;  //
		$addressnum  = $row->addressnum;//
		$address1    = $row->address1;	//
		$city        = $row->city;		//
		$state       = $row->state;		//
		$zip         = $row->zip;		//
		$country     = $row->country;	//
		$workphone   = $row->workphone;	//
		$fax         = $row->fax;		//
		$mobile      = $row->mobile;	//
		$email       = $row->email;		//
		$password    = $row->password;	//
		$mem_type    = $row->mem_type; if ($mem_type == "STAFF"){$mem_type = 'Y';}else{$mem_type = 'N';}
		$company     = $row->company;	//
		$co_id       = $row->CompanyID;	//

		$title       = $row->title;		//
		$prefix      = $row->prefix;	//
		$designation = $row->designation;
		$website     = $row->website;	//
		$asst_name   = $row->asst_name;	//
		$asst_phone  = $row->asst_phone;//
		$asst_email  = $row->asst_email;//
		$webinterest = explode(',',$row->webinterest);//
		$fullname    = $row->fullname;  //
		$wp_id       = $row->user_id;

		//$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('UPLOAD CHECK', '$wp_id')");



		update_user_meta($wp_id, 'first_name', $firstname);
		update_user_meta($wp_id, 'middle_name', $middlename);
		update_user_meta($wp_id, 'last_name', $lastname);
		update_user_meta($wp_id, 'nickname', $nickname);
		update_user_meta($wp_id, 'address_number', $addressnum);
		update_user_meta($wp_id, 'street_address', $address1);
		update_user_meta($wp_id, 'city', $city);
		update_user_meta($wp_id, 'state', $state);
		update_user_meta($wp_id, 'zip_code', $zip);
		update_user_meta($wp_id, 'country', $country);
		update_user_meta($wp_id, 'phone', $workphone);
		update_user_meta($wp_id, 'fax', $fax);
		update_user_meta($wp_id, 'CO_ID', $co_id);
		update_user_meta($wp_id, 'designation', $designation);
		update_user_meta($wp_id, 'mobile_phone', $mobile);
		update_user_meta($wp_id, 'aeh_staff', $mem_type);
		update_user_meta($wp_id, 'hospital_name', $company);
		update_user_meta($wp_id, 'job_title', $title);
		update_user_meta($wp_id, 'assistant_name', $asst_name);
		update_user_meta($wp_id, 'assistant_phone', $asst_phone);
		update_user_meta($wp_id, 'assistant_email', $asst_email);
		update_user_meta($wp_id, 'imisWebInterests', $webinterest);
		update_user_meta($wp_id, 'suffix', $suffix);
		update_user_meta($wp_id, 'prefix', $prefix);
		update_user_meta($wp_id, 'imis_verified', '1');

		if ($password != 'XXXXXXzzzzzz'){update_user_meta($wp_id, 'aeh_password', $password); wp_set_password( $password, $wp_id );}

		//wp_update_user(array('ID' => $wp_id, 'user_url' => $website, 'user_email' => $email));
		$wpdb->query("UPDATE $wpdb->users SET user_email = '$email' WHERE ID =  '$id' ");




		$wpdb->query("UPDATE `wp_aeh_import_full` SET `WP_post_ID` = $wp_id WHERE `rownum` = $rownum AND `ID` = $imis_id");
		//$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('imis_verified: ', '$imis_id')");
		$n++; //$string .= "$rownum: $imis_id - $fullname\r\n";
	}
}




/**************************************** CHECK IF IMIS USER EXISTS FROM THEIR EMAIL ADDRESS **********************************************/
// if they do then return an array of key => value pairs. If an error return false.
function does_imis_user_exist($email){

	$params = array(
		'securityPassword'=> SP_SECURITY_PWD,
		'name'            => SP_DOES_USER_EXIST,
		'parameters'      => "@email='$email'"
	);

	$result = post_request(IMIS_POST_URL, $params);
	//print_r($result);
	if ($result['status'] == 'ok'){ 									//if no status then an error occurred.
		$xml = simplexml_load_string($result['content']);
		if ($xml === false)return false;
		$xml = dom_import_simplexml($xml);
		if ($xml === false)return false;
		$nodelist = $xml->getElementsByTagName('Table');
		if ($nodelist->length==0)return false;
		$ID          = $nodelist->item(0)->getElementsByTagName('ID');
		$prefix      = $nodelist->item(0)->getElementsByTagName('PREFIX');
		$firstname   = $nodelist->item(0)->getElementsByTagName('FIRST_NAME');
		$middlename  = $nodelist->item(0)->getElementsByTagName('MIDDLE_NAME');
		$lastname    = $nodelist->item(0)->getElementsByTagName('LAST_NAME');
		$designation = $nodelist->item(0)->getElementsByTagName('DESIGNATION');
		//$informal    = $nodelist->item(0)->getElementsByTagName('INFORMAL');
		$workphone   = $nodelist->item(0)->getElementsByTagName('WORK_PHONE');
		$fax         = $nodelist->item(0)->getElementsByTagName('FAX');
		$suffix      = $nodelist->item(0)->getElementsByTagName('SUFFIX');
		$addressnum	 = $nodelist->item(0)->getElementsByTagName('ADDRESS_NUM');
		$address1	 = $nodelist->item(0)->getElementsByTagName('ADDRESS_1');
		$city        = $nodelist->item(0)->getElementsByTagName('CITY');
		$state       = $nodelist->item(0)->getElementsByTagName('STATE_PROVINCE');
		$zip         = $nodelist->item(0)->getElementsByTagName('ZIP');
		$country     = $nodelist->item(0)->getElementsByTagName('COUNTRY');
		$email       = $nodelist->item(0)->getElementsByTagName('EMAIL');
		$password    = $nodelist->item(0)->getElementsByTagName('WEB_PASSWORD');
		$mem_type    = $nodelist->item(0)->getElementsByTagName('MEMBER_TYPE');
		$company     = $nodelist->item(0)->getElementsByTagName('COMPANY');
		$co_id       = $nodelist->item(0)->getElementsByTagName('CO_ID');
		$title       = $nodelist->item(0)->getElementsByTagName('TITLE');
		$website     = $nodelist->item(0)->getElementsByTagName('WEBSITE');
		$mobile      = $nodelist->item(0)->getElementsByTagName('MOBILE_PHONE');
		$asst_name   = $nodelist->item(0)->getElementsByTagName('ASSISTANT_NAME');
		$asst_phone  = $nodelist->item(0)->getElementsByTagName('ASSISTANT_PHONE');
		$asst_email  = $nodelist->item(0)->getElementsByTagName('ASSISTANT_EMAIL');
		$webinterest = $nodelist->item(0)->getElementsByTagName('WEB_INTERESTS');


		unset ($result);$result = array();
		$result[ID] = $ID->item(0)->nodeValue;
		$result[prefix] = $prefix->item(0)->nodeValue;
		$result[firstname] = $firstname->item(0)->nodeValue;
		$result[middlename] = $middlename->item(0)->nodeValue;
		$result[lastname] = $lastname->item(0)->nodeValue;
		$result[suffix] = $suffix->item(0)->nodeValue;
		$result[designation] = $designation->item(0)->nodeValue;
		//$result[informal] = $informal->item(0)->nodeValue;
		$result[workphone] = $workphone->item(0)->nodeValue;
		$result[fax] = $fax->item(0)->nodeValue;
		$result[addressnum] = $addressnum->item(0)->nodeValue;
		$result[address1] = $address1->item(0)->nodeValue;
		$result[city] = $city->item(0)->nodeValue;
		$result[state] = $state->item(0)->nodeValue;
		$result[zip] = $zip->item(0)->nodeValue;
		$result[country] = $country->item(0)->nodeValue;
		$result[email] = $email->item(0)->nodeValue;
		$result[password] = $password->item(0)->nodeValue;
		$result[mem_type] = $mem_type->item(0)->nodeValue;
		$result[company] = $company->item(0)->nodeValue;
		$result[companyID] = $co_id->item(0)->nodeValue;
		$result[title] = $title->item(0)->nodeValue;
		$result[website] = $website->item(0)->nodeValue;
		$result[mobile] = $mobile->item(0)->nodeValue;
		$result[asst_name] = $asst_name->item(0)->nodeValue;
		$result[asst_phone] = $asst_phone->item(0)->nodeValue;
		$result[asst_email] = $asst_email->item(0)->nodeValue;
		$result[webinterest] = $webinterest->item(0)->nodeValue;

		$the_status = $result['status'];
		global $wpdb;






		return $result;
	}else{
		return false;
	}
}


/******************************************* GET IMIS USER BY IMIS ID **********************************************/

function get_imis_user($imisuser){
	$addressnum = false;
	$params = array(
		'securityPassword'=> SP_SECURITY_PWD,
		'name'            => SP_GET_IMIS_USER,
		'parameters'      => "@user=$imisuser"
	);

	$result = post_request(IMIS_POST_URL, $params);
	//print_r($result);
	if ($result['status'] == 'ok'){ 									//if no status then an error occurred.
		$xml = simplexml_load_string($result['content']);
		if ($xml === false)return false;
		$xml = dom_import_simplexml($xml);
		if ($xml === false)return false;
		$nodelist = $xml->getElementsByTagName('Table');
		if ($nodelist->length==0)return false;
		$ID          = $nodelist->item(0)->getElementsByTagName('ID');
		$prefix      = $nodelist->item(0)->getElementsByTagName('PREFIX');
		$firstname   = $nodelist->item(0)->getElementsByTagName('FIRST_NAME');
		$middlename  = $nodelist->item(0)->getElementsByTagName('MIDDLE_NAME');
		$lastname    = $nodelist->item(0)->getElementsByTagName('LAST_NAME');
		$designation = $nodelist->item(0)->getElementsByTagName('DESIGNATION');
		//$informal    = $nodelist->item(0)->getElementsByTagName('INFORMAL');
		$workphone   = $nodelist->item(0)->getElementsByTagName('WORK_PHONE');
		$fax         = $nodelist->item(0)->getElementsByTagName('FAX');
		$suffix      = $nodelist->item(0)->getElementsByTagName('SUFFIX');
		$addressnum	 = $nodelist->item(0)->getElementsByTagName('ADDRESS_NUM');
		$address1	 = $nodelist->item(0)->getElementsByTagName('ADDRESS_1');
		$city        = $nodelist->item(0)->getElementsByTagName('CITY');
		$state       = $nodelist->item(0)->getElementsByTagName('STATE_PROVINCE');
		$zip         = $nodelist->item(0)->getElementsByTagName('ZIP');
		$country     = $nodelist->item(0)->getElementsByTagName('COUNTRY');
		$email       = $nodelist->item(0)->getElementsByTagName('EMAIL');
		$password    = $nodelist->item(0)->getElementsByTagName('WEB_PASSWORD');
		$mem_type    = $nodelist->item(0)->getElementsByTagName('MEMBER_TYPE');
		$company     = $nodelist->item(0)->getElementsByTagName('COMPANY');
		$co_id       = $nodelist->item(0)->getElementsByTagName('CO_ID');
		$title       = $nodelist->item(0)->getElementsByTagName('TITLE');
		$website     = $nodelist->item(0)->getElementsByTagName('WEBSITE');
		$mobile      = $nodelist->item(0)->getElementsByTagName('MOBILE_PHONE');
		$asst_name   = $nodelist->item(0)->getElementsByTagName('ASSISTANT_NAME');
		$asst_phone  = $nodelist->item(0)->getElementsByTagName('ASSISTANT_PHONE');
		$asst_email  = $nodelist->item(0)->getElementsByTagName('ASSISTANT_EMAIL');
		$webinterest = $nodelist->item(0)->getElementsByTagName('WEB_INTERESTS');


		unset ($result);$result = array();
		$result[ID] = $ID->item(0)->nodeValue;
		$result[prefix] = $prefix->item(0)->nodeValue;
		$result[firstname] = $firstname->item(0)->nodeValue;
		$result[middlename] = $middlename->item(0)->nodeValue;
		$result[lastname] = $lastname->item(0)->nodeValue;
		$result[suffix] = $suffix->item(0)->nodeValue;
		$result[designation] = $designation->item(0)->nodeValue;
		//$result[informal] = $informal->item(0)->nodeValue;
		$result[workphone] = $workphone->item(0)->nodeValue;
		$result[fax] = $fax->item(0)->nodeValue;
		$result[addressnum] = $addressnum->item(0)->nodeValue;
		$result[address1] = $address1->item(0)->nodeValue;
		$result[city] = $city->item(0)->nodeValue;
		$result[state] = $state->item(0)->nodeValue;
		$result[zip] = $zip->item(0)->nodeValue;
		$result[country] = $country->item(0)->nodeValue;
		$result[email] = $email->item(0)->nodeValue;
		$result[password] = $password->item(0)->nodeValue;
		$result[mem_type] = $mem_type->item(0)->nodeValue;
		$result[company] = $company->item(0)->nodeValue;
		$result[companyID] = $co_id->item(0)->nodeValue;
		$result[title] = $title->item(0)->nodeValue;
		$result[website] = $website->item(0)->nodeValue;
		$result[mobile] = $mobile->item(0)->nodeValue;
		$result[asst_name] = $asst_name->item(0)->nodeValue;
		$result[asst_phone] = $asst_phone->item(0)->nodeValue;
		$result[asst_email] = $asst_email->item(0)->nodeValue;
		$result[webinterest] = $webinterest->item(0)->nodeValue;

		$the_status = $result['status'];
		global $wpdb;

		$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('get mis user :$the_status', '$result[ID]  - $result[firstname]')");




		return $result;
	}else{
		return false;
	}
}



/******************************************* CRON TO TAKE iMIS VALUES AND DETERMINE/CREATE WP USERS **********************************************/

add_action('aeh_find_new_wp_users', 'find_new_wp_users');
function find_new_wp_users() { // call this cron from 1-4 hourly
	global $wpdb;
	$results = $wpdb->get_col("SELECT `ID` FROM `wp_aeh_import_full`");
	$current_ids = $wpdb->get_col("SELECT `meta_value` FROM `wp_usermeta` WHERE `meta_key`='aeh_imis_id' ");

	$new_diffs = array_diff($results,$current_ids);

 	$a = array();
 	foreach ($new_diffs as $new_diff){
		array_push($a, $new_diff);
	}
	update_option('imis_users_to_add',$a);
	return $a;
}

add_action('aeh_insert_new_wp_users', 'insert_new_wp_users');
function insert_new_wp_users(){  // call this cron about 1-4 hourly but not at the same time as the above cron
	global $wpdb;

	$option = get_option('imis_users_to_add');

	foreach($option as $imis_id){
		$check = $wpdb->query("SELECT * FROM `wp_usermeta` WHERE `meta_key` = 'aeh_imis_id' AND `meta_value` = '$imis_id'"); //make sure this user doesn't already exist first
		if (!$check){ 														// if this user has been added from iMIS to WP then skip adding this user or else do the biz
			add_one_imis_user($imis_id);									// loop here adding individual WP accounts programmatically.
		}
	}
}

function add_one_imis_user($imis_id){
	global $wpdb;

	$row = $wpdb->get_row("SELECT * FROM `wp_aeh_import_full` WHERE `ID` = $imis_id"); // get all the iMIS parameters from the wp_imis_import_full table
	if (!$row)return false;
	$rownum      = $row->rownum;
	$firstname   = addslashes($row->firstname);
	$middlename  = addslashes($row->middlename);
	$lastname    = addslashes($row->lastname);
	$username    = addslashes($row->username);
	$suffix      = addslashes($row->suffix);
	$nickname    = addslashes($row->nickname);
	$addressnum  = addslashes($row->addressnum);
	$address1    = addslashes($row->address1);
	$city        = addslashes($row->city);
	$state       = addslashes($row->state);
	$zip         = addslashes($row->zip);
	$country     = addslashes($row->country);
	$workphone   = addslashes($row->workphone);
	$fax         = addslashes($row->fax);
	$mobile      = addslashes($row->mobile);
	$email       = addslashes($row->email);
	$password    = addslashes($row->password);
	$mem_type    = $row->mem_type; if ($mem_type == "STAFF"){$mem_type = 'Y';}else{$mem_type = 'N';}
	$company     = addslashes($row->company);
	$co_id       = addslashes($row->companyID);
	$title       = addslashes($row->title);
	$prefix      = addslashes($row->prefix);
	$designation = addslashes($row->designation);
	$website     = addslashes($row->website);
	$asst_name   = addslashes($row->asst_name);
	$asst_phone  = addslashes($row->asst_phone);
	$asst_email  = addslashes($row->asst_email);
	$webinterest = explode(',',$row->webinterest);
	$fullname    = addslashes($row->fullname);

	$userdata = array(
		'user_login'   =>  $username,
		'user_url'     =>  $website,
		'user_email'   =>  $email,
		'user_pass'    =>  $password,
		'first_name'   =>  $firstname,
		'display_name' =>  $fullname,
		'last_name'    =>  $lastname,
		'nickname'     =>  $nickname,
		'role'         =>  'member'
	);

	$wp_id = wp_insert_user($userdata);

	if( !is_wp_error($wp_id) ) {
	 echo "User created : ". $wp_id;
	}
	else{
		return $email;
	}


	//On success
	if(!is_wp_error($wp_id)){
		update_user_meta($wp_id, 'aeh_imis_id', $imis_id);
		update_user_meta($wp_id, 'aeh_member_type', 'hospital');
		update_user_meta($wp_id, 'role', 'member');
		update_user_meta($wp_id, 'aeh_password', $password);
		update_user_meta($wp_id, 'address_number', $addressnum);
		update_user_meta($wp_id, 'street_address', $address1);
		update_user_meta($wp_id, 'city', $city);
		update_user_meta($wp_id, 'state', $state);
		update_user_meta($wp_id, 'zip_code', $zip);
		update_user_meta($wp_id, 'country', $country);
		update_user_meta($wp_id, 'phone', $workphone);
		update_user_meta($wp_id, 'fax', $fax);
		update_user_meta($wp_id, 'designation', $designation);
		update_user_meta($wp_id, 'mobile_phone', $mobile);
		update_user_meta($wp_id, 'aeh_staff', $mem_type);
		update_user_meta($wp_id, 'hospital_name', $company);
		update_user_meta($wp_id, 'CO_ID', $co_id);
		update_user_meta($wp_id, 'job_title', $title);
		update_user_meta($wp_id, 'assistant_name', $asst_name);
		update_user_meta($wp_id, 'assistant_phone', $asst_phone);
		update_user_meta($wp_id, 'assistant_email', $asst_email);
		update_user_meta($wp_id, 'imisWebInterests', $webinterest);
		update_user_meta($wp_id, 'suffix', $suffix);
		update_user_meta($wp_id, 'title', $prefix);
		update_user_meta($wp_id, 'verified', 'true');
		update_user_meta($wp_id, 'imis_verified', 1);
	}

	$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('new wp user added : $wp_id', 'imis: $imis_id, $email')");
	return $wp_id; // if user added ok then $wp_id = the WP ID of the newly added user

}

/************************************ CUSTOM LOGIN FUNCTION TO RECORD LAST LOGIN TIME ************************************/

add_action('wp_login', 'check_custom_authentication', 10, 2);

function check_custom_authentication($user_login, $user) {

        //global $wpdb;

     	//if(!username_exists($username)) return;
		$user_id = $user->id;						//$user_id = WP ID field (user key)
		$logtime = date("Y-m-d H:i:s.000");

		$imis_id = (string)get_user_meta($user_id,'aeh_imis_id', TRUE);
		if ($imis_id!=""){

			$params = array(
				'securityPassword' => SP_SECURITY_PWD,
				'name' => SP_LOGIN_TIME,
				'parameters' => "@user_id = '$imis_id', @logtime ='$logtime'"
			);

			// Send a POST request to ibridge
			$result = post_request(SP_POST_UPDATE_URL, $params);
		}

        update_user_meta($user_id, 'user_last_login', $logtime);
		file_put_contents("userlog.txt", "$logtime $user_login ($user_id)\r\n", FILE_APPEND);
}


/******************************************* GET USER TITLES & WEB INTERESTS *********************************************/

add_action('aeh_get_imis_tables', 'get_imis_tables');				// get the unique user TITLE fields and store in wp_options
																	// also get the WEB_INTERESTS and store those in wp_options
function get_imis_tables(){





	//---------------APPROVED EMAIL LIST------------------------------------

	$params = array(
		'securityPassword' => SP_SECURITY_PWD,
		'name' => SP_EMAIL_LIST,
		'parameters' => ''
	);
	$domains = array();
	// Send a POST request to ibridge
	$result = post_request(IMIS_POST_URL, $params);
	if ($result['status'] == 'ok'){ //if no status then an error occurred.

		$xml = simplexml_load_string($result['content']);

		if ($xml === false){
			//echo 'Error while parsing the document';

		}else{

			$xml = dom_import_simplexml($xml);

			if (!$xml) {
				//echo 'Error while converting XML';
			}else{
				$nodelist = $xml->getElementsByTagName('Table');
				for($i = 0; $i < $nodelist->length; $i++){
					$t = $nodelist->item($i)->getElementsByTagName('EMAIL_SUFFIX');
					$domains[$i] = $t->item(0)->nodeValue;
					if($domains[$i]!= ''){
						//echo $i . " : " . $domains[$i] . "<br>";
						global $wpdb;
						$wpdb->query("INSERT INTO `wp_aeh_email` (`domain`, `organization`,staff) VALUES ('$domains[$i]', ' ', 'N')");

					}
				}

			}
		}
	}
	unset($xml);
	unset($result);


	//---------------USER TITLES------------------------------------

	$params = array(
		'securityPassword' => SP_SECURITY_PWD,
		'name' => SP_GET_TITLES,
		'parameters' => ''
	);
	$titles = array();
	// Send a POST request to ibridge
	$result = post_request(IMIS_POST_URL, $params);
	if ($result['status'] == 'ok'){ //if no status then an error occurred.

		$xml = simplexml_load_string($result['content']);

		if ($xml === false){
			//echo 'Error while parsing the document';

		}else{

			$xml = dom_import_simplexml($xml);

			if (!$xml) {
				//echo 'Error while converting XML';
			}else{
				$nodelist = $xml->getElementsByTagName('Table');
				for($i = 0; $i < $nodelist->length; $i++){
					$t = $nodelist->item($i)->getElementsByTagName('TITLE');
					$titles[$i] = $t->item(0)->nodeValue;
				}
				if($titles!= ''){
					update_option("user_titles", $titles);
				}
			}
		}
	}
	unset($xml);
	unset($result);


	//---------------WEB INTERESTS------------------------------------

	$params = array(
		'securityPassword' => SP_SECURITY_PWD,
		'name' => SP_WEB_INTERESTS,
		'parameters' => ''
	);
	$interests = array();

	$result = post_request(IMIS_POST_URL, $params);
	if ($result['status'] == 'ok'){ //if no status then an error occurred.

		$xml = simplexml_load_string($result['content']);
		//file_put_contents("webinterests.txt",$xml);return;

		if ($xml === false){
			//echo 'Error while parsing the document';

		}else{

			$xml = dom_import_simplexml($xml);

			if (!$xml) {
				//echo 'Error while converting XML';
			}else{
				$nodelist = $xml->getElementsByTagName('Table');
				for($i = 0; $i < $nodelist->length; $i++){
					$t = $nodelist->item($i)->getElementsByTagName('CODE');
					$interests['code'][$i] = $t->item(0)->nodeValue;
					$t = $nodelist->item($i)->getElementsByTagName('DESCRIPTION');
					$interests['description'][$i] = $t->item(0)->nodeValue;
				}
				if($titles!= '$interests'){
					update_option("user_web_interests", $interests);
				}
			}
		}
	}

	unset($xml);
	unset($result);



	//---------------COMPANY DROPDOWN LIST------------------------------------

	$params = array(
		'securityPassword' => SP_SECURITY_PWD,
		'name' => SP_COMPANY_LIST,
		'parameters' => ''
	);
	$companies = array();

	$result = post_request(IMIS_POST_URL, $params);
	if ($result['status'] == 'ok'){ //if no status then an error occurred.

		$xml = simplexml_load_string($result['content']);
		//file_put_contents("webinterests.txt",$xml);return;

		if ($xml === false){
			//echo 'Error while parsing the document';

		}else{

			$xml = dom_import_simplexml($xml);

			if (!$xml) {
				//echo 'Error while converting XML';
			}else{
				$nodelist = $xml->getElementsByTagName('Table');
				for($i = 0; $i < $nodelist->length; $i++){
					$t = $nodelist->item($i)->getElementsByTagName('HQ');
					$companies['hq'][$i] = $t->item(0)->nodeValue;
          $t = $nodelist->item($i)->getElementsByTagName('ID');
          $companies['id'][$i] = $t->item(0)->nodeValue;
					$t = $nodelist->item($i)->getElementsByTagName('COMPANY');
					$companies['company'][$i] = $t->item(0)->nodeValue;
					$t = $nodelist->item($i)->getElementsByTagName('COMPANY_SORT');
          $companies['company_sort'][$i] = $t->item(0)->nodeValue;
          $t = $nodelist->item($i)->getElementsByTagName('ADDRESS');
					$companies['address'][$i] = $t->item(0)->nodeValue;
					$t = $nodelist->item($i)->getElementsByTagName('CITY');
					$companies['city'][$i] = $t->item(0)->nodeValue;
					$t = $nodelist->item($i)->getElementsByTagName('STATE');
					$companies['state'][$i] = $t->item(0)->nodeValue;
          $t = $nodelist->item($i)->getElementsByTagName('ZIP');
          $companies['zip'][$i] = $t->item(0)->nodeValue;
          $t = $nodelist->item($i)->getElementsByTagName('WORK_PHONE');
          $companies['work_phone'][$i] = $t->item(0)->nodeValue;
          $t = $nodelist->item($i)->getElementsByTagName('FAX');
          $companies['fax'][$i] = $t->item(0)->nodeValue;

				}
				if($titles!= '$companies'){
					update_option("company_list", $companies);
					var_dump($companies);
					echo "<br>";
				}
			}
		}
	}

}

/********************************************** GET ROW COUNT **************************************************/

// find out the number of users in the iMIS DB (web users conforming to our criteria)
function get_imis_row_count(){

	$params = array(
		'securityPassword' => SP_SECURITY_PWD,
		'name' => SP_GET_ROW_COUNT,
		'parameters' => ''
	);
	$totalrows = false;
	// Send a POST request to ibridge
	$result = post_request(IMIS_POST_URL, $params);
	if ($result['status'] == 'ok'){ //if no status then an error occurred.

		$xml = simplexml_load_string($result['content']);

		if ($xml === false){
			//echo 'Error while parsing the document';

		}else{

			$xml = dom_import_simplexml($xml);

			if (!$xml) {
				//echo 'Error while converting XML';
			}else{
				$nodelist = $xml->getElementsByTagName('Table');
				$t = $nodelist->item(0)->getElementsByTagName('TOTAL_ROWS');
				$totalrows = $t->item(0)->nodeValue;
			}
		}
	}
	return $totalrows;
}
/********************************************** GET iMIS ADDRESS NUM **************************************************/




function get_address_num($imisuser){
	$addressnum = false;
	$params = array(
		'securityPassword'=> SP_SECURITY_PWD,
		'name'            => SP_GET_IMIS_USER,
		'parameters'      => "@user=$imisuser"
	);

	$result = post_request(IMIS_POST_URL, $params);
	$startunix = time();
	if ($result['status'] == 'ok'){ //if no status then an error occurred.
		$header = $result['header'];
		$xml = simplexml_load_string($result['content']);
		if ($xml !== false){
			$xml = dom_import_simplexml($xml);
			if ($xml){
				$nodelist = $xml->getElementsByTagName('Table');
				if ($nodelist->length>0){
					$addressnum	= $nodelist->item(0)->getElementsByTagName('ADDRESS_NUM');
					$addressnum = $addressnum->item(0)->nodeValue;
				}
			}
		}
	}
	return $addressnum;
}





/********************************************** POST REQUEST FUNCTION **************************************************/
function post_request($url, $data, $referer='') {

    // Convert the data array into URL Parameters like a=b&foo=bar etc.
    $data = http_build_query($data);

    // parse the given URL
    $url = parse_url($url);

    if ($url['scheme'] != 'http') {
        die('Error: Only HTTP request are supported !');
    }

    // extract host and path:
    $host = $url['host'];
    $path = $url['path'];

    // open a socket connection on port 80 - timeout: 30 sec
    $fp = fsockopen($host, 80, $errno, $errstr, 30);

    if ($fp){

        // send the request headers:
        fputs($fp, "POST $path HTTP/1.1\r\n");
        fputs($fp, "Host: $host\r\n");

        if ($referer != '')
            fputs($fp, "Referer: $referer\r\n");

        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-length: ". strlen($data) ."\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $data);

        $result = '';
        while(!feof($fp)) {
            // receive the results of the request
            $result .= fgets($fp, 4096);
        }
    }
    else {
        return array(
            'status' => 'err',
            'error' => "$errstr ($errno)"
        );
    }

    // close the socket connection:
    fclose($fp);

    // split the result header from the content
    $result = explode("\r\n\r\n", $result, 2);

    $header = isset($result[0]) ? $result[0] : '';
    $content = isset($result[1]) ? $result[1] : '';

    // return as structured array:
    return array(
        'status' => 'ok',
        'header' => $header,
        'content' => $content
    );
}

/**
 * XML2Array: A class to convert XML to array in PHP
 * It returns the array which can be converted back to XML using the Array2XML script
 * It takes an XML string or a DOMDocument object as an input.
 *
 * See Array2XML: http://www.lalit.org/lab/convert-php-array-to-xml-with-attributes
 *
 * Author : Lalit Patel
 * Website: http://www.lalit.org/lab/convert-xml-to-array-in-php-xml2array
 * License: Apache License 2.0
 *          http://www.apache.org/licenses/LICENSE-2.0
 * Version: 0.1 (07 Dec 2011)
 * Version: 0.2 (04 Mar 2012)
 * 			Fixed typo 'DomDocument' to 'DOMDocument'
 *
 * Usage:
 *       $array = XML2Array::createArray($xml);
 */

class XML2Array {

    private static $xml = null;
	private static $encoding = 'UTF-8';

    /**
     * Initialize the root XML node [optional]
     * @param $version
     * @param $encoding
     * @param $format_output
     */
    public static function init($version = '1.0', $encoding = 'UTF-8', $format_output = true) {
        self::$xml = new DOMDocument($version, $encoding);
        self::$xml->formatOutput = $format_output;
		self::$encoding = $encoding;
    }

    /**
     * Convert an XML to Array
     * @param string $node_name - name of the root node to be converted
     * @param array $arr - aray to be converterd
     * @return DOMDocument
     */
    public static function &createArray($input_xml) {
        $xml = self::getXMLRoot();
		if(is_string($input_xml)) {
			$parsed = $xml->loadXML($input_xml);
			if(!$parsed) {
				throw new Exception('[XML2Array] Error parsing the XML string.');
			}
		} else {
			if(get_class($input_xml) != 'DOMDocument') {
				throw new Exception('[XML2Array] The input XML object should be of type: DOMDocument.');
			}
			$xml = self::$xml = $input_xml;
		}
		$array[$xml->documentElement->tagName] = self::convert($xml->documentElement);
        self::$xml = null;    // clear the xml node in the class for 2nd time use.
        return $array;
    }

    /**
     * Convert an Array to XML
     * @param mixed $node - XML as a string or as an object of DOMDocument
     * @return mixed
     */
    private static function &convert($node) {
		$output = array();

		switch ($node->nodeType) {
			case XML_CDATA_SECTION_NODE:
				$output['@cdata'] = trim($node->textContent);
				break;

			case XML_TEXT_NODE:
				$output = trim($node->textContent);
				break;

			case XML_ELEMENT_NODE:

				// for each child node, call the covert function recursively
				for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
					$child = $node->childNodes->item($i);
					$v = self::convert($child);
					if(isset($child->tagName)) {
						$t = $child->tagName;

						// assume more nodes of same kind are coming
						if(!isset($output[$t])) {
							$output[$t] = array();
						}
						$output[$t][] = $v;
					} else {
						//check if it is not an empty text node
						if($v !== '') {
							$output = $v;
						}
					}
				}

				if(is_array($output)) {
					// if only one node of its kind, assign it directly instead if array($value);
					foreach ($output as $t => $v) {
						if(is_array($v) && count($v)==1) {
							$output[$t] = $v[0];
						}
					}
					if(empty($output)) {
						//for empty nodes
						$output = '';
					}
				}

				// loop through the attributes and collect them
				if($node->attributes->length) {
					$a = array();
					foreach($node->attributes as $attrName => $attrNode) {
						$a[$attrName] = (string) $attrNode->value;
					}
					// if its an leaf node, store the value in @value instead of directly storing it.
					if(!is_array($output)) {
						$output = array('@value' => $output);
					}
					$output['@attributes'] = $a;
				}
				break;
		}
		return $output;
    }

    /*
     * Get the root XML node, if there isn't one, create it.
     */
    private static function getXMLRoot(){
        if(empty(self::$xml)) {
            self::init();
        }
        return self::$xml;
    }
}

/*************************************************************************************************************************************************/

//Show Private posts on normal queries
function show_private($query) {
  if (!is_admin() && $_GET['preview'] != 'true') {
     $query->set('post_status', array('publish','private'));
  }
}
add_action('pre_get_posts','show_private');

//Action articles refuse to sort by date
add_action( 'pre_get_posts', 'mycpt_order' );
/**
 * Change order of custom post type to alphabetical ascending
 */
function mycpt_order( $query ) {
    // check if we're in admin, if not exit
    if ( ! is_admin() ) {
        return;
    }
    $post_type = $query->get('post_type');
    if ( $post_type == 'policy' ) {
        /* Post Column: e.g. title */
        if ( $query->get( 'orderby' ) == 'menu_order title' && $query->get( 'order' ) == 'asc') {
            $query->set( 'orderby', 'date' );
             $query->set( 'order', 'desc' );
        }
    }
}


//wpmembers stuff
add_filter( 'wpmem_login_form', 'remove_wpmem_txt' );
add_filter( 'wpmem_register_form', 'remove_wpmem_txt' );

function remove_wpmem_txt( $form ) {
	$old = array( '[wpmem_txt]', '<p>', '[/wpmem_txt]');
	$new = array( "" );
	return str_replace( $old, $new, $form );
}


function possibly_redirect(){
  global $pagenow;
  if( 'wp-login.php' == $pagenow ) {
    if ( isset( $_POST['wp-submit'] ) ||   // in case of LOGIN
      ( isset($_GET['action']) && $_GET['action']=='logout') ||   // in case of LOGOUT
      ( isset($_GET['checkemail']) && $_GET['checkemail']=='confirm') ||   // in case of LOST PASSWORD
      ( isset($_GET['checkemail']) && $_GET['checkemail']=='registered') ) return;    // in case of REGISTER
    else wp_redirect( home_url().'/membernetwork/registration/' ); // or wp_redirect(home_url('/login'));
    exit();
  }
}
add_action('init','possibly_redirect');


apply_filters('coauthors_show_create_profile_user_link',true);

add_filter( 'auth_cookie_expiration', 'keep_me_logged_in_for_1_year' );
function keep_me_logged_in_for_1_year( $expirein ) {
    return 31556926; // 1 year in seconds
}


add_filter('logout_url', 'logout_home', 10, 2);
function logout_home($logouturl, $redir){
	$redir = get_option('siteurl');
	return $logouturl . '&redirect_to=' . urlencode($redir);
}

function hwl_home_pagesize($query){
    if(is_admin() || !$query->is_main_query())
        return;

    if(is_tax() || is_tag() || is_category() || is_archive()){
	    $query->set('posts_per_page',999);
    }
}
add_action('pre_get_posts','hwl_home_pagesize',1);




//super special sticky comments
add_action( 'add_meta_boxes_comment', 'extend_comment_add_meta_box' );
function extend_comment_add_meta_box() {
    add_meta_box( 'title', __( 'Sticky Comment' ), 'extend_comment_meta_box', 'comment', 'normal', 'high' );
}
function extend_comment_meta_box( $comment ){
    $sticky = get_comment_meta( $comment->comment_ID, 'sticky_comment', true );
		if($sticky == true){
			$stickyOUT = 'checked="checked"';
		}else{
			$stickyOUT = '';
		}
    wp_nonce_field( 'extend_comment_update', 'extend_comment_update', false );
    ?>
    <p>
        <label for="title"><?php _e( 'Make comment sticky?' ); ?></label>
        <input type="checkbox" name="sticky_comment" <?php echo $stickyOUT; ?> class="widefat" />
    </p>
    <?php
}
add_action( 'edit_comment', 'extend_comment_edit_metafields' );

function extend_comment_edit_metafields( $comment_id ) {
    if( ! isset( $_POST['extend_comment_update'] ) || ! wp_verify_nonce( $_POST['extend_comment_update'], 'extend_comment_update' ) ) return;
  if( ( isset( $_POST['sticky_comment'] ) ) && ( $_POST['sticky_comment'] != '') ){
  	update_comment_meta( $comment_id, 'sticky_comment', true );
	}else{
  	delete_comment_meta( $comment_id, 'sticky_comment');
	}
}


function get_sticky_comments($post_id=0){
	global $wpdb;
	if($post_id == 0){
		$post_id = get_the_ID();
	}
	$comments = $wpdb->get_results("SELECT *
																	FROM $wpdb->comments comments, $wpdb->commentmeta commentmeta
																	WHERE comments.comment_post_ID = $post_id
																	AND commentmeta.comment_id = comments.comment_ID
																	AND commentmeta.meta_key = 'sticky_comment'
																	AND commentmeta.meta_value = '1'
																	");
	if(count($comments) > 0){
		$output = '<ol class="comment-list clearfix sticky">';
		foreach($comments as $comment){
			$author = $comment->user_id;
			$avatar = get_avatar($author);
			$name = $comment->comment_author;
			$date = new DateTime($comment->comment_date);
				$time = $date->format('g:i A');
				$date = $date->format('F jS, Y');
			$content = wpautop($comment->comment_content);
			$id = $comment->comment_ID;

			$output .= "<li class='comment byuser even thread-even depth-1' id='comment-$id'>
				<div id='div-comment-$id' class='comment-body'>
					<div class='comment-author vcard'>
						<a href='http://mlinson.staging.wpengine.com/membernetwork/member-profile/?member=$author'>
							$avatar
						</a>
						<cite class='fn'>
							<a href='http://mlinson.staging.wpengine.com/membernetwork/member-profile/?member=$author'>
								$name
							</a>
						</cite>
					</div>
					<div class='comment-meta commentmetadata'>
						<a href='http://mlinson.staging.wpengine.com/discussions/palliative-care-programs/#comment-$id'>
							$date at $time
						</a>
					</div>
					<div class='comment-contentbox'>
						$content
					</div>
				</div>
			</li>";
		}
		$output .= '</ol>';
	}
	echo $output;
}



// more stuff
require_once('functions/class-events.php');
require_once('functions/class-presentations.php');


?>
