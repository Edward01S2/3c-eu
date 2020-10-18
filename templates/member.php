<?php
/**
 * Template Name: Member
 */
remove_action ('genesis_loop', 'genesis_do_loop'); // Remove the standard loop
add_action( 'genesis_loop', 'custom_do_loop' ); // Add custom loop

function custom_do_loop() {
  echo '<div class="page hentry entry">';
	echo '<div class="entry-content">' . get_the_content() ;

  ?>
  <div class="member-box py-12">
    <div id="member-feed">
    <?php
      $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
      $args = array(
        'post_type' => 'member',
        'paged' => $paged,
        'posts_per_page' => 500,
        'post_status' => 'publish',
        'order' => 'ASC'
        
        // 'orderby' => 'date',
        // 'order' => 'DESC',
      );

      $wp_query = new WP_Query( $args );      
    ?>
    <?php if ( $wp_query->have_posts() ): ?>
    <?php while ( $wp_query->have_posts() ): $wp_query->the_post(); ?>
      <?php if(get_field('website')) :?>
      <?php
          $url = get_field('website');
          if($url) {
            if (substr($url, 0, 7) !== 'http://' && substr($url, 0, 8) !== 'https://') {
              $url = 'http://' . $url;
            }
          }
      ?>
      <a target="_blank" href="<?php echo $url ?>">
        <div class="flex items-center w-full">
          <div class="member-item"><?php the_title() ?></div>
          <svg class="member-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
        </div>
      </a>
      <?php else: ?>
        <div class="member-text font-bold">
          <div class="member-item"><?php the_title() ?></div>
        </div> 
      <?php endif; ?>
    
      <?php wp_reset_postdata(); endwhile; ?>
      <?php else: ?>
      <div class="entry no-reports">No Members Found</div>
      <?php endif; ?>

      <div class="pagination-load-more ajax-load-container flex flex-col w-full items-center pt-12">
        <div class="ajax-loading"></div>
        <button class="ajax-load" data-page="<?php echo $paged ?>">Load More</button>
      </div>

    </div>
  </div>
  <?php

}


genesis();
