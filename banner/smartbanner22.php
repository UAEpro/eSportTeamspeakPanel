<?php
require('/var/www/Arabic.php');
include("../request.php");
$req = createRequest("bannerInfo('".$_SERVER['REMOTE_ADDR']."')");
while(!isResultExists($req)){
}
sleep(1);
$result  = json_decode(readResult($req));

$Arabic = new I18N_Arabic('Glyphs'); 
$Msg = "تابعونا على تويتر";
$Msg2 = "@esportAE";
$Msg3 = "@esportAE تابعونا على تويتر";
$Msg = $Arabic->utf8Glyphs($Msg);
$Msg2 = $Arabic->utf8Glyphs($Msg2);
$Msg3 = $Arabic->utf8Glyphs($Msg3);
$Name = $Arabic->utf8Glyphs($result[0]);

$gif = new Imagick('Smart_banner.gif');

$draw = new ImagickDraw();    
$draw->setFont('arial.ttf');
$draw->setFontSize(20);

// put text on each frame
foreach($gif as $key=>$frame){
  $draw->setFillColor('black');
  $gif->annotateImage($draw, $x = 7, $y = 21, $angle = 0, 'Welcome: ');        
  $draw->setFillColor('white');  
  $gif->annotateImage($draw, $x = 6, $y = 20, $angle = 0, 'Welcome: ');
  $draw->setFillColor('black');
  $gif->annotateImage($draw, $x = 102, $y = 21, $angle = 0, $Name);        
  $draw->setFillColor('white');  
  $gif->annotateImage($draw, $x = 101, $y = 20, $angle = 0, $Name);
	if($key>=1 && $key <=17){
  $textLength = imagefontwidth(14) * (strlen($Msg)*0.2);
  $draw->setFillColor('black');
  $gif->annotateImage($draw, $x = (696-$textLength)/2, $y = 205, $angle = 0, $Msg);        
  $draw->setFillColor('white');  
  $gif->annotateImage($draw, $x = (696-$textLength)/2, $y = 204, $angle = 0, $Msg);      
	}
	if($key==18){ 
   $textLength = imagefontwidth(14) * (strlen($Msg2)*0.2);
  $draw->setFillColor('black');
  $gif->annotateImage($draw, $x = (696-$textLength)/2, $y = 205, $angle = 0, $Msg2);        
  $draw->setFillColor('white');  
  $gif->annotateImage($draw, $x = (696-$textLength)/2, $y = 204, $angle = 0, $Msg2);    
	}
	if($key>18){    
  $textLength = imagefontwidth(14) * (strlen($Msg3)*0.2);
  $draw->setFillColor('black');
  $gif->annotateImage($draw, $x = (696-$textLength)/2, $y = 205, $angle = 0, $Msg3);        
  $draw->setFillColor('white');  
  $gif->annotateImage($draw, $x = (696-$textLength)/2, $y = 204, $angle = 0, $Msg3);    
	}
  $draw->setFillColor('black');
  $gif->annotateImage($draw, $x = 621, $y = 21, $angle = 0, $result[1]."/".$result[2]);        
  $draw->setFillColor('white');  
  $gif->annotateImage($draw, $x = 620, $y = 20, $angle = 0, $result[1]."/".$result[2]);         
}    

header('Content-Type: image/gif');
print $gif->getImagesBlob();
?>