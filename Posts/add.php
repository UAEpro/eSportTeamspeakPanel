<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_NOTICE);
include("../config.php");
 mysqli_query($mysqli,"set names 'utf8'");
 mysqli_query($mysqli,"INSERT INTO Posts (Type,Msg,time) VALUES ('".$_GET['type']."','".$_GET['msg']."',".time().")");
?>