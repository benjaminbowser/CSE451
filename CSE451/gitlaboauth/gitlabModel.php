<?php
// Benjamin Bowser
// CSE451
// 3/15/19
// gitlab

//this calls in all autoload packages installed via composer
require __DIR__ . '/vendor/autoload.php'; 
require "key.php";

//bring guzzle client into code
use GuzzleHttp\Client;

//base uri -> it is important it end in /
$uri = "https://gitlab.csi.miamioh.edu/api/v4/";

//create a new client
$client = new Client([
    // Base URI is used with relative requests
    'base_uri' => $uri,
    // You can set any number of default request options.
    'timeout'  => 2.0,
]);

// Get list of projects on a users account
function getProjects($userID) {
  global $client;
try {
  $header = array("Authorization"=>"Bearer " . $_SESSION['token']);
  $response = $client->request('get',"users/$userID/projects",['json'=>['simple'=>false],'headers'=>$header]);

} catch (Exception $e) {
  print "There was an error getting the projects from Miami gitlab";
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
return $jbody;
}

// Get the ID for the current user using the page
function getUserID() {
  global $client;
try {
  $header = array("Authorization"=>"Bearer " . $_SESSION['token']);
  $response = $client->request('get',"user",['headers'=>$header]);

} catch (Exception $e) {
  print "There was an error getting the userID from Miami gitlab";
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
return $jbody->username;
}
?>
