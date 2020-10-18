<?php

add_action( 'wp_enqueue_scripts', 'cc_enqueue_single_story' );
function cc_enqueue_single_story() {
  $dir = get_stylesheet_directory_uri();
  wp_enqueue_script( 'lity', esc_url( $dir ) . '/assets/js/lity.min.js', null, null, true );
  wp_enqueue_script( 'lightgallery', esc_url( $dir ) . '/assets/js/lightgallery.min.js', null, null, true );
}

remove_action( 'genesis_before_footer', 'cc_before_footer_section', 99 );
add_action( 'genesis_before', 'cc_before_story' );
function cc_before_story() {
  remove_action( 'genesis_entry_header', 'cc_entry_thumbnail_ouput', 0 );
}

add_action( 'genesis_after_header', 'cc_single_story_after_header_hero' );
function cc_single_story_after_header_hero() {
  if ( $image = get_field( 'hero_image' ) ):
  $video = get_field( 'video' ); ?>
  <div class="story-hero">
  <?php if ( $video  ): ?>
  <div class="video-container"><?php echo $video ?></div>
  <?php endif; ?>
    <div class="content-container" style="background-image:url(<?php echo esc_url( wp_get_attachment_url( $image ) ); ?>)">
    <div class="wrap">
    <?php if ( $logo = get_field( 'lift_logo', 'option' ) ): ?>
      <div class="lift-logo">
        <img src="<?php echo esc_attr( $logo ); ?>">
      </div>
    <?php endif; ?>
      <div class="title-container">
        <h1 class="title"><?php echo wp_kses_post( get_the_title() ); ?></h1>
        <p class="info"><?php echo cc_get_story_info(); ?></p>
      </div>
      </div>
      <?php if ( $video ): ?>
      <div class="play-container">
        <a id="toggle-video">
          <i class="fas fa-play"></i>
        </a>
      </div>
      <?php endif; ?>
    </div>
  </div><?php
  endif;
}

add_action( 'genesis_after_header', 'cc_single_story_after_header_navigation' );
function cc_single_story_after_header_navigation() {
  ?>
  <div class="story-navigation">
    <div class="wrap">
      <div class="navigation-container">
        <a class="back-stories" href="<?php echo esc_url( get_post_type_archive_link( 'story' ) ); ?>">
          <i class="fas fa-long-arrow-alt-left"></i>
          <span>Back to <img src="<?php echo esc_url( get_field( 'lift_logo', 'option' ) ) ?>"> Stories</span>
        </a><?php
        if ( get_field( 'gallery' ) ): ?>
        <a class="to-gallery" href="#gallery">
          <i class="fas fa-camera"></i>
          <span>Photogallery</span>
        </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php
}

add_filter( 'genesis_attr_entry', 'cc_single_story_attributes_entry' );
function cc_single_story_attributes_entry( $attributes ) {
  $attributes['id'] = 'story';
  return $attributes;
}

add_action( 'genesis_entry_header', 'cc_single_story_entry_header_info' );
function cc_single_story_entry_header_info() {
  ?>
  <p class="info"><?php echo cc_get_story_info(); ?></p>
  <?php
}

add_action( 'genesis_before_footer', 'cc_single_story_before_footer' );
function cc_single_story_before_footer() {
  if ( $gallery = get_field( 'gallery' ) ):
  ?>
  <div id="gallery" class="story-gallery">
    <div class="wrap">
      <div class="heading-container">
        <h2 class="heading">Photo Gallery</h2>
      </div>
      <div class="gallery-container">
      <?php foreach ( $gallery as $image ): ?>
        <a class="image" href="<?php echo esc_url( wp_get_attachment_url( $image ) ); ?>">
          <img src="<?php echo esc_url( wp_get_attachment_image_src( $image, 'person' )[0] ); ?>">
        </a>
      <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php
  endif;
}

add_action( 'wp_footer', 'cc_wp_footer_story' );
function cc_wp_footer_story() {
  ?>
  <script>
  ;(function($) {
    $(document).ready(function() {
      if ($('.story-gallery').length > 0) {
        $('.story-gallery').lightGallery({
          selector: '.image',
        })
      }
    })
  })(jQuery);
  </script>
  <?php
}

genesis();
