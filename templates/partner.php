<?php
/**
 * Template Name: Partner
 */

add_action( 'genesis_before_footer', 'cc_partner_reports' );
function cc_partner_reports() {
  global $post;

  add_filter( 'genesis_attr_entry', function( $attributes ) {
    $attributes['class'] .= ' info-entry shadowed';
    return $attributes;
  } );
  add_filter( 'cc_entry_footer_show_read_more', '__return_false' );
  add_action( 'genesis_entry_footer', function() {
    ?>
    <a class="button" href="<?php echo esc_url( get_permalink() ); ?>">
      <span>View Report</span>
      <?php if ( get_field( 'report_type' ) === 'External Link' ): ?>
      <i class="fas fa-external-link-alt"></i>
      <?php else: ?>
      <i class="fas fa-download"></i>
      <?php endif;?>
    </a>
    <?php
  } );

  add_filter( 'cc_show_entry_thumbnail', '__return_true' );
  add_filter( 'cc_link_entry_thumbnail', '__return_true' );
  add_filter( 'genesis_entry_title_wrap', function() { return 'h2'; } );

  add_filter( 'genesis_post_title_text', function( $title ) {
    return sprintf( '<a href="%s" rel="bookmark">%s</a>', get_permalink(), $title );
  } );

  if ( $featured_reports = get_field( 'featured_reports' ) ):
  ?>
  <section class="featured-reports report-count-<?php echo esc_attr( count( $featured_reports ) ); ?>">
    <div class="wrap">
      <?php cc_heading( get_field( 'featured_reports_heading' ) ); ?>
      <div class="reports-container">
        <?php
        foreach ( $featured_reports as $post ):
        setup_postdata( $post );
        cc_entry_markup();
        endforeach;
        wp_reset_postdata(); ?>
      </div>
    </div>
  </section>
  <?php
  endif;
}

genesis();
