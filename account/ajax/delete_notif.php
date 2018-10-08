<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if (!isset($_SMALLURL['UID'])) {
	echo json_encode(array("res"=>false));
	die();
}
$u->notifications($_SMALLURL['UID'])->markread($_GET['id']);
echo json_encode(array("res"=>true,"unread"=>count($u->notifications($_SMALLURL['UID'])->unread())));
?>