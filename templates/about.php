<?php
/**
 * Template Name: About
 */

add_action( 'genesis_entry_content', 'cc_about_mission_statement' );
function cc_about_mission_statement() {
  if ( $mission_statement = get_field( 'mission_statement' ) ):
  ?>
  <div class="mission-statement shadowed">
    <?php cc_heading( get_field( 'mission_statement_heading' ) ); ?>
    <?php if ( $text = get_field( 'mission_statement' ) ): ?>
    <div class="text-container">
      <?php echo apply_filters( 'the_content', $text ); ?>
    </div>
    <?php endif; ?>
  </div>
  <?php
  endif;
}

add_action( 'genesis_before_footer', 'cc_about_actions' );
function cc_about_actions() {
  if ( have_rows( 'actions' ) ):
  ?>
  <section class="action-buttons-section">
    <div class="wrap">
      <div class="action-buttons children-thirds children-alternate">
        <?php while ( have_rows( 'actions' ) ): the_row(); ?>
        <div class="action-button shadowed">
          <div class="icon-container"><?php echo wp_kses_post( get_sub_field( 'icon' ) ); ?></div>
          <div class="title-container">
            <h3 class="title"><?php echo wp_kses_post( get_sub_field( 'title' ) ); ?></h3>
          </div>
          <div class="text-container">
            <?php echo wp_kses_post( get_sub_field( 'text' ) ); ?>
          </div>
          <?php cc_button( get_sub_field( 'button_text' ), get_sub_field( 'button_link' ) ); ?>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
  </section>
  <?php
  endif;
}

genesis();
