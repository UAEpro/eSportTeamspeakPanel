<?php 
ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_NOTICE);
	$Clan = $_GET["clan"];
	$Code = $_GET["code"];
	
	$host = "127.0.0.01";
	// Mysql User
	$user = "root";
	// Mysql Password
	$password = "XPApkV8w";
	// Mysql Database
	$db = "TS3-1";
	
	$mysqli = mysqli_connect($host,$user,$password,$db);
	mysqli_query($mysqli,"INSERT INTO `URLs`(`id`, `ClanId`, `time`) VALUES ('$Code','$Clan',".time().")");
?>