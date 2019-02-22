<?php
// Benjamin Bowser
// CSE451
// Week 3
// 2/18/19
// Handles database and API calls

require __DIR__ . '/vendor/autoload.php';
require "keys.php";

$mysqli = mysqli_connect("localhost", $user, $pass, $db);
if (mysqli_connect_errno($mysqli)) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	die;
}

use GuzzleHttp\Client;

$uri = "https://api.darksky.net/forecast/$apiKey/";

// Get a temperature and timestamp based on a key (coordinates) entered
function getValue($k) {
	global $mysqli;
	$stmt = $mysqli->prepare("select value, timestamp from temps where keyName=?");
	if (!$stmt) {
		error_log("Error on getValue " . $mysqli->error);
		return null;
	}

	$stmt->bind_param("s",$k);
	$stmt->execute();
	$stmt->bind_result($value, $timestamp);

	$results = array();

	while ($stmt->fetch()) {
		$rec = ['value'=>$value,'timestamp'=>$timestamp];
		array_push($results, $rec);
	}
	return $results;
}

// API call to get a new temperature for a string of decimal coordinates
$client = new Client(['base_uri' => $uri, 'timeout' => 2.0,]);
function getTemp($location) {
	$data = getValue($location);
	// Database call is not empty
	if (sizeof($data) != 0) {
		$weatherTime = strtotime($data[0]['timestamp']); // Time in db
		$currentTime = time(); // Current epoch
		// Data under an hour old? Return it.
		if (($currentTime - $weatherTime) < 3600) {
			$response = array();
			array_push($response, $data[0]['value']);
			array_push($response, "at ".(date("h:i A", strtotime($data[0]['timestamp'])))." EST ");
			return $response;
		} else {
			return apiCall($location);
		}
	} else {
		return apiCall($location);
	}
}

function apiCall($location) {
// Data is over an hour old or no data
	global $client;
	try {
		$response = $client->get($location);
	} catch (Exception $e) {
		print_r($e);
		return "Something went wrong trying to get the temp.";
	}
	$body = (string) $response->getBody();
	$jbody = json_decode($body);
	if (!$jbody) {
		return "Something went wrong decoding JSON.";
	}
	// Add location and temperature to the database
	add($location, $jbody->currently->temperature);  //stc where do you get rid of any existing entries?
	// Return an array [0]=> Temperature [1]=> String "currently "
	$response = array();
	array_push($response, $jbody->currently->temperature);
	array_push($response, "currently ");
	return $response;

}

// Takes in a string of a city and retruns a string of lat/long
function temperature($city) {
	switch ($city) {
		case "Oxford":      //stc -> It is not appropriate to put your hardcoded cities into the model. model should be open. Put thse in the index.php
			$lat = 39.5104;
			$long = -84.7423;
			return ("$lat, $long");
			break;
		case "International Falls":
			$lat = 48.6027;
			$long = -93.4022;
			return ("$lat, $long");
			break;

		case "San Diego":
			$lat = 32.7157;
			$long = -117.1611;
			return ("$lat, $long");
			break;

		case "New York":
			$lat = 40.7123;
			$long = -74.0078;
			return ("$lat, $long");
			break;

		case "Dallas":
			$lat = 32.7775;
			$long = -96.7976;
			return ("$lat, $long");
			break;
	}
}

// Used to reset/create the table used by this program
function createInfoTable() {
	global $mysqli;
	$mysqli->query("drop table if exists temps");
	print $mysqli->error;
	print "creating the database\n";

	$r = $mysqli->query("CREATE TABLE `temps` (
	`pk` int(11) NOT NULL AUTO_INCREMENT,
	`keyName` text NOT NULL,
	`value` text NOT NULL,
	`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`pk`))");
	print_r($r);

	print $mysqli->error;
}

// Add a new temperature record for a city as a string of decimal coordinates
function add($city, $temp) {
	global $mysqli;

$stmt = $mysqli->prepare("insert into temps (keyName, value) values (?,?)");
if (!$stmt) {
	error_log("error adding " . $mysqli->error);
	return "Error Adding";
}

$stmt->bind_param("ss", $city, $temp);//stc poor indenting
$stmt->execute();
return "Added.";
}
?>
