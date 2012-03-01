<?php
    
require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');
require_once(DIR_SYSTEM . 'vendor/facebook.php');

$config = new Config();
$registry = new Registry();
$registry->set('config', $config);

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);
	
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting");

foreach ($query->rows as $setting) {
	$config->set($setting['key'], $setting['value']);
}

//Facebook
$facebook = new Facebook(array(
  'appId'  => $config->get('fb_api_id'),
  'secret' => $config->get('fb_api_secret'),
));

	$user = $facebook->getUser();
		if ($user) {
		  try {
			$user_profile = $facebook->api('/me');
			$token = $facebook->getAccessToken();
			
		  } catch (FacebookApiException $e) {
			error_log($e);
			$user = null;
			
			}
		}


if(isset($_REQUEST['error'])){
	echo("<script> top.location.href='".$config->get('config_url')."index.php?route=account/create'</script>");
	}
		 
if ($user) {
  echo("<script> top.location.href='".$config->get('config_url')."index.php?route=account/create'</script>");
} else {

  $loginUrl = $facebook->getLoginUrl($params = array('scope' => 'email,'.$config->get('fb_req_ext_per')));
  
  echo("<script> top.location.href='" . $loginUrl . "'</script>");
}
?>