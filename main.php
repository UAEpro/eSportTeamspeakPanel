<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////
//// This is a file for the Smart TS3 Panel made by UAEpro, BlackBird and eSport Team.            ////
//// If you get this files without UAEpro permission you might be in serious problems.            ////
//// This file or files content owned by eSport.ae and no one can use it without eSport.ae Owner. ////
////             If you have access to this file send email to admin@esport.ae                    ////
//////////////////////////////////////////////////////////////////////////////////////////////////////
include("./config.php");
$mysqli->set_charset("utf8");
//include("./request.php");
$cid = base64_decode(base64_decode(base64_decode($_COOKIE["ClientDatabaseID"])));
$cid = $cid/25;
if($cid == 0)echo "<script>location.reload();</script>";
$req = createRequest("getInfo('".$_SERVER['REMOTE_ADDR']."',".$cid.")");
while(!waitForResult($req)){}
$result  = readResult($req);
$info = json_decode($result,true);
echo gettype($info);
if(sizeof($info[0]) == 0){
	include("notLogin.php");
	die();
}
if($info[1]["level"][0] == null){
	$info[1]["level"][0] = 0;
	$info[1]["level"][0] = 0;
	$info[1]["level"][2] = 60;
}
function formatMilliseconds($time) {
	$time = $time/ 1000;
	$days = floor($time / (24*60*60));
	$tmp_days = (1 == $days || $days == 0) ? 'day' : 'days';
	$hours = floor(($time - ($days*24*60*60)) / (60*60));
	$tmp_hours = (1 == $hours || $hours == 0) ? 'hour' : 'hours';
	$minutes = floor(($time - ($days*24*60*60)-($hours*60*60)) / 60);
	$tmp_mins = (1 == $minutes || $minutes == 0) ? 'minute' : 'minutes';
	$seconds = ($time - ($days*24*60*60) - ($hours*60*60) - ($minutes*60)) % 60;
	$tmp_secs = (1 == $seconds || $seconds == 0) ? 'second' : 'seconds';
	if($days == 0 ){
		if($hours == 0 ){
			if($minutes == 0){
				$text = ''.$seconds.' '.$tmp_secs.'';
			} else {
				$text = ' '.$minutes.' '.$tmp_mins.' '.$seconds.' '.$tmp_secs.'';
			}
		} else {
			$text = ' '.$hours.' '.$tmp_hours.' '.$minutes.' '.$tmp_mins.' '.$seconds.' '.$tmp_secs.'';
		}
	} else {
		$text = ' '.$days.' '.$tmp_days.' '.$hours.' '.$tmp_hours.' '.$minutes.' '.$tmp_mins.' '.$seconds.' '.$tmp_secs.'';
	}
	return $text;
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
$result = mysqli_query($mysqli,"SELECT * FROM `ClientsTime` WHERE `client_database_id` =".$info[0]["client_database_id"]);
$points = mysqli_fetch_assoc($result)["time"];
$Posts = mysqli_query($mysqli,"SELECT * FROM Posts ORDER BY time DESC LIMIT 0 , 5");
?>


        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">مرحبا بكم</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
			
            <div class="row">
			    <div class="col-lg-6 col-md-8">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-2">
                                    <i class="fa fa-user fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo htmlspecialchars($info[0]["client_nickname"]); ?></div>
                                    <div></div>
                                </div>
                            </div>
                        </div>
                        
                            <div class="panel-footer">
                                <span class="pull-left h4">Online Since: <?php echo formatMilliseconds($info[0]["connection_connected_time"]); ?></span>
                                <span class="pull-right h4"></span>
								<br>
								<br>
                                <span class="pull-left h4" style="" dir="ltr">description: <?php echo htmlspecialchars($info[0]["client_description"]); ?></span>
                                <span class="pull-right h4"></span>
                                <div class="clearfix"></div>
                            </div>
                       
                    </div>
                </div>			
				<div class="col-lg-3 col-md-4">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-2">
                                    <i class="fa fa-level-up fa-5x"></i>
                                </div>
                                <div class="col-xs-10 text-right">
                                    <div class="huge"><?php echo $info[1]["level"][0]; ?></div>
                                    <div>المستوى</div>
                                </div>
                            </div>
                        </div>
                        <a href="page/index">
                            <div class="panel-footer">
                                <span class="pull-left"><?php
								if($info[1]["level"][0] == 100){
									echo " مبروك وصلت اللفل الاخير :)";
								} else {
								echo 'Points: '.$points."/".$info[1]["level"][2]; 
								}
								?></span>
                                <span class="pull-right text-muted"><?php 
								if($info[1]["level"][0] == 100){
									echo "100";
								} else {
								echo sprintf('%0.2f',($points-$info[1]["level"][1])*100/($info[1]["level"][2]-$info[1]["level"][1])); 
								}
								?>%</span>
								<br>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $points; ?>" aria-valuemin="0" aria-valuemax="<?php echo $info[1]["level"][2];?>" style="width: <?php 
											if($info[1]["level"][0] == 100){
												echo "100";
											} else {
												echo ($points-$info[1]["level"][1])*100/($info[1]["level"][2]-$info[1]["level"][1]); 
											}
										?>%">
                                        </div>
									</div>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
			    <div class="col-lg-3 col-md-12">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-lg-3 col-md-1 col-sm-2 col-xs-2">
                                    <i class="fa fa-user fa-5x"></i>
                                </div>
                                <div class="col-sm-9 col-sx-11 text-right">
                                    <div class="huge"><?php echo $info[2][0]; ?></div>
                                    <div>شخص متصل بالتيم سبيك</div>
                                </div>
                            </div>
                        </div>
                        <a href="page/index">
                            <div class="panel-footer">
                                <span class="pull-left"></span>
                                <span class="pull-left">Clients: <?php echo $info[2][0]."/".$info[2][1]; ?></span>
                                </br>
								<div class="progress progress-striped active">
								<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $info[2][0]; ?>" aria-valuemin="0" aria-valuemax="512" style="width: <?php echo $info[2][0]/$info[2][1]*100; ?>%">
								</div>
								</div>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> متوسط عدد اللاعبين
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="morris-area-chart"></div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> لوحة شرف اليوتيوبرز
                            </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <!-- /.col-lg-4 (nested) -->
                                <div class="col-lg-12">
                                    <div id="morris-bar-chart"></div>
                                </div>
                                <!-- /.col-lg-8 (nested) -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-8 -->
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i> آخر التطورات والتغريدات
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
							<?php 
							  while ($row=mysqli_fetch_row($Posts))
								{
								   echo '<a href="page/index" class="list-group-item">
                                    <i class="'.$row[1].'"></i> '.$row[2].'
                                    <span class="pull-right text-muted small" dir="ltr"><em>&nbsp;&nbsp;'.humanTiming($row[3]).'</em>
                                    </span>
                                </a>';
								}
							?>
                            </div>
                            <!-- /.list-group -->
                            <!--a href="page/index" class="btn btn-default btn-block">مشاهدة الكل</a-->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> أكثر الألعاب اختيارا
                        </div>
                        <div class="panel-body">
                            <div id="morris-donut-chart"></div>
                           
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    <!-- /.panel .chat-panel -->
                </div>
                <!-- /.col-lg-4 -->
			</div>
            <!-- /.row -->
        </div>
		
		
        <!-- /#page-wrapper -->
    <!-- Morris Charts JavaScript -->
