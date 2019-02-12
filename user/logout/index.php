<?php

include_once '../../_config/headers.php';
include_once '../../_config/core.php';


setcookie("token", false, time()-1000, "/", "localhost", 0, 1);
returnSuccess($jwt);
