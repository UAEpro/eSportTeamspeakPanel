<?php
	include("clans.config.php");
	if($_POST['pk'] == "cn"){
			if (preg_match('/[^A-Za-z0-9\s-]/', $_POST['value'])){
				header('HTTP/1.0 400 Bad Request', true, 400);
				echo "الارقام و الحروف الانجليزية فقط !";
			}
			if(strlen($_POST['value']) >=15){
				header('HTTP/1.0 400 Bad Request', true, 400);
				echo "اسم الكلان اكثر من 15 حرف";
			}
		$clan = getClanFromName($_POST['value']);
	} elseif($_POST['pk'] == "ct") {
			if (preg_match('/[^A-Za-z0-9\s-]/', $_POST['value'])){
				header('HTTP/1.0 400 Bad Request', true, 400);
				echo "الارقام و الحروف الانجليزية فقط !";
			}
			if(strlen($_POST['value']) >=5){
				header('HTTP/1.0 400 Bad Request', true, 400);
				echo "اختصار الكلان اكثر من 5 حروف";
			}
		$clan = getClanFromTag($_POST['value']);
	} else {
		   if(count(str_split($_POST['value'])) >= 40){
			   header('HTTP/1.0 400 Bad Request', true, 400);
			   echo "اسم الروم طويل جدا";
		   }
	}
    sleep(1); 

    $pk = $_POST['pk'];
    $name = $_POST['name'];
    $value = $_POST['value'];

    if(!empty($value)) {
		die();
		if($pk == "cn"){
		 if($clan != false){
			header('HTTP/1.0 400 Bad Request', true, 400);
			echo "اسم الكلان مستخدم";
		 }
		} elseif ($pk == "ct"){
			 if($clan != false){
				header('HTTP/1.0 400 Bad Request', true, 400);
				echo "اختصار الكلان مستخدم";
			 }
		} else {
        print_r($_POST);
		}
    } else {
        header('HTTP/1.0 400 Bad Request', true, 400);
        echo "اكمل الحقل المطلوب !";
    }
?>