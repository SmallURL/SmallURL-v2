<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if (!has_param("id","POST")) {
	echo json_encode(do_result(false,"Missing URL ID!"));
	exit;
}

$urlid = get_param("id","POST");

// Check if the URL exists.
$urls = db::array_query("SELECT * FROM {$sql['prefix']}_entries WHERE id='{$urlid}' AND type='0' AND suspended='0';");

if (count($urls) <= 0) {
	echo json_encode(do_result(false,"Invalid SmallURL!"));
	exit;
}

// it exists.

if (is_admin(UID)) {
	// Remove it. Well not really. WE DO NOT DELETE URLS!
	db::bool_query("UPDATE {$sql['prefix']}_entries SET suspended='1' WHERE id='{$urlid}';");
	echo json_encode(do_result(true,"Done."));
	exit;
}
else {
	echo json_encode(do_result(false,"Insufficient Permission to remove this URL."));
	exit;
}
?>