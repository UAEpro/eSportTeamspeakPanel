<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////
//// This is a file for the Smart TS3 Panel made by UAEpro, BlackBird and eSport Team.            ////
//// If you get this files without UAEpro permission you might be in serious problems.            ////
//// This file or files content owned by eSport.ae and no one can use it without eSport.ae Owner. ////
////             If you have access to this file send email to admin@esport.ae                    ////
//////////////////////////////////////////////////////////////////////////////////////////////////////
ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_NOTICE);
session_start();
include("request.test.php");
// Setting is Here
// Allowed Pages :)
// الصفحات وروابطها
$pages = array(
// "name"  =>       "URL.php"   "CSS ICON"    "Show"
"الرئيسية" => array("index",'fa-globe',true),
"ايقونات الالعاب" => array("icons",'fa-gamepad',true),
"خصائص التيم سبيك" => array("setting",'fa-wrench',true),
"اليوتيوبرز" => array("youtube",'fa-youtube-play',true),
"نظام الكلانات" => array("clans",'fa-users',true,array()),
"المحرر الذكي" => array("editor",'fa-edit ',true),
"الأعضاء المحظروين" => array("bans",'fa-ban',false),
"addpost" => array("addpost",'',false),
"نظام الشكاوي" => array("contact",'fa-flag',false),
);

  if(!isset($_COOKIE["ClientDatabaseID"])) {
   $req = createRequest("getUser('".$_SERVER['REMOTE_ADDR']."')");
	while(!waitForResult($req)){}
   $result = json_decode(stripslashes(readResult($req)),true);
   if(isset($_POST['SelectAccount'])){
	//$cid = base64_encode(base64_encode(base64_encode($_POST['SelectAccount'])))/25;
	setcookie("ClientDatabaseID", $_POST['SelectAccount'], time() + 60*60, "/");
	} else {
	 if(sizeof($result) == 0){
		$notInTeamSpeak = true;
	 } elseif(sizeof($result) == 1){
		setcookie("ClientDatabaseID", base64_encode(base64_encode(base64_encode($result[0][1]*25))), time() + 60*60, "/");
	 }elseif(sizeof($result) >= 2){
		$SelectProfile = true;
	 }
	}
  }
$cid = base64_decode(base64_decode(base64_decode($_COOKIE["ClientDatabaseID"])))/25;
if($cid == 0)echo "<script>location.reload();</script>";
$req_ = createRequest("getInfo('".$_SERVER['REMOTE_ADDR']."',".$cid.")");
while(!waitForResult($req_)){}
$result  = readResult($req_);
$info = json_decode($result,true);
if(sizeof($info[0]) == 0){
	$notInTeamSpeak = true;
}
   $req2 = createRequest("getClientClan('".$_SERVER['REMOTE_ADDR']."',".$cid.")");
	while(!waitForResult($req2)){}
   $result2 = json_decode(stripslashes(readResult($req2)),true);
	if(empty($result2[0])){
	  array_push($pages["نظام الكلانات"][3],array("clans-list","قائمة الكلانات",true),array("cc","صنع كلان",true),array("joinClan","دخول كلان",false));
	} else {
		array_push($pages["نظام الكلانات"][3],array("clans-list","قائمة الكلانات",true),array("clans-members","أعضاء الكلان",true),array("clans-manger","لوحة التحكم بالكلان",true));
		if($result2[2] != "Owner"){
		array_push($pages["نظام الكلانات"][3],array("clans-leave","الخروج من الكلان",true));
		}
	}
	$isActive = true;
	if($result2[1] == false){
		if($_POST["accept"] != "accepted"){
			require_once("/var/www/ts3/libraries/TeamSpeak3/TeamSpeak3.php");
			$ts3 = TeamSpeak3::factory("serverquery://serveradmin:IwcrzOMP@tsip.esport.ae:10011/?server_port=9987");
			$channel = $ts3->channelGetById(10);
			$isActive = false;
		} else {
			$req = createRequest("ActivatClient(". $cid .")");
			while(!waitForResult($req)){}
			readResult($req);
		//	include("config.php");
			
		//	$fuckBader = $mysqli->query("SELECT *  FROM `Users` cdid=$cid";); // we named this variable because Bader is The biggest fucking gay
			if (mysqli_num_rows($fuckBader) > 0 ) {
			//	$mysqli->query("INSERT INTO `Users` (name,cdid,cuid,ip,Crystals) VALUES (?,?,?,?,?)");
			} else {
			//	$sql = "UPDATE `Users` SET name='Doe', cuid='Doe', ip='Doe', Crystals='Doe', WHERE cdid=$cid";
			//	$mysqli->query($sql);
			}
			$isActive = true;
		}
	}

// Maintenance mode ( true to lock the page to maintenance mode)
// وضع الصيانة
$maintenance = true;
if($_COOKIE["Access"] == "eSport"){
	$maintenance = false;
}


/* أمثلة الاستخدام
// مثال استخراج الكي
foreach($pages as $value){
if( current($pages) != $value) { prev($pages);}
echo key($pages)." => ".$value." <br>";
next($pages);
}

*/
 
function getStringBetween($str,$from,$to)
{
    $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
    return substr($sub,0,strpos($sub,$to));
}
function delete_all_between($beginning, $end, $string) {
  $beginningPos = strpos($string, $beginning);
  $endPos = strpos($string, $end);
  if ($beginningPos === false || $endPos === false) {
    return $string;
  }

  $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

  return str_replace($textToDelete, '', $string);
}
function remove_($string) {
  //$string = str_replace($bbcode, $htmlcode, $string);
  $string = nl2br($string);
  $string = str_replace("[center]","",$string);
  $string = str_replace("[/center]","",$string);
  $string = str_replace("<br />","",$string);
  $string = delete_all_between("[img]","[/img]",$string);
  $string = getStringBetween($string,"΅","΅");
  $string = substr($string,1);
  return $string;
}
  
 function getPages(){
	 global $pages;
	 $tmp = array();
	 foreach($pages as $page){
			 $tmp[$page[0]] = true;
			 if(is_array($page[3])){
				 foreach($page[3] as $page_){
					 $tmp[$page_[0]] = true;
				 }
			 }
	 }
	 return $tmp;
 }
 function getPagesName(){
	 global $pages;
	 $tmp = array();
	 foreach($pages as $key=>$page){
			 $tmp[$page[0]] = $key;
			 if(is_array($page[3])){
				 foreach($page[3] as $page_){
					 $tmp[$page_[0]] = $page_[1];
				 }
			 }
	 }
	 return $tmp;
 }
 header('Content-Type: text/html; charset=utf-8');  
 ini_set('default_charset','UTF-8'); 

/*
foreach($pages as  $key => $value){
if ( isset($_GET['page']) &&  $_GET['page'] == $value[0] {
	$pageNow = $key;
} else {
	 $pageNow = "الرئيسية";
}
*/
$pageNow = getPagesName()[$_GET["page"]];
if(!isset($pageNow)){
	$pageNow = "الرئيسية";
}
 ?>

<!DOCTYPE html>
<html>

<head>
<base href="/ePanel/page" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="text/html" >
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="eSport.ae Smart TS3 Panel, Where you can edit anything in ts3">
    <meta name="author" content="eSport.ae">


    <?php echo "<title>eSport Smart Panel | $pageNow </title>" ?>
	<?php 
if( $_GET['page'] == "editor" ){
	include("./include/editor.php");
	
}else{
	// فيه مشكلة غريبة بالجي كوايري بانه ما يقبل انه يتكرر مرتين 
	echo '<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>';
} ?>
    <!-- Bootstrap Core CSS -->
    <link href="./css/bootstrap-rtl.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="./css/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="./css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="./css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="./css/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="./css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link href="./css/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="./css/dataTables.responsive.css" rel="stylesheet">
	<link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css" rel="stylesheet"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->



</head>

<body>
<?
	if($isActive == false){
			 ?>
			 <link href="../tspanel/css/leftbar2.css" rel="stylesheet" />
			 <link href="skins/flat/blue.css" rel="stylesheet">
			 <script src="skins/icheck.js"></script>
				<script>
				$(document).ready(function(){
				  $('input').iCheck({
					checkboxClass: 'icheckbox_flat-blue',
					radioClass: 'iradio_flat-blue'
				  });
				});
				</script>
				<center><div class="logo"><img src="images/logo.png" alt="eSport" /></a></div>
				<br>
				<h1>قوآنين مجتمع eSport</h1>
				<br>
				 <textarea rows="10" cols="105" style="color: black; background-color: white; resize: none" readonly><?php echo remove_($channel->getInfo()["channel_description"]);?></textarea>
				 <br>
				 <br>
				 <form name='form' method='POST' action=''>
				<tr>
				<label>
				 <input type=checkbox name="accept" id="id" value="accepted" class=""  >  اتعهد وأقر بأني سأقوم بتطبيق القوانين في اي زمن كآن في مجتمع أي سبورت‬‎</label><br/>
				</tr>
				<br>
				</div><div align='center'><button type='submit' class='btn btn-default' >تفعيل</button></div></form>
				</center>
			 <?
		 die();}
		 ?>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="page/index">eSport Smart Panel</a>
            </div>
            <!-- /.navbar-header -->
		<?php if ($maintenance) { echo '        </nav>'; echo '<h1 style="text-align:center;">Maintenance - مغلق للصيانة</h1>'; die();} ?>
            <ul class="nav navbar-top-links navbar-left">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li>
                            <a>
                                <div>
                                    <span class="pull-right"><strong>نظام الرسائل الخاصة</strong></span>
                                    <span class="pull-left text-muted">
                                        <em>قريبا</em>
                                    </span>
                                </div>
								<br>
                                <div class="pull-right">تحت الدراسة والتطوير</div>
								<br>
                            </a>
                        </li>
                        <li class="divider"></li>
                    </ul>
                    <!-- /.dropdown-messages -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a >
                                <div>
                                    <i class="fa fa-bell fa-fw"></i> لا توجد تنبيهات جديدة
                                    <span class="pull-left text-muted small"></span>
                                </div>
                            </a>
                        </li>
        <?php /*         <li class="divider"></li>   
						<li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-tasks fa-fw"></i> New Task
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
					*/ // ^^^ مثال ^^ ?>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">

                        <li><a href="Login.php"><i class="fa fa-sign-in fa-fw"></i> تسجيل دخول</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->
	
			
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
					<?php

						foreach($pages as $value){
						if( current($pages) != $value) { prev($pages);}
						if( $value[2] ){
						echo "<li>";
						echo '<a href="page/'.$value[0].'"><i class="fa '.$value[1].' fa-fw"></i> '.key($pages).' </a>';
						$pagesNow  = $value[1];
						if($value[3]){
							echo '<ul class="nav nav-second-level collapse" >';
							 foreach($value[3] as $page){
								 if($page[2]){
									 if($page[0] == "clans-leave"){
									echo '<li><a href="javascript:void(0);" onclick="leaveClan();">'.$page[1].' </a></li>';
									 } else {
									echo '<li><a href="page/'.$page[0].'">'.$page[1].' </a></li>';
									 }
								 }
							 }
							echo '</ul>';
						}
						echo "</li>";
						}
						//echo key($pages)." => ".$value." <br>";
						next($pages);
						}
					?>					
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
		<?php
	     if($notInTeamSpeak){
			 include("notLogin.php");
			 die();
		 }
		 
	     if($SelectProfile){
			 include("profile.php");
			 die();
		 }
		?>
		<?php
		
			if (isset($_GET['page']) && file_exists($_GET["page"].".php") && $_GET['page'] != 'index' && getPages()[$_GET['page']] ) {
				include($_GET['page'].'.php');
				$pagesNow  = $value[1];
			} else {
				include('main.php');
			}
			

			?>
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->

    <!-- Bootstrap Core JavaScript -->
    <script src="./js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="./js/metisMenu.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>
<script src="http://www.chengxufan.com/codes/jquery.singleuploadimage.js/source/jquery.singleuploadimage.js"></script>
	<script>
	function leaveClan(){
		BootstrapDialog.confirm({
			title: 'تأكيد الأمر',
			message: 'هل انت متأكد انك تريد الخروج من الكلان؟',
			type: BootstrapDialog.TYPE_DANGER,
			closable: true, 
			draggable: true, 
			btnCancelLabel: 'إلغاء الأمر', 
			btnOKLabel: 'الخروج', 
			btnOKClass: 'btn-danger', 
			callback: function(result) {
				if(result) {
					xmlhttp = new XMLHttpRequest();
					xmlhttp.open("GET","leaveClan.php",true);
					xmlhttp.send();
					setInterval( function(){
					location.reload();						
					}, 5000 );					
				}
			}
		});
	}
	</script>
<?php 
if( $_GET['page'] == "index" || $_GET['page'] == "" ){
echo '
    <!-- Morris Charts JavaScript -->
    <script src="./js/raphael-min.js"></script>
    <script src="./js/morris.min.js"></script>
    <script src="./js/morris-data.js"></script>
';} 
if( $_GET['page'] == "clans-manger"){
echo '
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  
 <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>

<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script type="text/javascript" src="upload/js/jquery.form.min.js"></script>

';
echo <<<end
 <script>
     $(document).ready(function() {
        $("#sortable a").editable();
		$('#clanmsg').editable({
			success: changeMsg
		});
		$('#clanname').editable({
			success: changeName
		});
		$('#clantag').editable({
			success: changeTag
		});
    });
  $.fn.editableform.buttons = 
  '&nbsp;<button type="submit" class="btn btn-success editable-submit btn-mini"><i class="fa fa-check icon-white"></i></button>&nbsp;' +
 '<button type="button" class="btn btn-danger editable-cancel btn-mini"><i class="fa fa-remove"></i></button> ';         

</script>
end;
}
 ?>
    <!-- Custom Theme JavaScript -->
    <script src="./js/sb-admin-2.js"></script>


</body>
<footer>
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
               <a class="navbar-brand" style="float:left;" >eSport™ © 2014-2015 <center>
			   <a href="https://instagram.com/esport.ae/"><i class="fa fa-instagram e fa-3x"></i></a>&nbsp;
			   <a href="https://www.youtube.com/channel/UCIgaxXwUZ4q4jkE9rPpD7Fg"><i class="fa fa-youtube-square fa-3x"></i></a>&nbsp;
			   <a href="https://twitter.com/eSportAE"><i class="fa fa-twitter-square fa-3x"></i></a>&nbsp;
			   </center></a>
            <!-- /.navbar-header -->
        </nav>
</footer>
</html>
