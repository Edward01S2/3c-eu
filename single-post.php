<?php

add_action( 'genesis_before_footer', 'cc_single_related_posts' );
function cc_single_related_posts() {
  global $wpdb;
  global $post;
  if ( function_exists( 'get_crp_posts_id' ) ):
  $current_language = apply_filters( 'wpml_current_language', NULL );
  $all_posts = get_crp_posts_id( array(
    'postid' => get_the_id(),
    'match_content' => true,
    'limit' => 9,
  ) );

  $posts = array();
  $newsletter_category = get_field( 'newsletter_category', 'option' );
  foreach( $all_posts as $lang_post ) {
    $lang_details = apply_filters( 'wpml_post_language_details', NULL, $lang_post->ID );
    if ( $lang_details['language_code'] !== $current_language ) {
      continue;
    }
    if ( $newsletter_category && has_category( $newsletter_category, $lang_post->ID ) ) {
      continue;
    }
    $posts[] = $lang_post;
    if ( count( $posts ) === 3 ) {
      break;
    }
  }

  if ( count( $posts ) > 0 ):

  remove_action( 'genesis_entry_header', 'cc_post_entry_meta' );
  add_filter( 'cc_entry_thumbnail_icon', '__return_true' );
  add_filter( 'cc_entry_thumbnail_size', function() { return 'featured_square'; } );
  add_filter( 'cc_entry_footer_show_date', '__return_false' );
  add_filter( 'genesis_attr_entry', 'cc_single_related_posts_entry_attributes' );
  add_filter( 'cc_link_entry_thumbnail', '__return_true' );
  add_filter( 'genesis_entry_title_wrap', function() { return 'h2'; } );

  add_filter( 'genesis_post_title_text', function( $title ) {
    return sprintf( '<a href="%s" rel="bookmark">%s</a>', get_permalink(), $title );
  } );
  add_filter( 'cc_entry_footer_show_read_more', '__return_true' );

  ?>
  <section class="recent-posts-section">
    <div class="wrap">
      <div class="posts-container children-thirds"><?php
      foreach ( $posts as $post ) {
        $post = get_post( $post->ID );
        setup_postdata( $post );
        cc_entry_markup();wp_reset_postdata();
      }
       ?>
    </div>
    </div>
  </section>
  <?php
  endif;
  endif;
}


function cc_single_related_posts_entry_attributes( $attributes ) {
  $attributes['class'] .= ' post-list-entry shadowed';
  return $attributes;
}


genesis();
