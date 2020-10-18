<?php

if(get_field('website')) :?>
  <?php
    $url = get_field('website');
    if($url) {
      if (substr($url, 0, 7) !== 'http://' && substr($url, 0, 8) !== 'https://') {
        $url = 'http://' . $url;
      }
    }
  ?>
  <a target="_blank" href="<?php echo $url ?>">
    <div class="flex items-center w-full">
      <div class="member-item"><?php the_title() ?></div>
      <svg class="member-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
    </div>
  </a>
<?php else: ?>
  <div class="member-text font-bold">
    <div class="member-item"><?php the_title() ?></div>
  </div> 
<?php endif; ?>