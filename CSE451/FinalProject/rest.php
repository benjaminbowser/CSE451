<?php

$dbUser = "root";
$dbpassword = "redacted";
$mysqli = mysqli_connect("bowserbl.451.csi.miamioh.edu", $dbUser, $dbpassword,"finalProject");
if (mysqli_connect_errno($mysqli)) {
      	echo "Failed to connect to MySQL: " . mysqli_connect_error();
          	die;
}

// returns data as json
function retJson($data) {
  header('content-type: application/json');
  print json_encode($data);
  exit;
}

// get request method into $path variable
$method = strtolower($_SERVER['REQUEST_METHOD']);
if (isset($_SERVER['PATH_INFO']))
$path  = $_SERVER['PATH_INFO'];
else $path = "";

$pathParts = explode("/",$path);
if (count($pathParts) <2) {
  $ret = array('status'=>'FAIL','msg'=>'Invalid URL');
  retJson($ret);
}
if ($pathParts[2] !== "v1") {
  $ret = array('status'=>'FAIL','msg'=>'Invalid url or version');
  retJson($ret);
}

//get json data if any
$jsonData =array();
try {
  $rawData = file_get_contents("php://input");
  $jsonData = json_decode($rawData,true);
  if ($rawData !== "" && $jsonData==NULL) {
    $ret=array("status"=>"FAIL","msg"=>"invalid json");
    retJson($ret);
  }
} catch (Exception $e) {
};

// Look for url api/v1/comments.
// Json in:
// Json out: {"msg":string,"Comments":[{"id":int,"comment":string,"createTime":int (unixtime)},...}
if ($method==="get" && count($pathParts) == 4  && $pathParts[3] === "Comments") {
	$stmt = $mysqli->prepare("select * from comment");
	$stmt->execute();
	$result = $stmt->get_result();
	$comments = array();
	while ($row = $result->fetch_assoc()){
		$items = array("pk"=>$row['pk'], "comment"=>$row['comment'], 'createTime'=>$row['createTime']);
		$comments[] = $items;
		$items = array();
	}
  	$ret = array('msg'=>'OK','Comments' => $comments);
  	retJson($ret);
}

// Look for url api/v1/comments/Last.
// Json in:
// Json out: {"msg":string,"id":int,"comment":string,"createTime":int}
if ($method==="get" && count($pathParts) == 5  && $pathParts[3] === "Comments" && $pathParts[4] === "Last") {
	$stmt = $mysqli->prepare("select * from comment order by createTime DESC limit 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $comments = array();
        while ($row = $result->fetch_assoc()){
                $items = array("pk"=>$row['pk'], "comment"=>$row['comment'], 'createTime'=>$row['createTime']);
                $comments[] = $items;
                $items = array();
        }
	$ret = array('msg'=>'OK','Comment' => $comments);
        retJson($ret);
}

// Look for url api/v1/comment.
// Json in: {"password":"class","comment":string}
// Json out: {"msg":string}
if ($method==="put" && count($pathParts) == 4  && $pathParts[3] === "Comment") {
        $pass = $jsonData['password'];
	$comment = $jsonData['comment'];
	$time = time();
	if ($pass != "class" || !isset($jsonData['password']) || !isset($jsonData['comment'])) {
		$ret = array('msg'=>'FAIL','Comments'=>'Invalid data entered');
        	retJson($ret);
	} else {
		$stmt = $mysqli->prepare("insert into comment (comment, createTime) values (?, ?)");
		$stmt->bind_param("ss", $comment, $time);
		$stmt->execute();
		$ret = array('msg'=>'Inserted comment');
        	retJson($ret);
	}
}

// Look for url api/v1/comments/:id.
// Json in: {"password":"class"}
// Json out: {"msg":string}
// Delete comment
if ($method==="put" && count($pathParts) == 5  && $pathParts[3] === "Comment") {
	$pass = $jsonData['password'];
	if ($pass != "class" || !isset($jsonData['password'])) {
                $ret = array('msg'=>'FAIL','Comments'=>'Invalid data entered');
                retJson($ret);
        } else {
		$id = $pathParts[4];
		$stmt = $mysqli->prepare("delete from comment where pk =?");
		$stmt->bind_param("s", $id);
		$stmt->execute();
        	$ret = array('msg'=>'Comment deleted.');
        	retJson($ret);
	}
}

// Look for url api/v1/comments.
// Json in:{"password":"class"}
// Json out: {"msg":string}
if ($method==="delete" && count($pathParts) == 4  && $pathParts[3] === "Comments") {
        $pass = $jsonData['password'];
	if ($pass != "class" || !isset($jsonData['password'])) {
                $ret = array('status'=>'FAIL','msg'=>'Invalid data entered');
                retJson($ret);
        } else {
		$stmt = $mysqli->prepare("delete from comment");
        	$stmt->execute();
		$ret = array('msg'=>'All comments deleted');
        	retJson($ret);
	}
}



