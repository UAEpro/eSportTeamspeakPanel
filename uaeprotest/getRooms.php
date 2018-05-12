<?php

	ini_set('display_errors', 'On');
	error_reporting(E_ALL & ~E_NOTICE);

require_once("./../../libraries/TeamSpeak3/TeamSpeak3.php");

$ts3 = TeamSpeak3::factory("serverquery://serveradmin:IwcrzOMP@tsip.esport.ae:10011/?server_port=9987");

 $upload = $ts3->execute("ftinitupload", array("clientftfid" => rand(0x0000, 0xFFFF), "cid" => 0, "name" => "/icon_1487863728", "cpw" => "", "size" => 0, "overwrite" => false, "resume" => false))->toList(); 
	if(array_key_exists("status", $upload) && $upload["status"] != 0x00 && $upload["msg"] == "file already exists")
	{
		echo 'file already exists';
	}
?>