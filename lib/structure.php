<?php
// lib/structure.php //

// Posts

add_action( 'pre_get_posts', 'cc_pre_get_posts' );
function cc_pre_get_posts( $query ) {
  if ( is_admin() === false && $query->is_main_query() === true ):
  if ( is_home() ) {
    $query->set( 'orderby', 'date' );
    if ( $featured_posts = get_field( 'featured_posts' ) ) {
      $featured_posts = array_map( function( $p ) { return $p->ID; }, $featured_posts );
      $query->set( 'post__not_in', $featured_posts );
    }
    $query->set( 'posts_per_page', 9 );
    $cat = get_cat_ID('Coronavirus');
    $cat2 = get_cat_ID('Resilience');
    $cat_ids = get_terms( 'category', array( 'fields' => 'ids', 'exclude' => array()) );

    $query->set( 'lang', '' );
    if ( is_array( $query->get( 'tax_query' ) ) ) {
			$tax_query = $query->get( 'tax_query' );

			foreach ( $tax_query as $i => $row ) {
				if ( 'language' === $row['taxonomy'] ) {
					unset( $tax_query[ $i ] );
				}
			}

			$query->set( 'tax_query', $tax_query );
    }
    //$query->set( 'category__in', $cat_ids );
    // if ( $category = get_field( 'newsletter_category', 'option' ) ) {
    //   $query->set( 'category__not_in', $category );
    //   if ( $query->get( 'paged' ) === 0 ) {
    //     $query->set( 'posts_per_page', 8 );
    //   }
    // }
  }
  endif;
}

remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );
add_action( 'genesis_after_content', 'genesis_posts_nav' );

//
// Layout
//

add_filter( 'body_class', 'cc_body_classes' );
function cc_body_classes( $classes ) {
  if ( get_field( 'footer_section' ) === 'form' || ( is_single() && get_field( 'footer_section' ) !== 'none' )  ) {
    $classes[] = 'has-footer-form';
  }
  return $classes;
}

add_action( 'genesis_before', 'cc_before_site_inner_background' );
function cc_before_site_inner_background() {
  if ( is_singular( 'page' ) && has_post_thumbnail() ) {
    cc_background_style_tag( '.site-inner', get_the_post_thumbnail_url( get_the_id(), 'full' ) );
  }
}

add_filter( 'body_class', 'cc_featured_image_background' );
function cc_featured_image_background( $classes ) {
  $show_page_featured_background = apply_filters( 'cc_show_page_featured_background', is_singular( 'page' ) && has_post_thumbnail() );
  if ( $show_page_featured_background ) {
    $classes[] = 'has-featured-background';
  }
  return $classes;
}

// Sets the page style since that silly layouts metabox is removed
add_filter( 'genesis_site_layout', 'cc_site_layout' );
function cc_site_layout() {
  return 'full-width-content';
}

function cc_image_background() {
  $url = apply_filters( 'cc_image_background_url', get_the_post_thumbnail_url() );
  if ( $url ):
  ?>
  <div class="image-background">
    <img src="<?php echo esc_url( $url ); ?>">
  </div>
  <?php
  endif;
}

//
// Header
//

add_filter( 'genesis_seo_title', 'cc_title_logo', 10, 3 );
function cc_title_logo( $title, $inside, $wrap ) {
  $output = sprintf( '<%1$s class="site-title">%2$s</%1$s>', $wrap, $inside );
  if ( function_exists( 'get_field' ) && get_field( 'logo', 'option' ) !== false ) {
    $logo = sprintf( '<a aria-label="logo" href="%s"><img class="logo" src="%s"></a>', esc_url( get_home_url() ), get_field( 'logo', 'option' ) );
    $output = $logo . $output;
  }
  return $output;
}

// Removes secondary menu
add_theme_support( 'genesis-menus', array(
  'primary' => 'Primary Navigation Menu',
  'footer' => 'Footer Navigation Menu'
) );

add_action( 'genesis_header', 'cc_header_right' );
function cc_header_right() {
  ?>
  <div class="header-right">
    <?php do_action( 'cc_header_right' ); ?>
  </div>
  <?php
}

add_action( 'cc_header_right', 'cc_membership_buttons' );
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'cc_header_right', 'genesis_do_nav' );


//
// Inner
//

add_action( 'genesis_before_content_sidebar_wrap', 'cc_site_inner_wrap_open' );
function cc_site_inner_wrap_open() {
  echo '<div class="wrap">';
}

add_action( 'genesis_after_content_sidebar_wrap', 'cc_site_inner_wrap_close' );
function cc_site_inner_wrap_close() {
  echo '</div>';
}

unregister_sidebar( 'header-right' );
unregister_sidebar( 'sidebar' );
unregister_sidebar( 'sidebar-alt' );

//
// Footer
//

add_action( 'genesis_before_footer', 'cc_before_footer_section', 99 );
function cc_before_footer_section() {
  if ( is_post_type_archive() ) {
    $term = get_queried_object();
    $footer_section = get_field( "{$term->name}_footer_section", 'option' );
  } else {
    $footer_section = get_field( 'footer_section' );
  }
  if(!(is_post_type_archive('resource'))) {
    if ( $footer_section === 'join' ) {
      cc_join_footer_section();
    } else if ( $footer_section !== 'none' && $footer_section !== '' ) { // form on 404, search, etc
      cc_form_footer_section();
    }
  }
}

function cc_join_footer_section() {
  if ( $content = get_field( 'footer_join_content', 'option' ) ):
  // cc_background_style_tag( '.footer-join-section', get_field( 'footer_join_background_image', 'option' ) );
  ?>
  <section class="footer-section footer-join-section" style="background-image: url('<?php echo get_field( 'footer_join_background_image', 'option' ) ?>')">
    <div class="wrap small">
      <div class="content-container join-content-container">
        <img class="mx-auto" src="<?php echo get_field('footer_join_image', 'option')['url'] ?>" alt="">
        <?php echo apply_filters( 'the_content', $content ); ?>
        <?php echo cc_button( get_field( 'footer_join_button_text', 'option' ), get_field( 'footer_join_button_link', 'option' ) ); ?>
      </div>
    </div>
  </section>
  <?php
  endif;
}

function cc_form_footer_section() {
  if ( $form = get_field( 'footer_form', 'option' ) ):
  ?>
  <section class="footer-section footer-form-section">
    <div class="wrap">
      <div class="footer-form-container">
        <div class="content-container">
          <?php cc_heading( get_field( 'footer_form_heading', 'option' ) ); ?>
          <div class="form-container"><?php echo cc_form( $form ); ?></div>
        </div>
        <?php if ( $image = get_field( 'footer_form_image', 'option' ) ): ?>
        <div class="image-container" style="background-image:url(<?php echo esc_url( wp_get_attachment_image_src( $image, 'person' )[0] ); ?>);"></div>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <?php
  endif;
}

function cc_lift_form_footer_section() {
  if ( $form = get_field( 'lift_form', 'option' ) ):
  ?>
  <section class="footer-section footer-form-section">
    <div class="wrap">
      <div class="footer-form-container">
        <div class="content-container">
          <?php cc_heading( get_field( 'lift_form_heading', 'option' ) );
          if ( $text = get_field( 'lift_form_text', 'option' ) ): ?>
          <div class="text-container"><?php echo wp_kses_post( $text ); ?></div>
          <?php endif; ?>
          <div class="form-container"><?php echo cc_form( $form ); ?></div>
        </div>
        <?php if ( $image = get_field( 'lift_form_image', 'option' ) ): ?>
        <div class="image-container" style="background-image:url(<?php echo esc_url( wp_get_attachment_url( $image ) ); ?>);"></div>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <?php
  endif;
}

function cc_archive_story_buttons() {
  ?>
  <div class="lift-buttons">
    <div class="wrap">
      <div class="buttons-container"><?php
      if ( $link = get_field( 'lift_about_button_link', 'option' ) ): ?>
        <a class="about button outline" href="<?php echo esc_url( $link ); ?>"><?php
          if ( $image = get_field( 'lift_about_button_icon', 'option' ) ): ?>
          <img src="<?php echo esc_url( $image ); ?>"><?php
          endif; ?>
          <span>About the Connected Commerce</span>
        </a>
        <?php endif; ?>
        <?php if ( ( $join_button_text = get_field( 'join_button_text', 'option' ) ) && ( $join_button_url = get_field( 'join_button_url', 'option' ) ) ): ?>
        <a class="join button" href="<?php echo esc_url( $join_button_url ); ?>">Join Now</a><?php
        endif; ?>
      </div>
    </div>
  </div>
  <?php
}

function cc_get_story_info() {
  $state = get_field( 'state' );
  return sprintf( '%s, %s, %s', get_field( 'company' ), get_field( 'location' ), $state['value'] );
}

function cc_archive_story_before_footer() {
  if ( $content = get_field( 'lift_about_content', 'option' ) ):
  ?>
  <div class="story-archive-about">
    <div class="wrap">
      <div class="heading-container">
        <h2 class="heading">About</h2>
        <?php if ( $logo = get_field( 'lift_logo', 'option' ) ): ?>
        <div class="lift-logo">
          <img src="<?php echo esc_attr( $logo ); ?>">
        </div>
        <?php endif; ?>
      </div>
      <div class="content-container"><?php echo apply_filters( 'the_content', $content ); ?></div>
    </div>
  </div>
  <?php
  endif;
}

function cc_archive_story_loop( $posts ) {
  global $post;

  ?>
  <div class="archive-story-posts"><?php
    foreach ( $posts as $post ): setup_postdata( $post );
    $state = get_field( 'state' );
    ?>
      <div class="story">
        <div class="image">
          <img src="<?php echo esc_url( get_the_post_thumbnail_url( $post->ID, 'story' ) ); ?>">
        </div>
        <a class="info" href="<?php echo esc_url( get_permalink() ); ?>">
          <div class="top state">
            <span class="state-icon stateface stateface-replace stateface-<?php echo esc_attr( strtolower( $state['value'] ) ); ?>"></span>
            <span class="label"><?php echo esc_html( $state['value'] ); ?></span>
          </div>
          <div class="bottom">
            <h4 class="company"><?php echo wp_kses_post( get_field( 'company' ) ); ?></h4>
            <p class="state"><?php echo esc_html( $state['label'] ); ?>(<?php echo esc_html( $state['value'] ); ?>)</p>
            <p class="links"><?php
              if ( trim( $post->post_content ) !== '' ): ?>
              <span>
                <i class="fas fa-fw fa-bars"></i>
                <span>Read Story</span>
              </span><?php
              endif;
              if ( get_field( 'video' ) ): ?>
              <span>
                <i class="fas fa-fw fa-video"></i>
                <span>Watch Video</span>
              </span><?php
              endif; ?>
            </p>
          </div>
        </a>
      </div><?php
    endforeach; wp_reset_postdata(); ?>
  </div>
  <?php
}

remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'cc_footer_back_to_top' );
function cc_footer_back_to_top() {
  ?>
  <div class="back-top">
    <a href="#">
      <i class="fa fa-fw fa-chevron-up"></i>
    </a>
  </div>
  <?php
}

add_action( 'genesis_footer', 'cc_footer_menu' );
function cc_footer_menu() {
  if ( have_rows( 'social_networks', 'option' ) || has_nav_menu( 'primary' ) ):
  ?>
  <div class="footer-row">
    <?php cc_social_links_output(); ?>
    <?php if ( has_nav_menu( 'primary' ) ):
    echo '<nav class="nav-footer" itemtype="https://schema.org/SiteNavigationElement">';
    wp_nav_menu( array(
      'theme_location' => 'primary',
      'menu_class' => 'genesis-nav-menu',
      'menu_id' => 'menu-footer-menu'
    ) );
    echo '</nav>';
    endif; ?>
  </div>
  <?php
  endif;
}

add_action( 'genesis_footer', 'cc_footer_logo' );
function cc_footer_logo() {
  $logo = get_field( 'logo', 'option' );
  $membership_buttons = cc_membership_buttons( true );
  if ( $logo || $membership_buttons ):
  ?>
  <div class="footer-row horizontal">
    <div class="footer-logo">
      <img src="<?php echo esc_url( $logo ); ?>">
    </div>
    <?php echo $membership_buttons; ?>
  </div>
  <?php
  endif;
}

add_action( 'genesis_footer', 'cc_footer_copyright' );
function cc_footer_copyright() {
  if ( $content = get_field( 'footer_copyright', 'option' ) ):
  ?>
  <div class="footer-row">
    <div class="copyright">
      <span>&copy; <?php echo esc_attr( date( 'Y' ) ); ?></span>
      <?php echo wp_kses_post( $content); ?>
    </div>
  </div>
  <?php
  endif;
}

//
// Parts
//

add_action( 'cc_social_links', 'cc_social_links_output' );
function cc_social_links_output() {

  if ( ! class_exists('acf') ) return '';

  if ( have_rows( 'social_networks', 'option' ) ):
  ?>
  <div class="social-links">
    <ul class="sl-container s-container">
      <?php while( have_rows( 'social_networks', 'option' ) ): the_row(); ?>
        <li><a style="background-color: <?php echo esc_attr( get_sub_field( 'background_color' ) ); ?>" href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" target="_blank">
          <?php
          $icon = get_sub_field( 'icon' );
          if ( strip_tags( $icon ) == $icon ) { // class name ACF
            echo sprintf( '<i class="fa fa-fw %s"></i>', $icon );
          }
          else {
            echo $icon;
          }
          ?>
        </a></li>
      <?php endwhile; ?>
    </ul>
  </div>
  <?php
  endif;
}

function cc_membership_buttons( $return = false ) {
  $login_button_icon = get_field( 'login_button_icon', 'option' );
  $login_button_text = get_field( 'login_button_text', 'option' );
  $login_button_url = get_field( 'login_button_url', 'option' );

  $join_button_text = get_field( 'join_button_text', 'option' );
  $join_button_url = get_field( 'join_button_url', 'option' );
  if ( $return === true ) {
    ob_start();
  }
  if ( ( $login_button_text && $login_button_url ) || ( $join_button_text && $join_button_url ) ):
  ?>
  <div class="membership-buttons">
    <?php if ( $login_button_text && $login_button_url ): ?>
    <a class="button blue login small" href="<?php echo esc_url( $login_button_url ); ?>"><?php
      if ( $login_button_icon ): ?>
      <img src="<?php echo esc_url( $login_button_icon ); ?>"><?php
      endif; ?>
      <span><?php echo wp_kses_post( $login_button_text ); ?></span>
    </a><?php
    endif;
    if ( $join_button_text && $join_button_url ): ?>
    <a class="button small" href="<?php echo esc_url( $join_button_url ); ?>"><?php echo wp_kses_post( $join_button_text ); ?></a>
    <?php endif; ?>
  </div>
  <?php
  endif;
  if ( $return === true ) {
    return ob_get_clean();
  }
}

function cc_query_from_posts( $posts, $post_type = 'post' ) {
  if ( is_array( $posts ) === false ) {
    $posts = array( $posts );
  }
  $post_ids = array_map( function( $p ) { return $p->ID; }, $posts );
  global $wp_query;
  $args = array(
    'post_type' => $post_type,
    'post__in' => $post_ids,
  );
  $wp_query = new WP_Query( $args );
  while ( $wp_query->have_posts() ): $wp_query->the_post();
  cc_entry_markup();
  endwhile;
  wp_reset_postdata();
  wp_reset_query();
}

function cc_entry_markup() {
  genesis_markup( array(
    'open'    => '<article %s>',
    'context' => 'entry',
  ) );
    do_action( 'genesis_entry_header' );
    printf( '<div %s>', genesis_attr( 'entry-content' ) );
      the_excerpt();
    echo '</div>';
    do_action( 'genesis_entry_footer' );
  genesis_markup( array(
    'close'   => '</article>',
    'context' => 'entry',
  ) );
}

// add_filter( 'genesis_post_title_text', 'sk_post_title_text' );
// function sk_post_title_text( $title ) {
// 	if ( is_singular( 'post' ) ) {
// 		$single_post_link = get_field('external_link');

// 		if ( $single_post_link ) {
// 			$title = '<a href="'.$single_post_link.'" target="_blank">'.$title.'</a>';
// 		}

// 	}

// 	return $title;
// }

// add_filter( 'gform_us_states', 'us_states' );
// function us_states( $states ) {
//     $new_states = array();
//     foreach ( $states as $state ) {
//         $new_states[ GF_Fields::get( 'address' )->get_us_state_code( $state ) ] = $state;
//     }
 
//     return $new_states;
// }

function turn_blogposts_translation_off( $post_types, $is_settings ) {
  unset( $post_types['post'] );
  
  return $post_types;
}

add_filter( 'pll_get_post_types', 'turn_blogposts_translation_off', 10, 2 );

function turn_categoryblog_translation_off( $taxonomies, $is_settings ) {
  unset( $taxonomies['category'] );
  return $taxonomies;
}
add_filter( 'pll_get_taxonomies', 'turn_categoryblog_translation_off', 10, 2 );

function turn_tagblog_translation_off( $taxonomies, $is_settings ) {
  unset( $taxonomies['post_tag'] );
  return $taxonomies;
}
add_filter( 'pll_get_taxonomies', 'turn_tagblog_translation_off', 10, 2 );

// END lib/structure.php //
