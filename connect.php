
<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(0);
require_once("/var/www/libraries/TeamSpeak3/TeamSpeak3.php");
include("./config.php");
include("./request.php");
try {
array_map('unlink', glob("./requests/*"));
$ts3 = TeamSpeak3::factory("serverquery://" . $ts3user . ":" . $ts3pass . "@" . $ts3host . ":10011/?server_port=9987");
$ts3->execute("clientupdate", array("client_nickname" => "MI6"));
function isClientMebmerOfServerGroup($client,$sgid){
	foreach($client->memberOf() as $key =>$value){
		if($value->getID() == $sgid){
			return true;
		}	
	}
	return false;
}
$Levels = array(
1 => 62490, // 1
2 => 62491, // 2
3 => 62492, // 3
4 => 62493, // 4
5 => 62494, // 5
6 => 62495, // 6
7 => 62496, // 7
8 => 62497, // 8
9 => 62498, // 9
10 => 62499, // 10
11 => 62500, // 11
12 => 62501, // 12
13 => 62502, // 13
14 => 62503, // 14
15 => 62504, // 15
16 => 62505, // 16
17 => 62506, // 17
18 => 62507, // 18
19 => 62508, // 19
20 => 62509, // 20
21 => 62510, // 21
22 => 62511, // 22
23 => 62512, // 23
24 => 62513, // 24
25 => 62514, // 25
26 => 62515, // 26
27 => 62516, // 27
28 => 62517, // 28
29 => 62518, // 29
30 => 62519, // 30
31 => 62627, // 31
32 => 62628, // 32
33 => 62629, // 33
34 => 62630, // 34
35 => 62631, // 35
36 => 62632, // 36
37 => 62633, // 37
38 => 62634, // 38
39 => 62635, // 39
40 => 62636, // 40
41 => 62637, // 41
42 => 62638, // 42
43 => 62639, // 43
44 => 62640, // 44
45 => 62641, // 45
46 => 62642, // 46
47 => 62643, // 47
48 => 62644, // 48
49 => 62645, // 49
50 => 62646, // 50
51 => 62647, // 51
52 => 62648, // 52
53 => 62649, // 53
54 => 62650, // 54
55 => 62651, // 55
56 => 62652, // 56
57 => 62653, // 57
58 => 62654, // 58
59 => 62655, // 59
60 => 62656, // 60
61 => 62657, // 61
62 => 62658, // 62
63 => 62659, // 63
64 => 62660, // 64
65 => 62661, // 65
66 => 62662, // 66
67 => 62663, // 67
68 => 62664, // 68
69 => 62665, // 69
70 => 62666, // 70
71 => 62667, // 71
72 => 62668, // 72
73 => 62669, // 73
74 => 62670, // 74
75 => 62671, // 75
76 => 62672, // 76
77 => 62673, // 77
78 => 62674, // 78
79 => 62675, // 79
80 => 62676, // 80
81 => 62677, // 81
82 => 62678, // 82
83 => 62679, // 83
84 => 62680, // 84
85 => 62681, // 85
86 => 62682, // 86
87 => 62683, // 87
88 => 62684, // 88
89 => 62685, // 89
90 => 62686, // 90
91 => 62687, // 91
92 => 62688, // 92
93 => 62689, // 93
94 => 62690, // 94
95 => 62691, // 95
96 => 62692, // 96
97 => 62693, // 97
98 => 62694, // 98
99 => 62695, // 99
100 => 62696 // 100
);
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
 function bannerInfo($ip){
	 global $ts3;
	 $name = "";
	 $ts3->clientListReset();
	 foreach($ts3->clientList() as $client){
		 if($ip == $client["connection_client_ip"]){
			 $name = (string)$client["client_nickname"];
			 break;
		 }
	 }
	 return json_encode(array($name,sizeof($ts3->clientList()),$ts3["virtualserver_maxclients"]));
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
  function getClientClan($ip,$client_database_id){
	 global $ts3;
	 $ts3->clientListReset();
	 $ts3->serverGroupListReset();
	 $groups = array();
	foreach($ts3->clientList() as $client){
		if($ip == $client["connection_client_ip"]){
			if($id == false || ($id && $client["client_database_id"] == $id)){
			foreach($client->memberOf() as $group){
				if($group["sortid"] == 650){
				$groups[$group->getID()] = true;
				}
			}
			break;
		}
		}
	}
	return json_encode($groups);
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
	return json_encode(array($groups,$servergroups));
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
	return json_encode($info);
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
		 $group = str_replace('♦« ','',$group);
		 $group = str_replace(' »♦','',$group);
		 array_push($groups,array((string)$group,$players));
		 $i++;
	 }
	 return json_encode($groups);
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
function getAvatar($id){
	global $ts3;
	$ts3->clientListReset();
	return $ts3->clientGetByDbid($id)->avatarDownload();
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
}
catch(Exception $e)
{
  //die(exec ("php connect.php"));
  echo ( 'Message: ' .$e->getMessage());
}
?>