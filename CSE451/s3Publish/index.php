<?php
// Benjamin Bowser
// AWS S3 Assignment #1
// CSE451 Web Services

require "wikipedia.php";

if (isset($_POST['page1wiki'])) { // Check if submit pressed
	$page1 = $_POST['page1wiki'];
	$page2 = $_POST['page2wiki'];
	$page3 = $_POST['page3wiki'];
	$pass = $_POST['passwordwiki'];
	if ($pass != "class") { // Check password
		echo "<p>Incorrect Password</p>";
	}
	else if($page1 == "" || $page2 == "" || $page3 == "") { // Check that fields are entered
		echo "<p>All pages must be entered.</p>";
	} else {
		pageInput($page1, $page2, $page3); // Make get requests
		echo "<p><a href='https://campbest-451-s19-wikipedia.s3.amazonaws.com/bowserbl/index.html' target='_blank'>View Pages</a>";
	}
}
?>
<html>
<head>
<script src=
"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
</script>
</head>
<body>
<h1>AWS S3 Wikipedia Pages</h1>
<p>Enter 3 articles and the password</p>
<form name="form" id="form" method="post">
<label for="page1">Page 1:</label>
<input type="text" name="page1wiki"><br>
<label for="page2">Page 2:</label>
<input type="text" name="page2wiki"><br>
<label for="page3">Page 3:</label>
<input type="text" name="page3wiki"><br>
<label for="password">Password:</label>
<input type="password" name="passwordwiki"><br>
<input type="submit" name="submit" id="submit" value="Add Pages">
</form>
</body>
</html>
