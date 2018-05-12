<?php
/************************************/
/* File Created By eSport Team */
/************************************/

$mysqli_ = new mysqli("localhost", "root", "XPApkV8w", "TS3-1");
$mysqli_->query("SET NAMES 'utf-8'");  
$mysqli_->select_db("TS3-1");
$mysqli_->query("SET CHARACTER SET utf8");

 function getRequests(){
	global $mysqli_;
	 $files = array();
	$result=$mysqli_->query("SELECT * FROM `Requests`");
	while($request = mysqli_fetch_assoc($result)){
		if($request["Result"] == ""){
		array_push($files,$request["id"]);
		}
	}
	return $files;
 }

  function readRequest($request){
	global $mysqli_;
	 $result=$mysqli_->query("SELECT * FROM `Requests` WHERE id=$request");
	 $res = mysqli_fetch_row($result);
	 return $res[1];
 }
  function readResult($request){
	global $mysqli_;
	  $result=$mysqli_->query("SELECT * FROM `Requests` WHERE id=$request");
	  $res = mysqli_fetch_row($result);
	  $result_ = $res[2];
	  mysqli_query($mysqli_,"DELETE FROM Requests WHERE id=" . $res[0] . "");
	 return $result_;
 }
function createRequest($code){
	global $mysqli_;
    mysqli_query($mysqli_,'INSERT INTO `Requests`(`Request`, `Result`) VALUES ("'.$code.'","")');
	return mysqli_insert_id($mysqli_);
}
function createResult($request,$code){
	global $mysqli_;
	return mysqli_query($mysqli_,"UPDATE Requests SET Result='$code' WHERE id=$request");
}
function clearRequest(){
	global $mysqli_;
	return mysqli_query($mysqli_,"DELETE FROM Requests");
}
function waitForResult($id){
	  global $mysqli_;
	  $result=$mysqli_->query("SELECT * FROM `Requests` WHERE id=$id");
	  $res = mysqli_fetch_row($result);
	  if($res[2] == ""){
		  return false;
	  }
	 return true;
}
?>