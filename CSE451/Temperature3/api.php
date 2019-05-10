<?php
// Benjamin Bowser
// CSE451
// Week4 - Rest Server
// 2/22/19

header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET,POST,PUSH,OPTIONS"); 
header("content-type: application/json"); 
header("Access-Control-Allow-Headers: Content-Type");

include "darksky.php";
include "session.php";

// Send data as json
function sendJson($status, $msg, $result) {
	$returnData = array();
	$returnData['status'] = $status;
	$returnData['msg'] = $msg;
	foreach ($result as $k=>$v) {
		$returnData[$k] = $v;
	}
	print json_encode($returnData);
	exit;
}

if (isset($_SERVER['PATH_INFO'])) {
	$parts = explode("/", $_SERVER['PATH_INFO']);
	for ($i = 0; $i < count($parts); $i++) {
		$parts[$i] = htmlspecialchars($parts[$i]);
	}
} else {
	$parts = array();
}

array_shift($parts);

$method = strtolower($_SERVER['REQUEST_METHOD']);

if ($method == "options") {
	exit;
}

// Post method that gets temperature for a city that is entered
if ($method == "post" && sizeof($parts) == 3 && $parts[0] == "api" && $parts[1] == "v1" && $parts[2] = "temp") {
	$jsonBody = array();
	$errormsg = "none";
	try {
		$json_str = file_get_contents('php://input');
		$jsonBody = json_decode($json_str, true);
	} catch (Exception $e) {
		$errormsg = $e->getMessage();
		sendJson("FAIL", "JSON DECODE ERROR " . $errormsg,"");
	}

	if (!isset($jsonBody['location'])) {
		sendJson("FAIL", "JSON DECODE ERROR - No Key");
	}
	$key = htmlspecialchars($jsonBody['location']);
	$v = getTemp($key); // Get temperature for coordinates
	sendJson("OK","",array('value'=>$v));
}

// Delete method to delete a cached temperature for a city
if ($method == "delete" && sizeof($parts) == 3 && $parts[0] == "api" && $parts[1] == "v1" && $parts[2] = "temp") {
	$jsonBody = array();
	$errormsg = "none";
	try {
		$json_str = file_get_contents('php://input');
		$jsonBody = json_decode($json_str, true);
	} catch (Exception $e) {
		$errormsg = $e->getMessage();
		sendJson("FAIL", "JSON DECODE ERROR " . $errormsg, "");
	}
	if (!isset($jsonBody['location'])) {
		sendJson("FAIL", "JSON DECODE ERROR - No Key");
	}
	$key = htmlspecialchars($jsonBody['location']);
	$v = deleteZip($key); // delete cache
	sendJson("OK", "", array('value'=>$v));
}
?>
