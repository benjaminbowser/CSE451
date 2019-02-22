<?php
// Benjamin Bowser
// CSE451
// Week 3
// 2/18/19
//  Index page to display HTML and make calls to call.php

// Default load
require("call.php");
$value = "Oxford";
$reply = temperature($value);
$temp = getTemp($reply);

// If the user clicks the submit button
if(isset($_POST['selection'])){
	$value = $_POST['selection'];
	$reply = temperature($value);
	$temp = getTemp($reply);
}
?>

<HTML lang="en">
<HEAD>
<title>Weather for Cities</title>
<style>
.centered {
	text-align: center;
}
</style>
</head>
<body>
<div class="centered">
<h1>Temperature for a city:</h1>
<h2>The temperature <?php echo $temp[1]; echo " in "; echo $value; echo " is "; echo $temp[0]; echo "&deg F."; ?></h2>
<form method="post">
<!-- Check which box should be selected after reloading data -->
<select name="selection">
	<option value="Oxford"<?php if (isset($_POST['selection'])) { if ($_POST['selection'] == "Oxford"){ echo 'selected="selected"';}}?>>Oxford</option>
	<option value="International Falls"<?php if (isset($_POST['selection'])) { if ($_POST['selection'] == "International Falls"){ echo 'selected="selected"';}}?>>International Falls</option>
	<option value="San Diego"<?php if (isset($_POST['selection'])) { if ($_POST['selection'] == "San Diego") { echo 'selected="selected"';}}?>>San Diego</option>
	<option value="New York"<?php if (isset($_POST['selection'])) { if ($_POST['selection'] == "New York") { echo 'selected="selected"';}}?>>New York</option>
	<option value="Dallas"<?php if (isset($_POST['selection'])) { if ($_POST['selection'] == "Dallas") { echo 'selected="selected"';}}?>>Dallas</option>
</select>
<input type="submit" name="submit" value="Get Temperature">
</form>
</div>
</body>
<footer>
<div class="centered">
Benjamin Bowser - CSE451 - Spring 2019
</div>
</footer>
</html>


