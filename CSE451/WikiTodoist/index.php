<?php
// Benjamin Bowser
// CSE451
// 3/22/19
// todoist/wiki
require "key.php";
require "todoistModel.php";

session_start();
if (isset($_REQUEST['logout'])) {
  unset($_SESSION['token']);
      }

if (!isset($_SESSION['token'])) {
  header("Location: https://todoist.com/oauth/authorize?client_id=$clientID&scope=data:delete,data:read_write,task:add,project:delete&state=scott");
}

?>
<html>
<head>
<script src=
  "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
</script>
<script>
var token = "<?php echo $_SESSION['token'];?>";
console.log(token);
var id;

// Creates a project
function createProject(name, token) {
	$.ajax({
        url: "https://beta.todoist.com/API/v8/projects",
        headers: {"Authorization": "Bearer " + token},
        type: "POST",
        data: {name: name},
        dataType: "JSON",
        success: function(reply) {
		getProjects(token);
		return reply;
        },
        error: function ( xhr ){
		alert("Something went wrong in createProject");
        }
        });
}

// Adds a task to a project ID and a passed token
function addTask(id, content, token) {
	$.ajax({
        url: "https://beta.todoist.com/API/v8/tasks",
        headers: {"Content-Type":"application/json", "Authorization": "Bearer " + token},
	type: "POST",
	data: JSON.stringify({content: content, project_id: id}),
	dataType: "json",
	success: function(reply) {
		getTasks(token, id);
                return reply;
        },
        error: function ( xhr ){
               alert("Something went wrong in addTask");
        }
        });
}

// Gets the projects for a user, searching for cse451Project1
function getProjects(token) {
	$.ajax({
	url: "https://beta.todoist.com/API/v8/projects",
	headers: {"Authorization": "Bearer " + token},
	type: "GET",
	dataType: "json",
	success: function(reply) {
		var exists = false;
		for (var i = 0; i < reply.length; i++) {
			if (reply[i].name == "cse451Project1") {
				exists = true;
				id = reply[i].id;
				getTasks(token, id);
			}
		}
		if (exists == false) {
			createProject("cse451Project1", token);

		}
	},
	error: function ( xhr ){
		alert("Something went wrong in getProjects");
	}
	});
}

// Gets tasks for a project with a token and project id
function getTasks(token, id) {
	$.ajax({
	url: "https://beta.todoist.com/API/v8/tasks",
	headers: {"Authorization": "Bearer " + token},
        type: "GET",
        dataType: "json",
        success: function(reply) {
		$("#list").empty();
		for (var i = 0; i < reply.length; i++) {
			if (reply[i].project_id == id) {
				$("#list").append("<li>" + reply[i].content + "</li>");
			}
		}
        },
        error: function ( xhr ){
                alert("Something went wrong in getTasks");
        }
	});
}

$(document).ready(function() {
	getProjects(token);
	$("form").submit(function(event) {
		event.preventDefault();
		data = $("#task").val();
		addTask(id, data, token);
	});
});
</script>
</head>
<body>
You are authenticated
<br>
<h1>Here are the tasks for project cse451Project1:</h1>
<ul id="list">
</ul>
<h2>Add a task:</h2>
<form name="form" id="form" method="post">
<label for="task">
<input type="text" name="task" id="task">
<input type="submit" name="submit" id="submit" value="Add Task">
</form>
<a href="WikiTodoist.php">WikiTodoist</a>
</body>
</html>
