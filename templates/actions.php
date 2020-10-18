<?php
/**
 * Template Name: Actions
 */

add_action( 'genesis_before_footer', 'cc_actions' );
function cc_actions() {
  global $post;

  add_filter( 'cc_show_entry_thumbnail', '__return_true' );
  add_filter( 'cc_entry_thumbnail_icon', '__return_false' );
  add_filter( 'cc_entry_thumbnail_size', function() { return 'featured_rectangle'; } );
  add_filter( 'cc_entry_footer_show_date', '__return_false' );
  add_filter( 'genesis_attr_entry', 'cc_action_archive_attributes_entry' );
  add_filter( 'cc_link_entry_thumbnail', '__return_true' );
  add_filter( 'genesis_entry_title_wrap', function() { return 'h2'; } );

  add_filter( 'genesis_post_title_text', function( $title ) {
    return sprintf( '<a href="%s" rel="bookmark">%s</a>', get_permalink(), $title );
  } );
  add_action( 'genesis_entry_footer', 'cc_actions_entry_footer' );
  add_action( 'cc_entry_thumbnail_extra', 'cc_actions_location' );

  if ( get_field('headline')) : ?>
    <section class="mb-12 md:mb-20">
      <div class="wrap">
        <div class="bg-white">
          <div class="flex flex-col lg:flex-row lg:items-center lg:relative">
            <div class="lg:w-1/2 lg:self-stretch"> 
              <img class="md:object-center md:object-cover md:h-96 md:w-full lg:h-full" src="<?php echo get_field('image')['url'] ?>" alt="">
            </div>
            <div class="text-c-blue-400 bg-white p-12 lg:mt-0 lg:w-1/2 xl:p-24">
              <h2 class=""><?php echo get_field('headline') ?></h2>
              <p class=""><?php echo get_field('content') ?></p>
              <a class="button" href="<?php echo get_field('link')['url'] ?>"><?php echo get_field('link')['title'] ?></a>
            </div>
          </div>
        </div>
      </div>
    </section>
  <?php 
  endif;


  if ( get_field( 'actions' ) || get_field( 'show_next_action_cta' ) ):
  ?>
  <section class="actions-section">
    <div class="wrap">
      <div class="actions-container children-thirds">
        <?php $actions = get_field( 'actions' ) ? get_field( 'actions' ) : array();
        foreach ( $actions as $post ): setup_postdata( $post );
        cc_entry_markup();
        endforeach; wp_reset_postdata();
        cc_next_action();
        ?>
      </div>
    </div>
  </section>
  <?php
  endif;
}

function cc_action_archive_attributes_entry( $attributes ) {
  $attributes['class'] .= ' post-list-entry post-list-white-entry shadowed';
  return $attributes;
}

function cc_actions_location() {
  if ( $location = get_field( 'action_location' ) ):
  ?>
  <span class="action-location"><?php echo esc_html( $location['label'] ); ?></span>
  <?php
  endif;
}

function cc_actions_entry_footer() {
  if($link = get_field('connected_page')) {
    $link = $link;
  }
  else {
    $link = get_permalink();
  }
  echo cc_button( 'Take Action Now', $link );
}

function cc_next_action() {
  if ( get_field( 'show_next_action_cta' ) ):
  $rem = get_field( 'actions' ) > 0 ? count( get_field( 'actions' ) ) % 3 : 0;
  ?>
  <div class="next-action has-border-container rem-<?php echo esc_attr( $rem ); ?>">
    <div class="content-container border-container">
      <?php cc_heading( get_field( 'next_action_title' ) ); ?>
      <?php if ( $content = get_field( 'next_action_text' ) ): ?>
      <div class="text-container"><?php echo apply_filters( 'the_content', $content ); ?></div>
      <?php endif; ?>
        <div class="button-container">
          <?php if ( ( $button_text = get_field( 'footer_join_button_text', 'option' ) ) && ( $button_link = get_field( 'footer_join_button_link', 'option' ) ) ): ?>
          <a class="button join-button" href="<?php echo esc_url( $button_link ); ?>"><?php echo esc_html( $button_text ); ?></a>
          <?php endif;
          if ( ( $button_text = get_field( 'next_action_button_text' ) ) && ( $button_link = get_field( 'next_action_button_link' ) ) ): ?>
          <a class="button learn-more-button" href="<?php echo esc_url( $button_link ); ?>"><?php echo esc_html( $button_text ); ?></a>
          <?php endif; ?>
      </div>
    </div>
  </div>
  <?php
  endif;
}

genesis();
