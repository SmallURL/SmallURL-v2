<?php
// Are we logged into the main site?
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
$res = array();

if (isset($_SESSION['uid'])) {
	$res['res'] = true;
	$res['uname'] = get_user($_SESSION['uid']);
}
else {
	$res['res'] = false;
	$res['uname'] = get_user(0);
}
echo json_encode($res);
?>