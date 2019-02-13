<?php

$http_origin = $_SERVER['HTTP_ORIGIN'];
if ($http_origin == "http://localhost:8080" || $http_origin == "https://minska.eliareutlinger.ch"){
    header("Access-Control-Allow-Origin: $http_origin");
}

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Max-Age: 86400");

if ($_SERVER['REQUEST_METHOD'] === "OPTIONS") {
    die();
}
