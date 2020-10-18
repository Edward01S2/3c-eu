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
          <h1><?php echo get_field('Archive Title', 'options') ?></h1>
          <div class="break-words"><?php echo get_field('Archive Content', 'options') ?></div>
        </div>
        <div class="order-1 pb-72 bg-center bg-cover md:pb-108 lg:w-1/2 lg:order-2" style="background-image:url('<?php echo get_field('Image', 'options')['url'] ?>');">
      </div>
      </div>
  </section>
  <?php
}

add_action('genesis_before_content', 'es_resource_box');
function es_resource_box() { ?>
  <section class="bg-c-blue-200">
    <div class="wrap py-8 md:py-12 xl:p-32">
      <div class="flex flex-col lg:flex-row lg:items-center">
        <div class="text-white mb-12 lg:pr-16 lg:w-1/2">
          <div class="break-words"><?php echo get_field('Content', 'options') ?></div>
          <div>
            <form action="/covid19fundingfinder/" method="GET">
              <div class="">
                <label for="virus-state" class="sr-only">State</label>
                <select id="state-fund" name="state" required class="form-select py-2 px-3 py-0 border-solid border-2 border-white bg-c-blue-200 text-3xl text-white mb-8 focus:outline-none md:w-1/2 lg:w-3/4 lg:text-4xl xl:w-3/5">
                  <option value="" disabled selected>Select your state</option>
                  <option value="AL">Alabama</option>
                  <option value="AK">Alaska</option>
                  <option value="AZ">Arizona</option>
                  <option value="AR">Arkansas</option>
                  <option value="CA">California</option>
                  <option value="CO">Colorado</option>
                  <option value="CT">Connecticut</option>
                  <option value="DE">Delaware</option>
                  <option value="DC">District Of Columbia</option>
                  <option value="FL">Florida</option>
                  <option value="GA">Georgia</option>
                  <option value="HI">Hawaii</option>
                  <option value="ID">Idaho</option>
                  <option value="IL">Illinois</option>
                  <option value="IN">Indiana</option>
                  <option value="IA">Iowa</option>
                  <option value="KS">Kansas</option>
                  <option value="KY">Kentucky</option>
                  <option value="LA">Louisiana</option>
                  <option value="ME">Maine</option>
                  <option value="MD">Maryland</option>
                  <option value="MA">Massachusetts</option>
                  <option value="MI">Michigan</option>
                  <option value="MN">Minnesota</option>
                  <option value="MS">Mississippi</option>
                  <option value="MO">Missouri</option>
                  <option value="MT">Montana</option>
                  <option value="NE">Nebraska</option>
                  <option value="NV">Nevada</option>
                  <option value="NH">New Hampshire</option>
                  <option value="NJ">New Jersey</option>
                  <option value="NM">New Mexico</option>
                  <option value="NY">New York</option>
                  <option value="NC">North Carolina</option>
                  <option value="ND">North Dakota</option>
                  <option value="OH">Ohio</option>
                  <option value="OK">Oklahoma</option>
                  <option value="OR">Oregon</option>
                  <option value="PA">Pennsylvania</option>
                  <option value="RI">Rhode Island</option>
                  <option value="SC">South Carolina</option>
                  <option value="SD">South Dakota</option>
                  <option value="TN">Tennessee</option>
                  <option value="TX">Texas</option>
                  <option value="UT">Utah</option>
                  <option value="VT">Vermont</option>
                  <option value="VA">Virginia</option>
                  <option value="WA">Washington</option>
                  <option value="WV">West Virginia</option>
                  <option value="WI">Wisconsin</option>
                  <option value="WY">Wyoming</option>
                </select>
              </div>
              <input type="submit" class="bg-c-orange-100 uppercase px-8 py-2 w-full" value="<?php echo get_field('Button Text', 'options'); ?>" />
            </form>
          </div>
        </div>
        <div class="lg:w-1/2">
          <?php
            $map_id = get_field('Map ID', 'options');
            echo do_shortcode('[mapsvg id="' . $map_id . '"]') 
          ?>
        </div>
      </div>
    </div>
  </section>
  <?php
}

add_action( 'genesis_before_content', 'es_resource_aids' );
function es_resource_aids() {
  if(isset($_GET['state'])) {
    $state = $_GET['state'];

    $state_args = array(
      'post_type' => 'resource',
      'posts_per_page' => -1,
      'meta_query' => array(
        array(
          'key'  => 'State',
          'value' => $state,
        ),
        array(
          'key' => 'Aid Type',
          'value' => 'State'
        )
      )
    );
  } 

  $state_query = (isset($_GET['state'])) ? get_posts($state_args) : null;


  ?>
  <section class="resource-section bg-c-gray-100">
    <div class="pb-12 md:px-12 md:pt-12 xl:px-32">
      <div class="sticky w-full top-0 bg-white z-50 shadow-xs shadow py-4 px-4 md:py-0">
        <div class="flex flex-col md:flex-row md:justify-center">
          <div class="flex items-center">
            <div class="text-xl w-1/4 md:w-auto md:mr-4">Sort by:</div>
            <select name="" id="aid-type" class="form-select bg-c-gray-100 text-2xl border border-gray-300 border-solid md:hidden">
              <option value="all">All</option>
              <option value="federal">Federal</option>
              <?php if($state_query) : ?><option value="state">State</option> <?php endif; ?>
              <option value="private">Private</option>
            </select>
          </div>
          <div id="resource-nav" class="hidden grid grid-cols-3 gap-4 md:block md:mr-8 lg:mr-32">
            <a class="inline-block text-center text-c-blue-200 font-semibold px-4 py-4 hover:text-c-orange-100" href="#federal-aid">Federal</a>
            <?php if($state_query) : ?><a class="inline-block text-center text-c-blue-200 font-semibold px-4 py-4" href="#state-aid">State</a><?php endif; ?>
            <a class="inline-block text-center text-c-blue-200 font-semibold px-4 py-4" href="#private-aid">Private</a>
          </div>
          <div class="flex items-center mt-4 md:mt-0">
            <div class="text-xl w-1/4 md:w-auto md:mr-4">Type:</div>
            <select name="" id="aid-filter" class="form-select bg-c-gray-100 text-2xl border border-gray-300 border-solid md:hidden">
              <option value="all">All</option>
              <option value="grant">Grant</option>
              <option value="loan">Loan</option>
            </select>
            <div id="type-btns" class="hidden grid grid-cols-3 gap-4 md:block">
              <button id="all-btn" class="filter-button type-active">All</button>
              <button id="grant-btn" class="filter-button">Grant</button>
              <button id="loan-btn" class="filter-button">Loan</button>
            </div>
          </div>
        </div>
      </div>
      

      <div class="aid-container bg-c-gray-100 pt-16">

      <?php $fed_args = array(
          'post_type' => 'resource',
          'posts_per_page' => -1,
          'meta_key'  => 'Aid Type',
          'meta_value' => 'Federal',
        );
        $federal = get_posts($fed_args);
        if($federal):
        ?>
        <div id="federal-aid" class="resource-aid pb-16">
          <h2 class="text-center mb-16">Federal Aid</h2>
          <div id="aid-grid" class="px-8 grid grid-cols-1 gap-12 md:px-0 lg:grid-cols-2 lg:gap-16"> 
            <?php
            foreach($federal as $x) :
              if(get_post_format($x->ID) == 'link') {
                $link = get_field('external_link', $x->ID);
              } else {
                $link = get_permalink($x->ID);
              }
              $fed_tags = get_the_terms($x->ID, 'tag');

              $terms = [];
              if($fed_tags) {
                foreach($fed_tags as $tag) {
                  ($tag->name == 'Grant') ? array_push($terms, $tag->slug) : false;
                  ($tag->name == 'Loan') ? array_push($terms, $tag->slug)  : false;
                }
              }
              //var_dump($terms);

              $terms_list = ($terms) ? implode(" ", $terms) . ' ' : '';
              // echo $terms_list;
              
            ?>
              <a id="aid-item" class="<?php echo $terms_list ?>border-l-8 border-c-blue-200 bg-white transform transition-transform duration-300 hover:scale-105" href="<?php echo $link ?>" target="_blank">
                <div class="p-8">
                  <h4 class="text-c-blue-200 font-semibold mb-2"><?php echo $x->post_title ?></h4>
                  <p class="text-c-blue-200 leading-normal mb-0"><?php echo $x->post_excerpt?></p>
                </div>
              </a>
            <?php
            $terms = [];
            endforeach;
            wp_reset_postdata();
            ?>
          </div>
        </div>
          <?php endif;

        if($state_query):
        ?>
        <div id="state-aid" class="resource-aid pb-16">
          <h2 class="text-center text-c-blue-100 mb-16">State Aid</h2>
          <div id="aid-grid-2" class="px-8 grid grid-cols-1 gap-12 md:px-0 lg:grid-cols-2 lg:gap-16"> 
            <?php
            foreach($state_query as $x) :
              if(get_post_format($x->ID) == 'link') {
                $link = get_field('external_link', $x->ID);
              } else {
                $link = get_permalink($x->ID);
              } 

              $state_tags = get_the_terms($x->ID, 'tag');

              $terms = [];
              if($state_tags) {
                foreach($state_tags as $tag) {
                  ($tag->name == 'Grant') ? array_push($terms, $tag->slug) : false;
                  ($tag->name == 'Loan') ? array_push($terms, $tag->slug)  : false;
                }
              }
              //var_dump($terms);
      
              $terms_list = ($terms) ? implode(" ", $terms) . ' ' : '';
            ?>
              <a id="aid-item" class="<?php echo $terms_list ?>border-l-8 border-c-blue-100 bg-white transform transition-transform duration-300 hover:scale-105" href="<?php echo $link ?>" target="_blank">
                <div class="p-8">
                  <h4 class="text-c-blue-100 font-semibold mb-2"><?php echo $x->post_title ?></h4>
                  <p class="text-c-blue-200 leading-normal mb-0"><?php echo $x->post_excerpt?></p>
                </div>
              </a>
            <?php
            $terms = [];
            endforeach;
            wp_reset_postdata();
            ?>
          </div>
        </div>
        <?php endif; ?>
        
        <?php $private_args = array(
          'post_type' => 'resource',
          'posts_per_page' => -1,
          'meta_key'  => 'Aid Type',
          'meta_value' => 'Private',
        );
        $private = get_posts($private_args);
        if($private):
        ?>
        <div id="private-aid" class="resource-aid pb-16">
          <h2 class="text-center text-c-blue-300 mb-16">Private Aid</h2>
          <div id="aid-grid-3" class="px-8 grid grid-cols-1 gap-12 md:px-0 lg:grid-cols-2 lg:gap-16"> 
            <?php
            foreach($private as $x) :
              if(get_post_format($x->ID) == 'link') {
                $link = get_field('external_link', $x->ID);
              } else {
                $link = get_permalink($x->ID);
              }
              $priv_tags = get_the_terms($x->ID, 'tag');

              $terms = [];
              if($priv_tags) {
                foreach($priv_tags as $tag) {
                  ($tag->name == 'Grant') ? array_push($terms, $tag->slug) : false;
                  ($tag->name == 'Loan') ? array_push($terms, $tag->slug)  : false;
                }
              }
              //var_dump($terms);

              $terms_list = ($terms) ? implode(" ", $terms) . ' ' : '';
              // echo $terms_list; 
            ?>
              <a id="aid-item" class="<?php echo $terms_list ?>border-l-8 border-c-blue-300 bg-white transform transition-transform duration-300 hover:scale-105" href="<?php echo $link ?>" target="_blank">
                <div class="p-8">
                  <h4 class="text-c-blue-300 font-semibold mb-2"><?php echo $x->post_title ?></h4>
                  <p class="text-c-blue-200 leading-normal mb-0"><?php echo $x->post_excerpt?></p>
                </div>
              </a>
            <?php
            $terms = [];
            endforeach;
            wp_reset_postdata();
            ?>
          </div>
        </div>
        <?php endif; ?>

      </div>
    </div>
  </section>
  <?php
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
