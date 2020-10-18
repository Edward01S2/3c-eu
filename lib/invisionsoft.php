<?php 
define('ROOT_PATH',$_SERVER['DOCUMENT_ROOT']); 

include(ROOT_PATH . '/wp-load.php');
require_once(__DIR__ . '/../vendor/autoload.php');

// if(!(session_id())) {
//   session_start();
// }

$infusionsoft = new Infusionsoft\Infusionsoft(array(
  'clientId'     => 'gkfgjkdhsrrc87nhwrfnn983',
  'clientSecret' => 'MbydNKTmKu',
  'redirectUri'  => 'https://connectedcouncil.org/member-directory/',
));

// if($_POST['data'] === 'request') {
//   print_r('True');
// }

if(get_option('invision_token')) {
  $infusionsoft->setToken(get_option('invision_token'));
}

// if (isset($_SESSION['token'])) {
//   $infusionsoft->setToken(unserialize($_SESSION['token']));
// }


if(($_POST['data'] !== 'request') && !$infusionsoft->getToken())
{
  $code = $_POST['data'];
  $token = serialize($infusionsoft->requestAccessToken($code));
  update_option('invision_token', $token);
  //echo "Token requested";
}

// if (isset($_GET['code']) and !$infusionsoft->getToken()) {
//   // $_SESSION['token'] = serialize($infusionsoft->requestAccessToken($_GET['code']));
//   $token = $infusionsoft->requestAccessToken($_GET['code']);
//   update_option('invision_token', $token);

// }

if ($infusionsoft->getToken()) {
  try {
    // $_SESSION['token'] = serialize($infusionsoft->getToken());
    $token = $infusionsoft->getToken();

    update_option('invision_token', $token);

    //$memberslist = $infusionsoft->tags()->find('108')->contacts();
    //$memberslist = $infusionsoft->tags()->find('108')->contacts()->where('limit', 1000)->where('offset', 1000);

    $selected = ['Contact.Company'];
    $querydata=array(
      'GroupId' => '108');
    $selectedfields=array('Contact.Company', 'Contact.Website');
    $orderBy = 'Contact.Company';
    //Stupid api limitis to 1000
    $memberslist = $infusionsoft->data()->query('ContactGroupAssign', 1000, 0, $querydata, $selectedfields, $orderBy, false);
    $memberslist2 = $infusionsoft->data()->query('ContactGroupAssign', 1000, 1, $querydata, $selectedfields, $orderBy, false);
    $memberslist3 = $infusionsoft->data()->query('ContactGroupAssign', 1000, 2, $querydata, $selectedfields, $orderBy, false);
    //Combine arrays into one
    $result = array_merge($memberslist, $memberslist2, $memberslist3);
    //$contacts = $memberslist['contacts'];
    //Filter through and remove items that are unnecesary
    foreach($result as $k => $v) {
      if(empty($v) || $v == "" || $v['Contact.Company'] == "Test" || $v['Contact.Company'] == "None")
          unset($result[$k]);
    }
    $name = array_column($result, 'Contact.Company');
    array_multisort($name, SORT_ASC, $result);
    //print_r($result);
    $response_array = ['status' => 'success', 'data' => $result];
    echo json_encode($response_array);
    //echo json_encode($result);


    // $contact_id_arr = [];
    // foreach($contacts as $contact) {
    //   $contact_id = $contact['contact']['id'];
    //   array_push($contact_id_arr, $contact_id);
    // }
    // print_r($contact_id_arr);

    // //Initialize empty array to hold contacts that have name and website
    // $contact_info_arr = [];
    // foreach($contact_id_arr as $id) {
    //   $contact_info = $infusionsoft->contacts()->with('website')->find($id);
    //   $contact_array = $contact_info->toArray();
    //   //print_r($contact_info);

    //     //Check to see if contact has company name and a website listed
    //     if(!empty($contact_array['company']['company_name']) && !empty($contact_array['website'])) {
    //       $in_data = ['name' => $contact_array['company']['company_name'], 'website' => $contact_array['website']];
    //       array_push($contact_info_arr, ['company' => $in_data]);
    //     }

    //   //print_r($contact_array);
    //   //echo $contact_info->{'attributes:protected'};
    // }
    // print_r($contact_info_arr);
	}
	catch (\Infusionsoft\TokenExpiredException $e) {
		// If the request fails due to an expired access token, we can refresh
		// the token and then do the request again.
		$infusionsoft->refreshAccessToken();
		// Save the serialized token to the current session for subsequent requests
    // $_SESSION['token'] = serialize($infusionsoft->getToken());
    $token = $infusionsoft->getToken();
		update_option('invision_token', $token);
	}
	//var_dump($task);

} else {
  //global $infusion_url;
  //$infusion_url = $infusionsoft->getAuthorizationUrl();
  //echo '<a href="' . $infusionsoft->getAuthorizationUrl() . '">Click here to reauthorize</a>';
  $reauth_url = $infusionsoft->getAuthorizationUrl();
  $response_array = ['status' => 'failure', 'reauthorize' => $reauth_url];
  echo json_encode($response_array);
}

//Add cron job to refresh token every 20 hours
// $args = array(false);
// function schedule_my_cron() {
//   wp_schedule_event(time(), '20hr', 'refresh_invisonsoft_token', $args);
// }

// if(!wp_next_scheduled('refresh_invisonsoft_token', $args)){
//   add_action('init', 'schedule_my_cron');
// }

// function refresh_invisonsoft_token() {
//   $infusionsoft->refreshAccessToken();
//   $token = $infusionsoft->getToken();
//   update_option('invision_token', $token);
// }