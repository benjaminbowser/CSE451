<?php
require_once("password.php");
$db= 'tempCache';

$mysqli = mysqli_connect("localhost", $user,$pass,"mysql");
if (mysqli_connect_errno($mysqli)) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	die;
}
	$mysqli->query("drop database if exists $db");
	$mysqli->query("create database $db");


require "cache.php";

createCacheTable();



	

