<?php

add_action( 'wp_enqueue_scripts', 'cc_archive_story_enqueue_scripts' );
function cc_archive_story_enqueue_scripts() {
  wp_enqueue_style( 'states', get_stylesheet_directory_uri() . '/assets/css/states.min.css' );
}

add_action( 'wp_head', 'cc_archive_story_head' );
function cc_archive_story_head() {
  if ( $image = get_field( 'lift_archive_background', 'option' ) ):
  ?>
  <style>
    .site-inner { background-image: url(<?php echo esc_url( $image ); ?>); }
  </style>
  <?php
  endif;
}

remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_loop', 'cc_archive_story_after_header' );
function cc_archive_story_after_header() {
  $args = array(
    'post_type' => 'story',
    'posts_per_page' => -1
  );
  $meta_query = array();
  $selected_state = isset( $_GET['state'] ) ? sanitize_text_field( $_GET['state'] ) : '';
  $selected_type = isset( $_GET['type'] ) ? sanitize_key( $_GET['type'] ) : '';
  if ( $selected_state ) {
    $meta_query[] = array(
      'key' => 'state',
      'value' => $selected_state,
    );
  }
  if ( $selected_type ) {
    $meta_query[] = array(
      'key' => 'video',
      'value' => '',
      'compare' => $selected_type === 'video' ? '!=' : '==',
    );
  }
  $args['meta_query'] = $meta_query;
  $posts = get_posts( $args );
  if ( $selected_type === 'text' ) {
    $posts = array_filter( $posts, function( $post ) {
      return trim( $post->post_content) != '';
    } );
  }
  ?>
  <div class="story-archive-header">
    <div class="title-container">
      <?php if ( $logo = get_field( 'lift_logo', 'option' ) ): ?>
      <div class="lift-logo">
        <img src="<?php echo esc_attr( $logo ); ?>">
      </div>
      <?php endif; ?>
      <h1 class="title">Stories</h1>
    </div>
    <?php if ( $content = get_field( 'lift_archive_content', 'option' ) ): ?>
    <div class="content-container">
      <?php echo apply_filters( 'the_content', $content ); ?>
    </div>
    <?php endif;
    cc_archive_story_filters( $posts, $selected_state, $selected_type ); ?>
  </div>
  <?php
  cc_archive_story_loop( $posts );
}

function cc_archive_story_filters( $posts, $selected_state, $selected_type ) {
  $states = array();
  foreach ( $posts as $post ) {
    $state = get_field( 'state', $post->ID );
    $states[$state['value']] = $state['label'];
  }
  ksort( $states );
  $permalink = get_post_type_archive_link( 'story' );
  ?>
  <div class="archive-story-filters">
    <div class="filter">
      <span>State:</span>
      <select class="state-filter">
        <option value="">All States</option><?php
        foreach ( $states as $abbr => $label ): ?>
          <option <?php selected( $selected_state, $abbr, true ); ?>value="<?php echo esc_attr( $abbr ); ?>"><?php echo esc_attr( $label ); ?></option><?php
        endforeach; ?>
      </select>
    </div>
    <div class="filter">
      <a data-type="" class="button small <?php echo $selected_type === '' ? 'blue' : 'green'; ?>" href="<?php echo esc_url( $permalink ); ?>">All Stories</a>
      <a data-type="video" class="button small <?php echo $selected_type === 'video' ? 'blue' : 'green'; ?>" href="<?php echo esc_url( $permalink ); ?>">Video</a>
      <a data-type="text" class="button small <?php echo $selected_type === 'text' ? 'blue' : 'green'; ?>" href="<?php echo esc_url( $permalink ); ?>">Text</a>
    </div>
  </div>
  <?php
}

add_action( 'wp_footer', 'cc_wp_footer_archive_story' );
function cc_wp_footer_archive_story() {
  ?>
  <script>
  ;(function($) {
    $(document).ready(function() {
      $('.archive-story-filters .button').click(function(e) {
        e.preventDefault()
        if ($(this).hasClass('green')) {
          updateFilters($(this).attr('data-type'))
        }
      })
      $('.archive-story-filters select').change(function() {
        updateFilters()
      })
      function updateFilters(type) {
        var url = window.location.href.split('?')[0]
        var parts = []
        var state = $('.archive-story-filters select').find(':selected').val()
        if (state != '' ) {
          parts.push('state=' + state)
        }
        if (!type && type != '') {
          type = $('.archive-story-filters .button.blue').attr('data-type')
        }
        if (type != '') {
          parts.push('type=' + type)
        }
        if (parts.length > 0) {
          url += '?' + parts.join('&')
        }
        window.location.href = url
      }
    })
  })(jQuery);
  </script>
  <?php
}

remove_action( 'genesis_after_content', 'genesis_posts_nav' );

add_action( 'genesis_before_footer', 'cc_archive_story_before_footer' );

remove_action( 'genesis_before_footer', 'cc_before_footer_section', 99 );
add_action( 'genesis_before_footer', 'cc_lift_form_footer_section' );
add_action( 'genesis_before_footer', 'cc_archive_story_buttons' );

genesis();
