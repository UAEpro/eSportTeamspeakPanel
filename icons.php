<link href="skins/flat/blue.css" rel="stylesheet">
<script src="skins/icheck.js"></script>
<script>
$(document).ready(function(){
  $('input').iCheck({
    checkboxClass: 'icheckbox_flat-blue',
    radioClass: 'iradio_flat-blue'
  });
	if ($(this).find("input:checkbox").filter(":checked").length >= 2) {
		checkboxes = $(this).find("input:checkbox");  
		checkboxes.not(":checked").iCheck('disable');
	}
$(document).on("ifToggled", function() {
    checkboxes = $(this).find("input:checkbox");  

    if (checkboxes.filter(":checked").length >= 2) { 
        checkboxes.not(":checked").iCheck('disable'); 
    } else { 
        checkboxes.not(":checked").iCheck('enable');
    } 
});
});
</script>
<?php
$cid = base64_decode(base64_decode(base64_decode($_COOKIE["ClientDatabaseID"])));
$cid = $cid/25;
function isClientMebmerOfServerGroup($client,$sgid){
	global $ts3;
	foreach($ts3->clientGetServerGroupsByDbid($client) as $key =>$value){
		if($key == $sgid){
			return true;
		}	
	}
	return false;
}
if($cid == 0)echo "<script>location.reload();</script>";
	if($_POST == null){
		$_POST = array();
	}
$req = createRequest("iconsInfo('".$_SERVER['REMOTE_ADDR']."',".$cid.")");
while(!waitForResult($req)){}
$result  = readResult($req);
$info = json_decode(($result),true);
$Groups = $info[0];
$Games = $info[1];
  if(is_array($_POST) && strpos($_SERVER['HTTP_REFERER'],"/page/icons")){
	  $add = array();
	  $rem = array();
	  foreach($Games as $game){
		  array_push($rem,$game["id"]);
	  }
	  if(is_array($_POST["game"])){
	  foreach($_POST["game"] as $group){
		  if (($key = array_search($group, $rem)) !== false) {
			unset($rem[$key]);
			}
		array_push($add,$group);
	  }
	  }
   require_once("/var/www/libraries/TeamSpeak3/TeamSpeak3.php");
   include("./config.php");
   mysqli_close($mysqli);
	$ts3 = TeamSpeak3::factory("serverquery://" . $ts3user . ":" . $ts3pass . "@" . $ts3host . ":10011/?server_port=9987");
	$ts3->execute("clientupdate", array("client_nickname" => "Games [".(string)rand()."]"));
	  	foreach($add as $group){
			if(!isClientMebmerOfServerGroup($cid,$group)){
				$ts3->serverGroupClientAdd($group,$cid);
				$Groups[$group] = true;
			}
		}
	foreach($rem as $group){
		if(isClientMebmerOfServerGroup($cid,$group)){
			$ts3->serverGroupClientDel($group,$cid);
			$Groups[$group] = false;
		}
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
			<h1 class="page-header">ايقونات الألعاب</h1>
			</div>
		</div>
			<div class="row">
				<div class="col-lg-6 col-centered">
					<div class="panel panel-default">
						<div class="panel-heading">
							<center>
								<i class="fa fa-gamepad"></i><label>&nbsp; أيقونات الألعاب &nbsp;</label><i class="fa fa-gamepad"></i>
							</center>
						</div>
							<div class="panel-body">
							 <form name='form' method='POST' action=''>
							 <center>
							 <?php
							 	echo '<h1 align="center">Select Games</h1>';
								echo '<br>';
								echo '<div align="center" style="margin: 0px;" dir="ltr">';
								echo '<table>';
							 $i = 1;
							 foreach($Games as $group) { 
								if($Groups[$group["id"]]) {
									if ($i % 2 != 0) {
										echo '<tr><td>';
										echo '<label>  ';
										echo '• <input type=checkbox name=game['.$group["id"].'] id="'.$group["id"].'" value="'. $group["id"] .'"class="icono" checked > <img src="http://esport.ae/icon/'.$group['id']. '.png" alt="" />  '.$group["name"].'&nbsp;&nbsp;&nbsp;&nbsp;</label><br/>';
										echo '</td>';
									} else {
										echo '<td>';
										echo '<label>  ';
										echo '• <input type=checkbox name=game['.$group["id"].'] id="'.$group["id"].'" value="'. $group["id"] .'"class="icono" checked > <img src="http://esport.ae/icon/'.$group['id']. '.png" alt="" />  '.$group["name"].'</label><br/>';
										echo '</td></tr>';
									}
									} else {
										if ($i % 2 != 0) {
											echo '<tr><td>';
											echo '<label>  ';
											echo '• <input type=checkbox name=game['.$group["id"].'] id="'.$group["id"].'" value="'. $group["id"] .'"class="icono" > <img src="http://esport.ae/icon/'.$group['id']. '.png" alt="" />  '.$group["name"].'&nbsp;&nbsp;&nbsp;&nbsp;</label><br/>';
											echo '</td>';
										} else {
											echo '<td>';
											echo '<label>  ';
											echo '• <input type=checkbox name=game['.$group["id"].'] id="'.$group["id"].'" value="'. $group["id"] .'"class="icono" > <img src="http://esport.ae/icon/'.$group['id']. '.png" alt="" />  '.$group["name"].'</label><br/>';
											echo '</td></tr>';
										} 
									}
										$i++;
							 }
							 echo '</table>';
							 ?>
							 <br><br><br>
								<button type="submit" class="btn btn-danger">تطبيق</button></center></form>
							</div>
					</div>
				</div>
			</div>
</div>