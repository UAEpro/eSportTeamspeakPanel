<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_NOTICE);
include("../request.test.php");
$req = createRequest("getAvatar('".$_GET["id"]."')");
$avatar = "";
while(!waitForResult($req)){}
$avatar = readResult($req);
if($avatar != "error"){
	header("Content-Type: image/png");
echo $avatar;
} else {
		header("Content-Type: image/jpeg");
	echo file_get_contents("esport.jpg");
}
?>