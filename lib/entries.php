<?php
// lib/entries.php //

// add_action( 'template_redirect', function() {
//   if ( ( is_singular( 'post' ) && get_post_format() === 'link' ) || ( is_singular( 'report' ) && get_field( 'external_link' ) ) ) {
//     $url = get_field( 'external_link' ) ? get_field( 'external_link' ) : get_home_url();
//     wp_redirect( $url );
//     die();
//   }
// } );

add_filter( 'genesis_post_title_output', 'jdn_custom_post_title' );
function jdn_custom_post_title( $title ) {
  if ( (get_post_format() === 'link' ) || ( is_singular( 'report' ) && get_field( 'external_link' ) ) ) {
    // $title = '<div>Got Here</div>';
 $post_title = get_the_title( get_the_ID() );
 $url = get_field('external_link');
 $title = '<h2 class="entry-title" itemprop="headline"><a class="entry-title-link" rel="bookmark" href="' .$url.'" target="_blank">' . $post_title . '</a></h2>';
 }
 return $title;
}

add_action( 'genesis_before', 'cc_before_entries' );
function cc_before_entries() {
  remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
  remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
  add_action( 'genesis_entry_header', 'cc_entry_thumbnail_ouput', 0 );
  add_action( 'genesis_entry_footer', 'cc_entry_footer' );
  if ( cc_is_post_list_page() ) {
    add_filter( 'genesis_attr_entry', 'cc_attributes_entry' );
  }
  if ( is_search() ) {
    add_filter( 'genesis_attr_entry', function( $attributes ) {
      $attributes['class'] .= ' info-entry';
      return $attributes;
    } );
  }
  if ( is_singular( 'post' ) ) {
    add_action( 'genesis_entry_header', 'cc_post_entry_meta' );
  }
  if ( is_singular( array( 'post', 'issue' ) ) ) {
    add_action( 'genesis_after_content', 'cc_after_content_back_button' );
  }
  if ( is_singular( 'report' ) ) {
    add_action( 'genesis_entry_content', 'cc_entry_content_report_download' );
  }
}

function cc_entry_content_report_download() {
  if ( $pdf = get_field( 'pdf_download' ) ):
  ?>
  <div class="pdf-container">
    <a class="pdf-link" href="<?php echo esc_url( $pdf ); ?>" target="_blank">
      <i class="far fa-fw fa-file-pdf"></i>
      <span>Download PDF</span>
    </a>
  </div>
  <?php
  endif;
}

function cc_after_content_back_button() {
  if ( is_singular( 'post' ) && ( $home_id = get_option( 'page_for_posts' ) ) ) {
    if(has_category('resilience') && ($state = get_field('state') ) ) {
      $text = __( 'Back', 'connectedcouncil' );
      $url = '/' . $state['label'];
    }
    else {
      $url = get_permalink( $home_id );
      $text = __( 'Back to News', 'connectedcouncil' );
    }
  } else if ( is_singular( 'issue') ) {
    $posts = get_posts( array(
      'post_type' => 'page',
      'meta_key' => '_wp_page_template',
      'meta_value' => 'templates/issues.php'
    ) );
    if ( count( $posts ) > 0 ) {
      $url = get_permalink( $posts[0]->ID );
      $text = __( 'Back to Issues', 'connectedcouncil' );
    }
  }
  if ( $url && $text ):
  ?>
  <div class="back-to-archive">
    <a class="button outline" href="<?php echo esc_url( $url ); ?>"><i class="fas fa-long-arrow-alt-left"></i><?php echo esc_html( $text ); ?></a>
  </div>
  <?php
  endif;
}

function cc_post_entry_meta() {
  $category_text = get_the_category_list( ', ' );
  if ( strpos( $category_text, ',' ) > -1 ) {
    $category_text = sprintf( 'CATEGORIES: <strong>%s</strong>', $category_text );
  } else if ( $category_text ) {
    $category_text = sprintf( 'CATEGORY: <strong>%s</strong>', $category_text );
  }
  ?>
  <p class="entry-meta">
    <?php if ( $category_text ): ?>
    <span class="entry-categories"><?php echo wp_kses_post( $category_text ); ?></span>
    <?php endif; ?>
    <span>–</span>
    <span class="entry-date"><?php echo esc_html( get_the_date() ); ?></span>
  </p>
  <?php
}

function cc_attributes_entry( $attributes ) {
  $attributes['class'] .= ' post-list-entry shadowed';
  return $attributes;
}

add_action( 'cc_entry_thumbnail', 'cc_entry_thumbnail_ouput' );
function cc_entry_thumbnail_ouput() {
  $show_entry_thumbnail = apply_filters( 'cc_show_entry_thumbnail', has_post_thumbnail() && !is_singular( 'page' ) );
  $size = apply_filters( 'cc_entry_thumbnail_size', is_single() ? 'full' : 'featured_rectangle_wide' );
  $linked = apply_filters( 'cc_link_entry_thumbnail', cc_is_post_list_page() );
  $show_icon = apply_filters( 'cc_entry_thumbnail_icon', cc_is_post_list_page() );
  if ( $show_entry_thumbnail || $show_icon === true ):
  echo '<div class="entry-thumbnail">';
    do_action( 'cc_entry_thumbnail_extra' );
    if ( (get_post_format() === 'link' ) || (get_post_type() == 'report') ) {
      echo $linked ? sprintf( '<a target="_blank" href="%s">', get_field('external_link') ) : '';
    }
    elseif($conn = get_field('connected_page')) {
      echo $linked ? sprintf( '<a href="%s">', $conn ) : '';
    }
    else {
      echo $linked ? sprintf( '<a href="%s">', get_permalink() ) : '';
    }
    //echo $linked ? sprintf( '<a href="%s">', get_permalink() ) : '';
    if ( has_post_thumbnail() ) {
      if ( $show_icon === true ) {
        echo sprintf( '<span class="entry-icon entry-icon-secondary">%s</span>', cc_get_post_icon() );
      }
      the_post_thumbnail( $size );
    } else if ( $show_icon === true ) {
      echo sprintf( '<span class="entry-icon entry-icon-primary">%s</span>', cc_get_post_icon() );
    }
    echo $linked ? '</a>' : '';
  echo '</div>';
  endif;
}

function cc_entry_footer() {
  if ( apply_filters( 'cc_entry_footer_show_read_more', cc_is_post_list_page() ) ) {
    if ( apply_filters( 'cc_entry_footer_show_date', '__return_true' ) ) {
      echo sprintf( '<span class="entry-date">%s</span><span>–</span>', get_the_date() );
    }
    if ( (get_post_format() === 'link' )) {
      echo sprintf( '<a class="read-more" target="_blank" href="%s">%s <i class="fas fa-long-arrow-alt-right"></i></a>', get_field('external_link'), __( 'Read More', 'connectedcouncil' ) );
    } else {
      echo sprintf( '<a class="read-more" href="%s">%s <i class="fas fa-long-arrow-alt-right"></i></a>', get_permalink(), __( 'Read More', 'connectedcouncil' ) );
    }
  }
}


function cc_get_post_icon() {
  $category = get_the_category();
  $category = count( $category ) > 0 ? $category[0] : null;
  if ( $icon = get_field( 'post_icon' ) ) {
    return $icon;
  } else if ( $category !== null && $category->name !== 'Uncategorized' && $icon = get_field( 'icon', "term_$category->term_id" ) ) {
    return $icon;
  }
  if ( get_post_type() === 'event' ) {
    return '<i class="far fa-calendar-alt"></i>';
  }
  return '<i class="far fa-newspaper"></i>';
}

// 		}


// END lib/entries.php //
