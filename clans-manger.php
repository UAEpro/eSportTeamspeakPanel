<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_NOTICE);
	  include("clans.config.php");
	  
 $Leader = true;
 $MyRank = "";
 $clan = isMemberOfClan($cid);
 		   $result = mysqli_fetch_row($clan);
 	  $cid = base64_decode(base64_decode(base64_decode($_COOKIE["ClientDatabaseID"])));
	  $cid = $cid/25;
 	$req = createRequest("getClanMembersGroup(".$result[5].")");
	while(!waitForResult($req)){}
	$Ranks = json_decode(stripslashes(readResult($req)),true);
	$isLeader = true;//$Ranks[$cid] == "Owner" || $Ranks[$cid] == "Co-Owner" || $Ranks[$cid] == "Leader" ? true : false;
	if(!isset($Ranks[$cid])){ 
	$MyRank = "";
	} else {
	$MyRank = $Ranks[$cid];
	}
  if(!$isLeader){
	  ?>
	  <div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">لوحة التحكم</h1>
			</div>
		</div>
			<div class="alert alert-danger">
				<strong>ليس لديك صلاحيات لدخول الصفحة</strong>
			</div>			
	 </div>
	  <?
  } else {
	   if($clan != false && mysqli_num_rows($clan) > 0){
		   $Members = json_decode($result[4],true);
			// الاعضاء المتفاعلين
			$ActiveMembers = array();
			foreach($Members as $Member){
				$result_ = mysqli_query($mysqli_,"SELECT * FROM ClientsTime WHERE client_database_id='" . $Member . "'");
				if(mysqli_num_rows($result_) > 0 ){
					$result_ = mysqli_fetch_row($result_);
					if($result_["2"]+(60*60*24*3) >= time()){
						array_push($ActiveMembers,$Member);
					}
				}
			}
		   	  if($_GET["sort"]){
				$sort = $_GET["sort"];
				$names = $_GET["names"];
				$del = $_GET["d"];
				$new = 0;
				$old = 0;
			    $Max =  intval(count($ActiveMembers)/3);
				if(count(explode(",",$_GET["sort"]))-2 <= $Max){
				$req = createRequest("sortClanRooms(".$result[5].",'$sort','$names','$del')");
				while(!waitForResult($req)){}
				readResult($req);
				}
			  }
			  if($_GET["icon"]){
				  $data = file_get_contents($_GET["icon"]);
				  $data = base64_encode($data);
				$req = createRequest("setIconGroup(".$result[8].",'$data',".$result[5].")");
				while(!waitForResult($req)){}
				readResult($req);
			  }
			  if($_GET["cmsg"]){
				  changeClanMsg($result[7],$_GET["cmsg"]);
			  }
			  if($_GET["ctag"]){
				  if (!preg_match('/[^A-Za-z0-9\s-]/', $_GET["ctag"])){
					if(strlen($_GET["cname"]) <=5){
						$clan = getClanFromTag($_GET["ctag"]);
						if(!$clan){
							changeClanTag($result[7],$_GET["ctag"]);
							$req = createRequest("changeTag(".$result[8].",'".$_GET["ctag"]."')");
							while(!waitForResult($req)){}
							readResult($req);
						}
					}
				  }
			  }
			  if($_GET["cnam"]){
				  if (!preg_match('/[^A-Za-z0-9\s-]/', $_GET["cnam"])){
					if(strlen($_GET["cname"]) <=15){
						$clan = getClanFromName($_GET["cnam"]);
						if(!$clan){
							changeClanName($result[7],$_GET["cnam"]);
							$req = createRequest("changeName(".$result[5].",'".$_GET["cnam"]."')");
							while(!waitForResult($req)){}
							readResult($req);
						}
					}
				  }
			  }
		    $req = createRequest("getClanManger(".$result[8].",".$result[5].")");
			while(!waitForResult($req)){}
			$res = json_decode(readResult($req),true);
			$Rooms = $res[0];
			$icon =$res[1];
			// upload allowed
			$uploadAllow = false;
			$uResult = mysqli_query($mysqli_,"SELECT * FROM upload WHERE id='" . $cid . "'");
			if(mysqli_num_rows($uResult) > 0 ){
				$uResult = mysqli_fetch_row($uResult);
				if($uResult[1]+60 <= time()){
					$uploadAllow = true;
				}
			} else {
				$uploadAllow = true;
			}
	   }
?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <style>
  #sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
  #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-right: 1.5em; font-size: 1.4em; height: 40px; }
  #sortable li span { position: absolute; margin-right: -1.3em; }
  </style>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">لوحة التحكم</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>	
	<? if($MyRank == ""){ ?>
		<div class="alert alert-danger">
			<strong>لا يمكنك تعديل شيء لانك لست اداري في الكلان</strong>
		</div>	
	<? } ?>
	<div id="Massages"></div>
	<div class="row">
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
					معلومات الكلان
					</div>
					<div class="panel-body" id="settings">
					<div class="form-group">
					<table class="table table-striped table-bordered table-hover">
                          <tbody>
					  <tr>
						<td>اسم الكلان :</td>
						<? if($MyRank == "Owner"){ ?>
						<td><p class="form-control-static"><a herf="#" id="clanname" data-type="text" data-pk="cn" data-url="PostRoom.php" data-title="ضع الاسم الجديد" style="color:#000" ><?php echo $result[1] ?></a></p></td>
						<? } else { ?>
							<td><?php echo $result[1] ?></td>
						<? } ?>
                      </tr>
					  
					<tr>
					<td>اختصار الكلان :</td>
					<? if($MyRank == "Owner"){ ?>
					<td><p class="form-control-static"><a herf="#" id="clantag" data-type="text" data-pk="ct" data-url="PostRoom.php" data-title="ضع الاسم الجديد" style="color:#000" ><?php echo $result[2] ?></a></p></td>
						<? } else { ?>
							<td><?php echo $result[2] ?></td>
						<? } ?>
					</tr>
						<tr>
						<td>تغيير شعار الكلان :</td>
						<td>	
								<form action="upload/processupload.php" method="post" enctype="multipart/form-data" id="MyUploadForm">
								 <div id="IconDiv">
								  <?php if(!empty($icon)){ ?>
										<img src="data:image/png;base64,<? echo $icon; ?>" id="clan-icon" onclick="<?php if($uploadAllow){ if($MyRank == "Owner"){echo 'browse();'; }}else{ echo 'WaitUpload();';} ?>" />
								  <?php } else { ?>
										<label  id="clan-icon" onclick="<? if($MyRank == "Owner"){echo 'browse();'; } ?>">اظغط هنا لرفع شعار</label>
								  <?php } ?>
								  </div>
										<input name="image_file" id="imageInput" type="file" style="display: none;" onchange="upload();" />
										<input type="submit"  id="submit-btn" value="Upload" class="btn btn-danger"  style="display: none;"/>
										<img src="http://www.schultzlawoffice.com/img/loading/loading-x.gif" id="loading-img" style="display: none;"  alt="Please Wait"/>
										<div id="Loading"></div>
								</form>
								<div id="output" style="display: none;"></div>
						</td>
					</tr>
					<tr>
					<td>رسالة الكلان :</td>
					<? if($MyRank == "Owner" || $MyRank == "Co-Owner"){ ?>
					<td><p class="form-control-static"><a herf="#" id="clanmsg" data-type="text" data-pk="cm" data-url="PostRoom.php" data-title="ضع رسالة الكلان الجديدة" style="color:#000" ><?php if(empty($result[3])){ echo "لا توجد رسالة"; }else { echo $result[3]; } ?></a></p></td>
					<? } else { ?>
						<td><?php if(empty($result[3])){ echo "لا توجد رسالة"; }else { echo $result[3]; } ?></td>
					<? } ?>
					</tr>


					<tr>
						<td>عدد أعضاء الكلان :</td>
						<td><p class="form-control-static"><?php echo count($Members) ?></p></td>
					</tr>

					<tr>
						<td>عدد الأعضاء المتفاعلين :</td>
						<td><p class="form-control-static"><?php echo count($ActiveMembers) ?></p></td>
					</tr>
					
					<tr>
						<td>نسبة التفاعل :</td>
						<td><p class="form-control-static"><?php echo intval((count($ActiveMembers) / count($Members) )*100); ?>%</p></td>
					</tr>

							</tbody>
                        </table>
					</div>
				</div>
			</div>
			</div> 
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
					تغيير ترتيب الرومات
					</div>
					<div class="panel-body">
					<ul id="sortable">
				     <?
					  foreach($Rooms as $Key=>$Room){
						 if($Key == 0){
							 echo '<li class="ui-state-default ui-state-disabled" style="filter:Alpha(Opacity=100);opacity:1;"><a href="#" id="'.$Room[1].'" data-type="text" data-pk="1" data-url="PostRoom.php" data-title="ضع الاسم الجديد" style="filter:Alpha(Opacity=100);opacity:1;">'.$Room[0].'</a></li>';
						 }elseif($Key == count($Rooms)-1){
							 echo '<div id="nR"></div><li class="ui-state-default ui-state-disabled" style="filter:Alpha(Opacity=100);opacity:1;" ><a href="#" id="'.$Room[1].'" data-type="text" data-pk="1" data-url="PostRoom.php" data-title="ضع الاسم الجديد" style="filter: inherit; filter:Alpha(Opacity=100);opacity: 1">'.$Room[0].'</a></li>';
						 }else{
							 echo '<li class="ui-state-default"><a href="#" id="'.$Room[1].'" data-type="text" data-pk="1" data-url="PostRoom.php" data-title="ضع الاسم الجديد">'.$Room[0].'</a></li>';
						 }
					  }
					 ?>
					</ul>
					<ul id="sortable2" style="display: none;"> </ul>
					<center><button id="Button" class="btn btn-danger <? if($MyRank == ''){echo 'disabled';} ?>" type="submit" value="Submit" onclick="<? if($MyRank != ''){echo 'submit();';} ?>">حفظ</button> <button id="Button2" class="btn btn-danger" type="submit" value="Submit" onclick="addRoom();">إضافة روم</button></center>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
					إنشاء رابط دعوة
					</div>
					<div class="panel-body">
					<center><button id="Button3" class="btn btn-danger <? if($MyRank == ''){echo 'disabled';} ?>" type="submit" value="Submit" onclick="<? if($MyRank != ''){echo 'Invite();';} ?>">إضغط هنا لدعوة عضو للكلان</button>
					<div id="url"></div></center>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	function changeMsg(response, newValue){
		if(response.status != 'error'){
		  xmlhttp = new XMLHttpRequest();
		  xmlhttp.open("GET","http://esport.ae/ePanel/page/clans-manger?" + "cmsg=" + newValue,true);
		  xmlhttp.send();
		}
	}	
	function changeTag(response, newValue){
		if(response.status != 'error'){
		  xmlhttp = new XMLHttpRequest();
		  xmlhttp.open("GET","http://esport.ae/ePanel/page/clans-manger?" + "ctag=" + newValue,true);
		  xmlhttp.send();
		  	setInterval( function(){
			location.reload();
		}, 2000 );
		}
	}
	function changeName(response, newValue){
		if(response.status != 'error'){
		  xmlhttp = new XMLHttpRequest();
		  xmlhttp.open("GET","http://esport.ae/ePanel/page/clans-manger?" + "cnam=" + newValue,true);
		  xmlhttp.send();
		  	setInterval( function(){
			location.reload();
		}, 2000 );
		}
	}
	function WaitUpload(){
		  BootstrapDialog.show({
            title: 'تغير شعار الكلان',
			type: BootstrapDialog.TYPE_DANGER,
            message: 'يجب عليك الانتظار يوم واحد لتغير الشعار مره اخرى'
        });
	}
	function browse(){
		document.getElementById("imageInput").click();
	}
	function upload(){
		document.getElementById("submit-btn").click();
	}
	var Rooms = <? echo count($Rooms)-2; ?>;
	var MaxRooms = <? echo intval(count($ActiveMembers)/3); ?>;
	var RoomsAdded = 0;
    $(function() {
    $( "#sortable" ).sortable({
      items: "li:not(.ui-state-disabled)",
	  placeholder: "ui-state-highlight",
	  connectWith: '#sortable2',
    over: function (event, ui) {
        outside = false;
    },
    out: function (event, ui) {
        outside = true;
    },
    beforeStop: function (event, ui) {
        if (outside) {
			BootstrapDialog.confirm({
            title: 'تأكيد الأمر',
            message: 'هل تريد حذف الروم؟',
            type: BootstrapDialog.TYPE_DANGER,
            closable: false, 
            draggable: true, 
            btnCancelLabel: 'إلغاء الأمر', 
            btnOKLabel: 'حذف', 
            btnOKClass: 'btn-danger', 
            callback: function(result) {
                if(result) {
                    ui.item.prependTo('#sortable2');
					RoomsAdded = RoomsAdded -1;
                }else {
                   
                }
            }
        });
        }
    }
    });
	    $( "#sortable li" ).disableSelection();
		$('#sortable2').sortable({
			connectWith: '#sortable'
		});
	});
	function addRoom(){
	  if(Rooms+RoomsAdded < MaxRooms){
		var randString = Math.random().toString(36).substring(7);
		document.getElementById("nR").innerHTML = document.getElementById("nR").innerHTML + '<li class="ui-state-default"><a href="#" id="'+ Math.floor(Math.random() * 100) + 1   + '" data-type="text" data-pk="1" data-url="PostRoom.php" data-title="ضع الاسم الجديد">روم جديد</a></li>';
		$("#sortable a").editable();
		RoomsAdded = RoomsAdded +1;
	  } else {
		 BootstrapDialog.show({
            title: 'اضافة روم للكلان',
			type: BootstrapDialog.TYPE_DANGER,
            message: 'لقد وصلت الحد المسموح لكلانك , قم بزيادة الاعضاء المتفاعلين للحصول على روم اضافي'
        });
	  }
	}
	function submit(){
   var idsInOrder = [ ];//$("#sortable").sortable("toArray");
   var names =  [ ];
   $('#sortable li a').each(function(index){
	   var element = $(this).attr('id');
	   idsInOrder.push(element);
      names.push(document.getElementById(element).text); 
    });
	 var del = [ ];
	 $('#sortable2 li a').each(function(index){
	   var element = $(this).attr('id');
	   del.push(element);
	 });
   console.log(idsInOrder);
   console.log(names);
   console.log(del);
		  xmlhttp = new XMLHttpRequest();
		  xmlhttp.open("GET","http://esport.ae/ePanel/page/clans-manger?" + "sort=" + idsInOrder + "&names=" + names + "&d=" + del,true);
		  xmlhttp.send();
		document.getElementById("Massages").innerHTML = '<div class="alert alert-success"><strong>تمت العملية بنجاح</strong></div>';
		document.getElementById("Button").disabled = true;
		document.getElementById("Button2").disabled = true;
		setInterval( function(){
			document.getElementById("Massages").innerHTML = '';
			document.getElementById("Button").disabled = false;
			document.getElementById("Button2").disabled = false;
			location.reload();
		}, 5000 );
}
  </script>	
  <script type="text/javascript">
$(document).ready(function() { 
	var options = { 
			target: '#output',   // target element(s) to be updated with server response 
			beforeSubmit: beforeSubmit,  // pre-submit callback 
			success: afterSuccess,  // post-submit callback 
			resetForm: true        // reset the form after successful submit 
		}; 
		
	 $('#MyUploadForm').submit(function() { 
			$(this).ajaxSubmit(options);  			
			// always return false to prevent standard browser submit and page navigation 
			return false; 
		}); 
}); 

function afterSuccess()
{
	//$('#submit-btn').show(); //hide submit button
	//$('#loading-img').hide(); //hide submit button
	if(document.getElementById("output").innerHTML.substr(-4) === ".png" ){
		  BootstrapDialog.show({
            title: 'تغير شعار الكلان',
			type: BootstrapDialog.TYPE_DANGER,
            message: 'تم الرفع'
        });
		document.getElementById("IconDiv").innerHTML = '<img src="' + document.getElementById("output").innerHTML +'" id="clan-icon" onclick="WaitUpload();" />';
		  xmlhttp = new XMLHttpRequest();
		  xmlhttp.open("GET","http://esport.ae/ePanel/page/clans-manger?" + "icon=" + document.getElementById("output").innerHTML,true);
		  xmlhttp.send();
	} else {
		BootstrapDialog.show({
            title: 'تغير شعار الكلان',
			type: BootstrapDialog.TYPE_DANGER,
            message: 'خطأ : '+ document.getElementById("output").innerHTML
        });
		setInterval( function(){
					location.reload();
		}, 3000 );
	}
}

//function to check file size before uploading.
function beforeSubmit(){
	document.getElementById("clan-icon").onclick = WaitUpload;
		BootstrapDialog.show({
            title: 'تغير شعار الكلان',
			type: BootstrapDialog.TYPE_DANGER,
            message: 'جاري رفع الشعار الجديد'
        });
    //check whether browser fully supports all File API
   if (window.File && window.FileReader && window.FileList && window.Blob)
	{
		
		if( !$('#imageInput').val()) //check empty input filed
		{
			$("#output").html("Are you kidding me?");
			return false
		}
		
		var fsize = $('#imageInput')[0].files[0].size; //get file size
		var ftype = $('#imageInput')[0].files[0].type; // get file type
		

		//allow only valid image file types 
		switch(ftype)
        {
            case 'image/png':
                break;
            default:
                $("#output").html("<b>"+ftype+"</b> Unsupported file type!");
				return false
        }
		
		//Allowed file size is less than 1 MB (1048576)
		if(fsize>1048576) 
		{
			$("#output").html("<b>"+bytesToSize(fsize) +"</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.");
			return false
		}
				
		$('#submit-btn').hide(); //hide submit button
	//	$('#loading-img').show(); //hide submit button
		$("#output").html("");  
	}
	else
	{
		//Output error to older browsers that do not support HTML5 File API
		$("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
		return false;
	}
}

//function to format bites bit.ly/19yoIPO
function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Bytes';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}
	function Invite(){
		var code = Math.random().toString(36).substring(7);
		var Clan = '<? echo base64_encode($result[7]); ?>';
		  xmlhttp = new XMLHttpRequest();
		  xmlhttp.open("GET","http://esport.ae/ePanel/createURL.php?" + "clan=" + Clan + "&code=" + code,true);
		  xmlhttp.send();
		 document.getElementById("url").innerHTML = 'يرجى إرسال الرابط للأعضاء المراد إضافتهم للكلان <br>' + 'http://esport.ae/ePanel/page/joinClan?code=' + code;
	document.getElementById("clan-icon").onclick = WaitUpload;
		BootstrapDialog.show({
            title: 'تم إنشاء الرابط',
			type: BootstrapDialog.TYPE_DANGER,
            message: 'الرابط صالح للاستعمال مرة واحدة فقط , سوف يتم حذف الرابط بعد دقيقتين اذا لم يستخدم'
        });
	}
</script>

	<?
  }
  ?>
