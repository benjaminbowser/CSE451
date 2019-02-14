<?php
//stc  missing your required headers
//stc -> I also almost always start all php files with php at th etop
?>
<HTML lang="en"> <HEAD>
  <META name="generator" content=
  "HTML Tidy for HTML5 for Linux version 5.4.0">
  <META charset="utf-8">
  <META http-equiv="X-UA-Compatible" content="IE=edge">
  <META name="viewport" content=
  "width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other 
head content must come *after* these tags -->
  <TITLE>Campbest Week2</TITLE><!-- Bootstrap -->
  <!-- Latest compiled and minified CSS -->
  <LINK rel="stylesheet" href=
  "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <!-- jQuery library -->
  <SCRIPT src=
  "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></SCRIPT><!-- 
Latest compiled JavaScript -->
  <SCRIPT src=
  "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></SCRIPT><!-- 
HTML5 shim and Respond.js for IE8 support of HTML5 elements and media 
queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// 
-->
  <!--[if lt IE 9]> <script 
src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script> 
<script 
src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script> 
<![endif]-->
  <LINK rel="stylesheet" href="style.css" type="text/css"> </HEAD> 
<BODY>
  <DIV class='container-fluid center' id='mainpage'>
    <H1>Display keys and values</H1>
    <DIV id="msg"></DIV>
    <DIV id="info">
      <TABLE class="table">
        <THEAD>
          <TR>
            <TH>Key</TH>
            <TH>Value</TH>
          </TR>
        </THEAD>
<TBODY id='info-body'>

<?php
require_once("info.php");
function writeTable() {
$keys = getKeys();
for ($x = 0; $x < sizeof($keys); $x++) {
echo "<tr><td>";
echo $keys[$x];
echo "</td><td>";
echo getValue($keys[$x]);
echo "</td></tr>";
}
}
writeTable();

//stc you sould do this before you output the table data, this will cause issues wiht sequencing.
//stc -> also you did not use htmlspecialchars

if (isset($_POST["key"])) {
        $key = $_POST["key"];
        $value = $_POST["value"];
        $pass = $_POST["password"];
	if ($key == "" || $value == "" || $pass == "") {
		$reply = "All fields required.";
	} else {
        	$reply = add($key, $value, $pass);
		if ($reply == "OK") {
			echo "<tr><td>";
			echo $key;
			echo "</td><td>";
			echo $value;
			echo "</td></tr>";
		}
	}
}
?>
</TBODY>
</TABLE>
    </DIV><!-- close info-->
    <hr>
<?php
	echo "<div>";
	echo $reply;
	echo "</div>";
?>
<h1>Add New KeyValue Pair</h1>
	<div class="form-group" id="formArea">
	<form method="post" class="form-group">
	  <label for="key">Key:</label>
	  <input type="text" class="form-control" name="key">
	  <label for="value">Value:</label>
	  <input type="text" class="form-control" name="value">
	  <label for="password">Password:</label>
	  <input type="password" class="form-control" name="password">
	<button type="submit" class="btn btn-primary">Submit</button>
	</form>
	</div>
	<footer>
	 Benjamin Bowser - CSE451 - Spring 2019 - Week2
  	</footer>
   </DIV><!-- close 1st container-->
 </BODY>
</HTML>

