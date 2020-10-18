<?php

remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
add_action( 'genesis_before_content', 'genesis_do_taxonomy_title_description', 15 );

add_action( 'genesis_before', 'cc_before_category' );
function cc_before_category() {
  add_filter( 'cc_entry_thumbnail_size', function() { return 'featured_rectangle'; } );
}

add_filter( 'genesis_attr_entry', 'cc_category_attributes' );
function cc_category_attributes( $attributes ) {
  global $wp_query;
  $color = $wp_query->current_post % 2 === 0 ? 'dark' : 'light';
  $attributes['class'] .= " post-list-entry shadowed color-$color";
  return $attributes;
}

genesis();
