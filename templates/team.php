<?php
/**
 * Template Name: Team
 */

remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

add_action( 'genesis_entry_content', 'cc_team_section_links' );
function cc_team_section_links() {
  if ( have_rows( 'team_sections' ) ):
  ?>
  <ul class="team-section-links">
    <?php while ( have_rows( 'team_sections' ) ): the_row();
    $title = get_sub_field( 'title' );
    ?>
    <li class="team-section-link">
      <a class="button small" href="#<?php echo esc_attr( sanitize_title( $title ) ); ?>"><?php echo wp_kses_post( $title ); ?></a>
    </li>
    <?php endwhile; ?>
  </ul>
  <?php
  endif;
}

add_action( 'genesis_before_footer', 'cc_team_sections', 1 );
function cc_team_sections() {
  global $post;

  add_filter( 'cc_show_entry_thumbnail', '__return_true' );
  add_filter( 'cc_entry_thumbnail_size', function() { return 'person'; } );
  add_filter( 'cc_entry_thumbnail_icon', '__return_false' );
  add_filter( 'cc_entry_footer_show_read_more', '__return_true' );
  add_filter( 'cc_entry_footer_show_date', '__return_false' );
  add_filter( 'genesis_attr_entry', 'cc_team_attributes' );
  add_filter( 'cc_link_entry_thumbnail', '__return_true' );
  add_filter( 'genesis_entry_title_wrap', function() { return 'h2'; } );

  add_filter( 'genesis_post_title_text', function( $title ) {
    return sprintf( '<a href="%s" rel="bookmark">%s</a>', get_permalink(), $title );
  } );

  while ( have_rows( 'team_sections' ) ): the_row();
  $title = get_sub_field( 'title' );
  ?>
  <section id="<?php echo esc_attr( sanitize_title( $title ) ); ?>" class="team-section">
    <div class="wrap">
      <?php cc_heading( $title ); ?>
      <?php if ( $content = get_sub_field( 'content' ) ): ?>
      <div class="content-container entry-content"><?php echo apply_filters( 'the_content', $content ); ?></div>
      <?php endif;
      if ( $people = get_sub_field( 'people' ) ): ?>
      <div class="people-container children-thirds"><?php
      foreach ( $people as $post ): setup_postdata( $post );
        cc_entry_markup();
      endforeach; wp_reset_postdata(); ?>
      </div>
      <?php endif; ?>
    </div>
  </section>
  <?php
  endwhile;
}

function cc_team_attributes( $attributes ) {
  $attributes['class'] .= ' info-entry shadowed';
  return $attributes;
}

add_action( 'genesis_entry_header', 'cc_entry_header_person_position' );
function cc_entry_header_person_position() {
  if ( $position = get_field( 'position' ) ):
  ?>
  <h4 class="entry-info position"><?php echo esc_html( $position ); ?></h4>
  <?php
  endif;
}


genesis();
