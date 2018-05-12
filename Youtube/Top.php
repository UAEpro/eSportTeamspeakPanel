<?
require_once("../config.php");
$tbl_name="youtubers";
$Youtubers = array();

 $sql="SELECT * FROM `$tbl_name` ORDER BY subscribers DESC LIMIT 0 , 5";
  $result=mysqli_query($mysqli,$sql);
   while($youtuber = mysqli_fetch_assoc($result))
{
	 array_push($Youtubers,array(preg_replace('/[^a-z0-9_ ]/i', '', $youtuber["channelName"]),(int)$youtuber["subscribers"]));
}

echo json_encode($Youtubers);
?>