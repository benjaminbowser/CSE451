<?php
session_start();
// Benjamin Bowser
// CSE451
// 3/11/19
// Todoist

//this calls in all autoload packages installed via composer
require __DIR__ . '/vendor/autoload.php'; 
require "key.php";
require "creds.php";

// AWS
require 'vendor/autoload.php';

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

use Aws\Credentials\CredentialProvider;
$credentials = CredentialProvider::defaultProvider();


//bring guzzle client into code
use GuzzleHttp\Client;

//base uri -> it is important it end in /
$uri = "https://todoist.com/oauth/access_token";


//create a new client
$client = new Client([
    // Base URI is used with relative requests
    'base_uri' => $uri,
    // You can set any number of default request options.
    'timeout'  => 2.0,
]);
 $code =htmlspecialchars($_REQUEST['code']);
try {
  $data = array("client_id"=>$clientID,"client_secret"=>$clientSecret,"code"=>$code,'redirect_uri'=>'https://bowserbl.451.csi.miamioh.edu/cse451-bowserbl-web/todoist1/index.php');
  $response = $client->request('POST',"",['form_params'=>$data]);
} catch (Exception $e) {
  print "There was an error getting the token from todoist";
//  header("content-type: text/plain",true);
 // print_r($e);
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
//print $_SESSION['token'];

$sdk = new Aws\Sdk([
    'endpoint'   => 'https://dynamodb.us-east-1.amazonaws.com',
    'region'   => 'us-east-1',
    'version' => 'latest',
    'credentials' => ['key'=>$key,'secret'=>$secret,'token'=>$token]
]);

$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();

$tableName = 'final1';

$userid = "amzn1.ask.account." .  $_SESSION['USER'];
$key = $_SESSION['token'];

$item = $marshaler->marshalJson('
    {
        "userId": "' . $userid . '",
        "mapAttr": {
                "token": "' . $key . '"
        }
    }
');


/*
$item = $marshaler->marshalJson('
    {
        "userid": "' . $userid . '",
        "mapAttr": ['token' => ['S' => $key]]
    }
');
*/
$params = [
    'TableName' => 'final1',
    'Item' => $item
];

try {
    $result = $dynamodb->putItem($params);
    echo "Added items";

} catch (DynamoDbException $e) {
    echo "Unable to add item:\n";
    echo $e->getMessage() . "\n";
}

header("location: index.php");
?>
