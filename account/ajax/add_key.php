<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if (!isset($_POST['name']) || $_POST['name'] == "") {
	$ret = array('res'=>false,'msg'=>'Key Name cannot be left blank!');
	die(json_encode($ret));
}
if (!isset($_POST['domain']) || $_POST['domain'] == "") {
	$ret = array('res'=>false,'msg'=>'Domain cannot be left blank!');
	die(json_encode($ret));
}

$key_name = $_POST['name'];
$key_domain = $_POST['domain'];

$result = add_key($key_name,$key_domain);

$ret = array();
if ($result['res']) {
	$ret['res'] = true;
}
else {
	$ret['res'] = false;
	$ret['msg'] = $result['msg'];
}
die(json_encode($ret));
?>