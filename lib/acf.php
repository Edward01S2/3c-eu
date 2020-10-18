<?php 

use StoutLogic\AcfBuilder\FieldsBuilder;

if (class_exists('acf')) {
acf_add_options_sub_page( array(
  'page_title' 	=> 'Resource Settings',
  'menu_title' 	=> 'Settings',
  'menu_slug' => 'resource-settings',
  'parent_slug' 	=> 'edit.php?post_type=resource',
) );
}

if(class_exists('gfapi')) {
  $forms = GFAPI::get_forms();
  $choices= [];
  foreach($forms as $form) {
      $choices[] = [
          $form['id'] => $form['title']
      ];
  }
}
//var_dump($choices);

$gravityforms = new FieldsBuilder('gravityforms');
$gravityforms
    ->addSelect('form', [
        'label' => 'Select Form',
        'choices' => $choices,
]);

$funding = new FieldsBuilder('funding', [
  'menu_order' => 1,
]);
$funding
    ->addTab('Form')
    ->addText('Title')
    ->addWysiwyg('Content')
    ->addText('Button Text')
    ->addTab('Archive')
    ->addText('Archive Title', [
      'label' => 'Title'
    ])
    ->addWysiwyg('Archive Content', [
      'label' => 'Content'
    ])
    ->addImage('Image')
    ->addTab('Map')
    ->addText('Map ID')
    ->addTab('Join')
    ->addText('Form Text')
    ->addFields($gravityforms)
    ->addImage('Form Image', ['return_format' => 'id'])
    ->addTab('Filter')
    ->addText('Filter Title')
    ->addTaxonomy('Tags', [
      'taxonomy' => 'post_tag',
      'return_format' => 'object',
    ])
    ->setLocation('options_page', '==', 'resource-settings');

add_action('acf/init', function() use ($funding) {
   acf_add_local_field_group($funding->build());
});

$states = array(
  'AL'=>'Alabama',
  'AK'=>'Alaska',
  'AZ'=>'Arizona',
  'AR'=>'Arkansas',
  'CA'=>'California',
  'CO'=>'Colorado',
  'CT'=>'Connecticut',
  'DE'=>'Delaware',
  'DC'=>'District of Columbia',
  'FL'=>'Florida',
  'GA'=>'Georgia',
  'HI'=>'Hawaii',
  'ID'=>'Idaho',
  'IL'=>'Illinois',
  'IN'=>'Indiana',
  'IA'=>'Iowa',
  'KS'=>'Kansas',
  'KY'=>'Kentucky',
  'LA'=>'Louisiana',
  'ME'=>'Maine',
  'MD'=>'Maryland',
  'MA'=>'Massachusetts',
  'MI'=>'Michigan',
  'MN'=>'Minnesota',
  'MS'=>'Mississippi',
  'MO'=>'Missouri',
  'MT'=>'Montana',
  'NE'=>'Nebraska',
  'NV'=>'Nevada',
  'NH'=>'New Hampshire',
  'NJ'=>'New Jersey',
  'NM'=>'New Mexico',
  'NY'=>'New York',
  'NC'=>'North Carolina',
  'ND'=>'North Dakota',
  'OH'=>'Ohio',
  'OK'=>'Oklahoma',
  'OR'=>'Oregon',
  'PA'=>'Pennsylvania',
  'RI'=>'Rhode Island',
  'SC'=>'South Carolina',
  'SD'=>'South Dakota',
  'TN'=>'Tennessee',
  'TX'=>'Texas',
  'UT'=>'Utah',
  'VT'=>'Vermont',
  'VA'=>'Virginia',
  'WA'=>'Washington',
  'WV'=>'West Virginia',
  'WI'=>'Wisconsin',
  'WY'=>'Wyoming',
);

$resource = new FieldsBuilder('resource', [
  'position' => 'side'
]);

$resource
    ->addSelect('Aid Type', [
      'return_format' => 'value',
      'choices'=> array(
        'Federal' => 'Federal',
        'State' => 'State',
        'Private' => 'Private'
      ),
    ])
    ->addSelect('State', [
      'return_format' => 'array',
      'choices'=> $states,
    ])
    ->conditional('Aid Type', '==', 'State')
    ->setLocation('post_type', '==', 'resource');

add_action('acf/init', function() use ($resource) {
   acf_add_local_field_group($resource->build());
});

//SBPB Template Page
// acf_add_options_sub_page( array(
//   'page_title' 	=> 'Brief Settings',
//   'menu_title' 	=> 'Settings',
//   'menu_slug' => 'brief-settings',
//   'parent_slug' 	=> 'edit.php?post_type=brief',
// ) );

$brief = new FieldsBuilder('Briefings', [
  'style' => 'seamless'
]);

$brief
  ->addTab('Federal')
  ->addWysiwyg('Federal')
  ->addTab('State')
  ->addWysiwyg('State')
  ->addTab('SVG')
  ->addTextarea('SVG')
  ->setLocation('post_type', '==', 'brief');

add_action('acf/init', function() use ($brief) {
   acf_add_local_field_group($brief->build());
});

$sbpb = new FieldsBuilder('SBPB', [
  'hide_on_screen' => [
    'the_content',
  ]
]);

$sbpb
  ->addImage('Logo')
  ->addImage('Background')
  ->addRelationship('Briefs', [
    'max' => 4,
    'post_type' => 'brief'
  ])
  ->setLocation('page_template', '==', 'templates/sbpb.php');

add_action('acf/init', function() use ($sbpb) {
   acf_add_local_field_group($sbpb->build());
});

if (class_exists('acf')) {
acf_add_options_sub_page( array(
  'page_title' 	=> 'Standup Settings',
  'menu_title' 	=> 'Settings',
  'menu_slug' => 'Standup-settings',
  'parent_slug' 	=> 'edit.php?post_type=letter',
) );
}

//Small Business State Template


$section_1 = new FieldsBuilder('section_1', [
  'title' => 'Section 1',
  'position' => 'acf_after_title',
  'hide_on_screen' => [
    'the_content',
  ]
]);

$section_1
    ->addText('title')
    ->addTextarea('content')
    ->addLink('link')
    ->addImage('image')
    ->addImage('state bg')
    ->setLocation('post_template', '==', 'templates/sb-state.php');

add_action('acf/init', function() use ($section_1) {
   acf_add_local_field_group($section_1->build());
});

$vid_section = new FieldsBuilder('vid_section', [
  'title' => 'Video Section',
  'position' => 'acf_after_title',
  'hide_on_screen' => [
    'the_content',
  ]
]);

$vid_section
    ->addTrueFalse('vid_show', [
      'ui' => '1'
    ])
    ->addText('vid_title')
    ->addTextarea('vid_content')
    ->addURL('video')
    ->setLocation('post_template', '==', 'templates/sb-state.php');

add_action('acf/init', function() use ($vid_section) {
   acf_add_local_field_group($vid_section->build());
});

$section_2 = new FieldsBuilder('section_2', [
  'title' => 'Section 2',
  'position' => 'acf_after_title',
  'hide_on_screen' => [
    'the_content',
  ]
]);

$section_2
    ->addWysiwyg('content 2')
    ->addImage('image 2')
    ->addTrueFalse('show stories', [
      'ui' => 1,
    ])
    ->setLocation('post_template', '==', 'templates/sb-state.php');

add_action('acf/init', function() use ($section_2) {
   acf_add_local_field_group($section_2->build());
});

$section_3 = new FieldsBuilder('section_3', [
  'title' => 'Section 3',
  'position' => 'acf_after_title',
  'hide_on_screen' => [
    'the_content',
  ]
]);

$section_3
    ->addText('title 3')
    ->addRepeater('items 3', [
      'layout' => 'block'
    ])
      ->addImage('icon')
      ->addWysiwyg('content')
    ->endRepeater()
    ->addImage('bg 3')
    ->setLocation('post_template', '==', 'templates/sb-state.php');

add_action('acf/init', function() use ($section_3) {
   acf_add_local_field_group($section_3->build());
});

$section_4 = new FieldsBuilder('section_4', [
  'title' => 'Section 4',
  'position' => 'acf_after_title',
  'hide_on_screen' => [
    'the_content',
  ]
]);

$section_4
    ->addText('title 4')
    ->addWysiwyg('content 4')
    ->addLink('letter link')
    ->addNumber('signed count')
    ->addText('signed text')
    ->addSelect('form 4', [
      'label' => 'Select Form',
      'choices' => $choices
    ])
    ->addImage('image 4')
    ->addLink('state biz')
    ->setLocation('post_template', '==', 'templates/sb-state.php');

add_action('acf/init', function() use ($section_4) {
   acf_add_local_field_group($section_4->build());
});

$section_5 = new FieldsBuilder('section_5', [
  'title' => 'Section 5',
  'position' => 'acf_after_title',
  'hide_on_screen' => [
    'the_content',
  ]
]);

$section_5
    ->addText('title 5')
    ->addImage('image 5')
    ->setLocation('post_template', '==', 'templates/sb-state.php');

add_action('acf/init', function() use ($section_5) {
   acf_add_local_field_group($section_5->build());
});

$section_7 = new FieldsBuilder('section_7', [
  'title' => 'Section 7',
  'position' => 'acf_after_title',
  'hide_on_screen' => [
    'the_content',
  ]
]);

$section_7
    ->addText('title 7')
    ->addSelect('form 7', [
      'label' => 'Select Form',
      'choices' => $choices
    ])
    ->addLink('link 7')
    ->setLocation('post_template', '==', 'templates/sb-state.php');

add_action('acf/init', function() use ($section_7) {
   acf_add_local_field_group($section_7->build());
});

$letter = new FieldsBuilder('letter', [
  'title' => 'Letter',
  'position' => 'acf_after_title',
  'hide_on_screen' => [
    'the_content',
  ]
]);

$letter
    ->addWysiwyg('letter')
    ->addSelect('letter form', [
      'label' => 'Select Form',
      'choices' => $choices
    ])
    ->setLocation('post_template', '==', 'templates/sb-state.php');

add_action('acf/init', function() use ($letter) {
   acf_add_local_field_group($letter->build());
});

$social = new FieldsBuilder('social', [
  'title' => 'Social',
  'position' => 'acf_after_title',
  'hide_on_screen' => [
    'the_content',
  ]
]);

$social
    ->addText('twitter text')
    ->addText('fb text')
    ->addText('fb hashtag')
    ->setLocation('post_template', '==', 'templates/sb-state.php');

add_action('acf/init', function() use ($social) {
   acf_add_local_field_group($social->build());
});


//Makeover Template Page
$hero = new FieldsBuilder('hero', [
  'title' => 'Hero',
  'position' => 'acf_after_title',
  'hide_on_screen' => [
    'the_content',
  ],
  'menu_order' => 0,
]);

$hero
    ->addImage('logo')
    ->addText('title')
    ->addTextarea('content')
    ->addLink('video')
    ->addLink('info')
    ->addText('video bg')
    ->addImage('video poster')
    ->setLocation('post_template', '==', 'templates/makeover.php');

add_action('acf/init', function() use ($hero) {
   acf_add_local_field_group($hero->build());
});

$text_section_1 = new FieldsBuilder('text_section_1', [
  'title' => 'Text Section',
  'position' => 'acf_after_title',
  'hide_on_screen' => [
    'the_content',
  ],
  'menu_order' => 1,
]);

$text_section_1
    ->addWysiwyg('content_1')
    ->setLocation('post_template', '==', 'templates/makeover.php');

add_action('acf/init', function() use ($text_section_1) {
   acf_add_local_field_group($text_section_1->build());
});

$case_study = new FieldsBuilder('case_study', [
  'title' => 'Case Study',
  'position' => 'acf_after_title',
  'hide_on_screen' => [
    'the_content',
  ],
  'menu_order' => 2,
]);

$case_study
    ->addRepeater('slides', [
      'layout' => 'block'
    ])
      // ->addTrueFalse('zoom', [
      //   'ui' => 1,
      // ])
      ->addText('cs_title')
      ->addTextarea('cs_content')
      ->addImage('cs_image')
    ->endRepeater()
    ->setLocation('post_template', '==', 'templates/makeover.php');

add_action('acf/init', function() use ($case_study) {
   acf_add_local_field_group($case_study->build());
});

$companies = new FieldsBuilder('companies', [
  'title' => 'Companies',
  'position' => 'acf_after_title',
  'hide_on_screen' => [
    'the_content',
  ],
  'menu_order' => 3,
]);

$companies
    ->addText('comp title')
    ->addRepeater('companies')
      ->addImage('logo')
      ->addUrl('link')
    ->endRepeater()
    ->setLocation('post_template', '==', 'templates/makeover.php');

add_action('acf/init', function() use ($companies) {
   acf_add_local_field_group($companies->build());
});


$text_section_2 = new FieldsBuilder('text_section_2', [
  'title' => 'Text Section',
  'position' => 'acf_after_title',
  'hide_on_screen' => [
    'the_content',
  ],
  'menu_order' => 4,
]);

$text_section_2
    ->addWysiwyg('content_5')
    ->setLocation('post_template', '==', 'templates/makeover.php');

add_action('acf/init', function() use ($text_section_2) {
   acf_add_local_field_group($text_section_2->build());
});

$dm_feat = new FieldsBuilder('dm_feat', [
  'title' => 'Featured',
  'position' => 'acf_after_title',
  'hide_on_screen' => [
    'the_content',
  ],
  'menu_order' => 5,
]);

$dm_feat
    ->addImage('logo_6')
    ->addWysiwyg('content_6')
    ->setLocation('post_template', '==', 'templates/makeover.php');

add_action('acf/init', function() use ($dm_feat) {
   acf_add_local_field_group($dm_feat->build());
});