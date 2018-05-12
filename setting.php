<?
 $Settings = array(
  "Anti-poke" => 3358,
  "No Text Messages"=> 60166,
  "Anti Announce Messages" => 59642,
  "No Whisper" => 60165,
  "No Afk Mover" => array(62697,array(12850,12849,12848,12847,12846)),
 );
  $Allows = array(3358=>true,59642=>true,60165=>true,60166=>true,62448=>true);
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
  if(is_array($_POST) && strpos($_SERVER['HTTP_REFERER'],"/page/setting")){
	  
	  $add = array();
	  $rem = array();
	  foreach($_POST as $key=>$value){
		 if($Allows[base64_decode(base64_decode(base64_decode($value)))/30]){
			array_push($add,base64_decode(base64_decode(base64_decode($value)))/30);
	  }
   }
   foreach($Settings as $value){
	   if(is_array($value))$value = $value[0];
	   if(!in_array($value,$add)){
		   array_push($rem,$value);
	   }
   }
   require_once("/var/www/libraries/TeamSpeak3/TeamSpeak3.php");
   include("./config.php");
   mysqli_close($mysqli);
	$ts3 = TeamSpeak3::factory("serverquery://" . $ts3user . ":" . $ts3pass . "@" . $ts3host . ":10011/?server_port=9987");
	$ts3->execute("clientupdate", array("client_nickname" => "Options [".(string)rand()."]"));
	foreach($add as $group){
		if(!isClientMebmerOfServerGroup($cid,$group)){
		$ts3->serverGroupClientAdd($group,$cid);
		}
	}
	foreach($rem as $group){
		if(isClientMebmerOfServerGroup($cid,$group)){
		$ts3->serverGroupClientDel($group,$cid);
		}
	}
 }
$req = createRequest("getClientServerGroups('".$_SERVER['REMOTE_ADDR']."',".$cid.")");
while(!waitForResult($req)){}
$result  = readResult($req);
$info = json_decode(stripslashes($result),true);
 $AllowAFk = "disable";
  function getName($val){
	  if($val == "Anti-poke" || $val == "No Text Messages"){
		  return "Group1";
	  }
	  if($val == "No Afk Mover"){
		  return "NoAFk";
	  }
	  return $val;
  }
  $Checked = $info;
 $allow = "disable";
	foreach($Settings["No Afk Mover"][1] as $group){
		if($Checked[$group]){
			$allow = "enable";
			break;
		}
	}
 ?>
 <link href="skins/flat/blue.css" rel="stylesheet">
<script src="skins/icheck.js"></script>
<script>
$(document).ready(function(){
  $('input').iCheck({
    checkboxClass: 'icheckbox_flat-blue',
    radioClass: 'iradio_flat-blue'
  });
  $(this).find("input[id='NoAFk']").not(":checked").iCheck("<?php echo $allow; ?>");	
	if ($(this).find("input[id='Group1']").filter(":checked").length >= 1) {
		checkboxes = $(this).find("input[id='Group1']");  
		checkboxes.not(":checked").iCheck('disable');
	}
$(document).on("ifToggled", function() {
    checkboxes = $(this).find("input[id='Group1']");
    if (checkboxes.filter(":checked").length >= 1) { 
        checkboxes.not(":checked").iCheck('disable'); 
    } else { 
        checkboxes.not(":checked").iCheck('enable');
    } 
});
});
</script>
<style>
.col-centered{
float: none;
margin: 0 auto;
}
</style>
 <div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
			<h1 class="page-header">خصائص التيم سبيك</h1>
			</div>
		</div>
			<div class="row">
				<div class="col-lg-4 col-centered">
					<div class="panel panel-default">
						<div class="panel-heading">
							<center>
								<i class="fa fa-wrench"></i><label>&nbsp; خصائص التيم سبيك&nbsp;</label><i class="fa fa-wrench"></i>
							</center>
						</div>
							<div class="panel-body" dir="ltr">
							 <form name='form' method='POST' action=''>
								<?
								 foreach($Settings as $name=>$setting){
									 if(is_array($setting)){
										$setting = $setting[0];
									 }
									 $txt = $name;
									 if($txt=="No Afk Mover")$txt .= " ( للعضويات الخاصة )";
									 $id = base64_encode(base64_encode(base64_encode($setting*30)));
									 if($Checked[$setting]){
									 echo '<input type=checkbox name="'.getName($name).'" id="'.getName($name).'" value="'.$id.'" class="icono" checked ><label>&nbsp;&nbsp;'.$txt.'</label><br/>';
									 } else {
										 echo '<input type=checkbox name="'.getName($name).'" id="'.getName($name).'" value="'.$id.'" class="icono"  ><label>&nbsp;&nbsp;'.$txt.'</label><br/>';
									 }
								 }
								?>
								<center><button type="submit" class="btn btn-danger">تطبيق</button></center></form>
							</div>
					</div>
				</div>
			</div>
</div>