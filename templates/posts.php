<?php
/**
 * Template Name: Posts
 */

add_action( 'genesis_before_content', function() {
  ?>
  <div class="page-header">
    <h1 class="page-title"><?php echo wp_kses_post( get_the_title() ); ?></h1>
  </div>
  <?php
} );

remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_before_content', 'cc_posts_template_before_content' );
function cc_posts_template_before_content() {
  if ( $categories = get_field( 'categories' ) ):
  $current_category = isset( $_GET['category'] ) ? sanitize_key( $_GET['category'] ) : '';
  $permalink = get_permalink();
  $permalink .= strpos( $permalink, '?' ) > -1 ? '&' : '?';
  ?>
  <div class="post-filters"><?php
    if ( $heading = get_field( 'filter_heading' ) ): ?>
      <h3 class="filter-heading"><?php echo wp_kses_post( $heading ); ?></h3><?php
    endif; ?>
    <div class="filter-container">
      <a class="button filter<?php echo $current_category === '' ? ' selected' : '' ?>" href="<?php echo esc_url( get_permalink() ); ?>">All</a>
      <?php
    foreach ( $categories as $category ): ?>
      <a class="button filter<?php echo $category->term_id == $current_category ? ' selected' : ''; ?>"
        href="<?php echo sprintf( '%scategory=%s', $permalink, $category->term_id ); ?>"><?php echo wp_kses_post( $category->name ); ?></a><?php
    endforeach; ?>
    </div>
  </div>
  <?php
  endif;
}

add_action( 'genesis_loop', 'cc_posts_template_loop' );
function cc_posts_template_loop() {
  if ( $categories = get_field( 'categories' ) ):

  add_filter( 'cc_entry_thumbnail_size', function() { return 'featured_square'; } );
  $cats = array_values( array_map( function( $cat ) {
    return $cat->term_id;
  }, $categories ) );
  $current_category = isset( $_GET['category'] ) ? array( sanitize_key( $_GET['category'] ) ) : null;

  $args = array(
    'category__in' => $current_category ? $current_category : $cats,
    'posts_per_page' => get_field( 'posts_per_page' ),
    'paged' => max( 1, get_query_var( 'paged' ) ),
  );
  global $wp_query;
  $wp_query = new WP_Query( $args );
  while ( $wp_query->have_posts() ): $wp_query->the_post();
  cc_entry_markup();
  endwhile; wp_reset_postdata();
  // wp_reset_query();
  endif;
}

add_filter( 'genesis_attr_entry', 'cc_posts_template_attributes' );
function cc_posts_template_attributes( $attributes ) {
  global $wp_query;
  $color = $wp_query->current_post % 2 === 0 ? 'dark' : 'light';
  $attributes['class'] .= " post-list-entry shadowed color-$color";
  return $attributes;
}


genesis();
