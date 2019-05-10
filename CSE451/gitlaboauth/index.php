<?php
// Benjamin Bowser
// CSE451
// 3/15/19
// gitlab
require "key.php";
require "gitlabModel.php";

session_start();
if (isset($_REQUEST['logout'])) {
  unset($_SESSION['token']);
      }

if (!isset($_SESSION['token'])) {
  header("Location: https://gitlab.csi.miamioh.edu/oauth/authorize?client_id=$clientID&redirect_uri=https://bowserbl.451.csi.miamioh.edu/cse451-bowserbl-web/gitlaboauth/gitlabCode.php&response_type=code&state=bowserbl");
}
?>
<html>
<head>
</head>
<body>
You are authenticated.
<br>
<h1>Here are your projects:</h1>
<ul>
<?php
$userID = getUserID();
$projects = getProjects($userID);
// Print out tasks from project
foreach ($projects as $i) {
 print "<li>" . $i->path . "</li>";
}
?>
</ul>
</body>
</html>
