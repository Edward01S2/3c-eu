<?php

add_action( 'init', 'cc_register_post_types' );
function cc_register_post_types() {

	// People

  $labels = array(
    'name'               => 'People',
    'singular_name'      => 'Person',
    'menu_name'          => 'People',
    'name_admin_bar'     => 'Person',
    'add_new'            => 'Add New',
    'add_new_item'       => 'Add New Person',
    'new_item'           => 'New Person',
    'edit_item'          => 'Edit Person',
    'view_item'          => 'View Person',
    'all_items'          => 'All People',
    'search_items'       => 'Search People',
    'parent_item_colon'  => 'Parent People:',
    'not_found'          => 'No people found.',
    'not_found_in_trash' => 'No people found in Trash.',
  );

	$args = array(
		'label' => 'People',
		'labels' => $labels,
		'description' => '',
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_rest' => false,
		'rest_base' => '',
		'has_archive' => false,
		'show_in_menu' => true,
		'show_in_nav_menus' => false,
		'exclude_from_search' => false,
		'capability_type' => 'post',
		'map_meta_cap' => true,
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'person', 'with_front' => true ),
		'query_var' => true,
		'menu_position' => 20,
		'menu_icon' => 'dashicons-groups',
		'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
	);

	register_post_type( 'person', $args );


	// Reports

	$labels = array(
    'name'               => 'Reports',
    'singular_name'      => 'Report',
    'menu_name'          => 'Reports',
    'name_admin_bar'     => 'Report',
    'add_new'            => 'Add New',
    'add_new_item'       => 'Add New Report',
    'new_item'           => 'New Report',
    'edit_item'          => 'Edit Report',
    'view_item'          => 'View Report',
    'all_items'          => 'All Reports',
    'search_items'       => 'Search Reports',
    'parent_item_colon'  => 'Parent Reports:',
    'not_found'          => 'No reports found.',
    'not_found_in_trash' => 'No reports found in Trash.',
  );

	$args = array(
		'label' => 'Reports',
		'labels' => $labels,
		'description' => '',
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_rest' => false,
		'rest_base' => '',
		'has_archive' => false,
		'show_in_menu' => true,
		'show_in_nav_menus' => false,
		'exclude_from_search' => false,
		'capability_type' => 'post',
		'map_meta_cap' => true,
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'report', 'with_front' => true ),
		'query_var' => true,
		'menu_position' => 20,
		'menu_icon' => 'dashicons-analytics',
		'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
	);

	register_post_type( 'report', $args );


	// Issues

	$labels = array(
    'name'               => 'Issues',
    'singular_name'      => 'Issue',
    'menu_name'          => 'Issues',
    'name_admin_bar'     => 'Issue',
    'add_new'            => 'Add New',
    'add_new_item'       => 'Add New Issue',
    'new_item'           => 'New Issue',
    'edit_item'          => 'Edit Issue',
    'view_item'          => 'View Issue',
    'all_items'          => 'All Issues',
    'search_items'       => 'Search Issues',
    'parent_item_colon'  => 'Parent Issues:',
    'not_found'          => 'No issues found.',
    'not_found_in_trash' => 'No issues found in Trash.',
  );

	$args = array(
		'label' => 'Issues',
		'labels' => $labels,
		'description' => '',
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_rest' => false,
		'rest_base' => '',
		'has_archive' => false,
		'show_in_menu' => true,
		'show_in_nav_menus' => false,
		'exclude_from_search' => false,
		'capability_type' => 'post',
		'map_meta_cap' => true,
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'issue', 'with_front' => true ),
		'query_var' => true,
		'menu_position' => 20,
		// 'menu_icon' => 'dashicons-analytics',
		'supports' => array( 'title', 'editor', 'thumbnail', 'revisions' ),
	);

	register_post_type( 'issue', $args );


	// Events

	$labels = array(
    'name'               => 'Events',
    'singular_name'      => 'Event',
    'menu_name'          => 'Events',
    'name_admin_bar'     => 'Event',
    'add_new'            => 'Add New',
    'add_new_item'       => 'Add New Event',
    'new_item'           => 'New Event',
    'edit_item'          => 'Edit Event',
    'view_item'          => 'View Event',
    'all_items'          => 'All Events',
    'search_items'       => 'Search Events',
    'parent_item_colon'  => 'Parent Events:',
    'not_found'          => 'No events found.',
    'not_found_in_trash' => 'No events found in Trash.',
  );

	$args = array(
		'label' => 'Events',
		'labels' => $labels,
		'description' => '',
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_rest' => false,
		'rest_base' => '',
		'has_archive' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => false,
		'exclude_from_search' => false,
		'capability_type' => 'post',
		'map_meta_cap' => true,
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'events', 'with_front' => true ),
		'query_var' => true,
		'menu_position' => 20,
		'menu_icon' => 'dashicons-calendar',
		'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
	);

	register_post_type( 'event', $args );


	// Actions

	$labels = array(
    'name'               => 'Actions',
    'singular_name'      => 'Action',
    'menu_name'          => 'Actions',
    'name_admin_bar'     => 'Action',
    'add_new'            => 'Add New',
    'add_new_item'       => 'Add New Action',
    'new_item'           => 'New Action',
    'edit_item'          => 'Edit Action',
    'view_item'          => 'View Action',
    'all_items'          => 'All Actions',
    'search_items'       => 'Search Actions',
    'parent_item_colon'  => 'Parent Actions:',
    'not_found'          => 'No actions found.',
    'not_found_in_trash' => 'No actions found in Trash.',
  );

	$args = array(
		'label' => 'Actions',
		'labels' => $labels,
		'description' => '',
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_rest' => false,
		'rest_base' => '',
		'has_archive' => false,
		'show_in_menu' => true,
		'show_in_nav_menus' => false,
		'exclude_from_search' => false,
		'capability_type' => 'post',
		'map_meta_cap' => true,
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'actions', 'with_front' => false ),
		'query_var' => true,
		'menu_position' => 20,
		'menu_icon' => 'dashicons-hammer',
		'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
	);

	register_post_type( 'action', $args );


	// Stories

	$labels = array(
    'name'               => 'Stories',
    'singular_name'      => 'Story',
    'menu_name'          => 'Stories',
    'name_admin_bar'     => 'Story',
    'add_new'            => 'Add New',
    'add_new_item'       => 'Add New Story',
    'new_item'           => 'New Story',
    'edit_item'          => 'Edit Story',
    'view_item'          => 'View Story',
    'all_items'          => 'All Stories',
    'search_items'       => 'Search Stories',
    'parent_item_colon'  => 'Parent Stories:',
    'not_found'          => 'No stories found.',
    'not_found_in_trash' => 'No stories found in Trash.',
  );

	$args = array(
		'label' => 'Stories',
		'labels' => $labels,
		'description' => '',
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_rest' => false,
		'rest_base' => '',
		'has_archive' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => false,
		'exclude_from_search' => false,
		'capability_type' => 'post',
		'map_meta_cap' => true,
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'stories', 'with_front' => false ),
		'query_var' => true,
		'menu_position' => 20,
		'menu_icon' => 'dashicons-id-alt',
		'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
	);

	register_post_type( 'story', $args );
}
