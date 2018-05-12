<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_NOTICE);
include("../request.php");
$req = createRequest("getAvatar('".$_GET["id"]."')");
while(!isResultExists($req)){
}
$avatar = "";
sleep(1);
$avatar = readResult($req);
if($avatar != ""){
	header("Content-Type: image/png");
echo $avatar;
} else {
		header("Content-Type: image/jpeg");
	echo file_get_contents("esport.jpg");
}
?>