<?php
/**
 * Template Name: Digital Makeover
 */

add_filter( 'cc_show_page_featured_background', '__return_false' );
remove_action( 'genesis_before', 'cc_before_site_inner_background' );
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action('genesis_after_header', 'es_sections');
function es_sections() {
  ?>
  <section class="video-section relative">
    <video id="video" class="object-cover absolute inset-0 w-full h-full z-20" autoplay playsinline muted loop
      data-desktop-poster="<?php echo get_field( 'video poster' ) ; ?>"
      data-desktop-src="<?php echo get_field('video bg'); ?>"<?php
      if ( $mobile_video_poster = get_field( 'video_poster' ) ) {
        echo sprintf( 'data-mobile-poster="%s"', $mobile_video_poster );
      }
      if ( $mobile_video_url = get_field( 'video bg' ) ) {
        echo sprintf( 'data-mobile-src="%s"', $mobile_video_url );
      }
      ?>>
    </video>
    <div class="wrap relative z-30">
      <div class="text-white py-24 pb-32 md:max-w-3xl lg:max-w-2xl">
        <img class="mb-4 md:h-24" src="<?php echo get_field('logo')['url'] ?>" alt="">
        <h1 class="xl:text-7xl"><?php echo get_field('title') ?></h1>
        <p><?php echo get_field('content') ?></p>
        <a class="bg-c-orange-100 text-white uppercase font-semibold py-3 px-4 inline-flex items-center border border-c-orange-100 mr-4 hover:bg-c-blue-200 hover:border-c-blue-200" href="<?php echo get_field('video')['url'] ?>" data-lity>
          <svg class="fill-current text-white h-12 w-12 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
          </svg>
          <?php echo get_field('video')['title'] ?>
        </a>
        <a class="inline-flex items-center uppercase font-semibold text-white border-white border py-3 px-4 hover:bg-c-blue-200 hover:border-c-blue-200" href="<?php echo get_field('info')['url'] ?>">
          <svg class="fill-current text-white h-12 w-12 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" />
          </svg>
          <?php echo get_field('info')['title'] ?>
        </a>
      </div>
    </div>
  </section>
<?php 
}

add_action('genesis_before_content', 'es_sections_2');
function es_sections_2() {
  ?>
  <section id="section-2">
    <div class="py-12 xl:py-24">
      <div class="prose text-black text-center max-w-none lg:max-w-7xl lg:mx-auto xl:max-w-none xl:w-10/12">
        <?php echo get_field('content_1') ?>
      </div>
    </div>
  </section>

  <section class="container-full bg-gray-50">
    <div class="py-24 pb-0 lg:pt-12">
      <div class="study-carousel wrap relative pb-16 flickity-resize lg:pb-12">
        <?php 
        $slides = get_field('slides');
        foreach($slides as $slide) : ?>
          <div class="carousel-cell w-full flex flex-col mr-12 lg:flex-row  md:mr-16 lg:mr-24 lg:space-x-12 lg:space-x-reverse xl:mr-40">
            <div class="lg:order-1 lg:w-1/2 lg:relative">
              <div class="w-full h-full">
              <!-- <a class="block relative group transition duration-300 w-full h-full lg:static" href="<?php echo $slide['cs_image']['url'] ?>" data-lity> -->
                <div class="flex items-center justify-center w-full h-full">
                  <img class="lg:z-10" src="<?php echo $slide['cs_image']['url'] ?>" alt="">
                </div>
                <!-- <div class="absolute w-full h-full bg-black opacity-50 inset-0 z-20"></div> -->
                <?php if($slide['zoom']) : ?>
                <!-- <div class="absolute inset-0 w-full h-full flex items-center justify-center z-30">
                  <div class="flex items-center uppercase text-c-orange-100 bg-white rounded-full border border-c-orange-100 px-4 py-2 font-semibold transition duration-300 group-hover:bg-c-orange-100 group-hover:text-white">
                    <svg class="fill-current h-12 w-12 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Zoom In
                  </div>
                </div> -->
                <?php endif; ?>
              </div>
              <!-- </a> -->
            </div>
            <div class="text-black pt-8 lg:order-0 lg:w-1/2 lg:pb-16 lg:flex lg:items-center xl:pr-24">
              <div>
                <div class="h-1 w-24 bg-c-orange-100 my-8 mb-8"></div>
                <h3 class="text-c-orange-100"><?php echo $slide['cs_title'] ?></h3>
                <p><?php echo $slide['cs_content'] ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section>
    <div class="py-20 pb-12 xl:py-24">
      <h3 class="text-c-orange-100 text-center mb-12 xl:mb-20"><?php echo get_field('comp title') ?></h3>
      <div class="flex flex-wrap justify-center xl:justify-between">
        <?php 
        $companies = get_field('companies');
        foreach($companies as $item) : ?>
          <a class="inline-block mr-12 mb-12 xl:mr-0" href="<?php echo $item['link'] ?>" target="_blank">
            <img class="h-12 lg:mr-16" src="<?php echo $item['logo']['url'] ?>" alt="">
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="container-full bg-blue-1000 lg:pb-72 xl:pb-64">
    <div class="wrap py-24 xl:py-32">
      <div class="prose text-white text-center max-w-none lg:max-w-7xl lg:mx-auto xl:max-w-none xl:w-10/12">
        <?php echo get_field('content_5') ?>
      </div>
    </div>
  </section>
  
  <section class="container-full bg-gray-50 lg:bg-white lg:w-full lg:mx-0 lg:inset-0 lg:relative lg:-mt-64">
    <div class="wrap py-24 pb-20 lg:bg-gray-50">
      <img class="mx-auto mb-8 xl:mb-12" src="<?php echo get_field('logo_6')['url'] ?>" alt="">
      <div class="prose max-w-none text-center lg:max-w-6xl lg:mx-auto">
        <?php echo get_field('content_6') ?>          
      </div>
    </div>
  </section>

<?php
}

genesis();
// END index.php //
