<?php
// Mysql Host
$host = "127.0.0.01";
// Mysql User
$user = "root";
// Mysql Password
$password = "XPApkV8w";
// Mysql Database
$db = "TS3-1";

$mysqli = mysqli_connect($host,$user,$password,$db);

// Clan System - Functions -

 function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array();
    $alphaLength = strlen($alphabet) - 1; 
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}
// Attempt Functions
function attemptToCreateClan($name,$tag,$owner){
	global $mysqli;
	$id = randomPassword();
	$Members = array($owner);
	if(mysqli_query($mysqli,"INSERT INTO `attemptToCreateClan`(`name`, `tag`, `joined`, `uniqueID`,`time`) VALUES ('$name','$tag','".json_encode($Members)."','$id',".time().")")){
		return array($id);
	}
	return false;
}
function attemptToJoinClan($id,$member){
	global $mysqli;
	$result = mysqli_query($mysqli,"SELECT joined FROM attemptToCreateClan WHERE uniqueID='" . $id . "'");
	if($result){
	$Members = mysqli_fetch_row($result);
	$Members = json_decode($Members[0]);
	array_push($Members,$member);
    mysqli_query($mysqli,"UPDATE attemptToCreateClan SET joined='" . json_encode($Members) . "' WHERE uniqueID='" . $id . "'");
	return sizeof($Members);
	}
	return false;
}
function isMemberOfClan($member){
	global $mysqli;
	$result = mysqli_query($mysqli,"SELECT * FROM `attemptToCreateClan` WHERE `joined` LIKE '%$member,%'");
	if(mysqli_num_rows($result) > 0 ){
		return $result;
	}
	$result2 = mysqli_query($mysqli,"SELECT * FROM `attemptToCreateClan` WHERE `joined` LIKE '%$member]%'");
	if(mysqli_num_rows($result2) > 0 ){
		return $result2;
	}
	$result = mysqli_query($mysqli,"SELECT * FROM `Clans` WHERE `members` LIKE '%$member,%'");
	if(mysqli_num_rows($result) > 0 ){
		return $result;
	}
	$result2 = mysqli_query($mysqli,"SELECT * FROM `Clans` WHERE `members` LIKE '%$member]%'");
	if(mysqli_num_rows($result2) > 0 ){
		return $result2;
	}
  return false;
}
function isClanOwner($member){
	global $mysqli;
	$result = mysqli_query($mysqli,"SELECT * FROM `attemptToCreateClan` WHERE `joined` LIKE '%[$member%'");
	if(mysqli_num_rows($result) > 0 ){
		return $result;
	}
  return false;
}
function attemptToDeleteClan($id){
	global $mysqli;
	$result = mysqli_query($mysqli,"SELECT * FROM attemptToCreateClan WHERE uniqueID='" . $id . "'");
	if($result){
		return mysqli_query($mysqli,"DELETE FROM attemptToCreateClan WHERE uniqueID='" . $id . "'");
	}
 return false;
}
function isAttemptClanExists($id){
	global $mysqli;
	$result = mysqli_query($mysqli,"SELECT * FROM attemptToCreateClan WHERE uniqueID='" . $id . "'");
	if(mysqli_num_rows($result) > 0){
		return $result;
	}
 return false;
}
//
function getClans(){
	 global $mysqli;
	$result = mysqli_query($mysqli,"SELECT * FROM Clans");
	return $result;
}
function createClan($id,$room,$group){
	global $mysqli;
	$result = mysqli_query($mysqli,"SELECT * FROM attemptToCreateClan WHERE uniqueID='" . $id . "'");
	if(mysqli_num_rows($result) > 0){
	$result = mysqli_fetch_row($result);
	$name = $result[1];
	$tag = $result[2];
	$Members = $result[3];
	mysqli_query($mysqli,"DELETE FROM attemptToCreateClan WHERE uniqueID='" . $id . "'");
	mysqli_query($mysqli,"INSERT INTO `Clans`(`name`, `tag`, `description`, `members`, `joinMethod`, `CCID`, `uniqueID`,`SGID`) VALUES ('$name','$tag','','$Members','invite',$room,'$id',$group)");
	return "true";
	}
    return "false";
}
function getClanFromID($id){
	global $mysqli;
	$result = mysqli_query($mysqli,"SELECT * FROM Clans WHERE uniqueID='" . $id . "'");
	if(mysqli_num_rows($result) > 0){
		return $result;
	}
 return false;
}
function getClanFromName($name){
	global $mysqli;
	$result = mysqli_query($mysqli,"SELECT * FROM Clans WHERE name='" . $name . "'");
	if(mysqli_num_rows($result) > 0){
		return $result;
	}
 return false;
}
function getClanFromTag($tag){
	global $mysqli;
	$result = mysqli_query($mysqli,"SELECT * FROM Clans WHERE tag='" . $tag . "'");
	if(mysqli_num_rows($result) > 0){
		return $result;
	}
 return false;
}
function joinClan($id,$member){
	global $mysqli;
	$result = mysqli_query($mysqli,"SELECT members FROM Clans WHERE uniqueID='" . $id . "'");
	if(mysqli_num_rows($result) > 0){
	$Members = mysqli_fetch_row($result);
	$Members = json_decode($Members[0]);
	array_push($Members,$member);
    return mysqli_query($mysqli,"UPDATE Clans SET members='" . json_encode($Members) . "' WHERE uniqueID='" . $id . "'");
	}
	return false;
}
function leaveClan($id,$member){
	global $mysqli;
	$result = mysqli_query($mysqli,"SELECT members FROM Clans WHERE uniqueID='" . $id . "'");
	if(mysqli_num_rows($result) > 0){
	$Members = mysqli_fetch_row($result);
	$Members = json_decode($Members[0],true);
	$newMember = array();
	foreach($Members as $mem){
		if($mem != $member){
			array_push($newMember,$mem);
		}
	}
    return mysqli_query($mysqli,"UPDATE Clans SET members='" . json_encode($newMember) . "' WHERE uniqueID='" . $id . "'");
	}
	return false;
}
function changeJoinMethod($id,$newValue){
	global $mysqli;
		return mysqli_query($mysqli,"UPDATE Clans SET joinMethod='$newValue' WHERE uniqueID='" . $id . "'");
}
function changeClanMsg($id,$newValue){
	global $mysqli;
		return mysqli_query($mysqli,"UPDATE Clans SET description='$newValue' WHERE uniqueID='" . $id . "'");
}
function changeClanTag($id,$newValue){
	global $mysqli;
		return mysqli_query($mysqli,"UPDATE Clans SET tag='$newValue' WHERE uniqueID='" . $id . "'");
}
function changeClanName($id,$newValue){
	global $mysqli;
		return mysqli_query($mysqli,"UPDATE Clans SET name='$newValue' WHERE uniqueID='" . $id . "'");
}
?>