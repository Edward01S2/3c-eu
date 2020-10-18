<?php
/**
 * Template Name: Join
 */

add_action( 'genesis_entry_content', 'cc_join_content' );
function cc_join_content() {
  if ( $form = get_field( 'join_form' ) ):
  ?>
  <div class="join-form-container">
    <?php cc_heading( get_field( 'join_heading' ) ); ?>
    <div class="join-form"><?php echo cc_form( $form ); ?></div>
  </div>
  <?php
  endif;
}

add_filter( 'genesis_attr_entry', 'cc_join_attributes_entry' );
function cc_join_attributes_entry( $attributes ) {
  $attributes['class'] .= ' white-content';
  return $attributes;
}

genesis();
