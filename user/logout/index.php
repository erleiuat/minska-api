<?php

include_once '../../_config/headers.php';
include_once '../../_config/core.php';

$expire = time()-50;
header("Set-Cookie: secureToken=null; Dexpires=$expire; Path=/; samesite=strict; httpOnly; $secure");
returnSuccess($jwt);
