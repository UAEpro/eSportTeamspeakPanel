
<?php
if (isset($_POST['name'])) {
$name = strip_tags($_POST['name']);
$email = strip_tags($_POST['Email']);
$sug = strip_tags($_POST['sug']);
echo "Name		=".$name."</br>"; 
echo "Email		=".$email."</br>"; 
echo "Message		=".$sug."</br>"; 
echo "<span class=\"label label-info\" >your message has been submitted .. Thanks you</span>";
}?>

<style>
.col-centered{
float: none;
margin: 0 auto;
}
</style>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">نظام الشكاوي</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>	
	<div class="row">
 <div class="row">
<div class="col-sm-6 col-sm-offset-3 col-centered ">
    <div class="panel panel-default" style="margin-top: 10%;">
	<div class="panel-heading">
    <h3>إرسال شكوى او اقتراح</h3>
	</div>
	   <div class="panel-body">
			<form class="contact">
			<fieldset>
		        <ul class="nav nav-list">
				<li class="nav-header">المشتكى عليه</li>
				<li><input class="form-control" placeholder="يمكن تجاهل هذا الخيار" type="text" name="name"></li>
				<li class="nav-header">العنوان</li>
				<li><input class="form-control" placeholder="اكتب عنوان مفيد :)" type="text" name="topic"></li>
				<li class="nav-header">الرسالة</li>
				<li><textarea class="form-control" name="sug" rows="3" placeholder="عبر عما يجول في خاطرك يا حلو"></textarea></li>
				</ul> 
			</fieldset>
			</form>
			<br>
	         <button class="btn btn-success" id="submit">إرسال</button>
		</div>
	</div>

    </div>
</div>
	</div>
</div>


<script>
 $(function() {
//twitter bootstrap script
	$("button#submit").click(function(){
		   	$.ajax({
    		   	type: "POST",
			url: "contact.php",
			data: $('form.contact').serialize(),
        		success: function(msg){
					//alert(msg);
 		        },
			error: function(){
				alert("failure");
				}
      			});
	});
});
</script>


