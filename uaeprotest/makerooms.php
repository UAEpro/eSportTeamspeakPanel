<?php

	ini_set('display_errors', 'On');
	error_reporting(E_ALL & ~E_NOTICE);

// load framework files
require_once("./../../libraries/TeamSpeak3/TeamSpeak3.php");
// connect to local server, authenticate and spawn an object for the virtual server on port 9987
$ts3_VirtualServer = TeamSpeak3::factory("serverquery://serveradmin:IwcrzOMP@tsip.esport.ae:10011/?server_port=1234");
// create a top-level channel and get its ID
//$Last_clan = 21759;
$Cid = 15068;
$Client = "error";
foreach($ts3_VirtualServer->clientList() as $client_){
	if($client_["client_database_id"] == $Cid){
		$Client = $client_;
		break;
	}
}
if($Client == "error")die("Couldn't find the user");
$Last_clan = "error";
foreach($ts3_VirtualServer->channelList() as $channel){
	if($channel->getId() == 21758)break;
	if($channel->getLevel() == 0){
	$Last_clan = $channel->getId();
	}
}
if($Last_clan != "error"){
 if($Last_clan != 21759){
	$Last_clan = $ts3_VirtualServer->channelCreate(array(
 "channel_name" => "[*spacer".rand()."]━",
 "channel_flag_permanent" => TRUE,
 "CHANNEL_FLAG_MAXCLIENTS_UNLIMITED" => false,
 "CHANNEL_MAXCLIENTS" => "1",
 "CHANNEL_ORDER" => $Last_clan,
));
 }
$top_cid = $ts3_VirtualServer->channelCreate(array(
 "channel_name" => "[cspacer]Clan Name".rand(),
 "channel_topic" => "This is a top-level channel",
  "CHANNEL_FLAG_MAXCLIENTS_UNLIMITED" => false,
  "CHANNEL_MAXCLIENTS" => 0,
// "channel_codec" => TeamSpeak3::CODEC_SPEEX_WIDEBAND,
 "channel_flag_permanent" => TRUE,
 "CHANNEL_ORDER" => $Last_clan,
));
$Client->move($top_cid);
// create a sub-level channel and get its ID
$Client->setChannelGroup($top_cid,106);
	$Welcome_room = $ts3_VirtualServer->channelCreate(array(
 "channel_name" => "Welcome Room",
  "CHANNEL_FLAG_MAXCLIENTS_UNLIMITED" => false,
  "CHANNEL_MAXCLIENTS" => -1,
 "channel_flag_permanent" => TRUE,
 "cpid" => $top_cid,
));
	$afk_room = $ts3_VirtualServer->channelCreate(array(
 "channel_name" => "AFK Room",
 "CHANNEL_FLAG_MAXCLIENTS_UNLIMITED" => false,
 "CHANNEL_MAXCLIENTS" => "1",  
 "channel_flag_permanent" => TRUE,
 "cpid" => $top_cid,
));
$Client->move($Welcome_room);
die("Works");
}
die("error");
?>