<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if (is_admin(UID)) {
	$urls = $db->array_query("SELECT * FROM {$sql['prefix']}_entries WHERE flagged='1' AND suspended='0';");
	echo count($urls);
}
?>