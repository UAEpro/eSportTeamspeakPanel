<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////
//// This is a file for the Smart TS3 Panel made by UAEpro, BlackBird and eSport Team.            ////
//// If you get this files without UAEpro permission you might be in serious problems.            ////
//// This file or files content owned by eSport.ae and no one can use it without eSport.ae Owner. ////
////             If you have access to this file send email to admin@esport.ae                    ////
//////////////////////////////////////////////////////////////////////////////////////////////////////

$hasClan = false;
$clanAllowJoin = false;
?>
<script>
function showResult(str) {
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      document.getElementById("accordion").innerHTML=xmlhttp.responseText;
    }
  }
  xmlhttp.open("GET","livesearch.php?q="+str,true);
  xmlhttp.send();
}
showResult("");
</script>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">قائمة الكلانات</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	
	<div class="row">
<div class="panel panel-default">
                        <div class="panel-heading">
                            <h3>كلانات</h3>
                        </div>
                        <!-- .panel-heading -->
                        <div class="panel-body">
						<div class="input-group custom-search-form" dir="ltr">
                        <input class="form-control" placeholder="بحث عن كلان محدد" type="text" onkeyup="showResult(this.value)">
									<span class="input-group-addon"><i class="fa fa-search"></i></span>
						</div>
						<br>
                            <div class="panel-group" id="accordion">


                            </div>
                        </div>
                        <!-- .panel-body -->
                    </div>
	
	<!-- /.row -->
	</div>
<!-- /#page-wrapper -->
</div>
