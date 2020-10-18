<?php

add_action( 'pre_get_posts', 'cc_events_pre_get_posts' );
function cc_events_pre_get_posts( $query ) {
  if ( is_admin() === false && $query->is_main_query() === true && is_post_type_archive( 'event' ) ):
  $query->set( 'orderby', 'meta_value' );
  $query->set( 'meta_key', 'date' );
  if ( isset( $_GET['archived'] ) === false ) {
    // trick to show pagination
    $query->set( 'posts_per_page', 1 );
  }
  $query->set( 'meta_query', array(
    array(
      'key' => 'date',
      'value' => date( 'c' ),
      'type' => 'DATETIME',
      // 'compare' => isset( $_GET['archived'] ) ? '<' : '>=',
      'compare' => '<',
    )
  ) );
  endif;
}

add_filter( 'manage_event_posts_columns' , 'cc_event_post_columns' );
function cc_event_post_columns( $columns ) {
  unset( $columns['date'] );
  $columns['event_date'] = 'Date';
  $columns['start_time'] = 'Start Time';
  $columns['end_time'] = 'End Time';
  $columns['date'] = 'Date Published';
  return $columns;
}

add_action( 'manage_event_posts_custom_column' , 'cc_event_posts_custom_column', 10, 2 );
function cc_event_posts_custom_column( $column, $post_id ) {
  switch ( $column ) {
    case 'event_date' :
      echo get_field( 'date', $post_id );
      break;
    case 'start_time':
      echo get_field( 'all_day' ) ? 'All Day' : get_field( 'start_time', $post_id );
      break;
    case 'end_time':
      echo get_field( 'all_day' ) ? 'All Day' : get_field( 'end_time', $post_id );
      break;
  }
}
