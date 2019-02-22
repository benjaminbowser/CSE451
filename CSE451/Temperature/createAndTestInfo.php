<?php
/*
Benjamin Bowser (originally from Scott Campbell)
CSE451
Spring 2019
Week3

Creates database

*/

ini_set('register_argc_argv', 0);  

// Must be in command line
if (!isset($argc) || is_null($argc))
{
    echo 'Not CLI mode';
	die;
}


//create database -connect directly to mysql

require_once("keys.php");
$mysqli = mysqli_connect("localhost", $user,$pass,"mysql");
if (mysqli_connect_errno($mysqli)) {
	print "error";
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	die;
}

print "connected\n";
print "droping db\n";
$r = $mysqli->query("drop database if exists " . $db);
print_r($r);
print $mysqli->error;
$mysqli->query("create database " . $db);
print $mysqli->error;
$mysqli->close();

require_once("call.php");
createInfoTable();
//print_r(add("39.5104, -84.7423","32.00"));
//print_r(add("39.5104, -84.7423", "22.20"));
//print_r(getValue("39.5104, -84.7423"));

?>
