<?php

add_action( 'init', function() {
	register_extended_post_type( 'resource', [
        'admin_cols' => array(
            // The default Title column:
            'title',
            'type' => array(
              'title' => 'Type',
              'meta_key' => 'Aid Type',
            ),
            'state' => array(
              'title' => 'State',
              'meta_key' => 'State',
            ),
            // A taxonomy terms column:
            'genre' => array(
                'taxonomy' => 'tag'
            ),
            // A meta field column:
            'date' => array(
                'title'       => 'Date',
                'meta_key'    => 'published_date',
                'date_format' => 'd/m/Y'
            ),
        ),
        'menu_icon' => 'dashicons-location-alt',
        'show_in_rest' => true,
        'menu_position' => null,
        'supports' => [
          'post-formats', 'title', 'editor', 'excerpt'
        ],
        'archive' => array(
          'nopaging' => true
        ),
        'has_archive' => true
    ],
    [
        'singular' => 'Coronavirus Resource',
        'plural'   => 'Coronavirus Resources',
        'slug'     => 'covid19fundingfinder',   
    ]
    );
    register_extended_taxonomy( 'tag', 'resource', array(
        'meta_box' => 'simple',
    ), array(
    
        # Override the base names used for labels:
        'singular' => 'Tag',
        'plural'   => 'Tags',
        'slug'     => 'tag'
    
    ) );
} );

add_action( 'init', function() {
	register_extended_post_type( 'brief', [
        'admin_cols' => array(
            // The default Title column:
            'title',
            // A meta field column:
            'date' => array(
                'title'       => 'Date',
                'meta_key'    => 'published_date',
                'date_format' => 'd/m/Y'
            ),
        ),
        'menu_icon' => 'dashicons-clipboard',
        'show_in_rest' => true,
        'menu_position' => null,
        'supports' => [
         'title', 'thumbnail', 'post-formats',
        ],
    ],
    [
        'singular' => 'Brief',
        'plural'   => 'Briefings',
        'slug'     => 'brief',   
    ]
    );
} );

add_action( 'init', function() {
	register_extended_post_type( 'member', [
        'admin_cols' => array(
            // The default Title column:
            'title',
            // A meta field column:
            'date' => array(
                'title'       => 'Date',
                'meta_key'    => 'published_date',
                'date_format' => 'd/m/Y'
            ),
        ),
        'menu_icon' => 'dashicons-universal-access-alt',
        'show_in_rest' => true,
        'menu_position' => null,
        'supports' => [
         'title',
        ],
    ],
    [
        'singular' => 'Member',
        'plural'   => 'Members',
        'slug'     => 'member',   
    ]
    );
} );

add_action( 'init', function() {
	register_extended_post_type( 'letter', [
        'admin_cols' => array(
            // The default Title column:
            'title',
            'state' => array(
              'title' => 'State',
              'meta_key' => 'standup_state',
            ),
            // A meta field column:
            'date'
        ),
        'menu_icon' => 'dashicons-vault',
        'label' => 'Standups',
        'show_in_rest' => true,
        'menu_position' => null,
        'supports' => [
          'post-formats', 'title',
        ],
        'archive' => array(
          'nopaging' => true
        ),
        'has_archive' => true,
    ],
    [
        'singular' => 'Standup',
        'plural'   => 'Standups',
        'slug'     => 'standupforsmallbusiness',   
    ]
    );
} );