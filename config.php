<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////
//// This is Config file for the Smart TS3 Panel made by UAEpro, BlackBird and eSport Team.       ////
//// If you get this files without UAEpro permission you might be in serious problems.            ////
//// This file or files content owned by eSport.ae and no one can use it without eSport.ae Owner. ////
////             If you have access to this file send email to admin@esport.ae                    ////
//////////////////////////////////////////////////////////////////////////////////////////////////////


//Database Host
$host = "localhost";
//Database Name
$db = "TS3-1";
//Database Username
$dbuser = "root";
//Database Password
$dbpass = "XPApkV8w";

//TeamSpeak3 Host
$ts3host = 'tsip.esport.ae';
//TeamSpeak3 Query Port
$ts3port = 11011;
//TeamSpeak3 Query Login
$ts3user = 'serveradmin';
//TeamSpeak3 Query Password
$ts3pass = 'IwcrzOMP';


//Youtube Things




//connect to database
$mysqli = new mysqli($host, $dbuser, $dbpass, $db);
$mysqli->query("SET NAMES 'utf-8'");  
$mysqli->select_db($db);


?>