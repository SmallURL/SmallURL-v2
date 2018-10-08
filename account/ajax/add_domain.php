<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if (!isset($_POST['name']) || $_POST['name'] == "") {
	$ret = array('res'=>false,'msg'=>'Page Name cannot be left blank!');
	die(json_encode($ret));
}
$page_name = $_POST['name'];

if (!isset($_POST['domain'])) {
	$page_domain = false;
}
else {
	$page_domain = $_POST['domain'];
}

$result = add_domain($page_name,$page_domain);

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