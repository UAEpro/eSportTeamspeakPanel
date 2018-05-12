<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//// This is a file for the Smart TS3 Panel made by UAEpro, BlackBird and eSport Team. 
//// If you get this files without UAEpro permission you might be in serious problems. 
//// This file or files content owned by eSport.ae and no one can use it without eSport.ae Owner. 
////             If you have access to this file send email to admin@esport.ae                 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
require_once '/var/www/youtube/src/Google/autoload.php';
require_once '/var/www/youtube/src/Google/Client.php';
require_once '/var/www/youtube/src/Google/Service/YouTube.php';
include("./config.php");


$Groups = array(500 => 20775,1000 => 20774, 2000 => 31501, 5000 => 20773, 10000 =>20776, 15000 => 20772, 20000 => 20778, 25000 => 31494, 30000 => 20770, 50000 => 31498,70000 => 31499,90000 =>31496, 100000 => 31495,200000=>60164,300000=>60163);
$NextRank = array(500 => 1000,1000=>2000,2000=>5000,5000=>10000,10000=>15000,15000=>20000,20000=>25000,25000=>30000,30000=>50000,50000=>70000,7000=>90000,90000=>100000,100000=>200000,200000=>30000,300000=> -1);

$cid = base64_decode(base64_decode(base64_decode($_COOKIE["ClientDatabaseID"])));
$cid = $cid/25;

$OAUTH2_CLIENT_ID = '497219884969-69d2h6jlpdmicb4a60jd698so5kfie47.apps.googleusercontent.com';
$OAUTH2_CLIENT_SECRET = 'vV-fUizyedFyqzzYGlbKjlD7';
$client = new Google_Client();
$client->setClientId($OAUTH2_CLIENT_ID);
$client->setClientSecret($OAUTH2_CLIENT_SECRET);
$client->setScopes('https://www.googleapis.com/auth/youtube');
$redirect = "http://esport.ae/ePanel/page/youtube";
$client->setRedirectUri($redirect);
$youtube = new Google_Service_YouTube($client);
$authUrl = $redirect;
 function getChannelTitle($channel){
			$url = 'https://www.googleapis.com/youtube/v3/channels?part=snippet&id='.$channel.'&key=AIzaSyCVyAUEhJ8oZNmcaszJVGp7YBfMaMC19BA';
			$json = file_get_contents($url);
			$array = json_decode($json,true);
			return $array["items"]["0"]["snippet"]["title"] ;
}

if (isset($_GET['code'])) {
  if (strval($_SESSION['yt_state']) !== strval($_GET['state'])) {
    die('The session state did not match.'.$_SESSION['yt_state']);
  }
  $client->authenticate($_GET['code']);
  $_SESSION['yt_token'] = $client->getAccessToken();
  echo "<script>window.location = '".$redirect."';</script>";
  die();
}
$rest_session = false;
if (isset($_SESSION['yt_token'])) {
  $client->setAccessToken($_SESSION['yt_token']);
  	$listResponse = $youtube->channels->listChannels('statistics', array('mine' => true));
    $channelID = $listResponse['items'][0]["id"];
	$channelName = getChannelTitle($channelID);
	$Subs = $listResponse['items'][0]["statistics"]["subscriberCount"];
	$UserRank = "error";
	$sgid = "error";
		foreach($Groups as $rank=>$value) {
		  if($Subs >= $rank){
			  $UserRank = $rank;
			  $sgid = $value;
		  }
		}
	if($UserRank == "error" || $sgid == "error"){
		echo "<script> $(function() {
			BootstrapDialog.show({
				title: 'الحصول على رتبة يوتيوبر',
				type: BootstrapDialog.TYPE_DANGER,
				message: 'عدد مشتركينك: ".$Subs." , تحتاج 500 مشترك على الاقل للحصول على رتبة يوتيوبر'
			});
 });</script>";
	} else {
			 $teamspeak_name = "error";
			 $client_id = "error";
			 
			 require_once("/var/www/libraries/TeamSpeak3/TeamSpeak3.php");
			 $ts3_VirtualServer =  TeamSpeak3::factory("serverquery://serveradmin:IwcrzOMP@tsip.esport.ae:10011/?server_port=9987");
			 $ts3_VirtualServer->execute("clientupdate", array("client_nickname" => "Youtuber Bot_".(string)rand()));
			 	function removeClientFromGroups($cldbid,$ts3){
					foreach(array(20775,20774,31501,20773,20776,20772,20778,31494,20770,31498,31499,31496,31495,60164,60163) as $gid_){
						$groups = $ts3->clientGetServerGroupsByDbid($cldbid)[$gid_];
                        if(gettype($groups) == "array"){
						$ts3->serverGroupClientDel($gid_,$cldbid);
						}
					}
				}
			  foreach($ts3_VirtualServer->clientList() as $client_){
				if($client_["client_type"]) continue;
					$clientInfo = $client_->getInfo();
						if( $clientInfo['client_database_id'] ==  $cid ) {
							$teamspeak_name = $clientInfo['client_nickname'];
							$client_id = $clientInfo['clid'];
						break;
						}
			}
			if($teamspeak_name != "error") {
			 $resultcldbid = mysqli_query($mysqli,"SELECT * FROM `youtubers` WHERE cldbid=".$cid);
			  if ( mysqli_num_rows($resultcldbid) > 0 ) {
				  $array = mysqli_fetch_assoc($resultcldbid);
				  removeClientFromGroups($cid,$ts3_VirtualServer);
				  if($array["roomid"] != 0 ){
					$room = $ts3_VirtualServer->channelGetById($array["roomid"]);
						if($room){
							$room->delete(true);
						}
				  }
				  mysqli_query($mysqli,"DELETE FROM `youtubers` WHERE cldbid=".$array["cldbid"]);
			  }
			  $resultchid = mysqli_query($mysqli,"SELECT * FROM `youtubers` WHERE channelid='".$channelID."'");
				if ( mysqli_num_rows($resultchid) > 0 ) {
					// then fuck bader :$
				  $array = mysqli_fetch_assoc($resultchid);
				  removeClientFromGroups($array["cldbid"],$ts3_VirtualServer);
					if($array["roomid"] != 0 ){
					if(array_key_exists((string) $array["roomid"], $ts3_VirtualServer->channelList())){
						$room = $ts3_VirtualServer->channelGetById($array["roomid"]);
					}
					if($room){
						$room->delete(true);
					}
				  }
				  mysqli_query($mysqli,"DELETE FROM `youtubers` WHERE cldbid=".$array["cldbid"]);
				}
			$ts3_VirtualServer->serverGroupClientAdd($sgid,$cid);
			$url = 'https://www.googleapis.com/youtube/v3/channels?part=snippet&id='.$channelID.'&key=AIzaSyDoJwPOuChK58UHMfTAiNeWN1e6O5BPu2w';
			$json = file_get_contents($url);
			$array = json_decode($json,true);
				if( $Subs >= 1000 ){
					$sub_cid = $ts3_VirtualServer->channelCreate(array(
						"channel_name" => $teamspeak_name,
						"channel_codec" => TeamSpeak3::CODEC_OPUS_VOICE,
						"channel_codec_quality"  => 0x06,
						"channel_flag_permanent" => TRUE,
						"channel_description" => "[url=https://www.youtube.com/channel/".$channelID."/videos][img]http://esport.ae/tspanel/youtube/img.php?id=".$cid."[/img][/url]",
						"cpid" => 1804,
					));
				$icons = array(20775 => 4103307298,20774 => 1037773196, 31501 => 875888601, 20773 => 2124213980, 20776 =>74866158, 20772 => 3839278884, 20778 => 3522277254, 31494 => 2117567079, 20770 => 741735535, 31498 => 1302797173,31499 => 396902174,31496 =>2035136388, 31495 => 650480192,60164=>2755250890,60163=>3174183629); 
				$channel = $ts3_VirtualServer->channelGetById($sub_cid);
				$channel["channel_icon_id"] = $icons[$sgid];
				$ts3_VirtualServer->clientMove($client_id,$sub_cid);
				$ts3_VirtualServer->clientSetChannelGroup($cid,$sub_cid,5);
			 } else {
				 $sub_cid = 0;
			 }
			 $uid = $ts3_VirtualServer->clientInfoDb($cid)["client_unique_identifier"];
			 mysqli_query($mysqli,"INSERT INTO `youtubers` VALUES ($cid,'$channelID',$Subs,'$channelName','$teamspeak_name',$sub_cid,'$uid')");
			 //echo str_replace("000+","k+","<div dir='ltr'>".$text."</div>");UserRank
				  		echo "<script> $(function() {
							BootstrapDialog.show({
								title: 'الحصول على رتبة يوتيوبر',
								type: BootstrapDialog.TYPE_DANGER,
								message: 'مبروك لقد حصلت على رتبة يوتيوبر'
							});
				 });</script>";
			} else {
				  //echo "<b>يجب عليك التواجد في التيم سبيك لإضافة الرتبة !</b>";
				  		echo "<script> $(function() {
							BootstrapDialog.show({
								title: 'الحصول على رتبة يوتيوبر',
								type: BootstrapDialog.TYPE_DANGER,
								message: 'يجب عليك التواجد في التيم سبيك لإضافة الرتبة !'
							});
				 });</script>";
			 }
	}
	$rest_session = true;
}
if (!$client->getAccessToken()) {
  $state = mt_rand();
  $client->setState($state);
  $_SESSION['yt_state'] = $state;
  $authUrl = $client->createAuthUrl();
}
 if($rest_session){
	 echo '<script>$(function() { 
					setInterval( function(){
						xmlhttp = new XMLHttpRequest();
						xmlhttp.open("GET","http://esport.ae/ePanel/unset.php",true);
						xmlhttp.send(); 
					}, 2000 );
				} );</script>';
 }

$result = $mysqli->query("SELECT * FROM `youtubers` WHERE `cldbid` = '".$info[0]["client_database_id"]."' LIMIT 1");
if($result->num_rows > 0){
	$Channel = $result->fetch_assoc();
	$Channel["Rank"] = 20775;
	$Channel["Rank2"] = 500;
		foreach($Groups as $group => $gid_){
			if($Channel["subscribers"] >= $group){
				$Channel["Rank"] = $gid_;
				$Channel["Rank2"] = $group;
				$Channel["NextRank"] = $NextRank[$group];
			}
		}
	$Channel["Rank2"] .= "+";
	$isYoutuber = true;
	$Progress = (($Channel['subscribers']-$Channel["Rank2"])/($Channel['NextRank']-$Channel["Rank2"])*100);
if($Channel["NextRank"] == -1)$Progress = 100;
} else {
$isYoutuber = false;
}
?>


<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">صفحة اليوتيوبرز</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-red">
				<div class="panel-heading">
					<div class="row">
						<div class="col-lg-1 col-md-2 col-sm-2 col-xs-2 ">
							<i class="fa fa-youtube-play fa-5x"></i>
						</div>
						<div class="">
						<?php if( $isYoutuber) {
						echo    '<div class="huge">'.$Channel["channelName"].'</div>';
						} else {
						echo    '<div class="huge">لا توجد لديك قناة</div>';
						} ?>
							<div></div>
						</div>
					</div>
				</div>
					<div class="panel-footer">
						<?php if( $isYoutuber) {
						echo "<div class='pull-right h3'>عدد المشتركين : ".number_format($Channel['subscribers'])."</div>"; 
						echo "<div class='pull-left h3' dir='ltr'>TS3 Rank :&nbsp;<img src='http://esport.ae/tspanel/icons/getGroupIcon.php?id=".$Channel["Rank"]."'/>&nbsp;".str_replace("000+","k+",$Channel['Rank2'])."</div>";  
						echo ' <br><br><br>
						<div class="progress progress-striped active text-center">
								<div class="progress-bar progress-bar-danger" role="progressbar"  style="width: '.$Progress.'%">
									<span class="sr-only">0% Complete (danger)</span>
								</div>
							</div>
						'; 
							
						} else {
						echo '<a href="'.$authUrl.'" class="text-center btn btn-outline btn-danger btn-block btn-lg"> اضغط هنا للحصول على رتبة يوتيوبرز </a>';
						} ?>
						<div class="clearfix"></div>
					</div>
			   
			</div>
		</div>			
	</div>			
	<!-- /.row -->
	<div class="row">
		<div class="col-md-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					قائمة اليوتيوبرز
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover text-center">
							<thead>
								<tr>
									<th>#</th>
									<th>الرتبة</th>
									<th>الاسم</th>
									<th>اسم القناة</th>
									<th>عدد المشتركين</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
							<?
							   $mysqli->query("SET NAMES 'utf-8'");  
							$result=$mysqli->query("SELECT * FROM `youtubers` ORDER BY subscribers DESC LIMIT 0 , 30");
							$i = 1;
							while($youtuber = mysqli_fetch_assoc($result)){
								$gid = 3803;
								foreach($Groups as $group => $gid_){
									if($youtuber["subscribers"] >= $group){
										$gid = $gid_;
									}
								}
							echo '<tr>';
							echo '<td>'.$i.'</td>';
							echo '<td><img src="http://esport.ae/tspanel/icons/getGroupIcon.php?id='.$gid.'" /></td>';
							echo '<td>'.$youtuber["TeamSpeakName"].'</td>';
							echo '<td>'.$youtuber["channelName"].'</td>';
							echo '<td>'.number_format($youtuber["subscribers"]).'</td>';
							echo '<td></td>';
							echo '</tr>';
							$i++;
							}
							?>
							</tbody>
						</table>
					</div>
					<!-- /.table-responsive -->
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					رتب اليوتيوبر
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover text-center">
							<thead>
								<tr>
									<th>الرتبة</th>
									<th>عدد المشتركين</th>
								 </tr>
							</thead>
							<tbody>
							<?
							foreach ($Groups as $subs => $id) {
							echo '<tr>';
							echo '<td><img src="http://esport.ae/tspanel/icons/getGroupIcon.php?id='.$id.'" /></td>';
							$subs = $subs."+";
						    echo '<td>'.str_replace("000+","k+",$subs).'</td>';
							echo '</tr>';
							}
							?>
							</tbody>
						</table>
					</div>
					<!-- /.table-responsive -->
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>

	</div>
<!-- /#page-wrapper -->
</div>