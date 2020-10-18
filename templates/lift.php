<?php
/**
 * Template Name: LIFT
 */

add_action( 'wp_enqueue_scripts', 'cc_enqueue_single_story' );
function cc_enqueue_single_story() {
  $dir = get_stylesheet_directory_uri();
  wp_enqueue_script( 'lity', esc_url( $dir ) . '/assets/js/lity.min.js', null, null, true );
  wp_enqueue_style( 'slick-carousel', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css' );
  wp_enqueue_script( 'sick-carousel', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js', array( 'jquery' ), false, true );
  // wp_enqueue_style( 'states', $dir . '/assets/css/states.min.css' );
  wp_enqueue_script( 'youtube', 'https://www.youtube.com/iframe_api', array(), true );
}

add_action( 'genesis_after_header', 'cc_single_story_after_header_hero' );
function cc_single_story_after_header_hero() {
  if ( $image = get_field( 'hero_image' ) ):
  $video = get_field( 'video' ); ?>
  <div class="story-hero">
    <?php if ( $video ): ?>
    <div class="video-container"><?php echo $video ?></div>
    <?php endif; ?>
    <div class="content-container" style="background-image:url(<?php echo esc_url( wp_get_attachment_url( $image ) ); ?>)">
    <?php if ( $logo = get_field( 'lift_logo', 'option' ) ): ?>
      <div class="lift-logo">
        <img src="<?php echo esc_attr( $logo ); ?>">
      </div>
    <?php endif; ?>
      <div class="title-container">
        <h1 class="title"><?php echo wp_kses_post( get_field( 'hero_title' ) ); ?></h1>
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

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'cc_lift_about_content' );
function cc_lift_about_content() {
  ?>
  <div class="lift-about"><?php
    if ( $heading = get_field( 'about_heading' ) ): ?>
    <div class="heading-container">
      <h2 class="heading"><?php echo wp_kses_post( $heading ); ?></h2>
    </div>
    <?php endif;
    if ( $content = get_field( 'about_content' ) ): ?>
    <div class="content-container">
      <?php echo apply_filters( 'the_content', $content ); ?>
    </div><?php
    endif; ?>
  </div>
  <?php
}

add_action( 'genesis_before_footer', 'cc_lift_before_footer_episodes' );
function cc_lift_before_footer_episodes() {
  global $post;
  if ( $episodes = get_field( 'episodes' ) ):
  ?>
  <div class="episodes">
    <div class="wrap"><?php
      if ( $heading = get_field( 'episodes_heading' ) ): ?>
      <div class="heading-container">
        <h2 class="heading"><?php echo wp_kses_post( $heading ); ?></h2>
      </div><?php
      endif; ?>
      <div class="episodes-container"><?php
      $count = 0;
      foreach ( $episodes as $post ): setup_postdata( $post ); $count += 1; ?>
        <div class="episode">
          <div class="flex-container">
            <div class="left">
              <div class="video" style="background-image:url(<?php echo esc_url( wp_get_attachment_url( get_field( 'hero_image' ) ) ); ?>)"><?php
                if ( $video = get_field( 'video' ) ): ?>
                <div class="play-container">
                  <a href="<?php echo esc_url( get_field( 'video' ) ); ?>" data-lity>
                    <i class="fas fa-play"></i>
                  </a>
                </div><?php
                endif; ?>
              </div>
            </div>
            <div class="right">
              <div class="content-container">
                <p class="episode-number">LIFT Episode <?php echo $count; ?></p>
                <h3 class="title"><?php echo wp_kses_post( get_the_title() ); ?></h3>
                <h4 class="info"><?php echo cc_get_story_info() ?></h4>
                <div class="text-container">
                  <?php echo get_the_excerpt(); ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php
      endforeach; wp_reset_postdata(); ?>
      </div>
    </div>
  </div>
  <?php
  endif;
}

add_action( 'wp_footer', 'cc_lift_footer' );
function cc_lift_footer() {
  ?>
  <script>
  ;(function($) {
    $(document).ready(function() {
      $('.episodes-container').slick({
        dots: true,
        prevArrow: '<button type="button" class="slick-arrow slick-prev"><i class="fas fa-angle-left"></i></button>',
        nextArrow: '<button type="button" class="slick-arrow slick-next"><i class="fas fa-angle-right"></i></button>',
      })
    })
  })(jQuery);
  </script>
  <?php
}

add_action( 'genesis_before_footer', 'cc_lift_before_footer_featured' );
function cc_lift_before_footer_featured() {
  if ( $posts = get_field( 'featured' ) ):
  ?>
  <div class="featured-stories">
    <div class="wrap"><?php
    cc_archive_story_loop( array_slice( $posts, 0, 6 ) ); ?>
    <div class="read-more" style="background-image:url(<?php echo esc_url( get_field( 'featured_read_more_background' ) ); ?>);">
      <a href="<?php echo esc_url( get_post_type_archive_link( 'story' ) ); ?>">
        <span>Read More Stories</span>
        <i class="fas fa-long-arrow-alt-right"></i>
      </a>
    </div>
    </div>
  </div>
  <?php
  endif;
}

add_action( 'genesis_before_footer', 'cc_lift_before_footer_stories_state' );
function cc_lift_before_footer_stories_state() {
  $states = array();
  $posts = get_posts( array( 'post_type' => 'story', 'posts_per_page' => -1 ) );
  foreach ( $posts as $post ) {
    $state = get_field( 'state', $post->ID );
    $states[$state['value']] = $state['label'];
  }
  ksort( $states );
  if ( count( $states ) > 0 ):
  ?>
  <div class="story-states">
    <div class="wrap">
      <?php cc_heading( 'Stories by State' ); ?>
      <div class="states-container"><?php
        foreach ( $states as $abbr => $label ): ?>
        <a class="state" href="<?php echo esc_url( get_post_type_archive_link( 'story' ) ); ?>?state=<?php echo esc_attr( $abbr ); ?>">
          <span class="state-icon stateface stateface-replace stateface-<?php echo esc_attr( strtolower( $abbr ) ); ?>"></span>
          <span class="abbr"><?php echo $abbr; ?></span>
        </a>
        <?php
        endforeach; ?>
      </div>
    </div>
  </div>
  <?php
  endif;
}

add_action( 'genesis_before_footer', 'cc_lift_before_footer_info' );
function cc_lift_before_footer_info() {
  if ( have_rows( 'info' ) ):
  ?>
  <div class="story-info">
    <div class="wrap"><?php
      cc_heading( get_field( 'info_heading' ) ); ?>
      <div class="info-container"><?php
      while ( have_rows( 'info' ) ): the_row();
      if ( get_sub_field( 'has_arrow' ) ) {
        $class = 'has-arrow';
      } else {
        $class = sprintf( '%s-width %s-background', get_sub_field( 'width' ), get_sub_field( 'background_color' ) );
      }
      $style = '';
      if ( $image = get_sub_field( 'background_image' ) ) {
        $style = sprintf( ' style="background-image:url(%s);"', $image );
      }
      ?>
        <div class="info-item <?php echo esc_attr( $class ); ?>"<?php echo $style; ?>>
          <div class="text-container">
            <?php
            if ( get_sub_field( 'has_arrow' ) ) {
              echo sprintf( '<span>%s</span>', get_sub_field( 'arrow_text' ) );
              echo '<i class="fas fa-long-arrow-alt-down"></i>';
            } else {
              echo get_sub_field( 'text' );
            }
            ?>
          </div>
        </div><?php
      endwhile; ?>
      </div>
    </div>
  </div>
  <?php
  endif;
}

add_action( 'genesis_before_footer', 'cc_lift_before_footer_news' );
function cc_lift_before_footer_news() {
  if ( $category = get_field( 'news_category' ) ):
  $args = array(
    'category__in' => array( $category ),
    'posts_per_page' => 3,
  );
  global $wp_query;
  $wp_query = new WP_Query( $args );
  if ( $wp_query->have_posts() ):
  add_filter( 'cc_entry_thumbnail_size', function() { return 'featured_square'; } );
  ?>
  <div class="story-news">
    <div class="wrap">
      <div class="heading-container">
        <h2 class="heading">
          <?php if ( $logo = get_field( 'lift_logo', 'option' ) ): ?>
          <div class="lift-logo">
            <img src="<?php echo esc_attr( $logo ); ?>">
          </div>
          <?php endif; ?>
          <span>News</span>
        </h2>
      </div>
      <div class="posts-container"><?php
      while ( $wp_query->have_posts() ): $wp_query->the_post();
        cc_entry_markup();
      endwhile; wp_reset_postdata(); wp_reset_query(); ?>
      </div>
    </div>
  </div>
  <?php
  endif;
  endif;
}

add_filter( 'genesis_attr_entry', 'cc_lift_attributes' );
function cc_lift_attributes( $attributes ) {
  global $wp_query;
  $color = $wp_query->current_post % 2 === 0 ? 'dark' : 'light';
  $attributes['class'] .= " post-list-entry shadowed color-$color";
  return $attributes;
}

add_action( 'genesis_before_footer', 'cc_archive_story_before_footer' );
remove_action( 'genesis_before_footer', 'cc_before_footer_section', 99 );
add_action( 'genesis_before_footer', 'cc_lift_form_footer_section' );
add_action( 'genesis_before_footer', 'cc_archive_story_buttons' );

genesis();
