<?php
	ini_set('display_errors', 'On');
	error_reporting(E_ALL & ~E_NOTICE);
	include("clans.config.php");
	$host = "127.0.0.01";
	// Mysql User
	$user = "root";
	// Mysql Password
	$password = "XPApkV8w";
	// Mysql Database
	$db = "TS3-1";
 	  $cid = base64_decode(base64_decode(base64_decode($_COOKIE["ClientDatabaseID"])));
	  $cid = $cid/25;
	$mysqli = mysqli_connect($host,$user,$password,$db);
	$result = mysqli_query($mysqli,"SELECT * FROM `URLs` WHERE `id`='".$_GET["code"]."'");
	if(mysqli_num_rows($result) > 0){
		$result = mysqli_fetch_row($result);
		if($result[2]+(60*2) >= time()){
		$Clan = getClanFromID(base64_decode($result[1]));
		$Clan = mysqli_fetch_row($Clan);
			if($_GET["j"]){
				joinClan($Clan[7],$cid);
				$req = createRequest("addGroup(".$Clan[8].",$cid)");
				while(!waitForResult($req)){}
				$Ranks = readResult($req);
				$Joined = true;
				mysqli_query($mysqli,"DELETE FROM `URLs` WHERE `id`='".$_GET["code"]."'");
			}
		} else {
			mysqli_query($mysqli,"DELETE FROM `URLs` WHERE `id`='".$_GET["code"]."'");
		}
	}
?>
<style>
.col-centered{
float: none;
margin: 0 auto;
}
</style>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">دخول الكلان</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>	
	<? if(!isset($Clan)){ ?>
		<div class="alert alert-danger">
			<strong>الرابط غير صالح للعمل</strong>
		</div>	
	<? }elseif($Joined){ ?>
		<div class="alert alert-success">
			<strong>لقد انضممت للكلان بنجاح</strong>
		</div>	
	<? } else { ?>
	<div class="row">
 <div class="row">
<div class="col-sm-6 col-sm-offset-3 col-centered ">
    <div class="panel panel-default" style="margin-top: 10%;">
	<div class="panel-heading">
    <h3>دخول كلان</h3>
	</div>
	   <div class="panel-body">
		<fieldset>
		<ul class="nav nav-list">
		<li class="nav-header h4">اسم الكلان: <? echo $Clan[1]; ?></li>
		<li class="nav-header h4">اختصار الكلان: <? echo $Clan[2]; ?></li>
		<li class="nav-header h4">رسالة الكلان: <? echo $Clan[3]; ?></li>
		<li class="nav-header h4">ملاحظة: قد يكون هذا الكلان خطر على صحتك .. هل أنته متأكد من الإنظمام؟</li>
		</ul> 
	</fieldset> 
	<br>
	   <center><button class="btn btn-danger" id="submit" onclick="join();">تأكيد الإنظمام</button></center>
		</div>
	</div>

    </div>
</div>
	</div>
</div>
<script>
function join(){
	window.location.href='http://esport.ae/ePanel/page/<? echo basename($_SERVER['REQUEST_URI']); ?>&j=j';
}
</script>
<? } ?>