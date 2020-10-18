<?php
/**
 * Template Name: SBPB
 */

remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action('genesis_before_content', 'es_policy_content');
function es_policy_content() {
  ?>
  <section>
    <div class="bg-c-blue-200 flex flex-col">
      <div class="flex flex-col md:flex-row">
        <div class="order-2 p-8 md:w-1/2 md:order-1 md:p-16 md:py-24 lg:p-24 lg:pb-72">
          <img src="<?php echo get_field('Logo')['url'] ?>" alt="">
          <h2 class="text-white font-bold pt-4 max-w-sm lg:text-5xl xl:text-6xl xl:max-w-xl"><?php echo get_the_title() ?></h2>
        </div>
        <div class="order-1 py-32 bg-center bg-cover md:w-1/2 md:order-2 lg:z-20" style="background-image:url(<?php echo get_field('Background')['url'];?>);">
        </div>
      </div>
    </div>

    <div x-data="{ tab: 'tab-0' }" class="lg:-mt-56 lg:z-40 lg:relative">
      <div class="brief-icons flex lg:max-w-70 lg:mx-auto">
        <?php $briefs = get_field('Briefs');
          // var_dump($briefs);
          $count = 0;
          foreach($briefs as $item): 
          if(get_post_format($item->ID) == 'link'): ?>
            <a class="tab block w-1/4 border-2 border-r-0 border-b-0 border-white bg-c-blue-200 text-center focus:outline-none>" href="<?php echo get_field('external_link', $item->ID) ?>">
              <div class="flex justify-center py-4 md:pb-4 md:pt-0">
                <?php echo get_field('SVG', $item->ID); ?>
              </div>
              <span class="hidden normal-case md:block text-white font-bold"><?php echo $item->post_title ?></span>
            </a>
          <?php else: ?>
          <button class="tab w-1/4 border-2 border-r-0 border-b-0 border-white bg-c-blue-200 focus:outline-none" :class="{ 'active': tab === 'tab-<?php echo $count ?>' }" @click="tab = 'tab-<?php echo $count ?>'">
            <div class="flex justify-center py-4 md:pb-4 md:pt-0">
              <?php echo get_field('SVG', $item->ID); ?>
            </div>
            <span class="hidden normal-case md:block"><?php echo $item->post_title ?></span>
          </button>
          <?php endif; ?>
          <?php $count++; ?>
          <?php endforeach;
        ?>
      </div>

      <div class="tab-container bg-white lg:border-t-4 lg:border-c-orange-100 lg:px-16 xl:px-20">
        <?php $count2 = 0; ?>
        <?php foreach($briefs as $item): ?>
          <div x-show="tab === 'tab-<?php echo $count2 ?>'" class="p-8">
            <h3 class="mb-8 text-5xl md:hidden"><?php echo $item->post_title ?></h3>

            <div class="relative border-b border-c-orange-100 mb-8 md:border-t md:border-b-0 md:mt-20">
              <div class="inline-flex items-center relative">
                <span class="relative bg-c-orange-100 text-white uppercase font-bold z-30 text-lg p-4 pr-8 leading-none md:text-2xl md:text-center">Federal <br class="hidden md:block"/>Action</span>
                <svg class="h-full w-auto fill-current absolute right-0 text-c-orange-100 bg-white box-content z-20 stroke-current stroke-3" height="1" viewBox="0 0 31 110" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M1 2.807L29.928 55 1 107.193V2.807z" stroke="#f6a75d"></path>
                </svg>
              </div>
            </div>
            <div class="tab-content mb-8 md:ml-48 md:-mt-20 md:mb-24 lg:mb-32">
              <?php echo get_field('Federal', $item->ID); ?>
            </div>

            <div class="flex border-b border-c-orange-100 mb-8 md:border-t md:border-b-0">
            <div class="inline-flex items-center relative">
                <span class="relative bg-c-orange-100 text-white uppercase font-bold z-30 text-lg p-4 pr-8 leading-none md:text-2xl md:text-center">State <br class="hidden md:block"/>Action</span>
                <svg class="h-full w-auto fill-current absolute right-0 text-c-orange-100 bg-white box-content z-20 stroke-current stroke-3" height="1" viewBox="0 0 31 110" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M1 2.807L29.928 55 1 107.193V2.807z" stroke="#f6a75d"></path>
                </svg>
              </div>
            </div>
            <div class="tab-content mb-8 md:ml-48 md:-mt-20 md:mb-24 lg:mb-32">
              <?php echo get_field('State', $item->ID); ?>
            </div>
            
          </div>
          <?php $count2 ++; ?>
        <?php endforeach; ?>
      </div>
    </div>

  </section>
  <?php
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
