<?php
header("Content-Type: text/plain");
$response = [];
$response['res'] = false;
$response['msg'] = "ERROR: The SmallURL API has changed, To access the v1 API please access http://api.smallurl.in/v1/";
die(json_encode($response));
?>