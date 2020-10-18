<?php

// add_filter( 'cc_entry_thumbnail_icon', '__return_false' );
// add_filter( 'cc_entry_footer_show_read_more', '__return_true' );
// add_filter( 'cc_entry_footer_show_date', '__return_false' );

remove_action( 'genesis_loop', 'genesis_do_loop' );


add_action('genesis_before_content', 'es_resource_heading');
function es_resource_heading() { ?>
  <section class="bg-white">
      <div class="flex flex-col lg:flex-row lg:items-stretch">
        <div class="order-2 p-8 md:p-12 lg:w-1/2 lg:order-1 xl:p-32 xl:pr-12">
          <h1 class="font-bold"><?php echo get_field('standup_title', 'options') ?></h1>
          <div class="break-words"><?php echo get_field('standup_content', 'options') ?></div>
          <a href="#standup-state" class="group transition duration-300">
            <div class="flex items-center py-4 md:pb-0">
              <div class="rounded-full border-2 p-2 border-c-orange-100 inline-block group-hover:border-c-blue-200 transition duration-300"">
                <div class="rounded-full p-2 bg-c-orange-100 group-hover:bg-c-blue-200 transition duration-300"">
                  <svg class="h-4 w-4 rounded-full bg-c-orange-100 text-white group-hover:bg-c-blue-200 md:h-6 md:w-6 transition duration-300"" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                </div>
              </div>
              <div class="pl-4 leading-tight text-2xl font-bold text-c-blue-200">
                <?php echo get_field('standup_subtext', 'options') ?>
              </div>
            </div>
          </a>
        </div>
        <!-- <div class="order-1 pb-72 bg-center bg-cover md:pb-108 lg:w-1/2 lg:order-2" style="background-image:url('<?php echo get_field('standup_image', 'options')['url'] ?>');"> -->
        <div class="order-1 md:p-12 md:pb-0 lg:w-1/2 lg:pb-12">
          <img class="object-cover w-full object-center h-96 lg:h-full" src="<?php echo get_field('standup_image', 'options')['url'] ?>" alt="">
        </div>
      </div>
      </div>
  </section>
  <?php
}

add_action('genesis_before_content', 'es_resource_box');
function es_resource_box() { ?>
  <section class="bg-c-blue-200">
    <div id="standup-state" class="wrap py-8 md:py-12 xl:p-32 xl:pr-12 xl:py-12">
      <div class="flex flex-col lg:flex-row lg:items-center">
        <div class="lg:w-1/2">
          <?php
             $map_id = get_field('standup_map', 'options');
            echo do_shortcode('[mapsvg id="' . $map_id . '"]') 
          ?>
        </div>
        <div class="text-white mb-12 lg:w-1/2">
          <div class="standup-form mt-8 md:mt-16 lg:pl-8 lg:mx-auto">
            <?php
              $standup_form = get_field( 'standup_form', 'options')['title'];
              //var_dump($standup_form); 
              echo gravity_form( 14, false, false, false, null, true); 
            ?>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php
}

add_action('genesis_before_content', 'es_letters');
function es_letters() { ?>
  <section class="bg-white">
    <div class="p-8 md:p-12 xl:p-32 xl:py-16">
      <?php if(!isset($_GET['state'])) : ?>
      <div class="flex items-center justify-center">
        <svg class="fill-current text-c-orange-100 h-8 w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zM9 5v6h2V5H9zm0 8v2h2v-2H9z"/></svg>
        <div class="italic pl-4 leading-tight text-2xl"><?php echo get_field('letter_pretext', 'options') ?></div>
      </div>
      <?php endif; ?>
      <div id="letter-container" class="md:shadow-xl mt-16 md:mb-16 md:rounded-md md:px-8 md:border-solid md:border md:border-gray-300 lg:max-w-6xl lg:mx-auto">
        <div id="gov-letter" class="blur md:p-12 lg:pt-20">
          <?php 
          if(isset($_GET['state'])) {
            $state = $_GET['state'];
            $letters = get_field('letters', 'options');
            $default = get_field('default_letter', 'options'); 
            $letter_content = false;
            foreach($letters as $l) {
              if($state == $l['state']) {
                $letter_content = $l['content'];
              }
            }
            $letter = ($letter_content) ? $letter_content : $default;
            echo $letter;
          }
          else {
            echo get_field('default_letter', 'options'); 
          }
          ?>
        </div>
      </div>
    </div>
  </section>
  <?php
    if(isset($_GET['state'])) {
      $state = $_GET['state'];
      $args = array(
        'post_type' => 'letter',
        'posts_per_page' => -1,
        'meta_key' => 'standup_state',
        'meta_value' => $state,
      );
    }
    else {
      $args = array(
        'post_type' => 'letter',
        'posts_per_page' => 6,
      );
      }
    $companies = get_posts($args);

  if($companies) :
  ?>
  <section class="bg-c-gray-100">
    <div id="standup-companies" class="blur p-8 pt-16 md:p-12 md:py-16 xl:p-32">
    <div class="md:grid grid-cols-2 gap-8 lg:grid-cols-3 lg:gap-12 lg:px-16 xl:px-24">
      <?php foreach($companies as $c): ?>
        <div class="mb-8 md:mb-0">
          <h4 class="mb-0"><?php echo get_the_title($c->ID); ?></h4>
          <div class="text-3xl"><?php echo get_field('standup_city', $c->ID) . ', ' . get_field('standup_state', $c->ID); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
    </div>
  </section>
  <?php
  endif;
}


add_action( 'genesis_before_content', 'es_form_join_section' );
function es_form_join_section() {
  if ( $form = get_field( 'form', 'option' ) ):
  ?>
  <section class="footer-section footer-form-section mt-24">

    <div class="flex bg-c-blue-400">
      <div class="content-container">
        <?php cc_heading( get_field( 'Form Text', 'option' ) ); ?>
        <div class="form-container"><?php echo   gravity_form( $form, false, false, false, null, true, -1 ); ?></div>
      </div>
      <?php if ( $image = get_field( 'Form Image', 'option' ) ): ?>
      <div class="image-container" style="background-image:url(<?php echo esc_url( wp_get_attachment_image_src( $image, 'person' )[0] ); ?>);"></div>
      <?php endif; ?>
    </div>

  </section>
  <?php
  endif;
}

add_action( 'genesis_before_content', 'cc_virus_search' );
function cc_virus_search() {
  $search = isset( $_GET['rs'] ) ? sanitize_text_field( $_GET['rs'] ) : '';
  ?>
  <h2 id="virus-resources" class="resources-title">Coronavirus Resources</h2>
  <form class="reports-search" action="<?php echo esc_url( get_post_type_archive_link('resource') ); ?>">
    <input class="search-input text-c-blue-200" placeholder="Search Resources" type="text" name="rs" value="<?php echo $search; ?>">
    <button class="button plain small"><i class="fas fa-search"></i></button>
    <input type="submit" style="display:none">
  </form>
  <?php
}

add_action( 'genesis_before_content', 'cc_posts_template_before_content' );
function cc_posts_template_before_content() {
  if ( $categories = get_field( 'Tags', 'option' ) ):
  $current_category = isset( $_GET['category'] ) ? sanitize_key( $_GET['category'] ) : '';
  $permalink = get_post_type_archive_link('resource');
  $permalink .= strpos( $permalink, '?' ) > -1 ? '&' : '?';
  ?>
  <div class="post-filters"><?php
    if ( $heading = get_field( 'Filter Title', 'option' ) ): ?>
      <h3 class="filter-heading"><?php echo wp_kses_post( $heading ); ?></h3><?php
    endif; ?>
    <div class="filter-container">
      <form action="<?php echo esc_url( get_post_type_archive_link('resource') ); ?>#virus-resources" method="GET">
      <input class="button filter<?php echo $current_category === '' ? ' selected' : '' ?>" type="submit" value="All">
      </form>
    <?php
    foreach ( $categories as $category ): ?>
      <form action="<?php echo esc_url( get_post_type_archive_link('resource') ); ?>#virus-resources" method="GET">
      <input class="hidden" type='text' id='category' name='category' value='<?php echo $category->term_id ?>'>
      <input class="button filter<?php echo $category->term_id == $current_category ? ' selected' : ''; ?>" type="submit" value="<?php echo wp_kses_post( $category->name ); ?>">
      </form>
    <?php endforeach; ?>
    </div>
  </div>
  <?php
  endif;
}

add_action( 'genesis_loop', 'cc_posts_template_loop' );
function cc_posts_template_loop() {
  if ( $categories = get_field( 'Tags', 'option' ) ):

  add_filter( 'cc_entry_thumbnail_size', function() { return 'featured_square'; } );
  $cats = array_values( array_map( function( $cat ) {
    return $cat->term_id;
  }, $categories ) );

  $args = array(
    'posts_per_page' => 12,
    'category_name' => 'Coronavirus',
    'paged' => max( 1, get_query_var( 'paged' ) ),
  );

  $current_category = isset( $_GET['category'] ) ? array( sanitize_key( $_GET['category'] ) ) : null;
  if ( !empty( $_GET['category'] ) ) {
    $args['tag__in'] = $current_category ? $current_category : $cats;
  }

  $search = isset( $_GET['rs'] ) ? sanitize_text_field( $_GET['rs'] ) : '';
  if ( !empty( $_GET['rs'] ) ) {
    $args['s'] = $search;
    $args['posts_per_page'] = -1;
  }

  global $wp_query;
  $wp_query = new WP_Query( $args );

  if ( $wp_query->have_posts() ): ?>
    <?php while ( $wp_query->have_posts() ): $wp_query->the_post();
      cc_entry_markup();
    ?>
    <?php wp_reset_postdata(); endwhile; ?>
    <?php else: ?>
      <div class="entry no-reports">No Resources Found</div>
    <?php endif; ?>
    <?php
  endif;
}

add_action( 'genesis_after_content', function() {
  global $wp_query;
  global $old_query;
  $wp_query = NULL;
  $wp_query = $old_query;
  wp_reset_query();
} );

add_filter( 'genesis_attr_entry', 'cc_blog_attributes' );
function cc_blog_attributes( $attributes ) {
  global $wp_query;
  $color = $wp_query->current_post % 2 === 0 ? 'dark' : 'light';
  $attributes['class'] .= " post-list-entry color-$color";
  return $attributes;
}

genesis();
