<?php
/**
 * Template Name: SB State
 */

add_filter( 'cc_show_page_featured_background', '__return_false' );
remove_action( 'genesis_before', 'cc_before_site_inner_background' );
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action('genesis_before_content', 'es_sections');
function es_sections() {
  ?>
  <section id="state-hero" data-state="<?php echo stateAbbr(get_the_title()) ?>">
    <div class="pb-4 md:pt-8 lg:pt-12 xl:pt-24">
      <h1 class="font-semibold mb-8 text-center md:flex md:items-center md:justify-center lg:text-6xl xl:text-7xl xl:mb-16">
        <img class="h-16 inline-block md:h-20 md:mr-4" src="<?php echo get_the_post_thumbnail_url() ?>" alt="">
        <span class="md:inline-block">Save <?php echo the_title() ?> Small Businesses</span>
      </h1>
    </div>
  </section>

  <section>
    <div class="container-full blue-gradient md:flex">
      <div class="w-full relative md:-mr-124 z-10 lg:-mr-116">
        <img class="object-cover object-center w-full h-full md:object-left md:absolute md:inset-0 lg:object-center" src="<?php echo get_field('image')['url'] ?>" alt="">
      </div>
      <div class="md:w-3/4 md:flex-grow flex-shrink-0 relative z-20 bg-white my-12 md:my-16 md:border md:border-gray-300 md:border-solid md:shadow-md lg:w-3/5 lg:my-24 xl:w-1/2 xl:my-32">
        <div class="content-wrap px-8 relative w-full md:px-12 md:py-12 lg:p-20 lg:pr-12">
          <div class="relative z-20">
            <h2 class="lg:text-5xl xl:text-6xl"><?php echo get_field('title') ?></h2>
            <p class="xl:text-3xl"><?php echo get_field('content') ?></p>
            <a class="bg-c-teal-100 uppercase text-white px-16 py-4 inline-block mb-8 font-bold shadow-md rounded-md hover:opacity-75 md:mb-0" href="<?php echo get_field('link')['url']  ?>"><?php echo get_field('link')['title']  ?></a>
          </div>
          <!-- <img class="hidden w-auto absolute bottom-0 top-0 right-0 z-10 md:block md" src="<?php echo get_field('state bg')['url'] ?>" alt=""> -->
        </div>
      </div>
    </div>
  </section>

  <?php if(get_field('vid_show')) : ?>
  <section class="container-full bg-c-blue-50">
    <div class="wrap py-20 md:py-24">
      <div class="text-center">
        <h2 class="text-4xl xl:text-5xl"><?php echo get_field('vid_title') ?></h2>
        <p class="text-3xl md:mb-12"><?php echo get_field('vid_content') ?></p>
      </div>
      <div class="lg:max-w-6xl lg:mx-auto xl:max-w-8xl">
        <div class="embed-container">
          <iframe src='<?php echo get_field('video') ?>' frameborder='0' allowfullscreen></iframe>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <section>
    <div class="container-full bg-white py-20 lg:pt-24">
      <div class="wrap md:mb-12">
        <div class="md:flex md:space-x-16 md:space-x-reverse lg:space-x-24 lg:space-x-reverse">
          <div class="md:w-1/4 md:order-2 md:flex md:items-center xl:w-1/3">
            <img class="mb-8 w-full h-auto md:mb-0" src="<?php echo get_field('image 2')['url'] ?>" alt="">
          </div>
          <div class="sec-2-content mb-16 md:w-3/4 md:order-1 md:mb-0 xl:w-2/3">
            <?php echo get_field('content 2') ?>
          </div>
        </div>
      </div>

      <div class="wrap">
        <?php if(get_field('show stories')) : ?>
          <?php

            $post_state = get_the_title(); 
            //$state_value = get_field('state', 395812)['value'];
            $st = stateAbbr($post_state);
            //var_dump($st);

            $args = array(
              'category_name' => 'resilience',
              'posts_per_page' => -1,
              'meta_query'	=> array(
                'relation'		=> 'AND',
                array(
                  'key'	 	=> 'state',
                  'value'	  	=>  $st,
                  'compare' 	=> 'IN',
                ),
              ),
            );

            $the_query = new WP_Query( $args );
            //var_dump($wp_query->post_count)
            if( $the_query->have_posts() ): ?>
              <div class="story-carousel flickity-resize lg:pt-16">
              <?php while( $the_query->have_posts() ) : $the_query->the_post(); 
                if(get_post_format() == 'link') {
                  $external = get_field('external_link');
                }
                $internal = get_the_permalink();
              ?>
                <div class="carousel-cell w-full ml-8 md:flex md:flex-row md:space-x-12 lg:space-x-16 lg:ml-12">
                  <div class="mb-8 md:w-1/2 md:mb-0">
                    <?php $video_url = get_field('video_url'); ?>
                    <?php if($video_url) : ?>
                      <div class='embed-container'>
                        <iframe src='<?php echo $video_url ?>' frameborder='0' allowfullscreen></iframe>
                      </div>
                    <?php else : ?>
                      <div class='embed-container'>
                        <img class="object-cover object-center w-full h-full absolute" src="<?php echo get_the_post_thumbnail_url() ?>" alt="">
                      </div>
                    <?php endif; ?>
                  </div>
                  <div class="md:w-1/2 lg:flex lg:items-center">
                    <div>
                      <a href="<?php echo ($external) ? $external : $internal; ?>" target="<?php echo ($external) ? '_blank' : null; ?>">
                        <h4 class="font-bold text-c-blue-200 hover:text-c-teal-100 lg:text-4xl"><?php echo get_the_title() ?></h4>
                      </a>
                      <p class="mb-4 md:text-2xl lg:text-3xl"><?php echo get_the_excerpt() ?></p>
                      <a class="text-c-teal-100 flex items-center font-semibold" href="<?php echo ($external) ? $external : $internal; ?>" target="<?php echo ($external) ? '_blank' : null; ?>">
                        <span>Read More</span>
                        <svg class="fill-current h-6 w-6 ml-2" height="1" width="1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                      </a>
                    </div>
                  </div>
                </div>
                <?php
                  $external = '';
                  $internal = '';
                ?>
              <?php endwhile; ?>
              </div>
            <?php endif; ?>
            
            <?php wp_reset_query();
            
          ?>

        <?php endif; ?>

      </div>

    </div>
  </section>

  <section>
    <div class="container-full bg-center bg-cover" style="background-image:url('<?php echo get_field('bg 3')['url'] ?>')">
      <div class="wrap py-24 md:py-32 lg:py-40 xl:py-48">
        <h2 class="text-white text-center mb-12 lg:mb-24"><?php echo get_field('title 3')  ?></h2>
        <div class="flex flex-col space-y-8 md:space-y-16 lg:flex-row lg:space-y-0 lg:space-x-32 xl:space-x-40">
          <?php foreach(get_field('items 3') as $item) : ?>
          <div class="text-center lg:w-1/2 lg:text-left">
            <img class="mx-auto mb-8 lg:mb-16" src="<?php echo $item['icon']['url'] ?>" alt="">
            <div class="text-white">
              <?php echo $item['content'] ?>
            </div>      
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <section id="speak-out">
    <div class="container-full bg-c-blue-50">
      <div class="wrap py-24 lg:py-32">
        <div class="text-center mb-16 md:text-left">
          <h2 class="md:text-center md:mb-16"><?php echo get_field('title 4') ?></h2>
          <div><?php echo get_field('content 4') ?></div>
          <a class="inline-flex items-center text-c-teal-100 justify-center font-semibold md:justify-start" href="/small-business?state=<?php echo get_the_ID()?>">
            <span><?php echo get_field('letter link')['title'] ?></span>
            <svg class="fill-current h-6 w-6 ml-2" height="1" width="1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </a>
          <?php
          if(class_exists('gfapi')) {
            $form = get_field('form 4');
            $curr_state = stateAbbr(get_the_title());
            $search_criteria['field_filters'][] = array( 'key' => '6', 'value' => $curr_state );
            $total_count = 0; 
            $entries = GFAPI::get_entries($form, $search_criteria, null, null, $total_count);
            //var_dump($total_count);
            $set_count = get_field('signed count');
            if($set_count) {
              $total_count = $set_count;
            }
          }
          if($total_count > 0) :
          ?>
          <div class="block">
            <div class="stripe-bg border border-c-teal-200 border-solid px-8 py-3 inline-block rounded-md mt-8 md:px-12 md:py-4 md:inline-flex md:items-center">
              <svg class="inline h-8 w-8 mr-1 mb-1 md:mr-3 md:h-10 md:w-10" height="1" width="1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
              </svg>
              <span class="font-semibold text-2xl md:text-3xl"><?php echo $total_count . ' ' . get_field('signed text') ?></span>
            </div>
          </div>
          <?php endif; ?>
      </div>
      <div class="lg:flex lg:space-x-12">
        <img class="object-cover object-center w-full mb-12 md:h-124 lg:h-auto lg:w-2/5 lg:mb-0" src="<?php echo get_field('image 4')['url'] ?>" alt="">
        <div class="lg:w-3/5">
          <div class="mb-12">
            <?php

              gravity_form( $form, false, false, false, null, true, -1 ); 
            ?>
          </div>
          <?php if(get_field('state biz')) : ?>
          <div class="md:w-3/4 md:ml-auto">
            <a class="block text-c-teal-100 font-semibold xl:w-3/4" href="<?php echo get_field('state biz')['url'] ?>">
              <?php echo get_field('state biz')['title'] ?>
              <svg class="inline-block fill-current h-8 w-8 ml-2" height="1" width="1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </a>
          </div>
          <?php endif ?>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container-full bg-center bg-cover" style="background-image:url('<?php echo get_field('image 5')['url'] ?>')">
      <div class="wrap py-24 lg:py-64">
        <h2 class="text-white text-center mb-12 md:mb-20">
          <svg class="h-10 w-10 fill-current mr-1 inline-block lg:h-12 lg:w-12" height="1" width="1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" />
          </svg>
          <?php echo get_field('title 5') ?>
        </h2>
        <div class="a2a_kit flex flex-col justify-center items-center space-y-8 lg:space-y-0 lg:flex-row lg:space-x-12">
          <a class="a2a_button_twitter transition duration-200 bg-white px-12 py-5 w-2/3 rounded-md text-center shadow-md hover:opacity-75 hover:cursor-pointer md:w-1/3 lg:w-1/4 xl:w-1/5">
            <svg class="inline-block h-8 w-8 mr-2" height="1" width="1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 19.492"><path d="M132,258.315a10.318,10.318,0,0,1-2.467,2.543q.015.213.015.639a14,14,0,0,1-.578,3.952,14.327,14.327,0,0,1-1.759,3.785,14.892,14.892,0,0,1-2.81,3.2,12.5,12.5,0,0,1-3.929,2.223,14.364,14.364,0,0,1-4.919.83A13.659,13.659,0,0,1,108,273.284a10.425,10.425,0,0,0,1.188.061,9.627,9.627,0,0,0,6.106-2.1,4.931,4.931,0,0,1-4.6-3.412,6.211,6.211,0,0,0,.929.077,5.08,5.08,0,0,0,1.295-.168,4.834,4.834,0,0,1-2.825-1.7,4.751,4.751,0,0,1-1.119-3.129v-.061a4.868,4.868,0,0,0,2.223.624,4.914,4.914,0,0,1-1.6-1.751,4.931,4.931,0,0,1,.076-4.828,13.932,13.932,0,0,0,4.485,3.632,13.705,13.705,0,0,0,5.657,1.516,5.485,5.485,0,0,1-.122-1.127,4.924,4.924,0,0,1,8.513-3.366,9.634,9.634,0,0,0,3.122-1.188,4.773,4.773,0,0,1-2.162,2.711A9.782,9.782,0,0,0,132,258.315Z" transform="translate(-108 -256)" fill="#1da1f2"/></svg>
            <span class="text-c-blue-200 uppercase font-bold text-2xl">Share Tweet</span>
          </a>
          <a class="a2a_button_facebook transition duration-200 bg-white px-12 py-5 w-2/3 rounded-md text-center shadow-md hover:opacity-75 hover:cursor-pointer md:w-1/3 lg:w-1/4 xl:w-1/5">
            <svg class="inline-block h-8 w-8 mr-2" height="1" width="1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12.462 24"><path d="M491.462.173V3.981H489.2a2.119,2.119,0,0,0-1.673.519,2.391,2.391,0,0,0-.433,1.558V8.784h4.226l-.562,4.269h-3.664V24h-4.413V13.053H479V8.784h3.678V5.639a5.58,5.58,0,0,1,1.5-4.161A5.446,5.446,0,0,1,488.173,0,23.929,23.929,0,0,1,491.462.173Z" transform="translate(-479)" fill="#4267b2"/></svg>
            <span class="text-c-blue-200 uppercase font-bold text-2xl">Share Post</span>
          </a>

          <script>

            //var a2a_config = a2a_config || {};
            var a2a_config = a2a_config || {};
            a2a_config.templates = a2a_config.templates || {};
            a2a_config.linkname = 'Save <?php echo the_title() ?> Small Businesses';
            a2a_config.linkurl = '<?php echo get_permalink(); ?>';
            // a2a_config.templates = a2a_config.templates || {};

            a2a_config.templates.facebook = {
              app_id: "",
              redirect_uri: "",
              hashtag: "<?php echo get_field('fb hashtag') ?>",
              href: "${link}",
              quote: "<?php echo get_field('fb text') ?>"
            };

            a2a_config.templates.twitter = {
              text: "<?php echo get_field('twitter text') ?> ${link}",
            };

            //a2a.init('page');

          </script>
          <script async src="https://static.addtoany.com/menu/page.js"></script>
          <!-- <a class="a2a_button_email transition duration-200 bg-white px-12 py-5 w-2/3 rounded-md text-center shadow-md hover:opacity-75 hover:cursor-pointer md:w-1/3 lg:w-1/4 xl:w-1/5">
            <svg class="inline-block h-8 w-8 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 18.857"><path d="M24,262.08v10.634a2.148,2.148,0,0,1-2.143,2.143H2.143a2.068,2.068,0,0,1-1.514-.629A2.066,2.066,0,0,1,0,272.714V262.08a7.118,7.118,0,0,0,1.353,1.166q4.848,3.294,6.656,4.62.763.563,1.239.877a7.682,7.682,0,0,0,1.265.643,3.856,3.856,0,0,0,1.474.328h.026a3.856,3.856,0,0,0,1.474-.328,7.682,7.682,0,0,0,1.265-.643q.476-.315,1.239-.877,2.277-1.647,6.67-4.62A7.4,7.4,0,0,0,24,262.08Zm0-3.937a3.543,3.543,0,0,1-.656,2.022,6.382,6.382,0,0,1-1.634,1.648l-6.268,4.352q-.134.094-.569.409t-.723.509c-.192.129-.425.274-.7.435a4.005,4.005,0,0,1-.77.361,2.11,2.11,0,0,1-.67.121h-.026a2.11,2.11,0,0,1-.67-.121,4.005,4.005,0,0,1-.77-.361c-.272-.161-.505-.306-.7-.435s-.433-.3-.723-.509-.48-.346-.569-.409q-1.218-.857-3.509-2.444T2.3,261.813a6.646,6.646,0,0,1-1.567-1.547A3.132,3.132,0,0,1,0,258.438,2.7,2.7,0,0,1,.556,256.7a1.912,1.912,0,0,1,1.587-.7H21.857A2.16,2.16,0,0,1,24,258.143Z" transform="translate(0 -256)" fill="#f6a75d"/></svg>
            <span class="text-c-blue-200 uppercase font-bold text-2xl">Send Email</span>
          </a> -->
        </div>
      </div>
    </div>
  </section>

  <section>
    <?php

      $args2 = array(
        'category_name' => 'small-business',
        'posts_per_page' => -1,
        'meta_query'	=> array(
          'relation'		=> 'AND',
          array(
            'key'	 	=> 'state',
            'value'	  	=>  $st,
            'compare' 	=> 'IN',
          ),
        ),
      );

      $the_query2 = new WP_Query( $args2 );

      if( $the_query2->have_posts() ): ?>
      <div class="container-full bg-c-blue-50 py-24 pb-12 lg:pb-16 3xl:pb-40">
        <div class="wrap">
        <h2 class="text-center mb-8 md:mb-12 xl:mb-16">News</h2>
          <div class="news-carousel flickity-resize">
          <?php while( $the_query2->have_posts() ) : $the_query2->the_post(); 
            if(get_post_format() == 'link') {
              $external = get_field('external_link');
            }
            $internal = get_the_permalink();
          ?>
            <div class="carousel-cell w-full bg-white border border-gray-200 ml-8 border-solid rounded-md md:flex md:flex-row md:space-x-8 md:shadow-lg lg:ml-12">
              <div class="md:w-1/2 md:relative">
                <img class="object-cover object-center w-full h-64 md:h-full md:absolute" src="<?php echo get_the_post_thumbnail_url() ?>" alt="">
              </div>
              <div class="md:w-1/2">
                <div class="p-8 pb-12">
                  <a class="text-c-blue-200 hover:text-c-orange-100" href="<?php echo ($external) ? $external : $internal; ?>" target="<?php echo ($external) ? '_blank' : null; ?>">
                    <h3 class="md:text-4xl"><?php echo get_the_title() ?></h3>
                  </a>
                  <p class="mb-4 md:text-2xl lg:text-3xl"><?php echo get_the_excerpt() ?></p>
                  <a class="text-c-teal-100 flex items-center font-semibold" href="<?php echo ($external) ? $external : $internal; ?>" target="<?php echo ($external) ? '_blank' : null; ?>">
                    <span>Read More</span>
                    <svg class="fill-current h-6 w-6 ml-2" height="1" width="1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </a>
                </div>
              </div>
            </div>
            <?php
              $external = '';
              $internal = '';
            ?>
          <?php endwhile; ?>
          </div>
        </div>
      </div>
      <?php endif; ?>
      
      <?php wp_reset_query(); ?>
  </section>

  <section>
    <div class="container-full bg-c-teal-100">
      <div class="wrap py-20 md:max-w-4xl md:py-32 lg:max-w-5xl">
        <h2 class="text-white text-center mb-12 lg:mb-24 md:leading-normal"><?php echo get_field('title 7') ?></h2>
        <div>
          <?php
            $form2 = get_field('form 7');
            gravity_form( $form2, false, false, false, null, true, -1 ); 
          ?>
        </div>
      </div>
    </div>
  </section>

  <?php
}

genesis();
// END index.php //
