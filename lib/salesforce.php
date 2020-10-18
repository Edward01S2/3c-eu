<?php
// define('ROOT_PATH',$_SERVER['DOCUMENT_ROOT']); 

// require(ROOT_PATH . '/wp-config.php');
// require_once('../../../../wp-config.php');
// require_once(__DIR__ . '/../vendor/autoload.php');
use GuzzleHttp\{Client, RequestOptions};

// $apiCredentials = [
//     'client_id' => '3MVG9LBJLApeX_PCaEPlnwQ3I8Jq4XmlmEdT9fNxfwDw0.3yXQtorCspkbGzxz1txghvW_.fpyzXpUEngZWIV',
//     'client_secret' => '508FB9FF9E045E3B846B547DE8052212F58930DF2FCF5A8DFB0DEB47486D2F3A',
//     'security_token' => 'hhH19qteX15h5hgjQ9akr5X0',
// ];
// $userCredentials = [
//     'username' => 'edward@rndr.tech',
//     'password' => 'reClass&1395',
// ];

add_action('wp_ajax_get_salesforce', 'get_salesforce');
add_action('wp_ajax_nopriv_get_salesforce', 'get_salesforce');

function get_salesforce() {

  $load = isset($_POST['load_url']) ? $_POST['load_url'] : 0;
  // $offset = isset($_POST['offset']) ? $_POST['offset'] : 0;
  // $count = 0 + $offset;
  // $limit = 2000;

  $client = new Client();
  //$client = new Client(['base_uri' => 'https://connected-commerce-council.lightning.force.com/']);
  try {
    $response = $client->request(
        'POST',
        'https://login.salesforce.com/services/oauth2/token',
        [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => [
              'grant_type' => 'password',
              'client_id' => sf_client_id,
              'client_secret' => sf_client_secret,
              'username' => sf_user,
              'password' => sf_pass . sf_security_token,
            ]
        ]
      );

      $data = json_decode($response->getBody());
      // print_r($data);
  } catch (\Exception $exception) {
    echo $exception;
  }

  $hash = hash_hmac(
    'sha256', 
    $data->id . $data->issued_at, 
    sf_client_secret, 
    true
  );
  if (base64_encode($hash) !== $data->signature) {
    echo 'Access token is invalid';
  }
  $accessToken = $data->access_token; // Valid access token
  $instance_url = $data->instance_url;

  if($load) {
    try {
      $response = $client->get( $instance_url . $load, [
          RequestOptions::HEADERS => [
              'Authorization' => 'Bearer ' . $accessToken,
              'X-PrettyPrint' => 1,
          ],
        ]);
        $accounts = json_decode($response->getBody());
        $array = $accounts->records;

        $list = [];
        foreach($array as $v) {
          $url = $v->Website__c;
          if($url) {
            if (substr($url, 0, 7) !== 'http://' && substr($url, 0, 8) !== 'https://') {
              $url = 'http://' . $url;
            }
          }

          $list[] = [
            'name' => $v->Account->Name,
            'website' => $url,
          ];
          // $list[]['website'] = $v->Website__c;
        }
        $temp = array_unique(array_column($list, 'name'));
        $list = array_intersect_key($list, $temp);

        foreach($list as $acct) {
          if(!empty($acct['website'])) {
            echo '<a target="_blank" href="' . $acct['website'] . '">';
            echo '<div class="flex items-center w-full">';
            echo '<div class="member-item">' . $acct['name']. '</div>';
            echo '<svg class="member-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>';
            echo '</div>';
            echo '</a>';
          }
          else {
            echo '<div class="member-text font-bold">';
            echo '<div class="member-item">' . $acct['name'] . '</div>';
            echo '</div>';
          }
        }

        if(!$accounts->done) {
          echo '<div class="col-span-3 text-center sf-load-container"><button id="salesforce-load" class="inline-block" value="' . $accounts->nextRecordsUrl . '">Load More</button></div>';
        }

    } catch (\Exception $exception) {
        echo $exception;
    }

  }
  else {
    $query = 'Select id,account.Name,website__c from contact where type__c = \'Membership\' order by account.Name ASC NULLS FIRST';
      try {
        $response = $client->get( $instance_url . '/services/data/v45.0/query', [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer ' . $accessToken,
                'X-PrettyPrint' => 1,
            ],
            RequestOptions::QUERY => [
                'q' => $query
            ]
        ]);
        $accounts = json_decode($response->getBody());
        $array = $accounts->records;
        
        $list = [];
        foreach($array as $v) {

          $url = $v->Website__c;
          if($url) {
            if (substr($url, 0, 7) !== 'http://' || substr($url, 0, 8) !== 'https://') {
              $url = 'http://' . $url;
            }
          }

          $list[] = [
            'name' => $v->Account->Name,
            'website' => $url,
          ];
          // $list[]['website'] = $v->Website__c;
        }
        $temp = array_unique(array_column($list, 'name'));
        $list = array_intersect_key($list, $temp);

        // if(!$accounts->done) {
        //   $list['nextUrl'] = $accounts->nextRecordsUrl;
        // }

        // header('Content-Type: application/json');
        // echo json_encode($accounts);
        // '<a target="_blank"' + ((typeof v["Contact.Website"] !== 'undefined') ? 'href="' + url : "") + '"><div class="member-item">' + v["Contact.Company"] + '</div>' + ((typeof v["Contact.Website"] !== 'undefined') ? '<svg class="member-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>' : "") + '</a>'
        // header('Content-Type: application/json');
        // echo json_encode($list);
        foreach($list as $acct) {
          if(!empty($acct['name']) && isset($acct['name'])) {
            if(!empty($acct['website'])) {
              echo '<a target="_blank" href="' . $acct['website'] . '">';
              echo '<div class="flex items-center w-full">';
              echo '<div class="member-item">' . $acct['name']. '</div>';
              echo '<svg class="member-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>';
              echo '</div>';
              echo '</a>';
            }
            else {
              echo '<div class="member-text font-bold">';
              echo '<div class="member-item">' . $acct['name'] . '</div>';
              echo '</div>';
            }
          }
        }
        if(!$accounts->done) {
          echo '<div class="col-span-3 text-center sf-load-container"><button id="salesforce-load" class="inline-block" value="' . $accounts->nextRecordsUrl . '">Load More</button></div>';
        }

        // $response = '';
        // $accounts = '';

        // echo '<div>This is working</div>';
        // 
    } catch (\Exception $exception) {
        echo $exception;
    }

    //$resultsFound = $accounts->totalSize;
    //$results = $accounts->records;

    //header('Content-Type: application/json');
    //echo json_encode($accounts);
  }

  

  die();
}