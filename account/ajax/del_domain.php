<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if (!isset($_POST['id']) || $_POST['id'] == "") {
	$ret = array('res'=>false,'msg'=>'You can\'t delete nothing!');
	die(json_encode($ret));
}

$domain_id = $_POST['id'];

$result = del_domain($domain_id);


$ret = array();
if ($result['res']) {
	$ret = $result;
}
else {
	$ret['res'] = false;
	$ret['msg'] = $result['msg'];
}
die(json_encode($ret));
?>