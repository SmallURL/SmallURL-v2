<?php
// Instead of requesting a reset this file Updates the Password.
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if (!has_param('hash','POST')) {
	// Missing the HASH
	echo "HASH Missing";
}
if (get_param('hash','POST') != md5(client_hash())) {
	echo "HASH MisMatch";
}
if (!has_param('reset_hash','POST')) {
	die('Missing RESET HASH');
}
if (!has_param('pass_one','POST')) {
	die("Missing Password One");
}
if (!has_param('pass_two','POST')) {
	die("Missing Password Two");
}

$pass_one = get_param('pass_one','POST');
$pass_two = get_param('pass_two','POST');
if ($pass_one === $pass_two) {
	// They're matching.
}
else {
	echo "They dont match.";
}
?>