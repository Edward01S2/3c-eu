<?php

global $event_archived;

function cc_get_event_register_button( $class = '' ) {
  $link = get_field( 'register_type' ) === 'form' && get_field( 'register_form' ) ? '#register' : get_field( 'register_link' );
  if ( $link ) {
    echo sprintf( '<div class="button-container"><a class="button%s" href="%s">Register</a></div>', $class ? ' ' . $class : '', $link );
  }
}

add_action( 'genesis_before', 'cc_before_event_single' );
function cc_before_event_single() {
  remove_action( 'genesis_entry_header', 'cc_entry_thumbnail_ouput', 0 );
  remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
  remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
	remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

  if ( $date = get_field( 'date' ) ) {
    $date = get_field( 'start_time' ) ? $date . ' ' . get_field( 'end_time' ) : $date;
    // echo $date;
    // date_default_timezone_set('America/New_York');
    // $currentTimeinSeconds = time();
    // $currentDate = date('F j, Y, g:i a', $currentTimeinSeconds); 
    // echo $currentDate;
    //echo get_option('gmt_offset');
    $tz = 'America/New_York';
    $tz_obj = new DateTimeZone($tz);
    $today = new DateTime("now", $tz_obj);
    $result = $today->format('Y-m-d H:i:s');
    //echo $today;
    $now = strtotime($result);
    $time = strtotime( $date );

    // echo $time;
    global $event_archived;
    // echo time();
    $event_archived = $now > $time;
    // echo $event_archived;
    // echo !$event_archived ? 'false': '';
  }
}

add_action( 'genesis_before_content_sidebar_wrap', 'cc_image_background', 1 );
add_filter( 'cc_image_background_url', function() {
  return get_field( 'background_image' );
} );

add_action( 'genesis_entry_header', 'cc_single_title_register_button' );
function cc_single_title_register_button() {
  cc_get_event_register_button( 'small' );
}

add_action( 'genesis_before_footer', 'cc_event_info' );
function cc_event_info() {
  global $event_archived;
  $content = $event_archived ? get_field( 'archived_content' ) : get_post_field( 'post_content', get_the_id() );
  ?>
  <section class="event-section event-info-section">
    <div class="wrap">
      <div class="event-info-container shadowed">
        <div class="entry-content event-content"><?php
          echo apply_filters( 'the_content', $content );
          if ( $event_archived === false ) {
            cc_get_event_register_button();
          } ?>
        </div>
        <?php if ( $content = cc_get_event_info() ): ?>
        <div class="event-info"><?php echo wp_kses_post( $content ); ?></div>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <?php
}

function cc_get_event_info() {
  $content = '';
  if ( $logo = get_field( 'event_logo' ) ) {
    $content .= sprintf( '<div class="event-logo"><img src="%s"></div>', $logo );
  }
  if ( $date = get_field( 'date' ) ) {
    $content .= sprintf( '<div class="event-date"><i class="far fa-fw fa-calendar-alt"></i><span>%s</span></div>', $date );
  }
  if ( get_field( 'all_day' ) ) {
    $content .= sprintf( '<div class="event-time all-day"><i class="far fa-fw fa-clock"></i><span>All Day</span></div>' );
  } else if ( $start_time = get_field( 'start_time' ) ) {
    $end_time = get_field( 'end_time' ) ? sprintf( ' – %s', get_field( 'end_time' ) ) : '';
    $timezone = get_field('timezone') ? sprintf(' %s', get_field('timezone')) : '';
    $content .= sprintf( '<div class="event-time"><i class="far fa-fw fa-clock"></i><span>%s%s%s</span></div>', $start_time, $end_time, $timezone );
  }
  if ( get_field( 'location' ) || get_field( 'address' ) ) {
    $parts = array();
    if ( $location = get_field( 'location' ) ) {
      $parts[] = $location;
    }
    if ( $address = get_field( 'address' ) ) {
      $parts[] = $address;
    }
    $location = count( $parts ) > 0 ? implode( '<br>', $parts ) : '';
    $map_link = get_field( 'map_embed_code' ) ? '   <br><a href="#map">map <i class="fas fa-long-arrow-alt-right"></i></a>' : '';
    $content .= sprintf( '<div class="event-location"><i class="fas fa-fw fa-map-marker-alt"></i><span>%s%s</span></div>', $location, $map_link );
  }
  return $content;
}

add_action( 'genesis_before_footer', 'cc_event_gallery' );
function cc_event_gallery() {
  if ( $gallery = get_field( 'archived_gallery' ) ):
  ?>
  <section class="event-section gallery-section">
    <div class="wrap">
      <div class="gallery-container">
        <?php foreach ( $gallery as $image ): ?>
        <div class="gallery-image">
          <img src="<?php echo esc_url( $image['sizes']['person'] ); ?>">
        </div>
        <?php endforeach;?>
      </div>
    </div>
  </section>
  <?php
  endif;
}

add_action( 'wp_enqueue_scripts', 'cc_enqueue_event_scripts', 99 );
function cc_enqueue_event_scripts() {
  wp_enqueue_style( 'slick-carousel', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css' );
  wp_enqueue_script( 'sick-carousel', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js', array( 'jquery' ), false, true );
}

add_action( 'wp_footer', 'cc_event_footer', 99 );
function cc_event_footer() {
  ?>
  <script>
  ;(function($) {
    $(document).ready(function() {
      $('.gallery-container').slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        prevArrow: '<button type="button" class="slick-arrow slick-prev"><i class="fas fa-long-arrow-alt-left"></i></button>',
        nextArrow: '<button type="button" class="slick-arrow slick-next"><i class="fas fa-long-arrow-alt-right"></i></button>',
        responsive: [{
          breakpoint: 1024,
          settings: {
            slidesToShow: 2,
          }
        }, {
          breakpoint: 500,
          settings: {
            slidesToShow: 1,
          }
        }]
      })
    })
  })(jQuery);
  </script>
  <?php
}

add_action( 'genesis_before_footer', 'cc_event_register_form' );
function cc_event_register_form() {
  global $event_archived;
  if ( $event_archived === true || get_field( 'register_type' ) !== 'form' ) {
    return;
  }

  if ( $form = get_field( 'register_form' ) ):
  ?>
  <section id="register" class="event-section register-form-section">
    <div class="wrap">
      <div class="register-form-container shadowed">
        <?php cc_heading( get_field( 'register_form_heading' ) ? get_field( 'register_form_heading' ) : '' ); ?>
        <?php if ( $content = get_field( 'register_form_content' ) ): ?>
        <div class="content-container"><?php
          echo apply_filters( 'the_content', $content ); ?>
        </div>
        <?php endif; ?>
        <div class="form-container"><?php cc_form( $form ); ?></div>
      </div>
    </div>
  </section>
  <?php
  endif;
}

add_action( 'genesis_before_footer', 'cc_event_about' );
function cc_event_about() {
  global $event_archived;
  if ( $event_archived === true ){
    return;
  }
  if ( $content = get_field( 'about_content' ) ):
  cc_background_style_tag( '.about-section', get_field( 'about_background_image' ) );
  ?>
  <section class="event-section about-section">
    <div class="wrap small">
      <?php cc_heading( get_field( 'about_heading' ) ); ?>
      <div class="content-container entry-content"><?php echo apply_filters( 'the_content', $content ); ?></div>
    </div>
  </section>
  <?php
  endif;
}

add_action( 'genesis_before_footer', 'cc_event_agenda' );
function cc_event_agenda() {
  if ( have_rows( 'agenda' ) || have_rows( 'speakers' ) ):
  ?>
  <section class="event-section agenda-section">
    <div class="wrap">
      <div class="agenda-container shadowed">
        <?php if ( have_rows('agenda' ) ): ?>
        <div class="agenda">
          <?php cc_heading( 'Agenda' ); ?>
          <div class="agenda-items">
            <?php while ( have_rows( 'agenda' ) ): the_row(); ?>
            <div class="agenda-item">
              <span class="agenda-time"><?php echo esc_html( get_sub_field( 'time' ) ); ?></span>
              <div class="agenda-info">
                <h5><?php echo esc_html( get_sub_field( 'title' ) ); ?></h5><?php
                if ( $text = get_sub_field( 'text' ) ): ?>
                <p><?php echo wp_kses_post( $text ); ?></p><?php
                endif; ?>
              </div>
            </div>
            <?php endwhile; ?>
          </div><?php
          if ( $content = get_field( 'agenda_cta' ) ): ?>
          <div class="cta-container"><?php echo apply_filters( 'the_content', $content ); ?></div><?php
          endif;
          cc_get_event_register_button(); ?>
        </div>
        <?php endif; ?>
        <?php if ( have_rows( 'speakers' ) ): ?>
        <div class="speakers">
          <?php cc_heading( 'Speakers' ); ?>
          <div class="speakers-list">
            <?php while ( have_rows( 'speakers' ) ): the_row();
            $link = get_sub_field( 'link' ); ?>
            <div class="speaker">
              <?php if ( $link ): ?><a href="<?php echo esc_url( $link ); ?>"><?php endif; ?>
              <img class="speaker-image" src="<?php echo esc_url( wp_get_attachment_image_src( get_sub_field( 'image' ), 'person' )[0] ); ?>">
              <h4 class="speaker-name"><?php echo esc_html( get_sub_field( 'name' ) ); ?></h4>
              <?php if ( $title = get_sub_field( 'title' ) ): ?>
              <p class="speaker-title"><?php echo wp_kses_post( $title ); ?></p>
              <?php endif; ?>
              <?php if ( $link ): ?></a><?php endif; ?>
            </div>
            <?php endwhile; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <?php
  endif;
}

add_action( 'genesis_before_footer', 'cc_event_location' );
function cc_event_location() {
  if ( $embed = get_field( 'map_embed_code' ) ):
  ?>
  <section id="map" class="event-section location-section">
    <div class="wrap small">
      <?php cc_heading( 'Location' ); ?>
      <?php if ( $location = get_field( 'location' ) ): ?>
      <h3 class="location"><?php echo esc_html( $location ); ?></h3>
      <?php endif; ?>
      <?php if ( $address = get_field( 'address' ) ): ?>
      <h4 class="address"><?php echo wp_kses_post( $address ); ?></h4>
      <?php endif; ?>
      <?php if ( $content = get_field( 'map_content' ) ): ?>
      <div class="content-container"><?php echo apply_filters( 'the_content', $content); ?></div>
      <?php endif; ?>
    </div>
  </section>
  <section class="event-section map-section">
    <div class="wrap">
      <div class="map-container<?php echo get_field( 'map_image' ) ? ' has-image' : '' ?>">
        <div class="map">
          <?php echo $embed; ?>
        </div>
        <?php if ( $image = get_field( 'map_image' ) ): ?>
        <div class="map-image" style="background-image:url(<?php echo esc_url( $image ); ?>"></div>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <?php
  endif;
}


genesis();
