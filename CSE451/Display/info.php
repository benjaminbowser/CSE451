<?php
/*
   Scott Campbell
   info data model

pasword.php must have:
$user, $pass, $db and $addPass

 */

require_once("password.php");

$mysqli = mysqli_connect("localhost", $user,$pass,$db);
if (mysqli_connect_errno($mysqli)) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	die;
}

function getKeys() {
	global $mysqli;
	$sql = "select keyName from info";
	$res = $mysqli->query($sql);
	if (!$res) {
		error_log("Error on getKeys select " . $mysqli->error);
		return null;
	}

	$keys = array();
	while( $row = mysqli_fetch_assoc($res)) {
		array_push($keys,$row['keyName']);
	}
	return $keys;
}

function getValue($k) {
	global $mysqli;
	$stmt = $mysqli->prepare("select value from info where keyName=?");
	if (!$stmt) {
		error_log("Error on getValue " . $mysqli->error);
		return null;
	}

	$stmt->bind_param("s",$k);
	$stmt->execute();
	$stmt->bind_result($value);
	$stmt->fetch();
	return $value;


}


function add($k,$v,$pass) {
	global $addPass;
	global $mysqli;

	if ($pass != $addPass) {
		return "invalid password";
	}

	$stmt = $mysqli->prepare("insert into info (keyName,value) values (?,?)");
	if (!$stmt) {
		error_log("error on add " . $mysqli->error);
		return "error";
	}

	$stmt->bind_param("ss",$k,$v);
	$stmt->execute();

	return "OK";
}


function createInfoTable() {
	global $mysqli;
	$mysqli->query("drop table if exists info");
	print $mysqli->error;
print "creating db\n";
$r = $mysqli->query("CREATE TABLE `info` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `keyName` text NOT NULL,
  `value` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk`)
)");
print_r($r);


	print $mysqli->error;
}



?>
