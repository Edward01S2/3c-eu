<?php
/**
 * Template Name: Resilience
 */

add_action( 'genesis_before_content', function() {
  ?>
  <div class="page-header">
    <h1 class="page-title"><?php echo wp_kses_post( get_the_title() ); ?></h1>
  </div>
  <?php
} );

remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_loop', 'cc_posts_template_loop' );
function cc_posts_template_loop() {
  // if ( $categories = get_field( 'categories' ) ):

  add_filter( 'cc_entry_thumbnail_size', function() { return 'featured_square'; } );
  // $cats = array_values( array_map( function( $cat ) {
  //   return $cat->term_id;
  // }, $categories ) );
  // $current_category = isset( $_GET['category'] ) ? array( sanitize_key( $_GET['category'] ) ) : null;

  $args = array(
    'category_name' => 'resilience',
    'posts_per_page' => 9,
    'paged' => max( 1, get_query_var( 'paged' ) ),
  );
  global $wp_query;
  $wp_query = new WP_Query( $args );
  while ( $wp_query->have_posts() ): $wp_query->the_post();
  cc_entry_markup();
  endwhile; wp_reset_postdata();
  // wp_reset_query();
  // endif;
}

add_filter( 'genesis_attr_entry', 'cc_posts_template_attributes' );
function cc_posts_template_attributes( $attributes ) {
  global $wp_query;
  $color = $wp_query->current_post % 2 === 0 ? 'dark' : 'light';
  $attributes['class'] .= " post-list-entry shadowed color-$color";
  return $attributes;
}


genesis();
