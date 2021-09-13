<?php
/**
 * Template Name: Voice
 */

add_filter( 'cc_show_page_featured_background', '__return_false' );
remove_action( 'genesis_before', 'cc_before_site_inner_background' );
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action('genesis_before_content', 'es_sections');
function es_sections() {

?>
  <section>
    <div class="">
      <div class="text-center text-white bg-center bg-cover" style="background-image:url(<?php echo get_the_post_thumbnail_url() ?>);">
        <div class="px-8 py-40 mx-auto md:px-12 max-w-8xl xl:px-16">
          <h1 class="mb-8 font-semibold text-center md:flex md:items-center md:justify-center lg:text-6xl xl:text-7xl">
              <?php echo get_field('title') ?>
          </h1>
          <p class="text-3xl font-semibold md:text-4xl"><?php echo get_field('subtitle') ?></p>
        </div>
      </div>
    </div>
  </section>

  <section class="p-8 bg-white md:p-12 xl:p-16">
    <div class="flex flex-col space-y-16 xl:flex-row xl:space-y-0 xl:space-x-20">
      <div class="xl:w-1/2">
        <div>
          <?php echo get_field('content') ?>
          <a href="#voice-letter" class="button voice-letter">SIGN THE LETTER</a>
        </div>
      </div>
      <div class="xl:w-1/2">
        <h3 class="font-bold"><?php echo get_field('letter_title') ?></h3>
        <div class="p-8 bg-c-gray-200 md:p-12 xl:p-16">
          <?php echo get_field('letter') ?>
        </div>
      </div>
    </div>
  </section>

  <section id="voice-letter" class="p-8 mb-24 bg-c-blue-400 md:p-12 xl:p-16">
    <div class="text-white">
      <h3 class="text-center"><?php echo get_field('form_title') ?></h3>
      <div class="voice-form xl:max-w-[100rem] xl:mx-auto">
        <?php
          $form = get_field('form')['id'];
          $form = ($form) ? $form : 24; 
          gravity_form( $form, false, false, false, null, true, -1 ); 
        ?>
      </div>
    </div>
  </section>

<?php
}

genesis();
// END index.php //