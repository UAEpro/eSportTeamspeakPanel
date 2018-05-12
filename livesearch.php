<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_NOTICE);
include("clans.config.php");
$Clans = getClans();
$clans_tmp = array();
while ($row=mysqli_fetch_row($Clans)){
	$Members = json_decode($row[4],true);
	if(empty($row[3]))$row[3] = "لا توجد رسالة";
	$ActiveMembers = array();
	$mysqli = mysqli_connect("localhost","root","XPApkV8w","TS3-1");
foreach($Members as $Member){
	$result_ = mysqli_query($mysqli,"SELECT * FROM ClientsTime WHERE client_database_id='" . $Member . "'");
	if(mysqli_num_rows($result_) > 0 ){
		$result_ = mysqli_fetch_row($result_);
		if($result_["2"]+(60*60*24*3) >= time()){
			array_push($ActiveMembers,$Member);
		}
	}
}
	array_push($clans_tmp,array("Name" => $row[1],"ShortName" => $row[2],"Message" => $row[3],"Join" => $row[6],"Players" => count($Members),"Active" => count($ActiveMembers)));
}

$i = 0;

function mysort($a, $b) {
    if( ($a["Active"] / $a["Players"] )*100 > ($b["Active"] / $b["Players"] )*100 ) {
        return -1;
    }elseif( ($a["Active"] / $a["Players"] )*100 == ($b["Active"] / $b["Players"] )*100 ) {
        return 0;
    }else {
        return 1;
    }
}
usort($clans_tmp, "mysort");
foreach($clans_tmp as $Clan){
	if($_GET["q"] == "" || strstr(strtolower($Clan['Name']),strtolower($_GET["q"])) != false ){
		echo '                                <div class="panel panel-default" data-toggle="collapse" data-parent="#accordion" href="http://esport.ae/ePanel/page#'.($Clan['ShortName']).'">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="" aria-expanded="true" data-toggle="collapse" data-parent="#accordion" href="http://esport.ae/ePanel/page#'.($Clan['ShortName']).'"><center>'.$Clan["Name"].'</center></a>
                                        </h4>
                                    </div>
                                    <div style="" aria-expanded="true" id="'.($Clan['ShortName']).'" class="panel-collapse collapse">
                                        <div class="panel-body">
							<div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="80px">رسالة الكلان:</td>
                                            <td>'.$Clan["Message"].'</td>
                                        </tr>
                                        <tr>
                                            <td>اختصار الكلان</td>
                                            <td>'.$Clan['ShortName'].'</td>
                                        </tr>
                                        <tr>
                                            <td>الانضمام</td>';
										    if ($Clan["Join"] == "public") { 
												echo '<td><a herf="http://esport.ae/ePanel/page/cc?accept='.$Clan["uniqueID"].'" class="btn btn-danger">انضمام</a></td>';
											}else{
												echo '<td><button type="button" class="btn btn-danger disabled">دعوات فقط</button></td>';
											}
                                       echo'</tr>
                                        <tr>
                                            <td>عدد الأعضاء</td>
                                            <td>'.$Clan["Players"].'</td>
                                        </tr>
                                        <tr>
                                            <td>تفاعل الكلان</td>
                                            <td>'. ($Clan["Active"] / $Clan["Players"] )*100 .'%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                                        </div>
                                    </div>
                                </div>';
								$i++;
	}
}
if($i == 0){
	echo '	<div class="row">
		<div class="alert alert-danger">
            <h2 class="text-center">لا توجد نتائج</h2>
         </div>

	
	<!-- /.row -->
	</div>';
}
?>