<?php
/**
 * Template Name: Virus
 */


remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action('genesis_before_content', 'es_virus_content');
function es_virus_content() {
  ?>
  <section>
    <div class="post-content">
      <?php 
      $page_id = get_queried_object_id();
      $post_object = get_post( $page_id );
      echo apply_filters('the_content', $post_object->post_content); ?>
    </div>
  </section>
  <?php
}

add_action('genesis_before_content', 'es_google_content');
function es_google_content() {
  if(get_field('show_google_resources') == 1) :
  ?>
  <section>
    <div class="bg-c-gray-200">
      <div class="py-12 wrap md:px-16 md:py-20 xl:pb-24 xl:px-32">
        <div class="flex flex-col mb-4 md:flex-row md:items-center md:mb-8">
          <h2 class="mb-2 md:mr-4"><?php echo get_field('google_title') ?></h2>
          <div>
            <img class="z-10 h-16" src="<?php echo get_field('logo')['url'] ?>" alt="">
          </div>
        </div>
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 lg:gap-12">
          <?php
            if ( have_rows( 'resources' ) ):
              while( have_rows('resources')) : the_row(); ?>
              <div>
                <a href="<?php echo get_sub_field('link')['url'] ?>" target="_blank">
                  <img class="md:object-cover md:object-center md:w-full md:h-116 lg:h-96 xl:h-116" src="<?php echo get_sub_field('image')['url'] ?>" alt="">
                </a>
                <a class="relative z-20 flex items-center justify-center w-4/5 py-5 ml-auto -mt-10 text-white bg-c-orange-100 group md:w-1/2 lg:w-3/5 xl:w-1/2" href="<?php echo get_sub_field('link')['url'] ?>" target="_blank">
                  <span class="mr-4 text-xl font-bold uppercase transition duration-200 group-hover:text-c-blue-400 xl:text-2xl"><?php echo get_sub_field('link')['title'] ?></span>
                  <svg class="w-10 h-10 text-white transition duration-200 transform group-hover:translate-x-px" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
                </a>
              </div>
            <?php endwhile;
            endif;
          ?>
        </div>
      </div>
    </div>
  </section>
  <?php
  endif;
}

add_action('genesis_before_content', 'es_join_content');
function es_join_content() {
  ?>
  <section>
    <div class="pt-20 pb-12 bg-white md:pt-16 md:pb-8 lg:pl-16 xl:pt-12 xl:pl-20 xl:pb-0">
      <div class="py-12 wrap md:px-16 md:py-20 xl:pb-24 xl:px-32">
        <div class="flex flex-col items-center border-4 border-gray-200 shadow-join lg:flex-row lg:justify-between"> 
          <div class="-mt-16 lg:mt-0 lg:flex-shrink-0 lg:-ml-16 xl:-ml-20">
            <div class="inline-block p-4 px-6 bg-c-blue-400">
              <img class="inline-block h-24 xl:h-32" src="<?php echo get_field('join_image')['url']; ?>" alt="">
            </div>
          </div>
          <div>
            <p class="p-8 pb-0 text-center md:pt-12 md:pb-4 md:text-3xl lg:text-left lg:mb-0 lg:p-16 xl:p-20 xl:text-4xl xl:mb-0"><?php echo get_field('join_text'); ?></p>
          </div>
          <div class="pb-12 lg:pb-0 lg:pr-12 lg:flex-shrink-0 xl:pr-16">
            <a class="button" href="<?php echo get_field('join_link')['url'] ?>"><?php echo get_field('join_link')['title'] ?></a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php
}

// add_action('genesis_before_content', 'es_resource_box');
// function es_resource_box() { ?>
<!--    <section class="bg-c-blue-200 md:pb-4">
        <div class="py-12 wrap xl:p-32">
//       <div class="flex flex-col lg:flex-row lg:items-center">
//         <div class="mb-12 text-white lg:w-1/2">
//           <h3><?php echo get_field('Title', 'options') ?></h3>
//           <p class="break-words"><?php echo get_field('Content', 'options') ?></p>
//           <div>
//             <form action="/covid19fundingfinder/" method="GET">
//               <div class="">
//                 <label for="virus-state" class="sr-only">State</label>
//                 <select name="state" required class="px-3 py-0 py-2 mb-8 text-3xl text-white border-2 border-white border-solid form-select bg-c-blue-200 focus:outline-none md:w-1/2 lg:w-3/4 lg:text-4xl xl:w-3/5">
//                   <option value="" disabled selected>Select your state</option>
//                   <option value="AL">Alabama</option>
//                   <option value="AK">Alaska</option>
//                   <option value="AZ">Arizona</option>
//                   <option value="AR">Arkansas</option>
//                   <option value="CA">California</option>
//                   <option value="CO">Colorado</option>
//                   <option value="CT">Connecticut</option>
//                   <option value="DE">Delaware</option>
//                   <option value="DC">District Of Columbia</option>
//                   <option value="FL">Florida</option>
//                   <option value="GA">Georgia</option>
//                   <option value="HI">Hawaii</option>
//                   <option value="ID">Idaho</option>
//                   <option value="IL">Illinois</option>
//                   <option value="IN">Indiana</option>
//                   <option value="IA">Iowa</option>
//                   <option value="KS">Kansas</option>
//                   <option value="KY">Kentucky</option>
//                   <option value="LA">Louisiana</option>
//                   <option value="ME">Maine</option>
//                   <option value="MD">Maryland</option>
//                   <option value="MA">Massachusetts</option>
//                   <option value="MI">Michigan</option>
//                   <option value="MN">Minnesota</option>
//                   <option value="MS">Mississippi</option>
//                   <option value="MO">Missouri</option>
//                   <option value="MT">Montana</option>
//                   <option value="NE">Nebraska</option>
//                   <option value="NV">Nevada</option>
//                   <option value="NH">New Hampshire</option>
//                   <option value="NJ">New Jersey</option>
//                   <option value="NM">New Mexico</option>
//                   <option value="NY">New York</option>
//                   <option value="NC">North Carolina</option>
//                   <option value="ND">North Dakota</option>
//                   <option value="OH">Ohio</option>
//                   <option value="OK">Oklahoma</option>
//                   <option value="OR">Oregon</option>
//                   <option value="PA">Pennsylvania</option>
//                   <option value="RI">Rhode Island</option>
//                   <option value="SC">South Carolina</option>
//                   <option value="SD">South Dakota</option>
//                   <option value="TN">Tennessee</option>
//                   <option value="TX">Texas</option>
//                   <option value="UT">Utah</option>
//                   <option value="VT">Vermont</option>
//                   <option value="VA">Virginia</option>
//                   <option value="WA">Washington</option>
//                   <option value="WV">West Virginia</option>
//                   <option value="WI">Wisconsin</option>
//                   <option value="WY">Wyoming</option>
//                 </select>
//               </div>
//               <input type="submit" class="w-full px-8 py-2 uppercase bg-c-orange-100" value="<?php echo get_field('Button Text', 'options'); ?>" />
//             </form>
//           </div>
//         </div>
//         <div class="lg:w-1/2">
//           <?php
//             $map_id = get_field('Map ID', 'options');
//             echo do_shortcode('[mapsvg id="' . $map_id . '"]') 
//           ?>
//         </div>
//       </div>
//     </div> -->

     <!-- <?php if(get_field('res_title')) : 
//       $res = get_posts(array(
//           'posts_per_page' => 1,
//           'category_name' => 'resilience'
//         )
//       );
//       //var_dump($res);  
//     ?>
//       <div class="bg-white">
//         <div class="relative flex flex-col pb-12 md:p-12 lg:flex-row xl:p-32 xl:pt-24">
//           <a href=""></a>
//             <h3 class="py-4 mb-0 font-bold text-center text-white bg-c-orange-100 md:py-6 lg:absolute lg:top-0 lg:right-0 lg:w-1/2 lg:mr-16 lg:mt-16 lg:text-5xl xl:mr-32 xl:mt-24"><?php echo get_field('res_title') ?></h3>
//           </a>
//           <div class="lg:w-1/2 lg:pt-16">
//             <img class="w-full h-auto lg:object-cover lg:h-full" src="<?php echo get_the_post_thumbnail_url($res[0]->ID) ?>" alt="">
//           </div>
//           <div class="p-8 md:px-0 lg:w-1/2 lg:pl-12 lg:pt-40 xl:pl-16">
//           <?php $link = (get_field('external_link', $res[0]->ID)) ? get_field('external_link', $res[0]->ID) : get_permalink($res[0]->ID)  ?>
//             <a <?php if(get_field('external_link', $res[0]->ID)) echo 'target="_blank" ' ?>class="text-c-blue-200 hover:text-c-orange-100" href="<?php echo $link ?>">
//               <h4 class="font-bold md:text-5xl"><?php echo get_the_title($res[0]->ID) ?></h4>
//             </a>
//             <p><?php echo get_the_excerpt($res[0]->ID) ?></p>
//             <div class="flex flex-col md:flex-row">
//               <a <?php if(get_field('external_link', $res[0]->ID)) echo 'target="_blank" ' ?>class="mb-8 button md:mb-0 md:mr-12" href="<?php echo $link ?>">Learn More</a>
//               <a class="inline-block px-16 py-4 font-bold leading-loose text-center uppercase border-2 border-c-orange-100 hover:bg-c-orange-100 hover:text-white" href="<?php echo get_field('res_link')['url'] ?>"><?php echo get_field('res_link')['title'] ?></a>
//             </div>
//           </div>
//         </div>
//       </div>
//     <?php endif; ?> -->

     <!-- <?php if(get_field('policy_title')) : ?>
//     <div class="pb-12 bg-white md:mx-12 md:pb-0 md:mb-12 md:mt-12">
//       <div class="flex flex-col md:flex-row">
//         <div class="md:w-1/2 md:order-2">
//           <img class="w-auto md:h-88 lg:h-96 xl:h-116" src="<?php echo get_field('image_right')['url'] ?>" alt="">
//         </div>
//         <div class="p-8 md:w-1/2 md:order-1 md:pb-4 xl:pl-20 xl:pt-16">
//           <a class="max-w-xs" href="<?php echo get_field('policy_link')['url']?>">
//             <img class="md:h-20 md:w-auto xl:h-24" src="<?php echo get_field('policy_logo')['url'] ?>" alt="">
//             <h3 class="text-c-orange-100"><?php echo get_field('policy_title') ?></h3>
//           </a>
//           <div class="mb-4 md:text-xl policy-content lg:text-2xl xl:text-3xl">
//             <?php echo get_field('policy_content')?>
//           </div>
//           <a class="flex items-center text-c-orange-100 group" href="<?php echo get_field('policy_link')['url']?>">
//             <span class="mr-4 font-bold uppercase xl:text-3xl"><?php echo get_field('policy_link')['title']?></span>
//             <svg class="w-8 h-8 text-c-orange-100 group-hover:text-c-blue-200" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8"><path d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg> 
//           </a>
//         </div>
//       </div>
//     </div>
//     <?php endif; ?> 
   </section> -->
   <?php
// }

add_action('genesis_before_content', 'es_virus_boxes');
function es_virus_boxes() {
  if ( have_rows( 'boxes' ) ):
    ?>
    <section class="virus-boxes md:pt-12 xl:pt-0">
      <div class="wrap">
        <div class="virus-grid">
          <?php while ( have_rows( 'boxes' ) ): the_row(); ?>
          <?php if ( $image = wp_get_attachment_image_src( get_sub_field( 'image' ), 'featured_rectangle_wide' ) ) ?>
          <a class="virus-link" href="<?php echo get_sub_field('url')['url'] ?>" target="_blank">
            <div class="virus-box">
              <img class="virus-box-img" src="<?php echo esc_url( $image[0] ); ?>" alt="" />
              <div class="virus-text">
                <div class="virus-icon" style="background-color:<?php echo get_sub_field('color') ?>">
                  <img class="mx-auto" src="<?php echo  get_sub_field( 'icon')['url']; ?>">
                </div>
                <h3 class="title">
                  <?php echo get_sub_field('url')['title'] ?>
                  <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </h3>
                <div class="virus-spacer"></div>
              </div>
            </div>
          </a>
          <?php endwhile; ?>
        </div>
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
  <form class="reports-search" action="<?php echo esc_url( get_permalink() ); ?>">
    <input class="search-input text-c-blue-400" placeholder="Search Resources" type="text" name="rs" value="<?php echo $search; ?>">
    <button class="button plain small"><i class="fas fa-search"></i></button>
    <input type="submit" style="display:none">
  </form>
  <?php
}

add_action( 'genesis_before_content', 'cc_posts_template_before_content' );
function cc_posts_template_before_content() {
  if ( $categories = get_field( 'Tags', 'option' ) ):
  $current_category = isset( $_GET['category'] ) ? sanitize_key( $_GET['category'] ) : '';
  $permalink = get_permalink();
  $permalink .= strpos( $permalink, '?' ) > -1 ? '&' : '?';
  ?>
  <div class="post-filters"><?php
    if ( $heading = get_field( 'Filter Title', 'option' ) ): ?>
      <h3 class="filter-heading"><?php echo wp_kses_post( $heading ); ?></h3><?php
    endif; ?>
    <div class="filter-container">
      <form action="<?php echo esc_url( get_permalink() ); ?>#virus-resources" method="GET">
      <input class="button filter<?php echo $current_category === '' ? ' selected' : '' ?>" type="submit" value="All">
      </form>
    <?php
    foreach ( $categories as $category ): ?>
      <form action="<?php echo esc_url( get_permalink() ); ?>#virus-resources" method="GET">
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


// add_action( 'genesis_loop', 'cc_virus' );
// function cc_virus() {
//   global $wp_query;
//   global $old_query;
//   $args = array(
//     'category_name' => 'Coronavirus',
//     //'post_type' => 'report',
//     'paged' => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
//     'posts_per_page' => 12,
//     // 'orderby' => 'date',
//     // 'order' => 'DESC',
//   );
//   $search = isset( $_GET['rs'] ) ? sanitize_text_field( $_GET['rs'] ) : '';
//   if ( !empty( $_GET['rs'] ) ) {
//     $args['s'] = $search;
//     $args['posts_per_page'] = -1;
//   }

//   $virus_query = new WP_Query( $args );
//   if ( $virus_query->have_posts() || isset( $_GET['rs'] ) ):
//   $old_query = $wp_query;
//   $wp_query = NULL;
//   $wp_query = $virus_query;


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

add_action( 'genesis_before_footer', 'es_before_footer_section', 99 );
function es_before_footer_section() {
  cc_form_footer_section();
}

genesis();
// END index.php //
