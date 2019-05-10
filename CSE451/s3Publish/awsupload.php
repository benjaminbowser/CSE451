<?php
// Benjamin Bowser
// AWS Uploader
// CSE451 - Web Services

require "./vendor/autoload.php";
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\Credentials\CredentialProvider;
use Aws\S3\MultipartUploader;
use Aws\Common\Exception\MultipartUploadException;

function upload() {
$profile = 'class';   //this specifies which profile inside of credential file to use
$path = '/var/www/.aws/credentials';        //path to credential file

$provider = CredentialProvider::ini($profile, $path);
$provider = CredentialProvider::memoize($provider);

$s3Client = new S3Client([
    'region' => 'us-east-1',
    'version' => '2006-03-01',
    'credentials' => $provider
]);

//header("content-type: text/plain");	//set content type of response to non-html

$file = "index.html"; // File on local server
$key = "bowserbl/index.html"; // Dir/File to create on AWS bucket
$contentType ="text/html"; // Type of file
$bucket = "campbest-451-s19-wikipedia"; // Bucket name

try {
	$uploader = new MultipartUploader($s3Client,$file,[
		'Bucket' => $bucket,
		'Key' => $key,
		'ACL' => 'public-read',
		'ContentType' => $contentType
	]);

	$result = $uploader->upload();
	//echo "uploaded";
} catch (S3Exception $e) {
	echo "An upload error to AWS occured.";
}
	//var_dump($result);
}
