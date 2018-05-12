<?php
include("clans.config.php");

$cid = base64_decode(base64_decode(base64_decode($_COOKIE["ClientDatabaseID"])));
$cid = $cid/25;

$clan = isMemberOfClan($cid);
$isLeader = false;
$Members = array();
 if($clan != false){
	$result = mysqli_fetch_row($clan);
	$Members = json_decode($result[4],true);
	$req = createRequest("getClanMembersGroup(".$result[5].")");
	while(!waitForResult($req)){}
	$Ranks = json_decode(stripslashes(readResult($req)),true);
	$isLeader = $Ranks[$cid] == "Owner" || $Ranks[$cid] == "Co-Owner" || $Ranks[$cid] == "Leader" ? true : false;
	foreach($Members as $key=>$member){ 
		if(!isset($Ranks[$member])){
			$Ranks[$member] = "Member";
		}
	}
	if($_GET["k"]){
		if($isLeader && isLeaderAccess($_GET['k'])){
			leaveClan($result[7],$_GET["k"]);
			$req = createRequest("removeRank(".$result[5].",".$_GET['k'].")");
			while(!waitForResult($req)){}
			readResult($req);
		}
	}
 }
 function humanTiming ($time)
{

    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'')." ago";
    }

}
function getSortOrder($c) {
	global $Ranks;
	$c = $Ranks[$c];
    $sortOrder = array("Owner","Co-Owner","Leader","Member");
    $pos = array_search($c, $sortOrder);

    return $pos !== false ? $pos : 99999;
}

function mysort($a, $b) {
    if( getSortOrder($a) < getSortOrder($b) ) {
        return -1;
    }elseif( getSortOrder($a) == getSortOrder($b) ) {
        return 0;
    }else {
        return 1;
    }
}
function isLeaderAccess($rank){
	global $Ranks,$cid;
	$myRank = $Ranks[$cid];
	if($myRank == "Owner" && $rank != "Owner")return true;
	if($myRank == "Co-Owner" && $rank != "Owner" && $rank != "Co-Owner")return true;
	if($myRank == "Leader" && $rank != "Owner" && $rank != "Co-Owner" && $rank != "Leader")return true;
	return false;
}
?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">أعضاء الكلان</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>	
	<div id="Massages"></div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					الأعضاء
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover text-center">
							<thead>
								<tr>
									<th>#</th>
									<th>الاسم</th>
									<th>الرتبة</th>
									<th>اخر زيارة</th>
									<?php
										if($isLeader){
											echo '<th>طرد</th>';
										}
									?>
									<th></th>
								</tr>
							</thead>
							<tbody>
							<? 
							usort($Members, "mysort");
							foreach($Members as $key=>$member){ 
							$result = mysqli_query($mysqli_,"SELECT * FROM ClientsTime WHERE client_database_id=" . $member . "");
								if( $result && mysqli_num_rows($result) > 0){
									$result = mysqli_fetch_row($result);
							?>
							<tr>
							<td><? echo $key+1; ?></td>
							<td><? echo $result[3] ?></td>
							<td><? if($Ranks[$member] == null){ echo 'Member'; }else { echo $Ranks[$member]; } ?></td>
							<td dir="ltr"><font color="<? if($result[2]+65 >= time()){ echo '#00cc00'; }else { echo '#cc0000'; } ?>"><? if($result[2]+65 >= time()){ echo 'متواجد'; }else { echo humanTiming($result[2]); }?></font></td>
									<?
										if($Ranks[$member] != null && isLeaderAccess($Ranks[$member])){
											echo '<td><button type="button" id="'.$member.'"  class="btn btn btn-danger" onclick="kick(this.id)">&nbsp;&nbsp;طرد&nbsp;&nbsp;</button></td>';
										} else {
											if($isLeader){
												echo  '<td><button type="button" class="btn btn-danger disabled">&nbsp;&nbsp;طرد&nbsp;&nbsp;</button></td>';
											}
										}
									?>
							<td></td>
							</tr>
							<? } } ?>
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
</div>

<script>
function kick(id){
		BootstrapDialog.confirm({
            title: 'تأكيد الأمر',
            message: 'هل تريد طرد هذا العضو المسكين من كلانك :(',
            type: BootstrapDialog.TYPE_DANGER,
            closable: true, 
            draggable: true, 
            btnCancelLabel: 'إلغاء الأمر', 
            btnOKLabel: 'طرد', 
            btnOKClass: 'btn-danger', 
            callback: function(result) {
                if(result) {
					document.getElementById("Massages").innerHTML = '<div class="alert alert-success"><strong>تمت العملية بنجاح</strong></div>';
					 xmlhttp = new XMLHttpRequest();
					xmlhttp.open("GET","http://esport.ae/ePanel/page/clans-members?" + "k=" + id,true);
					xmlhttp.send();
					var cells = document.getElementsByClassName("btn"); 
					for (var i = 0; i < cells.length; i++) { 
						cells[i].disabled = true;
					}
					setInterval( function(){
						document.getElementById("Massages").innerHTML = '';
						var cells = document.getElementsByClassName("btn"); 
						for (var i = 0; i < cells.length; i++) { 
							cells[i].disabled = false;
						}
						location.reload();
					}, 5000 );
                }
            }
        });
}
</script>