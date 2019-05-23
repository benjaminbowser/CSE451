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

//bring guzzle client into code
use GuzzleHttp\Client;

$provider = CredentialProvider::defaultProvider();

$sdk = new Aws\Sdk([
    'endpoint'   => 'https://dynamodb.us-east-1.amazonaws.com',
    'region'   => 'us-east-1',
    'version' => 'latest',
    'credentials' => ['key'=>$key,'secret'=>$secret,'token'=>$token]
]);

$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();

$tableName = 'AlexaFinal';

$userid = "Test";
$key = "keyTest";

$item = $marshaler->marshalJson('
    {
	"userId": "' . $userid . '",
	"mapAttr": {
		"token": "' . $key . '"
	}
    }
');

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


?>
