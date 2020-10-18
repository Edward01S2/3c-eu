<?php
// lib/theme.php //

// Soil
// add_theme_support('soil-clean-up');
// // add_theme_support('soil-disable-asset-versioning');
// add_theme_support('soil-disable-trackbacks');
// add_theme_support('soil-jquery-cdn');
// add_theme_support('soil-js-to-footer');
// add_theme_support('soil-nav-walker');
// add_theme_support('soil-nice-search');
add_theme_support('soil-relative-urls');

add_theme_support( 'post-formats', array( 'link' ) );

// ACF

// Save ACF options to json
add_filter( 'acf/settings/save_json', 'cc_save_acf_json' );
function cc_save_acf_json( $path ) {
	$path = get_stylesheet_directory() . '/acf_json';
	return $path;
}

add_filter( 'acf/settings/load_json', 'cc_acf_json_load_point' );
function cc_acf_json_load_point( $paths ) {
	unset($paths[0]);
	$paths[] = get_stylesheet_directory() . '/acf_json';
	return $paths;
}

// Genesis

// Tells Genesis to use HTML5 markup
add_theme_support( 'html5' );

// Fucking comments
remove_action( 'genesis_comments', 'genesis_do_comments' );

// Prevents users from zooming like dicks, adjust or remove if needed
add_action( 'genesis_meta', 'cc_viewport_meta_tag_output' );
function cc_viewport_meta_tag_output() {
  ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <?php
}

add_filter( 'genesis_search_text', function() {
  return 'Search...';
} );

remove_action( 'admin_init', 'genesis_add_taxonomy_seo_options' );
remove_action( 'admin_init', 'genesis_add_taxonomy_layout_options' );

// Remove default Genesis templates
add_filter( 'theme_page_templates', 'cc_remove_genesis_page_templates' );
function cc_remove_genesis_page_templates( $page_templates ) {
  unset( $page_templates['page_archive.php'] );
  unset( $page_templates['page_blog.php' ] );
  return $page_templates;
}

// Adjust settings without need of the admin menu page
add_filter('genesis_options', 'cc_set_genesis_defaults', 10, 2);
function cc_set_genesis_defaults( $options, $setting ) {
  if ( $setting == 'genesis-settings' ) {
    $options['content_archive'] = 'excerpts';
    $options['comments_posts'] = false;
    $options['posts_nav'] = 'prev-next';
  }
  return $options;
}

// Other

add_filter( 'wpseo_metabox_prio', '__return_null'); // Puts Yoast metabox as low as possible


// Wordpress

add_filter( 'excerpt_length', 'cc_excerpt_length' );
function cc_excerpt_length( $length ) {
  return 20;
}

add_filter('excerpt_more', 'cc_get_read_more_link');
add_filter( 'the_content_more_link', 'cc_get_read_more_link' );
function cc_get_read_more_link() {
  return '...';
}


// GCT

add_action( 'wp_head', 'cc_wp_head' );
function cc_wp_head() {
  ?>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700&display=swap" rel="stylesheet">
  <?php
}

add_action( 'wp_enqueue_scripts', 'cc_enqueue_scripts', 99 );
function cc_enqueue_scripts() {
  $dir = get_stylesheet_directory_uri();
  $dir_array = array('url' => $dir);
  wp_enqueue_style( 'default', esc_url( $dir ) . '/dist/styles/all.css' );
  // wp_enqueue_script( 'slicknav', esc_url( $dir ) . '/assets/js/jquery.slicknav.min.js', array( 'jquery' ), false, true );
  wp_enqueue_script( 'functions', esc_url( $dir ) . '/dist/scripts/app.js', array( 'jquery' ), false, true );
  wp_localize_script( 'functions', 'ajax_url', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
  wp_enqueue_script( 'json','/wp-content/plugins/gravityforms/js/jquery.json.min.js', array('jquery'), false, true);
  // wp_enqueue_script( 'gravityforms','/wp-content/plugins/gravityforms/js/gravityforms.min.js', array('jquery'), false, true);
  // wp_localize_script( 'functions', 'dir', $dir_array );
  wp_dequeue_style('wp-block-library');

  // $api_vars = array(
  //   'cliendId' => ,
  //   'clientSecret' => ,
  //   'redirectUri' => ,
  // )
}

// add_filter( 'style_loader_tag',  'style_preload', 10, 2);
// function style_preload( $html, $handle ){
//   if($handle === 'fas' || $handle === 'far') {
//     return str_replace('rel=\'stylesheet\'', 'rel="preload" as="font" type="font/woff2" crossorigin="anonymous"', $html);
//   }
//   return $html;   
// }


// if ( WP_DEBUG === true ) {
//   add_action( 'wp_enqueue_scripts', 'cc_enqueue_dev_scripts', 99 );
//   function cc_enqueue_dev_scripts() {
//     if ( isset( $_SERVER['HTTP_HOST'] ) && strpos( $_SERVER['HTTP_HOST'], '.localdev' ) > -1 ) {
//       wp_enqueue_script( 'browsersync', 'http://localhost:3000/browser-sync/browser-sync-client.js?v=2.24.4', null, false, true );
//     }
//   }
// }

// Add custom image sizes here
add_action( 'after_setup_theme', 'cc_setup_image_sizes' );
function cc_setup_image_sizes() {
  add_image_size( 'featured_rectangle_wide', 960, 465, true );
  add_image_size( 'featured_rectangle', 960, 675, true );
  add_image_size( 'featured_square', 960, 894, true );
  add_image_size( 'person', 540, 540, true );
  add_image_size( 'story', 730, 730, true );
}

// Not mine originally. Useful for upsizing shit images to fit the theme correctly
// Does not replace kicking whoever is using bad images
add_filter( 'image_resize_dimensions', 'cc_image_crop_dimensions', 10, 6 );
function cc_image_crop_dimensions( $default, $orig_w, $orig_h, $new_w, $new_h, $crop ){
  if ( !$crop ) return null;
  // 150, 150 so anything above default WP 'thumbnail' will be upsized
  if ( $orig_w <= 150 || $orig_h <= 150 ) return;

  $aspect_ratio = $orig_w / $orig_h;
  $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

  $crop_w = round($new_w / $size_ratio);
  $crop_h = round($new_h / $size_ratio);

  $s_x = floor( ($orig_w - $crop_w) / 2 );
  $s_y = floor( ($orig_h - $crop_h) / 2 );

  if( is_array( $crop ) ) { if( $crop[ 0 ] === 'left' ) { $s_x = 0; } else if( $crop[ 0 ] === 'right' ) { $s_x = $orig_w - $crop_w;} if( $crop[ 1 ] === 'top' ) { $s_y = 0; } else if( $crop[ 1 ] === 'bottom' ) { $s_y = $orig_h - $crop_h; } }

  return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}


// Scripts

add_action( 'wp_head', 'cc_header_scripts', 999 );
function cc_header_scripts() {
  if ( $code = get_field( 'header_scripts', 'option' ) ) {
    echo "\n";
    echo $code;
    echo "\n";
  }

  if ( $code = get_field( 'header_scripts'  ) ) {
    echo "\n";
    echo $code;
    echo "\n";
  }
}

add_action( 'genesis_after', 'cc_before_scripts', 1 );
function cc_before_scripts() {
  if ( $code = get_field( 'body_scripts', 'option' ) ) {
    echo "\n";
    echo $code;
    echo "\n";
  }

  if ( $code = get_field( 'body_scripts' ) ) {
    echo "\n";
    echo $code;
    echo "\n";
  }
}

add_action( 'wp_footer', 'cc_footer_scripts', 999 );
function cc_footer_scripts() {
  if ( $code = get_field( 'footer_scripts', 'option' ) ) {
    echo "\n";
    echo $code;
    echo "\n";
  }

  if ( $code = get_field( 'footer_scripts' ) ) {
    echo "\n";
    echo $code;
    echo "\n";
  }
}

// Include if Facebook sharing is required
// add_action( 'wp_head', 'cc_print_facebook_init' );
function cc_print_facebook_init() {
  $facebook_id = function_exists( 'get_field' ) ? get_field( 'facebook_id', 'option' ) : false;
  if ( $facebook_id !== false ) {
    echo '<script>window.fbAsyncInit = function(){FB.init({appId: \'' . esc_attr( $facebook_id ) . '\', status: true, cookie: true, xfbml: true });};(function(d, debug){var js, id = \'facebook-jssdk\', ref = d.getElementsByTagName(\'script\')[0];if(d.getElementById(id)) {return;}js = d.createElement(\'script\'); js.id = id; js.async = true;js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";ref.parentNode.insertBefore(js, ref);}(document, /*debug*/ false));function postToShare(url){var obj = {method: \'share\',href: url};function callback(response){}FB.ui(obj, callback);}</script>' . "\n";
  }
}

// END lib/theme.php //
