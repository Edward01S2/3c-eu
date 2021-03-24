<?php

add_action('genesis_after_header', 'es_front_page_banner');
function es_front_page_banner() {
  if( get_field( 'banner_text') && get_field('banner_link')) : ?>
    <section class="banner-section bg-c-blue-400">
      <div class="wrap">
        <div class="flex items-center justify-between pt-1 md:py-4 md:justify-center md:pb-3 xl:pb-3">
          <div class="flex items-center text-xl font-semibold text-center text-white md:mb-0 md:pr-8 lg:text-2xl lg:mt-1 xl:text-3xl">
          <?php
          if( $banner_icon = get_field('banner_icon')) {
            echo sprintf('<span class="text-3xl text-white md:text-4xl md:mr-4 lg:text-4xl xl:mr-8"><i class="%s"></i></span>', $banner_icon);
          } ?>
          <div> <?php echo get_field('banner_text'); ?></div>
          </div>
          <div clss="mt-4">
            <?php $banner_link = get_field('banner_link'); ?>
            <a class="hidden px-6 py-3 text-xl font-bold text-white uppercase bg-c-orange-100 shadow-button hover:bg-c-blue-300 hover:text-white md:block lg:text-2xl" href="<?php echo $banner_link['url'] ?>"><?php echo $banner_link['title'] ?></a>
            <a class="md:hidden" href="<?php echo $banner_link['url'] ?>">
              <svg fill="currentColor" width="1" height="1" viewBox="0 0 20 20" class="w-8 h-8 text-c-orange-100 hover:text-white md:hidden"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </a>
          </div>
        </div>
      </div>
    </section>
  <?php
  endif;
}

add_action( 'genesis_after_header', 'cc_front_page_video' );
function cc_front_page_video() {
  if ( $video_url = get_field( 'video_url' ) ):
  ?>
  <!-- <section class="video-section">
    <video id="video" autoplay playsinline muted loop
      data-desktop-poster="<?php echo esc_url( get_field( 'video_poster' ) ); ?>.webp"
      data-desktop-src="<?php echo esc_url( $video_url ); ?>"<?php
      if ( $mobile_video_poster = get_field( 'mobile_video_poster' ) ) {
        echo sprintf( 'data-mobile-poster="%s.webp"', $mobile_video_poster );
      }
      if ( $mobile_video_url = get_field( 'mobile_video_url' ) ) {
        echo sprintf( 'data-mobile-src="%s"', $mobile_video_url );
      }
      ?>>
    </video>
  </section> -->
  <section class="relative">
      <img class="absolute z-10 object-cover object-center w-full h-full" src="<?php echo get_field('hero_bg')['url'] ?>" alt="">
      <div class="absolute z-20 w-full h-full bg-black dark-overlay bg-opacity-35"></div>
      <div class="relative z-30 py-24 wrap md:py-32 lg:py-48 xl:py-72">
        <h1 class="text-5xl font-bold text-white md:text-6xl lg:leading-tight xl:leading-snug xl:text-7xl"><?php echo get_field('hero_text') ?></h1>
        <a class="inline-flex items-center px-10 py-4 text-2xl font-bold transition duration-150 bg-white rounded-md shadow-md text-c-blue-200 hover:bg-c-orange-100 hover:text-white xl:text-3xl" href="<?php echo get_field('hero_link')['url'] ?>" target="_blank">
        <svg class="w-8 h-8 mr-2 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
        <?php echo get_field('hero_link')['title'] ?>
        </a>
      </div>
  </section>
  <?php
  endif;
}

add_action( 'genesis_before', 'cc_before_front_page' );
function cc_before_front_page() {
  add_filter( 'cc_entry_footer_show_date', '__return_false' );
}

remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_loop', 'cc_front_page_featured' );
function cc_front_page_featured() {
  ob_start();
  do_action( 'front_page_featured_row_1' );
  if ( $content = ob_get_clean() ):
  ?>
  <section class="home-section home-featured-section home-featured-1">
    <?php echo $content; ?>
  </section><?php
  endif;

  do_action( 'front_page_community' );

  ob_start();
  do_action( 'front_page_featured_row_2' );
  if ( $content = ob_get_clean() ):
  ?>
  <section class="home-section home-featured-section home-featured-2">
    <?php echo $content; ?>
  </section>
  <?php
  endif;
}

add_action('front_page_community', 'es_community');
function es_community() {
  ?>
  <section class="relative mb-16 shadowed bg-c-blue-100">
    <div class="relative z-20 p-12 text-white">
      <h2><?php echo get_field('com_title'); ?></h2>
      <div><?php echo get_field('com_content'); ?></div>
      <div class="grid grid-cols-1 py-8 gap-y-8 md:gap-y-12 lg:grid-cols-2 lg:gap-16 lg:pt-8 lg:pb-0">
      <?php
        if ( have_rows( 'com_items' ) ):
          while( have_rows('com_items')) : the_row(); ?>
          <div class="flex flex-col items-center justify-center md:flex-row md:justify-start md:space-x-12">
            <img class="mb-6 md:w-40 md:h-auto xl:w-48" src="<?php echo get_sub_field('image')['url'] ?>" alt="">
            <div class="text-center md:text-left"><?php echo get_sub_field('content') ?></div>
          </div>
        <?php endwhile;
        endif;
      ?>
      </div>
      <?php if(get_field('citation')) : ?>
        <div class="pt-4 text-right">
          <a class="italic text-white hover:text-c-orange-100" href="<?php echo get_field('citation')['url'] ?>" target="_blank"><?php echo get_field('citation')['title'] ?></a>
        </div>
      <?php endif; ?>
    </div>
    <img class="absolute inset-0 z-0 object-center mx-auto md:my-auto" src="<?php echo get_field('com_bg')['url'] ?>" alt="">
  </section>
  <?php
}

add_action( 'front_page_featured_row_1', 'cc_front_page_cta' );
function cc_front_page_cta() {
  if ( $title = get_field( 'cta_title' ) ):
  ?>
  <article class="entry cta-entry post-list-entry shadowed"><?php
    if ( $image = wp_get_attachment_image_src( get_field( 'cta_image' ), 'full' ) ): ?>
    <div class="entry-thumbnail">
      <img src="<?php echo esc_url( $image[0] ); ?>">
    </div><?php
    endif; ?>
    <header class="entry-header">
      <h2 class="entry-title"><?php echo wp_kses_post( $title ); ?></h2>
    </header><?php
    if ( $content = get_field( 'cta_content' ) ): ?>
    <div class="entry-content">
      <?php echo apply_filters( 'the_content', $content ); ?>
    </div><?php
    endif; ?>
    <footer class="entry-footer"><?php
    if ( ( $join_button_text = get_field( 'join_button_text', 'option' ) ) && ( $join_button_url = get_field( 'join_button_url', 'option' ) ) ): ?>
    <a class="join button small" href="<?php echo esc_url( $join_button_url ); ?>"><?php echo wp_kses_post( $join_button_text ); ?></a><?php
    endif;
    if ( ( $text = get_field( 'cta_button_text' ) ) && ( $link = get_field( 'cta_button_link' ) ) ): ?>
    <a class="button small white" href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $text ); ?></a><?php
    endif; ?>
    </footer>
  </article>
  <?php
  endif;
}

add_action( 'front_page_featured_row_1', 'cc_front_page_row_1_pinned_post' );
function cc_front_page_row_1_pinned_post() {
  global $post;
  if ( $post = get_field( 'pinned_post_1' ) ):
  add_filter( 'cc_entry_thumbnail_size', function() { return 'featured_rectangle'; } );
  cc_query_from_posts( $post, get_post_type( $post ) );
  endif;
  wp_reset_postdata();
}

add_action( 'front_page_featured_row_2', 'cc_front_page_row_2_pinned_post' );
function cc_front_page_row_2_pinned_post() {
  global $post;
  if ( $post = get_field( 'pinned_post_2' ) ):
  add_filter( 'cc_entry_thumbnail_size', function() { return 'featured_rectangle'; } );
  cc_query_from_posts( $post, get_post_type( $post ) );
  endif;
  wp_reset_postdata();
}

add_action( 'front_page_featured_row_2', 'cc_front_page_row_2_form' );
function cc_front_page_row_2_form() {
  if ( $form = get_field( 'form' ) ):
  ?>
  <div class="signup-form shadowed"><?php
    if ( $image = get_field( 'form_image' ) ): ?>
    <div class="form-image">
      <img src="<?php echo esc_url( $image ); ?>">
    </div><?php
    endif; ?>
    <div class="form-content"><?php
      cc_heading( get_field( 'form_heading' ) );
      // var_dump($form);
      //gravity_form( 2, false, false, false, null, true, -1 );
      cc_form( $form ); ?>
    </div>
  </div>
  <?php
  endif;
}

add_action( 'genesis_loop', 'cc_front_page_recent_posts' );
function cc_front_page_recent_posts() {
  add_filter( 'cc_entry_thumbnail_size', function() { return 'featured_square'; } );

  $posts_per_page = get_field( 'recent_posts_count' ) ? get_field( 'recent_posts_count' ) : 3;
  $categories = get_field( 'recent_posts_categories' ) ? get_field( 'recent_posts_categories' ) : [];
  $args = array(
    'posts_per_page' => $posts_per_page,
    'category__in' => $categories,
    'category__not_in' => get_field( 'newsletter_category', 'option' ) ? get_field( 'newsletter_category', 'option' ) : [],
  );
  global $wp_query;
  $wp_query = new WP_Query( $args );
  if ( $wp_query->have_posts() ):
  ?>
  <section class="home-section recent-posts-section">
    <div class="posts-container children-thirds"><?php
      while ( $wp_query->have_posts() ): $wp_query->the_post();
      cc_entry_markup();
      endwhile; wp_reset_postdata(); wp_reset_query(); ?>
    </div>
  </section>
  <?php
  endif;
}
