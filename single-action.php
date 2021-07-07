<?php

add_action( 'wp_enqueue_scripts', 'cc_action_enqueue_state_styles' );
function cc_action_enqueue_state_styles() {
  if ( get_field( 'action_icon' ) ) {
    wp_enqueue_style( 'states', get_stylesheet_directory_uri() . '/assets/css/states.min.css' );
  }
}

remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_loop', 'cc_action_entry' );
function cc_action_entry() {
  genesis_markup( array(
    'open'    => '<article %s>',
    'context' => 'entry',
  ) );
		echo sprintf( '<header %s>', genesis_attr( 'entry-header' ) );
      if ( $icon = get_field( 'action_location' ) ):
      echo sprintf( '<span class="entry-icon state-icon stateface stateface-replace stateface-%s"></span>', strtolower( $icon['value'] ) );
      endif;
      echo sprintf( '<h1 class="entry-title">%s</h1>', get_the_title() );
    echo sprintf( '</header>' );
  genesis_markup( array(
    'close'   => '</article>',
    'context' => 'entry',
  ) );
}

add_action( 'genesis_loop', 'cc_action_loop' );
function cc_action_loop() {
  ?>
  <div class="action-container">
    <div class="action-content-container shadowed">
      <?php
      cc_entry_thumbnail_ouput(); ?>
      <div class="entry-content xl:p-16"><?php
        echo apply_filters( 'the_content', get_post_field( 'post_content', get_the_id() ) );
        if ( $cta = get_field( 'action_cta' ) ): ?>
        <h5 class="cta"><?php
          echo $cta ?>
          <i class="fas fa-long-arrow-alt-right"></i>
        </h5>
        <?php endif; 
          if( $form = get_field('form')) : ?>
          <div>
          <div class="form-content">
          <?php
            cc_form( $form ); 
          ?>
          </div>
          </div>
        <?php endif;
        ?>
      </div>
    </div>
    <?php if ( $embed = get_field( 'form_embed_code' ) ): ?>
    <div class="action-form-container shadowed"><?php
      echo $embed; ?>
    </div>
    <?php endif; ?>
  </div>
  <?php
}

add_action( 'genesis_before_footer', 'cc_action_loop_related_posts' );
function cc_action_loop_related_posts() {
  global $post;
  add_filter( 'cc_link_entry_thumbnail', '__return_true' );
  add_filter( 'cc_entry_thumbnail_icon', '__return_true' );
  add_filter( 'cc_entry_thumbnail_size', function() { return 'featured_square'; } );
  add_filter( 'genesis_attr_entry', 'cc_attributes_entry' );
  add_filter( 'genesis_entry_title_wrap', function() { return 'h2'; } );
  add_filter( 'genesis_post_title_text', function( $title ) {
    return sprintf( '<a href="%s" rel="bookmark">%s</a>', get_permalink(), $title );
  } );
  add_filter( 'cc_entry_footer_show_read_more', '__return_true' );

  if ( $related_posts = get_field( 'related_posts' ) ):
  ?>
  <section class="recent-posts-section">
    <div class="wrap">
      <?php cc_heading( get_field( 'related_posts_heading' ) ); ?>
      <div class="posts-container children-thirds"><?php
        foreach ( $related_posts as $post ): setup_postdata( $post );
        cc_entry_markup();
        endforeach; wp_reset_postdata(); ?>
      </div>
    </div>
  </section>
  <?php
  endif;
}

genesis();
