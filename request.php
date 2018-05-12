<?php
/************************************/
/* File Created By eSport Team */
/************************************/
 function getRequests(){
	 $files = array();
		foreach (glob("./requests/*.req") as $file) 
		{
			$name = str_replace("./requests/","",$file);
			$name = str_replace(".req","",$name);
			if(!file_exists("./requests/".$name.".res"))
			{
			array_push($files,$name);
			}
		}
	return $files;
 }

  function readRequest($request){
	 return file_get_contents("/var/www/ePanel/requests/".$request.".req");
 }
  function readResult($request){
	  $result = file_get_contents("/var/www/ePanel/requests/".$request.".res");
	  unlink("/var/www/ePanel/requests/".$request.".res");
	 return $result;
 }
 function isResultExists($request){
	 return file_exists("/var/www/ePanel/requests/".$request.".res");
 }
 function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array();
    $alphaLength = strlen($alphabet) - 1; 
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}
function createRequest($code){
	$request = randomPassword();
    file_put_contents("/var/www/ePanel/requests/".(string)$request.".req",$code);
	return $request;
}
function createResult($request,$code){
	unlink ("/var/www/ePanel/requests/".$request.".req");
	return file_put_contents("/var/www/ePanel/requests/".$request.".res",$code);
}
?>