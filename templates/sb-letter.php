<?php
/**
 * Template Name: SB Letter
 */

add_filter( 'cc_show_page_featured_background', '__return_false' );
remove_action( 'genesis_before', 'cc_before_site_inner_background' );
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action('genesis_before_content', 'es_sections');
function es_sections() {
  $state = isset( $_GET['state'] ) ? sanitize_key( $_GET['state'] ) : null;
  if ( !empty( $_GET['state'] ) ) {
    $state_id = (int)$state;
  }
  //var_dump($state_id);
  $title = ($state) ? get_the_title($state_id) : null;
  $letter = get_field('letter', $state_id);
  $svg = ($state) ? get_the_post_thumbnail_url($state_id) : null;
  $bg = get_the_post_thumbnail_url();

  //var_dump($bg);
?>
  <section id="state-hero" data-state="<?php echo stateAbbr($title) ?>">
    <div class="pb-4 pt-12 lg:pt-16 lg:pb-12 xl:pt-24">
      <h1 class="font-semibold mb-8 text-center md:flex md:items-center md:justify-center lg:text-6xl xl:text-7xl">
        <?php if($svg) :?>
          <img class="h-16 inline md:h-20 md:mr-4" src="<?php echo $svg ?>" alt="">
        <?php endif; ?>
        <span class="inline">Save <?php echo $title ?> Small Businesses</span>
      </h1>
    </div>
  </section>
  
  <section>
    <div class="container-full bg-center bg-cover" style="background-image: url('<?php echo $bg ?>');">
      <div class="wrap">
        <h1 class="text-white text-center text-5xl mb-0 py-24 md:py-40 lg:text-6xl">Our letter to<?php echo ' ' . $title ?> policy makers:</h1>
      </div>
    </div>
  </section>

  <?php if($state_id) : ?>
  <section>
    <div class="pb-16 lg:max-w-6xl lg:mx-auto lg:pb-24 lg:pt-16 xl:max-w-7xl">
      <a class="inline-block text-c-teal-100 font-bold py-8 mb-0 lg:pb-12" href="/<?php echo $title ?>">
        [ 
        <svg class="inline-block h-6 w-6" xmlns="http://www.w3.org/2000/svg" width="1" height="1" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Back ]
      </a>
      <?php echo $letter ?>
    </div>
  </section>
  <?php endif; ?>

  <section>
    <div class="container-full bg-c-blue-50">
      <div class="wrap py-24 md:py-32">
        <div class="lg:max-w-5xl lg:mx-auto">
          <h3 class="text-center mb-12 md:mb-16">Add your name</h3>
          <div>
            <?php
              $form = get_field('letter form', $state_id);
              $form = ($form) ? $form : 17; 
              gravity_form( $form, false, false, false, null, true, -1 ); 
            ?>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php
}

genesis();
// END index.php //