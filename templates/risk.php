<?php
/**
 * Template Name: Risk
 */

remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action('genesis_before_content', 'es_virus_content');
function es_virus_content() {
  ?>
  <section>
    <div class="bg-white post-content text-c-blue-200">
      <?php 
      $page_id = get_queried_object_id();
      $post_object = get_post( $page_id );
      echo apply_filters('the_content', $post_object->post_content); ?>
    </div>
  </section>
  <?php
}

genesis();
