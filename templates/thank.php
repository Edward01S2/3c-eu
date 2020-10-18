<?php
/**
 * Template Name: Thank You
 */

add_filter( 'genesis_attr_entry', 'cc_join_attributes_entry' );
function cc_join_attributes_entry( $attributes ) {
  $attributes['class'] .= ' white-content';
  return $attributes;
}

genesis();
