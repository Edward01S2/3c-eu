<?php
// lib/common.php //

function cc_encode_twitter( $text ) {
  return htmlspecialchars(urlencode(html_entity_decode($text, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');
}

function cc_starts_with( $haystagct, $needle ) {
  return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

function cc_end_with( $haystack, $needle ) {
  return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

function cc_heading( $heading ) {
  if ( $heading ):
  ?>
  <div class="heading-container">
    <h2 class="heading"><?php echo wp_kses_post( $heading ); ?></h2>
  </div>
  <?php
  endif;
}

function cc_button( $text, $link ) {
  if ( $text && $link ):
  ?>
  <div class="button-container">
    <a class="button" href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $text ); ?></a>
  </div>
  <?php
  endif;
}

function cc_image_url( $image_id, $size = 'hero' ) {
  $image_array = wp_get_attachment_image_src( $image_id, $size );
  return $image_array !== false ? $image_array[0] : '';
}

function cc_form( $form ) {
  // echo $form['id'];
  gravity_form( $form['id'], false, false, false, null, true, -1 );
  //echo $form['id'];
}

function cc_is_post_list_page() {
  return is_archive() || is_category() || is_home() || is_front_page() || is_page_template( 'templates/front.php' );
}

function cc_background_style_tag( $element, $url ) {
  if ( $url ) {
    echo sprintf( '<style>%s{background-image:url(%s);}</style>', $element, $url );
  }
}

function stateAbbr($state) {
  $states = array(
    'Alabama'=>'AL',
    'Alaska'=>'AK',
    'Arizona'=>'AZ',
    'Arkansas'=>'AR',
    'California'=>'CA',
    'Colorado'=>'CO',
    'Connecticut'=>'CT',
    'Delaware'=>'DE',
    'District of Columbia' => 'DC',
    'Florida'=>'FL',
    'Georgia'=>'GA',
    'Hawaii'=>'HI',
    'Idaho'=>'ID',
    'Illinois'=>'IL',
    'Indiana'=>'IN',
    'Iowa'=>'IA',
    'Kansas'=>'KS',
    'Kentucky'=>'KY',
    'Louisiana'=>'LA',
    'Maine'=>'ME',
    'Maryland'=>'MD',
    'Massachusetts'=>'MA',
    'Michigan'=>'MI',
    'Minnesota'=>'MN',
    'Mississippi'=>'MS',
    'Missouri'=>'MO',
    'Montana'=>'MT',
    'Nebraska'=>'NE',
    'Nevada'=>'NV',
    'New Hampshire'=>'NH',
    'New Jersey'=>'NJ',
    'New Mexico'=>'NM',
    'New York'=>'NY',
    'North Carolina'=>'NC',
    'North Dakota'=>'ND',
    'Ohio'=>'OH',
    'Oklahoma'=>'OK',
    'Oregon'=>'OR',
    'Pennsylvania'=>'PA',
    'Rhode Island'=>'RI',
    'South Carolina'=>'SC',
    'South Dakota'=>'SD',
    'Tennessee'=>'TN',
    'Texas'=>'TX',
    'Utah'=>'UT',
    'Vermont'=>'VT',
    'Virginia'=>'VA',
    'Washington'=>'WA',
    'West Virginia'=>'WV',
    'Wisconsin'=>'WI',
    'Wyoming'=>'WY'
  );

  return $states[$state];
}

//Filter blog page of Coronavirus LOL
// function be_exclude_category_from_blog( $query ) {
	
// 	if( $query->is_main_query() && ! is_admin() && $query->is_home() ) {

//     $query->set('posts_per_page', 9);
// 	}
// }
// add_action( 'pre_get_posts', 'be_exclude_category_from_blog' );

// END lib/common.php //
