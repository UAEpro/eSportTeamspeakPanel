<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_NOTICE);
include("./request.test.php");
echo createRequest("stop");
?>