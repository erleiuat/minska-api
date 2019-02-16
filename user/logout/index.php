<?php

include_once '../../_config/headers.php';
include_once '../../_config/core.php';

$domain = ".eliareutlinger.ch";
//$domain = "localhost";

$unset = "";

header("Set-Cookie: appToken=$unset; Domain=$domain; expires=Thu, 01 Jan 1970 00:00:00 GMT; Path=/; samesite=strict; $secure");
header("Set-Cookie: secureToken=$unset; Domain=$domain; expires=Thu, 01 Jan 1970 00:00:00 GMT; Path=/; samesite=strict; httpOnly; $secure", false);

returnSuccess($jwt);
