<?php
	ini_set('display_errors', 'On');
	error_reporting(E_ALL & ~E_NOTICE);

require_once './../youtube/src/Google/autoload.php';
require_once './../youtube/src/Google/Client.php';
require_once './../youtube/src/Google/Service/YouTube.php';
session_start();

function getChannelTitle($channel){
			$url = 'https://www.googleapis.com/youtube/v3/channels?part=snippet&id='.$channel.'&key=AIzaSyDoJwPOuChK58UHMfTAiNeWN1e6O5BPu2w';
			$json = file_get_contents($url);
			$array = json_decode($json,true);
			return $array["items"]["0"]["snippet"]["title"] ;
}
function getChannelSubscriberCount($channel){
			$url = 'https://www.googleapis.com/youtube/v3/channels?part=statistics&id='.$channel.'&key=AIzaSyDoJwPOuChK58UHMfTAiNeWN1e6O5BPu2w';
			$json = file_get_contents($url);
			$array = json_decode($json,true);
			return $array["items"]["0"]["statistics"]["subscriberCount"] ;
}
$OAUTH2_CLIENT_ID = '497219884969-69d2h6jlpdmicb4a60jd698so5kfie47.apps.googleusercontent.com';
$OAUTH2_CLIENT_SECRET = 'vV-fUizyedFyqzzYGlbKjlD7';
$client = new Google_Client();
$client->setClientId($OAUTH2_CLIENT_ID);
$client->setClientSecret($OAUTH2_CLIENT_SECRET);
$client->setScopes('https://www.googleapis.com/auth/youtube.readonly');
$redirect = "http://esport.ae/ePanel/test.php";
$client->setRedirectUri($redirect);
$youtube = new Google_Service_YouTube($client);
if (isset($_GET['code'])) {
  if (strval($_SESSION['state']) !== strval($_GET['state'])) {
    die('The session state did not match.');
  }
  $client->authenticate($_GET['code']);
  $_SESSION['token'] = $client->getAccessToken();
  header('Location: ' . $redirect);
}
if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
  	$listResponse = $youtube->channels->listChannels('brandingSettings', array('mine' => true));
    $id = $listResponse['items'][0]["id"];
	$name = getChannelTitle($id);
	die($name);
}
if ($client->getAccessToken()) {
  try {
	
    $htmlBody .= "<h3>Subscription</h3><ul>";
    $htmlBody .= sprintf('<li>%s (%s)</li>',
        $subscriptionResponse['snippet']['title'],
        $subscriptionResponse['id']);
    $htmlBody .= '</ul>';
	
  } catch (Google_Service_Exception $e) {
    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
  }
  $_SESSION['token'] = $client->getAccessToken();
} else {
  $state = mt_rand();
  $client->setState($state);
  $_SESSION['state'] = $state;
  $authUrl = $client->createAuthUrl();
  $htmlBody = <<<END
  <center>
  <br><br>
  <a href="$authUrl">
  <div align='center'><button type='submit' class='btn btn-default' >دخول الفعالية</button></div></a>
  </center>
END;
}
?>

<!doctype html>
<html>
<head>
<title>Returned Subscription</title>
</head>
<body>
  <?=$htmlBody?>
</body>
</html>