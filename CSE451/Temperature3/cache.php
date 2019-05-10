<?php
//this calls in all autoload packages installed via composer
require_once __DIR__ . '/vendor/autoload.php'; 

require_once("password.php");
$db= 'tempCache';

$mysqli = mysqli_connect("localhost", $user,$pass,$db);
if (mysqli_connect_errno($mysqli)) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	die;
}

//get temperature from cache for given zip, if not present return null
//
function getTemperatureFromCache($zip) {
	global $mysqli;
	$stmt = $mysqli->prepare("select temperature,updateTime from cache where zipcode=?");
	if (!$stmt) {
		error_log("Error on getValue " . $mysqli->error);
		return null;
	}

	$stmt->bind_param("s",$zip);
	if (!$stmt->execute()) {
		error_log("execute error");
		error_log($mysqli->error);
		return null;
	}

	$stmt->bind_result($temp,$updateTime);
	if ($stmt->fetch() === false) {
		error_log( "fetch error");
		error_log($mysqli->error);
		return null;
	}

	return array('temp'=>$temp,'updateTime'=>$updateTime);
}

//remove cache entry for zipcode
function deleteZip($zip) {
	global $mysqli;
	$mysqli->query("delete from cache where zipcode='$zip'");
}

//add temp cache
function addTemp($zip,$temp) {
	global $mysqli;
	$mysqli->query("lock tables cache write");

	deleteZip($zip);

	$stmt = $mysqli->prepare("insert into cache (zipcode,temperature,updateTime) values (?,?,?)");
	if (!$stmt) {
		$mysqli->query("unlock tables");
		error_log("error on add " . $mysqli->error);
		return "error";
	}

	$now = time();
	$stmt->bind_param("sss",$zip,$temp,$now);
	$stmt->execute();

	$mysqli->query("unlock tables");
	return "OK";
}

//helper function to create cache table
function createCacheTable() {
	global $mysqli;
print "creating db\n";
$r = $mysqli->query("CREATE TABLE `cache` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `zipcode` text NOT NULL,
  `temperature` text NOT NULL,
  `updateTime` int,
  PRIMARY KEY (`pk`)
)");
	print $mysqli->error;
}

?>
