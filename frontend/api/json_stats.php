<?php
// OLD Code, Left in for people using bots to pull stats, Soon to be part of API
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
$json = array();
$json['total'] = url_count_total();
$json['random'] = url_count_rand();
$json['custom'] = url_count_custom();
echo json_encode($json);
?>