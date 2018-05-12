<?
include("clans.config.php");
include("request.test.php");
$cid = base64_decode(base64_decode(base64_decode($_COOKIE["ClientDatabaseID"])))/25;
$clan = isMemberOfClan($cid);
if($clan != false){
	$result = mysqli_fetch_row($clan);
	leaveClan($result[7],$cid);
	$req = createRequest("removeGroup(".$result[5].",".$result[8].",".$cid.")");
	while(!waitForResult($req)){}
	readResult($req);
}
?>