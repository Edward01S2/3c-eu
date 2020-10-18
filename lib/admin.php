<?php
// lib/admin.php //


// Genesis

remove_action( 'admin_menu', 'genesis_add_inpost_seo_box' );
remove_theme_support( 'genesis-seo-settings-menu' );
remove_theme_support( 'genesis-admin-menu' );

remove_theme_support( 'genesis-inpost-layouts' );
remove_action( 'admin_menu', 'genesis_add_inpost_scripts_box' );

genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );
genesis_unregister_layout( 'sidebar-content-sidebar' );


// Wordpress

add_filter( 'use_block_editor_for_post_type', 'cc_disable_gutenberg', 10, 2 );
function cc_disable_gutenberg( $can_edit ) {
  return false;
}

add_filter( 'edit_post_link', '__return_false' );
add_filter('widget_text', 'do_shortcode');

add_action( 'widgets_init', 'cc_unregister_genesis_widgets', 20 );
function cc_unregister_genesis_widgets() {
  unregister_widget( 'Genesis_eNews_Updates' );
  unregister_widget( 'Genesis_Featured_Page' );
  unregister_widget( 'Genesis_Featured_Post' );
  unregister_widget( 'Genesis_Latest_Tweets_Widget' );
  unregister_widget( 'Genesis_Menu_Pages_Widget' );
  unregister_widget( 'Genesis_User_Profile_Widget' );
  unregister_widget( 'Genesis_Widget_Menu_Categories' );
  unregister_widget('WP_Widget_Pages');
  unregister_widget('WP_Widget_Calendar');
  unregister_widget('WP_Widget_Archives');
  unregister_widget('WP_Widget_Links');
  unregister_widget('WP_Widget_Meta');
  unregister_widget('WP_Widget_Search');
  //    unregister_widget('WP_Widget_Text');
  unregister_widget('WP_Widget_Categories');
  unregister_widget('WP_Widget_Recent_Posts');
  unregister_widget('WP_Widget_Recent_Comments');
  unregister_widget('WP_Widget_RSS');
  unregister_widget('WP_Widget_Tag_Cloud');
  unregister_widget('WP_Nav_Menu_Widget');
}

add_action( 'genesis_admin_before_metaboxes', 'cc_remove_genesis_theme_metaboxes' );
function cc_remove_genesis_theme_metaboxes( $hook ) {
  remove_meta_box( 'genesis-theme-settings-version',    $hook, 'main' );
  remove_meta_box( 'genesis-theme-settings-feeds',      $hook, 'main' );
  remove_meta_box( 'genesis-theme-settings-layout',     $hook, 'main' );
  remove_meta_box( 'genesis-theme-settings-header',     $hook, 'main' );
  remove_meta_box( 'genesis-theme-settings-nav', 	      $hook, 'main' );
  remove_meta_box( 'genesis-theme-settings-breadcrumb', $hook, 'main' );
  remove_meta_box( 'genesis-theme-settings-comments',   $hook, 'main' );
  remove_meta_box( 'genesis-theme-settings-posts',      $hook, 'main' );
  remove_meta_box( 'genesis-theme-settings-blogpage',   $hook, 'main' );
  remove_meta_box( 'genesis-theme-settings-scripts',    $hook, 'main' );

  remove_meta_box( 'genesis-seo-settings-doctitle', $hook, 'main' );
  remove_meta_box( 'genesis-seo-settings-homepage', $hook, 'main' );
  remove_meta_box( 'genesis-seo-settings-dochead',  $hook, 'main' );
  remove_meta_box( 'genesis-seo-settings-robots',   $hook, 'main' );
  remove_meta_box( 'genesis-seo-settings-archives', $hook, 'main' );
}

add_action( 'admin_bar_menu', 'cc_admin_bar_remove_menu_items', 99 );
function cc_admin_bar_remove_menu_items( $wp_admin_bar ) {
  $wp_admin_bar->remove_node( 'wp-logo' );
  $wp_admin_bar->remove_node( 'comments' );
}

add_action( 'admin_menu', 'cc_remove_menus' );
function cc_remove_menus(){
  global $menu;
  remove_menu_page( 'edit-comments.php' );
  remove_menu_page( 'admin.php?page=genesis' );
  // remove_menu_page( 'tools.php' );

  remove_meta_box('postcustom', 'post', 'normal');

  $post_types = get_post_types( array( 'show_ui' => true ) );
  foreach( $post_types as $post_type ) {
    remove_meta_box('commentstatusdiv', $post_type, 'normal');
    remove_meta_box('commentsdiv', $post_type, 'normal');
  }
}

add_action( 'wp_dashboard_setup', 'cc_disable_dashboard_widgets' );
function cc_disable_dashboard_widgets() {
  remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
  remove_meta_box( 'rg_forms_dashboard', 'dashboard', 'normal' );
}

add_filter( 'admin_footer_text', 'cc_admin_footer_text' );
function cc_admin_footer_text() {
  remove_filter( 'update_footer', 'core_update_footer' );
}


// ACF
if (class_exists('acf')) {
  $parent = acf_add_options_page( array(
    'page_title'    => 'Theme',
    'capability'    => 'manage_options',
    'position'      => '59.1',
    'redirect' 		=> true
  ) );

  acf_add_options_sub_page(array(
    'page_title' 	=> 'Options',
    'menu_title' 	=> 'Options',
    'parent_slug' 	=> $parent['menu_slug'],
  ) );

  acf_add_options_sub_page(array(
    'page_title' 	=> 'Scripts',
    'menu_title' 	=> 'Scripts',
    'parent_slug' 	=> $parent['menu_slug'],
  ) );

  acf_add_options_sub_page( array(
    'page_title' 	=> 'Event Archive Options',
    'menu_title' 	=> 'Event Options',
    'parent_slug' 	=> 'edit.php?post_type=event',
  ) );
}

add_action( 'acf/input/admin_footer', 'cc_acf_admin_footer_metabox_title', 1 );
function cc_acf_admin_footer_metabox_title() {
  $field_groups = acf_get_field_groups( array( 'post_id' => get_the_id() ) );

  foreach ( $field_groups as $field_group ) {
    if ( !isset( $field_group['metabox_title'] ) || $field_group['metabox_title'] == '' ) continue;
    ?>
    <script type="text/javascript">
    document.getElementById('acf-<?php echo $field_group['key'] ?>').getElementsByTagName('h2')[0].childNodes[0].innerHTML = '<?php echo $field_group['metabox_title'] ?>';
    </script>
    <?php
  }
}

add_action( 'acf/render_field_group_settings', 'cc_render_options' );
function cc_render_options( $field_group ) {

  if ( $field_group['style'] == 'default' ):

    $metabox_title = isset( $field_group['metabox_title'] ) ? $field_group['metabox_title'] : '';
    acf_render_field_wrap(array(
      'label'         => __('Metabox Title','acf'),
      'instructions'  => '',
      'type'          => 'text',
      'name'          => 'metabox_title',
      'prefix'        => 'acf_field_group',
      'value'         => $metabox_title,
    ));

  endif;

  $custom_css = isset( $field_group['custom_css'] ) ? $field_group['custom_css'] : '';
  acf_render_field_wrap(array(
    'label'         => __('Custom CSS','acf'),
    'instructions'  => '',
    'type'          => 'textarea',
    'name'          => 'custom_css',
    'prefix'        => 'acf_field_group',
    'value'         => $custom_css,
  ));
}

add_action( 'acf/input/admin_head', 'cc_acf_admin_head' );
function cc_acf_admin_head() {
  $field_groups = acf_get_field_groups( array( 'post_id' => get_the_id() ) );
  foreach ( $field_groups as $field_group ) {
    if ( !isset( $field_group['custom_css'] ) ) continue;
    echo sprintf( '<style type="text/css">%s</style>', $field_group['custom_css'] );
  }
  echo '<style type="text/css">.hide-label > .acf-label{display:none;}</style>';
}

add_action( 'admin_head', 'cc_wp_admin_head' );
function cc_wp_admin_head() {
    ?>
    <style type="text/css">
    .acf-settings-wrap .acf-postbox .handlediv,
    .acf-settings-wrap .acf-postbox .handlediv,
    .acf-settings-wrap .acf-postbox .hndle,
    .acf-settings-wrap .acf-postbox .hndle {
        display: none;
    }

    .acf-postbox.default .acf-tab-group {
        background: #f1f1f1;
        /*#f5f5f5*/
    }
    </style>
    <?php
}

add_filter( 'manage_person_posts_columns' , 'cc_person_columns' );
function cc_person_columns( $columns ) {
  unset( $columns['date'] );
  $columns['position'] = 'Position';
  $columns['date'] = 'Date Published';
  return $columns;
}

add_action( 'manage_person_posts_custom_column' , 'cc_person_custom_columns', 10, 2 );
function cc_person_custom_columns( $column, $post_id ) {
  switch ( $column ) {
    case 'position' :
      echo get_field( 'position', $post_id );
      break;
  }
}

// function my_cron_schedules($schedules){
//   if(!isset($schedules["20hr"])){
//       $schedules["20hr"] = array(
//           'interval' => 60*60*20,
//           'display' => __('Once every 20hrs'));
//   }
//   return $schedules;
// }
// add_filter('cron_schedules','my_cron_schedules');

// // Schedule a call of the invisionsoft.php file to refresh token if need be

// $args = array(false);
// if(!wp_next_scheduled('refresh_invisionsoft_token', $args)){
//   wp_schedule_event(time(), '20hr', 'refresh_invisionsoft_token', $args);
// }

// function refresh_invisionsoft_token() {
//   require_once('../invisionsoft.php');
// }

add_action( 'gform_pre_submission_12', 'pre_submission_handler' );
function pre_submission_handler( $form ) {
    //$_POST['input_19'] = wp_generate_password();
    $_POST['input_20'] = 'Membership -> 3C';
}

add_action( 'gform_pre_submission_2', 'pre_submission_handler_2' );
function pre_submission_handler_2( $form ) {
    // $_POST['input_17'] = wp_generate_password();
    $_POST['input_6'] = '3C Subscribe';
}

add_action( 'gform_pre_submission_13', 'pre_submission_handler_13' );
function pre_submission_handler_13( $form ) {
    // $_POST['input_17'] = wp_generate_password();
    $_POST['input_6'] = 'Coronavirus';
}

add_action( 'gform_pre_submission_14', 'pre_submission_handler_14' );
function pre_submission_handler_14( $form ) {
    // $_POST['input_17'] = wp_generate_password();
    $_POST['input_7'] = 'May 2020 Govenor Letter';
}

add_action( 'gform_pre_submission_19', 'pre_submission_handler_19' );
function pre_submission_handler_19( $form ) {
    // $_POST['input_17'] = wp_generate_password();
    $state = rgpost('input_6');
    $_POST['input_8'] = $state . ' Competition 2020';
}

//Remove comments
function comments_clean_header_hook(){
  wp_deregister_script( 'comment-reply' );
  }
 add_action('init','comments_clean_header_hook');

//  add_filter( 'object_sync_for_salesforce_pull_object_allowed', 'check_user', 10, 5 );
//  // can always reduce this number if all the arguments are not necessary
//  function check_user( $pull_allowed, $object_type, $object, $sf_sync_trigger, $salesforce_mapping ) {
//    if ( ($object_type !== 'Contact') || ($object['Tags__c'] !== 'May 2020 Govenor Letter') || ( $object['Lead_Source__c'] !== 'Facebook') ) {
//      $pull_allowed = false;
//    }
//    return $pull_allowed;
//  }

//  add_action( 'object_sync_for_salesforce_pre_pull', 'before_pull', 10, 5 );
// function before_pull( $wordpress_id, $mapping, $object, $wordpress_id_field_name, $params ) {
//   $states = array(
//     'Alabama'=>'AL',
//     'Alaska'=>'AK',
//     'Arizona'=>'AZ',
//     'Arkansas'=>'AR',
//     'California'=>'CA',
//     'Colorado'=>'CO',
//     'Connecticut'=>'CT',
//     'Delaware'=>'DE',
//     'District of Columbia' => 'DC',
//     'Florida'=>'FL',
//     'Georgia'=>'GA',
//     'Hawaii'=>'HI',
//     'Idaho'=>'ID',
//     'Illinois'=>'IL',
//     'Indiana'=>'IN',
//     'Iowa'=>'IA',
//     'Kansas'=>'KS',
//     'Kentucky'=>'KY',
//     'Louisiana'=>'LA',
//     'Maine'=>'ME',
//     'Maryland'=>'MD',
//     'Massachusetts'=>'MA',
//     'Michigan'=>'MI',
//     'Minnesota'=>'MN',
//     'Mississippi'=>'MS',
//     'Missouri'=>'MO',
//     'Montana'=>'MT',
//     'Nebraska'=>'NE',
//     'Nevada'=>'NV',
//     'New Hampshire'=>'NH',
//     'New Jersey'=>'NJ',
//     'New Mexico'=>'NM',
//     'New York'=>'NY',
//     'North Carolina'=>'NC',
//     'North Dakota'=>'ND',
//     'Ohio'=>'OH',
//     'Oklahoma'=>'OK',
//     'Oregon'=>'OR',
//     'Pennsylvania'=>'PA',
//     'Rhode Island'=>'RI',
//     'South Carolina'=>'SC',
//     'South Dakota'=>'SD',
//     'Tennessee'=>'TN',
//     'Texas'=>'TX',
//     'Utah'=>'UT',
//     'Vermont'=>'VT',
//     'Virginia'=>'VA',
//     'Washington'=>'WA',
//     'West Virginia'=>'WV',
//     'Wisconsin'=>'WI',
//     'Wyoming'=>'WY'
//   );

//   if($object['MailingState']) {
//     $state = $object['MailingState'];
//     $object['MailingState'] = $states[$state];

//     error_log( 'state is equal to' . $states[$state]);
//   }

//     // do things before the plugin saves any data in wordpress
//     // $wordpress_id is the object id
//     // $mapping is the field map between the object types
//     // $object is the object data
//     // $wordpress_id_field_name is the wordpress id field's name
//     // $params is the data mapping between the two systems
// }

// add_filter( 'object_sync_for_salesforce_pull_params_modify', 'change_pull_params', 10, 6 );
// function change_pull_params( $params, $mapping, $object, $sf_sync_trigger, $use_soap, $is_new ) {
//   $states = array(
//     'Alabama'=>'AL',
//     'Alaska'=>'AK',
//     'Arizona'=>'AZ',
//     'Arkansas'=>'AR',
//     'California'=>'CA',
//     'Colorado'=>'CO',
//     'Connecticut'=>'CT',
//     'Delaware'=>'DE',
//     'Florida'=>'FL',
//     'Georgia'=>'GA',
//     'Hawaii'=>'HI',
//     'Idaho'=>'ID',
//     'Illinois'=>'IL',
//     'Indiana'=>'IN',
//     'Iowa'=>'IA',
//     'Kansas'=>'KS',
//     'Kentucky'=>'KY',
//     'Louisiana'=>'LA',
//     'Maine'=>'ME',
//     'Maryland'=>'MD',
//     'Massachusetts'=>'MA',
//     'Michigan'=>'MI',
//     'Minnesota'=>'MN',
//     'Mississippi'=>'MS',
//     'Missouri'=>'MO',
//     'Montana'=>'MT',
//     'Nebraska'=>'NE',
//     'Nevada'=>'NV',
//     'New Hampshire'=>'NH',
//     'New Jersey'=>'NJ',
//     'New Mexico'=>'NM',
//     'New York'=>'NY',
//     'North Carolina'=>'NC',
//     'North Dakota'=>'ND',
//     'Ohio'=>'OH',
//     'Oklahoma'=>'OK',
//     'Oregon'=>'OR',
//     'Pennsylvania'=>'PA',
//     'Rhode Island'=>'RI',
//     'South Carolina'=>'SC',
//     'South Dakota'=>'SD',
//     'Tennessee'=>'TN',
//     'Texas'=>'TX',
//     'Utah'=>'UT',
//     'Vermont'=>'VT',
//     'Virginia'=>'VA',
//     'Washington'=>'WA',
//     'West Virginia'=>'WV',
//     'Wisconsin'=>'WI',
//     'Wyoming'=>'WY'
//   );

//   $state_field = $params['standup_state']['value'];
//   if(strlen($state_field) > 2) {
//     $state = $states[$state_field];
//     $params['standup_state']['value'] = $state;
//   }
//   // $params['post_status'] = array ( // wordpress field name
// 	// 	'value' => 'publish',
// 	// 	'method_modify' => 'wp_update_post',
// 	// 	'method_read' => 'get_posts'
// 	// );
//   //error_log(print_r($params, TRUE));
//   //error_log('State = ' . $state);
// 	return $params;
// }

// function extend_admin_search( $query ) {

//   if ( $query->is_search ) {

//       if ($query->query['post_type'] == 'letter') {
//           $query->set( 'post_type',  'letter' );
//       } else {
//           $query->set( 'post_type', 'post' );
//       }
//   }

//   return $query;

// }
// add_action( 'pre_get_posts', 'extend_admin_search' );
// END lib/admin.php //

// function wca_reduce_action_scheduler_retention() {
//   return DAY_IN_SECONDS;
// }

// add_filter( 'action_scheduler_retention_period', 'wca_reduce_action_scheduler_retention' );

function resource_load_more(){
 
  // prepare our arguments for the query
  $page = isset($_POST['page']) ? $_POST['page'] + 1 : 0;
	$args = [
    'post_type' => 'member',
    'post_status' => 'publish',
    'posts_per_page' => 500,
    'paged' => $page,
    'order' => 'ASC'
  ];
	// it is always better to use WP_Query but not here
	$wp_query = new \WP_Query($args);
 
  if( $wp_query->have_posts() ) :
    while( $wp_query->have_posts() ) : $wp_query->the_post();

      $view .= include(locate_template('templates/member-entry.php'));
    endwhile;

    //echo $view;
    //echo $wp_query->max_num_pages;
    $button = ($page < $wp_query->max_num_pages) ? $page : false;

    if($button) { ?>
      <div class="pagination-load-more ajax-load-container flex justify-center w-full pt-12">
        <div class="ajax-loading"></div>
        <button class="ajax-load" data-page="<?php echo $button ?>">Load More</button>
      </div>
    <?php }
    
  endif;
  wp_reset_query();

	die; // here we exit the script and even no wp_reset_query() required!
}
 
 
 
add_action('wp_ajax_resource_load_more', 'resource_load_more'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_resource_load_more', 'resource_load_more'); // wp_ajax_nopriv_{action}

add_filter( 'gform_init_scripts_footer', '__return_true' );

// Allow SVG
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

  global $wp_version;
  if ( $wp_version !== '4.7.1' ) {
     return $data;
  }

  $filetype = wp_check_filetype( $filename, $mimes );

  return [
      'ext'             => $filetype['ext'],
      'type'            => $filetype['type'],
      'proper_filename' => $data['proper_filename']
  ];

}, 10, 4 );

function cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

function fix_svg() {
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action( 'admin_head', 'fix_svg' );