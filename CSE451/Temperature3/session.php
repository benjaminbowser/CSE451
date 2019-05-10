// Benjamin Bowser
// Week 5 Rest - CAS
// 2/25/2019
<?php
require_once 'config.php';
require_once 'CAS.php';

phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);

phpCAS::setNoCasServerValidation(); // disable ssl

phpCAS::forceAuthentication();

// Check for authentication and then store variables
$attributes = phpCAS::getAttributes();
$dateTime = $attributes['authenticationDate'];
$fullName = $attributes['displayName'];
$uid = $attributes['uid'];

?>
