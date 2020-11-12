<?php
/**
 * Template Name: Thank You
 */

add_action( 'wp_head', 'es_header_scripts', 999 );
function es_header_scripts() {
  if ( $code = get_field( 'script'  ) ) {
    echo "\n";
    echo $code;
    echo "\n";
  }
}

add_filter( 'genesis_attr_entry', 'cc_join_attributes_entry' );
function cc_join_attributes_entry( $attributes ) {
  $attributes['class'] .= ' white-content';
  return $attributes;
}

genesis();
