<?php
/**
 * Template Name: Contact
 */

add_filter( 'cc_show_page_featured_background', '__return_false' );
remove_action( 'genesis_before', 'cc_before_site_inner_background' );

add_action( 'genesis_before_content_sidebar_wrap', 'cc_image_background', 1 );

add_action( 'genesis_entry_content', 'cc_contact_form' );
function cc_contact_form() {
  if ( $form = get_field( 'contact_form' ) ):
  ?>
  <div class="contact-form">
    <?php cc_form( $form ); ?>
  </div>
  <?php
  endif;
}

genesis();
