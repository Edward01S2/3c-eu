<?php

add_action( 'genesis_before', 'cc_before_single_person' );
function cc_before_single_person() {
  add_filter( 'cc_entry_thumbnail_size', function() { return 'person'; } );
  add_action( 'genesis_entry_header', function() {
    echo '<div class="entry-container">';
  }, 4 );

  add_action( 'genesis_entry_header', function() {
    if ( $position = get_field( 'position' ) ):
    ?>
    <h4 class="entry-info position"><?php echo esc_html( $position ); ?></h4>
    <?php
    endif;
  } );

  add_action( 'genesis_after_post', function() {
    echo '</div>';
  } );
}

genesis();
