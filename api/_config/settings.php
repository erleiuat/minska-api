<?php

// Application Params
error_reporting(E_ALL);
//error_reporting(0); <-- to deactivate
date_default_timezone_set('Europe/Zurich');

$api_conf = array(
    "environment" => "test", // 'test', 'prod'
    "corsOrigin" => "http://localhost:8080",
    "cookie" => array(
        "domain" => ".osis.io", //IE11 doesn't like this
        "secure" => false //Set TRUE if HTTPS
    )
);

$token_conf = array(
    "secret" => 'reihu123@lkas:_:asdas', //Change for PROD!
    "algorithm" => array('HS256'),
    "issuer" => 'Minska Application',
    "issuedAt" => time(),
    "notBefore" => time(),
    "expireAt" => time() + (15*60) // 15*60=15Min
);

$db_conf = array(
    "host" => "localhost",
    "database" => "minska",
    "user" => "root",
    "pass" => "",
);
