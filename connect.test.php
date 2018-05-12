<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(0);
require_once("/var/www/libraries/TeamSpeak3/TeamSpeak3.php");
include("./config.php");
include("./request.test.php");
clearRequest();
TeamSpeak3::init();
TeamSpeak3_Helper_Signal::getInstance()->subscribe("serverqueryWaitTimeout", "onTimeout");
$ts3 = TeamSpeak3::factory("serverquery://serveradmin:IwcrzOMP@tsip.esport.ae:10011/?server_port=9987");
$ts3->execute("clientupdate", array("client_nickname" => "MI6.test"));
function onTimeout(TeamSpeak3_Adapter_ServerQuery $adapter)
{
  echo "no reply from the server for " . $seconds . " seconds\n";
    echo "[INFO] sending keep-alive command\n";
    $adapter->request("clientupdate");
}

function getUser($ip){
	global $ts3;
	$ts3->clientListReset();
	$Users = array();
	foreach($ts3->clientList() as $client){
		if($ip == $client["connection_client_ip"]){
			array_push($Users,array((string)$client["client_nickname"],$client["client_database_id"]));
		}
	}
	return json_encode($Users);
}
 function getInfo($ip,$id=false){
	 global $ts3;
	 $ts3->clientListReset();
	$info = array(array(),array(),array(sizeof($ts3->clientList()),$ts3["virtualserver_maxclients"]));
	global $ts3;
	foreach($ts3->clientList() as $client){
		if($ip == $client["connection_client_ip"]){
			if($id == false || ($id && $client["client_database_id"] == $id)){
				
			foreach($client->getInfo() as $key=>$value){
				if(!is_numeric($value)){
					$info[0][$key] = (string)$value;
				} else{
					$info[0][$key] = $value;
				}
			}
			$info[1]["level"] = getClientLevel($client);
			break;
		}
		}
	}
	return addslashes(json_encode($info));
}
 function iconsInfo($ip,$client_database_id){
	 global $ts3;
	 $ts3->clientListReset();
	 $ts3->serverGroupListReset();
	 $groups = array(false);
	foreach($ts3->clientList() as $client){
		if($ip == $client["connection_client_ip"]){
			if($client_database_id == false || ($client_database_id && $client["client_database_id"] == $client_database_id)){
			$groups = array();
			foreach($client->memberOf() as $group){
				if(isset($group["sgid"])) {
				$groups[$group->getID()] = true;
				}
			}
			break;
		}
		}
	}
	$server_groups = $ts3->serverGroupList();
	$servergroups = array();
	$servergroups1 = array();
	$servergroups2 = array();
	$add = false;
	foreach($server_groups as $group) {
		if($group->type != 1) { continue; }
			if($group->sgid == 12678) {$add = false;}
				if($add){
					if($group["sortid"] == 301){
						$servergroups1[] = array('name' => (string)$group, 'id' => $group->sgid, 'type' => $group->type);
					} else {
						$servergroups2[] = array('name' => (string)$group, 'id' => $group->sgid, 'type' => $group->type);
					}
				}
			if($group->sgid == 12) {$add = true;}
	} 
	sort($servergroups1);
	$servergroups = array_merge($servergroups1,$servergroups2);
	return addslashes(json_encode(array($groups,$servergroups)));
 }
 function getClientServerGroups($ip,$client_database_id){
	 global $ts3;
	 $ts3->clientListReset();
	 $ts3->serverGroupListReset();
	 $groups = array(false);
	foreach($ts3->clientList() as $client){
		if($ip == $client["connection_client_ip"]){
			if($id == false || ($id && $client["client_database_id"] == $id)){
			$groups = array();
			foreach($client->memberOf() as $group){
				$groups[$group->getID()] = true;
			}
			break;
		}
		}
	}
	return json_encode($groups);
 }
 function getTop10Games(){
	global $ts3; 
	$ts3->serverGroupListReset ();
	$server_groups = $ts3->serverGroupList();
 $groups = array();
 $groups_ = array();
 $add = false;
  foreach($server_groups as $group) {
		if($group->type != 1) { continue; }
		if($group->sgid == 12678) {$add = false;}
			if($add){
			$name = $group->getInfo()["name"];
			$groups_[(string)$group] = sizeof($group->clientList());
			}
			if($group->sgid == 12) {$add = true;}
	} 
	asort($groups_);
	$groups_ = array_reverse($groups_, true);
    $i = 1;
	 foreach($groups_ as $group => $players){
		 if($i == 6){
			 break;
		 }
		 $group = str_replace('♦«','',$group);
		 $group = str_replace(' »♦','',$group);
		 array_push($groups,array((string)$group,$players));
		 $i++;
	 }
	 return json_encode($groups);
}
function getAvatar($id){
	global $ts3;
	$ts3->clientListReset();
	$img = $ts3->clientGetByDbid($id)->avatarDownload();
	if($img == "")$img = "error";
	return addslashes($img);
}
function isClientMebmerOfServerGroup($client,$sgid){
	foreach($client->memberOf() as $key =>$value){
		if($value->getID() == $sgid){
			return true;
		}	
	}
	return false;
}
  function getClientClan($ip,$client_database_id){
	 global $ts3;
	 $ts3->clientListReset();
	 $ts3->serverGroupListReset();
	 $groups = array();
	 $active = true;
	 $rank = getClinetRank($client_database_id);
	foreach($ts3->clientList() as $client){
		if($ip == $client["connection_client_ip"]){
			if($id == false || ($id && $client["client_database_id"] == $id)){
			foreach($client->memberOf() as $group){
				if($group["sortid"] == 650){
				$groups[$group->getID()] = true;
				}
			}
			if(!isClientMebmerOfServerGroup($client,12672)){
				$active = false;
			}
			break;
		}
		}
	}
	return json_encode(array($groups,$active,$rank));
 }
 
$Levels = array(1 => 62490,2 => 62491,3 => 62492,4 => 62493,5 => 62494,6 => 62495,7 => 62496,8 => 62497,9 => 62498,10 => 62499,11 => 62500,12 => 62501,13 => 62502,14 => 62503,15 => 62504,16 => 62505,17 => 62506,18 => 62507,19 => 62508,20 => 62509,21 => 62510,22 => 62511,23 => 62512,24 => 62513,25 => 62514,26 => 62515,27 => 62516,28 => 62517,29 => 62518,30 => 62519,31 => 62627,32 => 62628,33 => 62629,34 => 62630,35 => 62631,36 => 62632,37 => 62633,38 => 62634,39 => 62635,40 => 62636,41 => 62637,42 => 62638,43 => 62639,44 => 62640,45 => 62641,46 => 62642,47 => 62643,48 => 62644,49 => 62645,50 => 62646,51 => 62647,52 => 62648,53 => 62649,54 => 62650,55 => 62651,56 => 62652,57 => 62653,58 => 62654,59 => 62655,60 => 62656,61 => 62657,62 => 62658,63 => 62659,64 => 62660,65 => 62661,66 => 62662,67 => 62663,68 => 62664,69 => 62665,70 => 62666,71 => 62667,72 => 62668,73 => 62669,74 => 62670,75 => 62671,76 => 62672,77 => 62673,78 => 62674,79 => 62675,80 => 62676,81 => 62677,82 => 62678,83 => 62679,84 => 62680,85 => 62681,86 => 62682,87 => 62683,88 => 62684,89 => 62685,90 => 62686,91 => 62687,92 => 62688,93 => 62689,94 => 62690,95 => 62691,96 => 62692,97 => 62693,98 => 62694,99 => 62695,100 => 62696);
$Levels2 = array(1 ,16 ,58 ,127 ,223 ,346 ,496 ,673 ,877 ,1108 ,1366 ,1651 ,1963 ,2302 ,2668 ,3061 ,3481 ,3928 ,4402 ,4903 ,5431 ,5986 ,6568 ,7177 ,7813 ,8476 ,9166 ,9883 ,10627 ,11398 ,12196 ,13021 ,13873 ,14752 ,15658 ,16591 ,17551 ,18538 ,19552 ,20593 ,21661 ,22756 ,23878 ,25027 ,26203 ,27406 ,28636 ,29893 ,31177 ,32488 ,33826 ,35191 ,36583 ,38002 ,39448 ,40921 ,42421 ,43948 ,45502 ,47083 ,48691 ,50326 ,51988 ,53677 ,55393 ,57136 ,58906 ,60703 ,62527 ,64378 ,66256 ,68161 ,70093 ,72052 ,74038 ,76051 ,78091 ,80158 ,82252 ,84373 ,86521 ,88696 ,90898 ,93127 ,95383 ,97666 ,99976 ,102313 ,104677 ,107068 ,109486 ,111931 ,114403 ,116902 ,119428 ,121981 ,124561 ,127168 ,129802 ,132463);
function getClientLevel($client){
	global $Levels,$Levels2;
	foreach($Levels as $key => $value){
		if(isClientMebmerOfServerGroup($client,$value)){
			return array($key,$Levels2[$key-1],$Levels2[$key]);
		}	
	}
	return false;
 }
 function ActivatClient($cid){
	 global $ts3;
	 $ts3->clientListReset();
	 $ts3->channelListReset();
	 $client = "error";
	 foreach($ts3->clientList() as $client_){
		 if($client_["client_database_id"] == $cid){
			 $client = $client_;
			 break;
		 }
	 }
	if($client != "error"){
		global $mysqli;
		$clientName = $client["client_nickname"];
		$cuid       = $client->getUniqueId();
		$ip         = $client["connection_client_ip"];
		$check = $mysqli->query("SELECT * FROM Users WHERE cdid='". $cid ."'");
		if ( mysqli_num_rows($check) == 0 ) {
			$mysqli->query("INSERT INTO Users (name,cdid,cuid,ip) VALUES('". $clientName ."','". $cid ."','". $cuid ."','". $ip ."')");
		}
		$client->addServerGroup(12672);
		return true;
	}
  return false;
 }
 //Clans
function createClanRooms($Members,$Name){
  global $ts3;
  $ts3->channelListReset ();
  $Client = "error";
  $Members = json_decode($Members,true);
	foreach($ts3->clientList() as $client_){
		if($client_["client_database_id"] == $Members[0]){
			$Client = $client_;
		  break;
		}
	}
	if($Client == "error")return false;
	
	$Last_clan = "error";
 foreach($ts3->channelList() as $channel){
	if($channel->getId() == 2344)break;
		if($channel->getLevel() == 0){
			$Last_clan = $channel->getId();
		}
	}
 if($Last_clan != "error"){
	if($Last_clan != 19556){
		$Last_clan = $ts3->channelCreate(array(
			"channel_name" => "[*spacer".rand()."]━",
			"channel_flag_permanent" => TRUE,
			"CHANNEL_FLAG_MAXCLIENTS_UNLIMITED" => false,
			"CHANNEL_MAXCLIENTS" => "0",
			"CHANNEL_ORDER" => $Last_clan,
		));
	}
		$top_cid = $ts3->channelCreate(array(
			"channel_name" => "[cspacer]▌◥ $Name ◤ ▌",
			"channel_topic" => "This is a top-level channel",
			"channel_flag_permanent" => TRUE,
			"CHANNEL_FLAG_MAXCLIENTS_UNLIMITED" => false,
			"CHANNEL_MAXCLIENTS" => "0",
			"CHANNEL_ORDER" => $Last_clan,
		));
	$Client->move($top_cid);
	$Client->setChannelGroup($top_cid,24);
	
	$Welcome_room = $ts3->channelCreate(array(
		"channel_name" => "Welcome Room",
		"channel_flag_permanent" => TRUE,
		"cpid" => $top_cid,
	));
	$afk_room = $ts3->channelCreate(array(
		"channel_name" => "AFK Room",
		"CHANNEL_NEEDED_TALK_POWER"  => "1000",
		"channel_flag_permanent" => TRUE,
		"cpid" => $top_cid,
	));
//$Client->move($Welcome_room);
  foreach($Members as $member){
	  $Client = "error";
	  foreach($ts3->clientList() as $client_){
		  if($client_["client_database_id"] == $member){
			$Client = $ts3->clientGetByDbid($member);
		  }
	  }
	  if($Client != "error"){
		$Client->move($Welcome_room);
	  }
  }
return $top_cid;
}
return false;
}
function getClanManger($sgid,$room){
	global $ts3;
	$ts3->serverGroupListReset();
	$icon = $ts3->serverGroupGetById($sgid)->iconDownload();
	$room = getClanRooms($room);
	return json_encode(array($room,base64_encode($icon)),JSON_UNESCAPED_UNICODE);
}
function setIconGroup($sgid,$data,$room){
	global $ts3;
	$ts3->serverGroupListReset();
	$icon = $ts3->iconUpload(base64_decode($data));
	$ts3->serverGroupPermAssign($sgid,"i_icon_id",$icon);
	if(!array_key_exists((string) $room, $ts3->channelList()))return false;
	$Clan = $ts3->channelGetById($room);
	foreach($Clan->subChannelList() as $channel){
		$channel["channel_icon_id"] = $icon;
	}
	return "Works";
}
function getClanRooms($room){
	global $ts3;
	$ts3->channelListReset();
	if(!array_key_exists((string) $room, $ts3->channelList()))return false;
 $Clan = $ts3->channelGetById($room);
 $Rooms = array();
	foreach($Clan->subChannelList() as $channel){
		array_push($Rooms,array((string)$channel,$channel->getId()));
	}
  return $Rooms;
}
function sortClanRooms($Clan,$Rooms,$Names,$Delete){
	global $ts3;
	$ts3->channelListReset();
	if(!array_key_exists((string) $Clan, $ts3->channelList())) return false;
	$Clan_ = $ts3->channelGetById($Clan);
	$icon = 0;
	foreach($Clan_->subChannelList() as $channel){
		$id = $channel->getId();
		$icon = $channel["channel_icon_id"];
		break;
	}
	$Delete = explode(",",$Delete);
	$Names = explode(",",$Names);
	$Rooms = explode(",",$Rooms);
	 array_shift($Rooms);
	 array_pop($Rooms);
	 
	foreach($Delete as $room){
		if(array_key_exists((string) $room, $ts3->channelList())){
			$channel = $ts3->channelGetById($room);
			$channel->delete(true);
		}
	}
	
	$ts3->channelListReset();
	
	foreach($Rooms as $room){
		if($room <= 1000){
			$room_ = $ts3->channelCreate(array(
				"channel_name" => "Room-".rand(),
				"channel_flag_permanent" => TRUE,
				"channel_order" => $id,
				"cpid" => $Clan,
			));
			if($icon != 0){
				$channel = $ts3->channelGetById($room_);
				$channel["channel_icon_id"] = $icon;
				}
		 $id = $room_;
		} else {
			if(array_key_exists((string) $room, $ts3->channelList())){
				$channel = $ts3->channelGetById($room);
					if($channel["channel_order"] != $id){
						$channel["channel_order"] = $id;
					}
				$id = $channel->getId();
			}
		}
	}
	
	$ts3->channelListReset();
	$i = 0;
	
	foreach($Clan_->subChannelList() as $channel){
		if($channel["channel_name"] != $Names[$i]){
			$error = false;
			foreach($ts3->channelList() as $channel_){
				if($channel_["channel_name"] == $Names[$i]){
					$error = true;
				}
			}
			if($error == false){
				$channel["channel_name"] = $Names[$i];
			}
		}
	 $i++;
	}
	return true;
}
function getClanMembersGroup($id){
	global $ts3;
	$Arr = array();
	$ownerGroup = $ts3->channelGroupGetById(24);
	foreach($ownerGroup->clientList() as $list){
		if($list["cid"] == $id){
			$Arr[$list["cldbid"]] = "Owner";
		}
	}
	
	$co_ownerGroup = $ts3->channelGroupGetById(68);
	foreach($co_ownerGroup->clientList() as $list){
		if($list["cid"] == $id){
			$Arr[$list["cldbid"]] = "Co-Owner";
		}
	}

	$leaderGroup = $ts3->channelGroupGetById(39);
	foreach($leaderGroup->clientList() as $list){
		if($list["cid"] == $id){
			$Arr[$list["cldbid"]] = "Leader";
		}
	}
	return addslashes(json_encode($Arr));
}
function getClinetRank($id){
	global $ts3;
	
	foreach($ts3->channelGroupGetById(24)->clientList() as $list){
		if($list["cldbid"] == $id){
			return  "Owner";
		}
	}
	
	foreach($ts3->channelGroupGetById(68)->clientList() as $list){
		if($list["cldbid"] == $id){
			return "Co-Owner";
		}
	}

	foreach($ts3->channelGroupGetById(39)->clientList() as $list){
		if($list["cldbid"] == $id){
			return "Leader";
		}
	}
	return "Member";
}
function removeRank($cid,$cdbid){
	global $ts3;
	$ts3->execute("setclientchannelgroup",array("cgid" => 8,"cid" =>$cid,"cldbid" => $cdbid));
	return "Works";
}
function createClanGroup($name){
	global $ts3;
	$group = $ts3->serverGroupCreate($name);
	$ts3->serverGroupPermAssign($group,'i_group_needed_modify_power',60);
	$ts3->serverGroupPermAssign($group,'i_group_needed_member_add_power',50);
	$ts3->serverGroupPermAssign($group,'i_group_needed_member_remove_power',50);
	$ts3->serverGroupPermAssign($group,'i_group_sort_id',650);
	return $group;
}
function createClan($Name,$Members,$Tag){
	global $ts3;
	$rooms = createClanRooms($Members,$Name);
	$group = createClanGroup($Tag);
	$Members = json_decode($Members,true);
	$group_ = $ts3->serverGroupGetById($group);
	  foreach($Members as $member){
		$group_->clientAdd($member);
	  }
	return json_encode(array($rooms,$group));
}
function addGroup($sgid,$cdbid){
	global $ts3;
	$ts3->serverGroupListReset();
	if(!array_key_exists((string) $sgid, $ts3->serverGroupList())){
		return false;
	}	
	$group = $ts3->serverGroupGetById($sgid);
	$group->clientAdd($cdbid);
	return "Works";
}
function removeGroup($cid,$sgid,$cdbid){
	global $ts3;
	$ts3->serverGroupListReset();
	if(!array_key_exists((string) $sgid, $ts3->serverGroupList())){
		return false;
	}	
	$group = $ts3->serverGroupGetById($sgid);
	$group->clientDel($cdbid);
	removeRank($cid,$cdbid);
	return "Works";
}
function changeTag($sgid,$tag){
	global $ts3;
	$ts3->serverGroupListReset();
	if(!array_key_exists((string) $sgid, $ts3->serverGroupList())){
		return false;
	}	
	$group = $ts3->serverGroupGetById($sgid);
	$group->rename($tag);
	return "Works";
}
function changeName($cid,$name){
	global $ts3;
	$ts3->channelListReset();
	if(array_key_exists((string) $cid, $ts3->channelList())){
		$channel = $ts3->channelGetById($cid);
		$channel["channel_name"] = "[cspacer]▌◥ $name ◤ ▌";
		return "Works";
	}
 return false;
}
while(1){
	$requests = getRequests();
	if(sizeof($requests) > 0){
		foreach($requests as $request){
			$req = readRequest($request);
			if($req == "stop"){
			  	createResult($request,"");
				readResult($request);
				die();
			}		
			$result = eval("return ".$req.";");
			 createResult($request,$result);
		}
	}
}
?>