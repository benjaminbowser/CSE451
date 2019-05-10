<?php
/*
   Scott Campbell
   webhook for todoist

   read in json body
   verifies hmac from todoist
   writes log file
 */
require_once "key.php";


# Get JSON as a string
$json_str = file_get_contents('php://input');

# Get as an object
$json_obj = json_decode($json_str);

$headers = getallheaders();

$valid = false;
//X-Todoist-Hmac-Sha256
if (isset($headers['X-Todoist-Hmac-Sha256'])) {
  //check header
  $check = $headers['X-Todoist-Hmac-Sha256'];
  $check1 = base64_encode(hash_hmac("sha256",$json_str,$clientSecret,true));
  if ($check == $check1) {
    $valid = "valid";
  }
} else {
  $check = "null";
  $check1="null";
}


if ($valid) {
$f=fopen("log.txt","a");
fputs($f,"$json_str \n");
fclose($f);
}
?>
