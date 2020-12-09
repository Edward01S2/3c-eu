<?php
/**
 * Template Name: Reports
 */

remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_before_content', 'cc_report_archive_heading', 5 );
function cc_report_archive_heading() {
  ?>
  <div class="archive-description report-archive-description">
    <h1 class="archive-title"><?php echo wp_kses_post( get_the_title() ); ?></h1>
    <?php if ( $content = get_post_field( 'post_content', get_the_id() ) ): ?>
    <div class="archive-text"><?php echo apply_filters( 'the_content', $content ); ?></div>
    <?php endif; ?>
  </div>
  <?php
}

add_action( 'genesis_before_content', 'cc_featured_reports' );
function cc_featured_reports() {
  global $post;
  global $wp_filter;

  add_filter( 'cc_show_entry_thumbnail', '__return_true' );
  add_filter( 'genesis_attr_entry', 'cc_reports_attributes_entry' );
  add_filter( 'cc_entry_footer_show_read_more', '__return_false' );
  add_action( 'genesis_entry_footer', 'cc_report_entry_footer' );

  add_filter( 'cc_link_entry_thumbnail', '__return_true' );
  add_filter( 'cc_entry_thumbnail_size', function() { return 'featured_rectangle'; } );
  add_filter( 'genesis_entry_title_wrap', function() { return 'h2'; } );

  add_filter( 'genesis_post_title_text', function( $title ) {
    return sprintf( '<a href="%s" rel="bookmark" target="_blank">%s</a>', get_field('external_link'), $title );
  } );

  add_action( 'genesis_entry_header', function() {
    if ( $source = get_field( 'source' ) ):
    ?>
    <h4 class="entry-info">(<?php echo esc_html( $source ); ?>)</h4>
    <?php
    endif;
  } );

  if ( have_rows( 'featured_reports' ) ):
  ?>
  <div class="featured-reports report-count-<?php echo esc_attr( count( get_field( 'featured_reports' ) ) ); ?>">
    <?php cc_heading( get_field( 'featured_reports_heading' ) ); ?>
    <div class="reports-container">
      <?php
      while ( have_rows( 'featured_reports' ) ): the_row();
      $post = get_sub_field( 'report' );
      setup_postdata( $post );
      if ( $image = get_sub_field( 'image' ) ) {
        add_filter( 'wp_get_attachment_image_src', function( $image ) {
          $image[0] = get_sub_field( 'image' );
          return $image;
        }, 10, 4);
      }
      if ( get_sub_field( 'title' ) ) {
        add_filter( 'the_title', function() {
          return get_sub_field( 'title' );
        } );
      }
      if ( $text = get_sub_field( 'text' ) ) {
        add_filter( 'get_the_excerpt', function() {
          return get_sub_field( 'text' );
        } );
      }
      cc_entry_markup();
      wp_reset_postdata();
      unset( $wp_filter['wp_get_attachment_image_src'] );
      unset( $wp_filter['the_title'] );
      unset( $wp_filter['get_the_excerpt'] );
      endwhile; ?>
    </div>
  </div>
  <?php
  endif;
}

add_action( 'genesis_before_content', 'cc_report_search' );
function cc_report_search() {
  $search = isset( $_GET['rs'] ) ? sanitize_text_field( $_GET['rs'] ) : '';
  ?>
  <form class="reports-search" action="<?php echo esc_url( get_permalink() ); ?>">
    <span class="search-label">Search Reports:</span>
    <input class="search-input" type="text" name="rs" value="<?php echo $search; ?>">
    <button class="button plain small">Search <i class="fas fa-search"></i></button>
    <input type="submit" style="display:none">
  </form>
  <?php
}

add_action( 'genesis_loop', 'cc_reports' );
function cc_reports() {
  global $wp_query;
  global $old_query;
  $args = array(
    'post_type' => 'report',
    'paged' => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
    // 'orderby' => 'date',
    // 'order' => 'DESC',
  );
  $search = isset( $_GET['rs'] ) ? sanitize_text_field( $_GET['rs'] ) : '';
  if ( !empty( $_GET['rs'] ) ) {
    $args['s'] = $search;
    $args['posts_per_page'] = -1;
  }

  $reports_query = new WP_Query( $args );
  if ( $reports_query->have_posts() || isset( $_GET['rs'] ) ):
  $old_query = $wp_query;
  $wp_query = NULL;
  $wp_query = $reports_query;
  ?>


  <?php if ( $wp_query->have_posts() ): ?>
    <?php while ( $wp_query->have_posts() ): $wp_query->the_post();
    cc_entry_markup();
    ?>
    <?php wp_reset_postdata(); endwhile; ?>
    <?php else: ?>
    <div class="entry no-reports">No Reports Found</div>
    <?php endif; ?>

  <?php
  endif;
}

add_action( 'genesis_after_content', function() {
  global $wp_query;
  global $old_query;
  $wp_query = NULL;
  $wp_query = $old_query;
  wp_reset_query();
} );

add_filter('next_posts_link_attributes', function() {
  return 'class="pagination-next"';
} );

function cc_report_entry_footer() {
  ?>
  <a class="button" href="<?php echo esc_url( get_field('external_link') ); ?>" target="_blank">
    <span>View Report</span>
    <?php if ( get_field( 'report_type' ) === 'External Link' ): ?>
    <i class="fas fa-external-link-alt"></i>
    <?php else: ?>
    <i class="fas fa-download"></i>
    <?php endif;?>
  </a>
  <?php
}

add_filter( 'genesis_attr_content', 'cc_reports_attributes_content' );
function cc_reports_attributes_content( $attributes ) {
  $attributes['class'] .= ' reports-container children-thirds';
  return $attributes;
}

function cc_reports_attributes_entry( $attributes ) {
  $attributes['class'] .= ' info-entry shadowed';
  return $attributes;
}

genesis();
