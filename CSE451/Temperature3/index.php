<?php
// Benjamin Bowser
// Week5 Rest - CAS

require_once 'config.php';
require_once 'CAS.php';

phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);

phpCAS::setNoCasServerValidation(); // disable ssl

phpCAS::forceAuthentication(); // check if authenticated

$attributes = phpCAS::getAttributes();
$dateTime = $attributes['authenticationDate'];
$fullName = $attributes['displayName'];
$uid = $attributes['uid'];

// Open a logging file for logging visits
//https://www.w3schools.com/php/php_file_create.asp
$file = fopen("temp.log", "a+") or die("Something went wrong with the file.");
$entry = $fullName . " - " . $uid . " - " . $dateTime . "\n";
fwrite($file, $entry);
fclose($file);

if (isset($_REQUEST['logout'])) {
	phpCAS::logout();
}

// Check for post data and log it to a file
$json_str = file_get_contents('php://input');
$jsonBody = json_decode($json_str, true);
if ($jsonBody != null) {
	$city = htmlspecialchars($jsonBody['city']);
	$type = htmlspecialchars($jsonBody['method']);
	$log = "API Call - " . $type . "-" . $city . " - By: " . $uid . "\n";

	$file = fopen("temp.log", "a+") or die("Something went wrong with the file.");
	fwrite($file, $log);
	fclose($file);
}
?>
<!-- Benjamin Bowser -- CSE451 -->
<HTML lang="en">
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="script.js"></script>
<title>Bowserbl Week 4</title>
</head>
<body>
<div>
<h1>Temperatures in cities</h1>
<h2 id="data" name="data"></h2>
<form name="form" id="form" method="post">
<select name="selection" id="selection">
	<option value="Oxford">Oxford</option>
	<option value="International Falls">International Falls</option>
	<option value="San Diego">San Diego</option>
	<option value="New York">New York</option>
	<option value="Dallas">Dallas</option>
</select>
<input type="submit" name="submit" id="submit" value="Get Temperature">
</form>
</div>
<hr>
<div>
<h2>Delete an entry</h2>
<h3 id="data2" id="data2"></h2>
<form name="form2" id="form2" method="post">
<select name="selection2" id="selection2">
	<option value="Oxford">Oxford</option>
	<option value="International Falls">International Falls</option>
	<option value="San Diego">San Diego</option>
	<option value="New York">New York</option>
	<option value="Dallas">Dallas</option>
</select>
<input type="submit" name="submit2" id="submit2" value="Delete cached city">
</form>
</div>
<hr>
<footer>
<p><a href="?logout=">Logout</a></p>
Benjamin Bowser - CSE451 - Spring 2019
</footer>
</body>
</html>
