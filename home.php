<?php

add_action( 'genesis_before_content', 'cc_home_heading', 5 );
function cc_home_heading() {
  $home_id = get_option( 'page_for_posts' );
  ?>
  <div class="archive-description event-archive-description event-description">
    <h1 class="archive-title"><?php echo wp_kses_post( get_the_title( $home_id ) ); ?></h1>
  </div>
  <?php
}

add_action( 'genesis_before_content', 'cc_blog_featured_posts' );
function cc_blog_featured_posts() {
  if ( $featured_posts = get_field( 'featured_posts', get_option( 'page_for_posts' ) ) ):
  global $post;
  add_filter( 'cc_entry_thumbnail_size', function() { return 'featured_rectangle_wide'; } );
  ?>
  <div class="featured-posts"><?php
    foreach ( $featured_posts as $post ): setup_postdata( $post );
    cc_entry_markup();
    if ( count( $featured_posts ) === 2 ) {
      add_filter( 'cc_entry_thumbnail_size', function() { return 'featured_rectangle'; } );
    }
    endforeach; wp_reset_postdata(); ?>
  </div>
  <?php
  endif;
}

add_action( 'genesis_loop', 'cc_loop_entry_attributes', 5 );
function cc_loop_entry_attributes() {
  add_filter( 'cc_entry_thumbnail_size', function() { return 'featured_square'; } );
}

// remove_action( 'genesis_loop', 'genesis_do_loop' );
// add_action( 'genesis_loop', 'child_do_custom_loop' );
 
// // function child_do_custom_loop() {
// //     global $paged;
// //     global $query_args; // grab the current wp_query() args
    
// //     $args = array(
// //         'category__not_in' => array($cat), // exclude posts from category i.d 30
// //     );
 
// // genesis_custom_loop( wp_parse_args($query_args, $args) );

// // }

// add_action( 'genesis_loop', 'cc_home_loop_newsletters' );
// function cc_home_loop_newsletters() {
//   global $wp_query;
//   if ( ( $category = get_field( 'newsletter_category', 'option' ) ) && $wp_query->get( 'paged' ) === 0 ):
//   $home_id = get_option( 'page_for_posts' );
//   $posts = get_posts( array(
//     'cat' => $category,
//     'supress_filters' => false,
//     'posts_per_page' => get_field( 'newsletters_post_count', $home_id ) ? intval( get_field( 'newsletters_post_count', $home_id ) ) : 4,
//   ) );
  ?>
  <!-- <article class="entry newsletters-entry color-dark">
    <div class="entry-thumbnail">
      <span class="entry-icon entry-icon-primary"><i class="fas fa-envelope"></i></span>
    </div>
    <header class="entry-header">
      <h2 class="entry-title">Recent Newsletters</h2>
    </header>
    <div class="recent-newsletters"><?php
      foreach ( $posts as $post ): $post_id = $post->ID; ?>
      <div class="recent-newsletter">
        <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
          <h4 class="title"><?php echo wp_kses_post( $post->post_title ); ?></h4>
          <p class="date"><?php echo get_the_date( get_option( 'date_format' ), $post->ID ); ?></p>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </article> -->
    <?php
  //endif;
//}

add_filter( 'genesis_attr_entry', 'cc_blog_attributes' );
function cc_blog_attributes( $attributes ) {
  global $wp_query;
  $color = $wp_query->current_post % 2 === 0 ? 'dark' : 'light';
  $attributes['class'] .= " color-$color";
  return $attributes;
}


genesis();
