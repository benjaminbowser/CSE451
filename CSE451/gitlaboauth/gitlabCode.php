<?php
session_start();
// Benjamin Bowser
// CSE451
// 3/15/19
// gitlab

//this calls in all autoload packages installed via composer
require __DIR__ . '/vendor/autoload.php'; 
require "key.php";

//bring guzzle client into code
use GuzzleHttp\Client;

$uri = "https://gitlab.csi.miamioh.edu/oauth/token";

//create a new client
$client = new Client([
    // Base URI is used with relative requests
    'base_uri' => $uri,
    // You can set any number of default request options.
    'timeout'  => 2.0,
]);
 $code = htmlspecialchars($_REQUEST['code']);
 try {
  $data = array(
  	'client_id'=>$clientID,
  	'client_secret'=>$clientSecret,
  	'code'=>$code,
  	'redirect_uri'=>'https://bowserbl.451.csi.miamioh.edu/cse451-bowserbl-web/gitlaboauth/gitlabCode.php',
	'grant_type'=>'authorization_code'
 );
  $response = $client->request('POST',"", ['query'=>$data]);

} catch (Exception $e) {
  print "There was an error getting the token";
  header("content-type: text/plain",true);
  print_r($e);
  $a=print_r($e,true);
  error_log($a);
  exit;
}
$body = (string) $response->getBody();
$jbody = json_decode($body);
if (!$jbody) {
  error_log("no json");
  exit;
}
$_SESSION['token'] = $jbody->access_token;

header("location: index.php");
?>
