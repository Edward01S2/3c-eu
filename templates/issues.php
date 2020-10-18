<?php
/**
 * Template Name: Issues
 */

add_action( 'genesis_loop', 'cc_issues' );
function cc_issues() {
  if ( have_rows( 'issues' ) ):
  ?>
  <div class="issues">
    <?php while ( have_rows( 'issues' ) ): the_row();
    $link = get_sub_field( 'link' );
    ?>
    <article class="issue-entry entry post-list-entry shadowed">
      <?php if ( $image = wp_get_attachment_image_src( get_sub_field( 'image' ), 'featured_rectangle_wide' ) ): ?>
      <div class="entry-thumbnail">
        <?php if ( $link ): ?><a href="<?php echo esc_url( $link ); ?>"><?php endif; ?>
        <img src="<?php echo esc_url( $image[0] ); ?>">
        <?php if ( $link ): ?></a><?php endif; ?>
      </div>
      <?php endif; ?>
      <header class="entry-header">
        <h2 class="entry-title"><?php echo wp_kses_post( get_sub_field( 'title' ) ); ?></h2>
      </header>
      <div class="entry-content"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></div>
      <?php if ( $link ): ?>
      <footer class="entry-footer">
        <a class="read-more" href="<?php echo esc_url( $link ); ?>"><?php echo __( 'Learn More', 'connectedcouncil' ); ?> <i class="fas fa-long-arrow-alt-right"></i></a>
      </footer>
      <?php endif; ?>
    </article>
    <?php endwhile; ?>
  </div>
  <?php
  endif;
}

genesis();