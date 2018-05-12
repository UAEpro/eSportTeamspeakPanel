<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////
//// This is a file for the Smart TS3 Panel made by UAEpro, BlackBird and eSport Team.            ////
//// If you get this files without UAEpro permission you might be in serious problems.            ////
//// This file or files content owned by eSport.ae and no one can use it without eSport.ae Owner. ////
////             If you have access to this file send email to admin@esport.ae                    ////
//////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<script type="text/javascript">
function showPosts() {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("blackbird").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("POST","./Posts/Posts.php",true);
        xmlhttp.send();
}
setInterval(function () {showPosts()}, 1000);
function addPost(){
	var x = document.getElementsByName("optionsRadiosInline");
	for (i = 0; i < x.length; i++) {
      if(x[i].checked == true){
		  xmlhttp = new XMLHttpRequest();
		  xmlhttp.open("GET","./Posts/add.php?" + "type=" + x[i].value + "&msg=" + document.getElementsByName("Msg")[0].value,true);
		  xmlhttp.send();
		  document.getElementsByName("Msg")[0].value = "";
		  x[i].checked = false;
		  x[0].checked = true;
		  break;
	  }
	}
}
</script>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">أضافة آخر الاحداث</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
			
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i> آخر التطورات والتغريدات
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="blackbird" class="list-group">

                            </div>
                            <!-- /.list-group -->
							 <form action='' method='post' border='0' id="form1">
							<div class="form-group">
                                            <label>الرجاء اختيار الايقونة</label>
                                            <label class="radio-inline">
                                                <input name="optionsRadiosInline" id="optionsRadiosInline1" value="fa fa-twitter fa-fw" checked="" type="radio">&nbsp;&nbsp;&nbsp; <i class="fa fa-twitter fa-fw"></i>
                                            </label> 
                                            <label class="radio-inline">
                                                <input name="optionsRadiosInline" id="optionsRadiosInline2" value="fa fa-tasks fa-fw" type="radio">  &nbsp;&nbsp;&nbsp; <i class="fa fa-tasks fa-fw"></i>  
                                            </label>
                                            <label class="radio-inline">
                                                <input name="optionsRadiosInline" id="optionsRadiosInline3" value="fa fa-upload fa-fw" type="radio">  &nbsp;&nbsp;&nbsp; <i class="fa fa-upload fa-fw"></i>  
                                            </label>
                                            <label class="radio-inline">
                                                <input name="optionsRadiosInline" id="optionsRadiosInline3" value="fa fa-bolt fa-fw" type="radio">  &nbsp;&nbsp;&nbsp; <i class="fa fa-bolt fa-fw"></i>  
                                            </label>
                                            <label class="radio-inline">
                                                <input name="optionsRadiosInline" id="optionsRadiosInline3" value="fa fa-warning fa-fw" type="radio">  &nbsp;&nbsp;&nbsp; <i class="fa fa-warning fa-fw"></i>  
                                            </label>
                                            <label class="radio-inline">
                                                <input name="optionsRadiosInline" id="optionsRadiosInline3" value="fa fa-money fa-fw" type="radio">  &nbsp;&nbsp;&nbsp; <i class="fa fa-money fa-fw"></i>  
                                            </label><br>
                                            <label>ما هو الجديد ؟</label>
                                            <input class="form-control" name="Msg">
											<input type='button' value='Submit' id="submit" onclick="addPost()" class="btn btn-default" />
							</div>
							</form>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel .chat-panel -->
                </div>
                <!-- /.col-lg-4 -->
			</div>
            <!-- /.row -->
        </div>
		
		
        <!-- /#page-wrapper -->
    <!-- Morris Charts JavaScript -->
