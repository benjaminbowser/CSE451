<?php

//this calls in all autoload packages installed via composer
require_once __DIR__ . '/vendor/autoload.php'; 
require_once "cache.php";
require "password.php";

//bring guzzle client into code
use GuzzleHttp\Client;

//base uri -> it is important it end in /
$uri = "https://api.darksky.net/forecast/$key/";

//create a new client
$client = new Client([
	// Base URI is used with relative requests
	'base_uri' => $uri,
	// You can set any number of default request options.
	'timeout'  => 2.0,
]);

//this function makes a get call to retrieve the temp for a given lat/long
function darkSky_getTemp($latlong) {
	global $client;
	try {
		$response = $client->request('get',"$latlong");
	} catch (Exception $e) {
		print "error on get temp from dark web $lat $long\n";
		$a=print_r($e,true);
		error_log($a);
		return "Temperature Error on fetch";
	}
	$body = (string) $response->getBody();
	$jbody = json_decode($body);
	if (!$jbody) {
		return "error on decoding json";
	}

	return $jbody->currently->temperature;
}

//get temp from cache or darksky
function getTemp($latlong) {
	$cache = getTemperatureFromCache($latlong);

	if ($cache != null) {
		$diff = time()-$cache['updateTime'];
		if ($diff < 3600) {
			$temp = $cache['temp'];
			$t =$cache['updateTime'];
			return array("temp"=>$temp,"updateTime"=>$t,"cache"=>"y");
		}
	}

	$temp =  darksky_getTemp($latlong);

	addTemp($latlong,$temp);
	return array("temp"=>$temp,"updateTime"=>time());
}
?>
