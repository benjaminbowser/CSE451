<?php
// Benjamin Bowser
// CSE451
// 3/22/19
// todoist/wiki

//this calls in all autoload packages installed via composer
require __DIR__ . '/vendor/autoload.php'; 
require "key.php";

//bring guzzle client into code
use GuzzleHttp\Client;

//base uri -> it is important it end in /
$uri = "https://beta.todoist.com/API/v8/";

//create a new client
$client = new Client([
    // Base URI is used with relative requests
    'base_uri' => $uri,
    // You can set any number of default request options.
    'timeout'  => 2.0,
]);

// Function to create a new project with todoist
function createProject($name) {
  global $client;
try {
	$header = array("Authorization"=>"Bearer " . $_SESSION['token']);
	$response = $client->request('post', "projects", ['headers'=>$header, 'form_params'=>['name'=>$name]]);
} catch (Exception $e) {
	print "There was an error adding a project to todoist";
	print_r($e);
	$a=print_r($e,true);
	error_log($a);
	return false;
	exit;
}
return true;
}

// Function to add a new task to a project
function addTask($id, $content) {
  global $client;
try {
	 $header = array("Authorization"=>"Bearer " . $_SESSION['token']);
	 $response = $client->request('post', "tasks", ['json'=>['content'=>$content, 'project_id'=>$id], 'headers'=> $header]);
} catch (Exception $e) {
	 print "There was an error adding a task to todoist";
 	 print_r($e);
	 $a=print_r($e,true);
	 error_log($a);
	 return false;
	 exit;
}
return true;
}

// Get list of projects on a users account
function getProjects() {
  global $client;
try {
  $header = array("Authorization"=>"Bearer " . $_SESSION['token']);

  $response = $client->request('get',"projects",['headers'=>$header]);
} catch (Exception $e) {
  print "There was an error getting the projects from todoist";
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

return $jbody;
}

// Get a users tasks from their account
function getTasks() {
 global $client;
try {
  $header = array("Authorization"=>"Bearer " . $_SESSION['token']);
  $response = $client->request('get',"tasks", ['headers'=>$header]);
} catch (Exception $e) {
  print "There was an error getting the tasks from todoist";
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
?>
