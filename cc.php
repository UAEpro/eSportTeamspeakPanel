<?php
$result = "";
$die = false;
include("clans.config.php");
$cid = base64_decode(base64_decode(base64_decode($_COOKIE["ClientDatabaseID"])));
$cid = $cid/25;
	  if(isMemberOfClan($cid) != false){
		  $clan = isMemberOfClan($cid);
		  $result = mysqli_fetch_row($clan);
		   if($result[4]+600 < time()){
			   attemptToDeleteClan($result[5]);
			  ?>
			  	<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">خطأ :(</h1>
					</div>
				</div>
				<div class="alert alert-danger">
					<strong>الرابط غير صالح للعمل</strong>
				</div>	
			</div>
			  <?
			  $die = true;
		   }
		  if(!$die){
		 ?>
		 <script>
		 function startTimer(duration, display) {
			var timer = duration, minutes, seconds;
			setInterval(function () {
			minutes = parseInt(timer / 60, 10)
			seconds = parseInt(timer % 60, 10);

			minutes = minutes < 10 ? "0" + minutes : minutes;
			seconds = seconds < 10 ? "0" + seconds : seconds;

			display.textContent = minutes + ":" + seconds;

			if (--timer < 0) {
				display.textContent = "الرجاء قم بتحديث الصفحة";
			}
			}, 1000);
		}

	window.onload = function () {
		var fiveMinutes = <?php echo ($result[4]+600)-time() ?>,
		display = document.querySelector('#timer');
		startTimer(fiveMinutes, display);
	};
		</script>
			<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">معلومات الكلان</h1>
					</div>
				</div>
			<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
					 معلومات الكلان
					</div>
					<div class="panel-body">
					<label>أسم الكلان : <?php echo $result[1] ?></label>
					<br>
					<label>أختصار الكلان :<?php echo $result[2] ?></label>
					<br>
					<label>عدد أعضاء الكلان : <?php echo sizeof(json_decode($result[3])) ?></label>
					<? if(isClanOwner($cid) !== false):?>
					<br><label>رابط الكلان : http://esport.ae/ePanel/page/cc?accept=<?php echo $result[5]; ?></label>
					<br><label>الوقت المتبقي : <div id="timer">10:00</div><label>
					<br><label>سوف يتم حذف الكلان اذا لم يصل الى العدد المطلوب خلال الوقت المتبقي</label>
					<? endif ?>
					<br><br>
					<label> ملاحظة : يحتاج الكلان 10 اعضاء ليتم إنشاؤه</label>
					</div>
			    </div>
			</div>
		</div>
			</div>
		  <?
		  $die = true;
	  }
	}
   if(!$die){
	if($_GET["accept"]){
	  if($_POST["JoinClan"]){
		  $clan = isAttemptClanExists(base64_decode($_POST["JoinClan"]));
		  if($clan){
			  $result = mysqli_fetch_row($clan);
			  $players = attemptToJoinClan(base64_decode($_POST["JoinClan"]),$cid);
			  if($players){
				 if($players >= 3){
					 $result[3] = json_decode($result[3],true);
					 array_push($result[3],$cid);
					 $req = createRequest("createClan('".$result[1]."','".json_encode($result[3])."','".$result[2]."')");
					 while(!waitForResult($req)){}
					 $res = json_decode(readResult($req),true);
					 if(is_array($res) && !sizeof($res) == 0){
						 createClan($result[5],$res[0],$res[1]);
					 }
				 }
				  ?>
				  <div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">تم الانضمام لكلان <?php echo $result[1]; ?></h1>
					</div>
				</div>
				<div class="alert alert-success">
					<strong>باقي <?php echo 10-$players ?> اعضاء لانشاء الكلان</strong>
				</div>	
			</div>
				  <?
				  $die = true;
			  }
		  }
		  if(!$die){
		  ?>
			<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">خطأ :(</h1>
					</div>
				</div>
				<div class="alert alert-danger">
					<strong>حدث خطأ غير متوقع</strong>
				</div>	
			</div>
		  <?
		  }
		$die = true;
	  } else {
	  $clan = isAttemptClanExists($_GET["accept"]);
	   if($clan){
		  $result = mysqli_fetch_row($clan);
		?>
		<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">تم دعوتك الى كلان <?php echo $result[1]; ?></h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<div class="row">
		<div class="col-md-9">

		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
					 معلومات الكلان
					</div>
					<div class="panel-body">
					<label>أسم الكلان : <?php echo $result[1] ?></label>
					<br>
					<label>أختصار الكلان :<?php echo $result[2] ?></label>
					<br>
					<label>عدد أعضاء الكلان : <?php echo sizeof(json_decode($result[3])) ?></label>
						<form role="form" method="post" action="">
							<center><button type="submit" name='JoinClan' value='<?php echo base64_encode($_GET["accept"]); ?>' class="btn btn-lg btn-danger">قبول</button></center></form>
						</form>
					</div>
			    </div>
			</div>
		</div>
	</div>
	</div>	
		<?
		$die = true;
	   }
	   }
	   if(!$die){
	   ?>
			<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">خطأ :(</h1>
					</div>
				</div>
				<div class="alert alert-danger">
					<strong>الرابط غير صالح للعمل</strong>
				</div>	
			</div>
		  <?
		$die = true;
	}
	}
 }
	function addClan() {
		global $cid;
		if ( empty($_POST["name"])) {
			return "الرجاء كتابة اسم الكلان";
		} 
		if ( empty($_POST["tag"])) {
			return "الرجاء كتابة اختصار الكلان";
		} 
		if ( strlen($_POST["name"]) >= 15) {
			return "اسم الكلان اكثر من 15 حرف";
		} 
		if ( strlen($_POST["tag"]) >= 5) {
			return "اختصار الكلان اكثر من 5 حروف";
		} 
		if (preg_match('/[^A-Za-z0-9\s-]/', $_POST["name"])){
			return "مسموح فقط الحروف الانجليزية و الارقام في اسم الكلان";
		}
		if (preg_match('/[^A-Za-z0-9\s-]/', $_POST["tag"])){
			return "مسموح فقط الحروف الانجليزية و الارقام في اختصار الكلان";
		}
		return attemptToCreateClan($_POST["name"],$_POST["tag"],$cid);
	}
  if(!$die){
	if($_POST["Do"]){
	 $result = addClan();
	if($result == false)$result = "خطأ غير معروف";
	}
	if(is_array($result)){
		  $clan = isMemberOfClan($cid);
		  $result = mysqli_fetch_row($clan);

		?>
		<script>
		 function startTimer(duration, display) {
			var timer = duration, minutes, seconds;
			setInterval(function () {
			minutes = parseInt(timer / 60, 10)
			seconds = parseInt(timer % 60, 10);

			minutes = minutes < 10 ? "0" + minutes : minutes;
			seconds = seconds < 10 ? "0" + seconds : seconds;

			display.textContent = minutes + ":" + seconds;

			if (--timer < 0) {
				display.textContent = "الرجاء قم بتحديث الصفحة";
			}
			}, 1000);
		}

	window.onload = function () {
		var fiveMinutes = <?php echo ($result[4]+600)-time() ?>,
		display = document.querySelector('#timer');
		startTimer(fiveMinutes, display);
	};
		</script>
		<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">معلومات الكلان</h1>
					</div>
				</div>
			<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
					 معلومات الكلان
					</div>
					<div class="panel-body">
					<label>أسم الكلان : <?php echo $result[1] ?></label>
					<br>
					<label>أختصار الكلان :<?php echo $result[2] ?></label>
					<br>
					<label>عدد أعضاء الكلان : <?php echo sizeof(json_decode($result[3])) ?></label>
					<br>
					<label>رابط الكلان : http://esport.ae/ePanel/page/cc?accept=<?php echo $result[5]; ?></label>
					<br><label>الوقت المتبقي : <div id="timer">10:00</div><label>
					<br><label>سوف يتم حذف الكلان اذا لم يصل الى العدد المطلوب خلال الوقت المتبقي</label>
					<br><br>
					<label> ملاحظة : يحتاج الكلان 10 اعضاء ليتم إنشاؤه</label>
					</div>
			    </div>
			</div>
		</div>
			</div>
			<?
			$die = true;
	}
  }
  if(!$die){
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">صنع كلان</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<? if(gettype($result) == "string" && $result != ""): ?>
<div class="alert alert-danger">
  <strong><? echo $result; ?></strong>
</div>	
<? endif ?>
	<div class="row">
		<div class="col-md-9">

		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
					صنع كلان
					</div>
					<div class="panel-body">
						<form role="form" method="post" action="">
						<div class="col-lg-6">
							<div class="form-group">
								<label>أسم الكلان:</label>
								<input name="name" class="form-control" vk_12433="subscribed" maxlength="15">
								<p class="help-block">الحد الأقصى 15 حرف</p>
								</br>
								<label>أختصار الكلان:</label>
								<input name="tag" class="form-control" vk_12433="subscribed" maxlength="5">
								<p class="help-block">الحد الأقصى 5 أحرف</p>
							</div>
							<button type="submit"name='Do' value='Do'  class="btn btn-default" >صنع</button>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<? } ?>