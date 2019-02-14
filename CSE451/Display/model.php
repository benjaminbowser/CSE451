<?php
/*
   scott campbell
   model for info database


   requires password.php
   $user=
   $password=
   $databaseName=


   Schema:
   Table: temp
   pk -> primary Key
   zipcode -> text
   temperature -> float
   when-> timestamp of reading


 */

require_once("passwords.php");
$mysqli = mysqli_connect("localhost", $user,$password,$databaseName);
if (mysqli_connect_errno($mysqli)) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  die;
}

//does not use prepare since no input in query
function getZips() {

}


//uses prepare since taking input
function getTemp($zipCode,$startTime,$endTime) {
  global $mysqli;   //use global variable
  $stmt = $mysqli->prepare("select pk,temperature,when from temp where when >= ? and when =<?");

  if (!$stmt) {
    print "failed to prepare " . $mysqli->error;
    return null;
  }:q



  if (!$stmt->bind_param("ss",$startTime,$endTime)) { print $mysqli->error; return;}

  if (!$stmt->execute()) { 
    print $mysqli->error;
    return null;
  }

  if (!$stmt->bind_result($pk,$temperature,$when)) {
    print "Failed to bind output";
    return null;
  }

  $results = array();
  while ($stmt->fetch()) {
    $rec = ['pk'=>$pk,'temperature'=>$temperature,'when'=>$when];
    array_push($results,$rec);
  }

  return $results;
}

function createAndLoad() {
  global $mysqli;
  $sql = "
}


?>




