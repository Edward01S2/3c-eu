<?php
/**
 * Template Name: Member Benefits
 */

add_action( 'genesis_entry_footer', 'cc_benefits_join_button' );
function cc_benefits_join_button() {
  cc_button( get_field( 'join_button_text' ), get_field( 'join_button_url', 'option' ) );
}

add_action( 'genesis_before_footer', 'cc_benefits' );
function cc_benefits() {
  if ( have_rows( 'benefits' ) ):
  ?>
  <section class="action-buttons-section">
    <div class="wrap">
      <div class="action-buttons children-thirds children-alternate">
        <?php while ( have_rows( 'benefits' ) ): the_row(); ?>
        <div class="action-button shadowed">
          <div class="icon-container"><?php echo wp_kses_post( get_sub_field( 'icon' ) ); ?></div>
          <div class="title-container">
            <h3 class="title"><?php echo wp_kses_post( get_sub_field( 'title' ) ); ?></h3>
          </div>
          <div class="text-container">
            <?php echo wp_kses_post( get_sub_field( 'text' ) ); ?>
          </div>
          <?php
          $link = get_sub_field( 'external_link' ) ? get_sub_field( 'external_link') : get_sub_field( 'link' );
          if ( $link ): ?>
          <div class="link-container">
            <a class="read-more" href="<?php echo esc_url( $link ); ?>"><?php echo __( 'Learn More', 'connectedcouncil' ); ?> <i class="fas fa-long-arrow-alt-right"></i></a>
          </div>
          <?php endif; ?>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
  </section>
  <?php
  endif;
}

genesis();
