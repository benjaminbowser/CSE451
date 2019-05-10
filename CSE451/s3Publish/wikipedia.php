<?php
// Benjamin Bowser
// Wikipedia requests for S3 assignment
// CSE451 Web Services

require "awsupload.php";
require __DIR__ . '/vendor/autoload.php';
use GuzzleHttp\Client;

// Make get requests to wikipedia and make HTML
function pageInput($page1, $page2, $page3) {
	$html = "<!doctype html><html lang='en'><head><title>Wiki Articles</title><meta charset='UTF-8'></head><body>";
	$html .= apiCall($page1);
	$html .= apiCall($page2);
	$html .= apiCall($page3);
	$html .= "</body></html>";
	writeFile($html);
}

// Write output to a file
function writeFile($content) {
	$f = fopen("index.html", "w");
	fputs($f, $content);
	fclose($f);
	upload();
}

$uri = "https://en.wikipedia.org/api/rest_v1/page/summary/";
$client = new Client(['base_uri' => $uri, 'timeout' => 2.0,]);

// Call the API on wikipedia
function apiCall($pageName) {
	global $client;
	try {
		$response = $client->get($pageName);
	} catch (Exception $e) { // Invalid page name
		//print_r($e);
		echo "<h1>" . $pageName . " is not valid. Please go back and enter a valid page.</h1>";
		exit();
	}
	$body = (string) $response->getBody();
	$jbody = json_decode($body);
	if (!$jbody) {
		return "Something went wrong decoding JSON.";
	}
	$title = $jbody->title;
	if ($title == "Not Found.") { // No title
		echo $pageName . " is not valid.";
		exit();
	}
	$html = "<div><h1>" . $title . "</h1>";
	if (isset($jbody->thumbnail->source)) { // Check if it has an image
		$image = $jbody->thumbnail->source;
		$html .= "<img src='" . $image . "'alt='wikipedia image'>";
	}
	if (isset($jbody->extract_html)) { // Check for description
		$description = $jbody->extract_html;
		$html .= "<br><p>" . $description . "</p>";
	}
	$html .= "</div>";
	return $html;
}
?>

