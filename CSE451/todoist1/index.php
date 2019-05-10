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
  header("Location: https://todoist.com/oauth/authorize?client_id=$clientID&scope=data:delete,data:read_write,task:add,project:delete&state=ben");
}
?>
<html>
<head>
</head>
<body>
You are authenticated
<br>
<h1>Here are the tasks for project cse451Project1:</h1>
<ul>
<?php
$a=getProjects();
$b=getTasks();
$exists = false;
// Check if cse451Project1 exists already
foreach ($a as $i) {
 if ($i->name == "cse451Project1") {
	$exists = true;
 }
}

// Create a project if it doesn't exist
if ($exists == false) {
	createProject("cse451Project1");
	$a=getProjects();
}
// get ID for cse451Project1
foreach ($a as $i) {
 if ($i->name == "cse451Project1") {
	$id = $i->id;
}
}
// Print out tasks from project
foreach ($b as $i) {
 if ($i->project_id == $id) {
 print "<li>" . $i->content . "</li>";
}
}

// Capture form submsission data
if (isset($_POST["task"])) {
        $task = $_POST["task"];
	addTask($id, $task);
	header("Refresh:0");
}
?>
</ul>
<h2>Add a task:</h2>
<form name="form" id="form" method="post">
<label for="task">
<input type="text" name="task">
<input type="submit" name="submit" id="submit" value="Add Task">
</form>
</body>
</html>
