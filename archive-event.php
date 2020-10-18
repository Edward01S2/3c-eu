<?php

add_filter( 'cc_entry_thumbnail_icon', '__return_false' );
add_filter( 'cc_entry_footer_show_read_more', '__return_true' );
add_filter( 'cc_entry_footer_show_date', '__return_false' );

add_action( 'genesis_before', 'cc_before_events_archive' );
function cc_before_events_archive() {
  if ( isset( $_GET['archived'] ) === false ) {
    remove_action( 'genesis_loop', 'genesis_do_loop' );
  } else {
    remove_action( 'genesis_loop', 'cc_future_events_loop' );
  }
}

add_filter( 'genesis_noposts_text', function() {
  return get_field( 'no_upcoming_events_text', 'option' ) ? get_field( 'no_upcoming_events_text', 'option' ) : 'No Events Found';
} );

add_action( 'genesis_before_content', 'cc_event_archive_heading', 5 );
function cc_event_archive_heading() {
  $title = get_field( 'event_archive_title', 'option' ) ? get_field( 'event_archive_title', 'option' ) : 'Upcoming Events';
  ?>
  <div class="archive-description event-archive-description event-description">
    <h1 class="archive-title"><?php echo wp_kses_post( $title ); ?></h1>
  </div>
  <?php
}

add_action( 'genesis_loop', 'cc_future_events_loop' );
function cc_future_events_loop() {
  global $wp_query;

  //date_default_timezone_set('America/New_York');
  //$time = date('H:i:s');
  $time = new DateTime("now", new DateTimeZone('America/New_York'));
  $current = $time->format('H:i:s');
  //echo $time;
  $today = date('Ymd');
  //echo $current;
  // echo $today;
  //echo $current;
  // $today = current_time('Y-m-d');

  $args = array(
    'post_type' => 'event',
    'posts_per_page' => -1,
    'meta_query' => array(
      'relation' => 'AND', 
      array(
        'key' => 'date',
        'value' => $today,
        'type' => 'DATETIME',
        'compare' => '>=',
      ),
      // array(
      //   'key' => 'end_time',
      //   'value' => $current,
      //   'type' => 'TIME',
      //   'compare' => '>=',
      // ) 
    ),
  );
  // var_dump($args);
  //echo(get_field('date'));
  $wp_query = new WP_Query( $args );
  if ( $wp_query->have_posts() ):
  while ( $wp_query->have_posts() ): $wp_query->the_post();
  //echo get_field('date');
  // $time = new DateTime(null, new DateTimeZone('America/New_York'));
  // $current = $time->format('g:i a');
  // echo $current;
  // if(get_field('end_time') >= $current) {
  //   echo 'Maybe true';
  // }
  // $today = current_time('d M, y');
  // echo $today;
  cc_entry_markup();
  wp_reset_postdata();
  endwhile;
  wp_reset_query();
  // query reset in genesis_after_content
  else:
  genesis_do_noposts();
  endif;
}

add_action( 'genesis_after_content', 'cc_after_content_archive_pagination' );
function cc_after_content_archive_pagination() {
  global $wp_query;
  if ( $wp_query->have_posts() === false ):
  ?>
  <div class="archive-pagination pagination">
    <div class="pagination-next alignright">
      <a href="<?php echo esc_url( get_post_type_archive_link( 'event' ) ); ?>?archived=true" data-more-text="<?php echo __( 'Load Archived Events', 'connectedcouncil' ) ?>">Next Page »</a>
    </div>
  </div>
  <?php
  endif;
  wp_reset_query();
}

add_action( 'genesis_entry_header', 'cc_entry_header_event_info' );
function cc_entry_header_event_info() {
  $date = get_field( 'date' );
  if ( $date ) {
    $date = date( 'F j, Y', strtotime( $date ) );
    if ( get_field( 'add_day' ) ) {
      $date .= ', All Day';
    } else {
      if ( $start_time = get_field( 'start_time' ) ) {
        $date .= ", <upper>$start_time</upper>";
      }
      if ( $end_time = get_field( 'end_time' ) ) {
        $date .= " – <upper>$end_time</upper>";
      }
      if( $timezone = get_field('timezone') ) {
        $date .= " <upper>$timezone</upper>";
      }
    }
  }
  $location = get_field( 'location' );
  if ( $location ) {
    if ( $address = get_field( 'address' ) ) {
      $location .= ", $address";
    }
  }

  if ( $date || $location ):
  ?>
  <p class="entry-meta">
    <?php if ( $date ): ?>
    <span><b>Date:</b> <?php echo $date; ?></span>
    <?php endif;
    if ( $location ): ?>
    <span><b>Location:</b> <?php echo $location; ?></span>
    <?php endif; ?>
  </p>
  <?php
  endif;
}

add_filter( 'genesis_attr_entry', 'cc_event_archive_attributes_entry' );
function cc_event_archive_attributes_entry( $attributes ) {
  $attributes['class'] .= ' post-list-white-entry';
  return $attributes;
}

add_filter( 'get_pagenum_link', function( $url ) {
  if ( isset( $_GET['archived'] ) === false ) {
    return get_post_type_archive_link( 'event' ) . '?archived=true';
  }
  return $url . '?archived=true';
} );

add_filter('next_posts_link_attributes', function() {
  return 'data-more-text="' . __( 'Load Archived Events', 'connectedcouncil' ) . '"';
} );

genesis();
