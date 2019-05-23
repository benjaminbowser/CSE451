<?php
// Benjamin Bowser
// CSE451
// 3/11/19
// todoist
require "key.php";
require "todoistModel.php";

session_start();
if (isset($_REQUEST['logout'])) {
  unset($_SESSION['token']);
      }

if (!isset($_SESSION['token'])) {
  	$_SESSION['USER'] = $_GET['USER'];
	header("Location: https://todoist.com/oauth/authorize?client_id=$clientID&scope=data:delete,data:read_write,task:add,project:delete&state=scott");
}

?>
<html>
<head>
</head>
<body>
You are authenticated. Return to Alexa and prompt again.
<br>
</body>
</html>
