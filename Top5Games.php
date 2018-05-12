<?php
include("./request.test.php");
$req = createRequest("getTop10Games()");
while(!waitForResult($req)){}
$result  = readResult($req);
echo $result;
?>