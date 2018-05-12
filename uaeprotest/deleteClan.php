<?php

	ini_set('display_errors', 'On');
	error_reporting(E_ALL & ~E_NOTICE);

// load framework files
require_once("./../../libraries/TeamSpeak3/TeamSpeak3.php");
// connect to local server, authenticate and spawn an object for the virtual server on port 9987
$ts3_VirtualServer = TeamSpeak3::factory("serverquery://serveradmin:IwcrzOMP@tsip.esport.ae:10011/?server_port=1234");
// create a top-level channel and get its ID
//$Last_clan = 21759;
$Clan_id = 22092;
$Spacer = "error";
foreach($ts3_VirtualServer->channelList() as $channel){
	if($channel->getId() == $Clan_id)break;
	if($channel->getLevel() == 0){
	$Spacer = $channel->getId();
	}
}
if($Spacer != "error" && $Spacer != 21759){
	$ts3_VirtualServer->channelDelete($Spacer);
}
	$ts3_VirtualServer->channelDelete($Clan_id);	
?>