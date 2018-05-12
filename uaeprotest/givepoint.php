<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require_once("./../../libraries/TeamSpeak3/TeamSpeak3.php");
 require_once("/var/www/tspanel/config.php");
 
$tbl_name="ClientsTime";
  function isClientMebmerOfServerGroup($client,$sgid){
	foreach($client->memberOf() as $key =>$value){
		if($value->getID() == $sgid){
			return true;
		}	
	}
	return false;
   }
$amount = 0;

  $mysqli = mysqli_connect("$dphost", "$DB_user", "$DB_pass")or die("cannot connect");
  $mysqli->query("SET NAMES 'utf-8'");  
  mysqli_select_db($mysqli,"$db")or die("cannot select DB");
  $mysqli->query("SET CHARACTER SET utf8");

$ts3 = TeamSpeak3::factory("serverquery://serveradmin:IwcrzOMP@tsip.esport.ae:10011/?server_port=9987");
$ts3->execute("clientupdate", array("client_nickname" => "ᅠᅠᅠ"));
foreach($ts3->clientList() as $client){
		  $id = $client->getInfo()["client_database_id"];
	  $result=mysqli_query($mysqli,"SELECT * FROM `$tbl_name` WHERE client_database_id = ".$id);
		if ( mysqli_num_rows($result) > 0 ) {
					$result = mysqli_query($mysqli,"SELECT time FROM `$tbl_name` WHERE client_database_id = $id");
					$array = mysqli_fetch_assoc($result);
					$time = $array["time"];
					$newtime = $time + $amount;
					// my point 201893
					mysqli_query($mysqli,"UPDATE `$tbl_name` SET `time`=$newtime,`lastseen`=".time().",`Name`='".(string)$client."' WHERE `client_database_id`=$id "); 
					$client->message("[URL=client://340/K1N9XaBz50npwnZ9WupNWO2SJ6Y=~.%2FUAEpro%23]./UAEpro#[/URL] : الحمدلله في سنة 2015 صارت أشياء حلوة وكسبنا ناس حلوين مثلكم ولذلك أحب أهديكم عدد ".$amount ." نقطة من فريق اي سبورت "); 

					}
		}


?>