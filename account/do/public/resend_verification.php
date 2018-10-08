<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if (!$_SMALLURL['LOGGEDIN']) {
	// Not logged in!
	header("Location: //account.{SITE_URL}/login");
	die('Not loggedin');
}
if ($_SMALLURL['UID'] == 0) {
	// Also not logged in.
	header("Location: //account.{SITE_URL}/login");
	die('guest');
}

// Check if they need to verify.
$verified = $u->verified($_SMALLURL['UID']);
if ($verified) {
	// You're already verified. :|
	header("Location: //account.{SITE_URL}/?already");
	die('already verified');
}

// Get the goods.
$email = $u->core($_SMALLURL['UID'])->get("email");
$key = $u->core($_SMALLURL['UID'])->get("verifykey");
$username = $u->core($_SMALLURL['UID'])->get("username");

// Send the Email.
//do_email($email, $username, "Email Validation","To begin using your SmallURL account, you need your email verifying. <br /> Please visit <a href='http://account.smallurl.in/verify/{$key}'> http://account.smallurl.in/verify/{$key} </a> to validate your account.");

$e->init($email,"Email Validation");
$e->set_var("key",$key);
$e->set_var("username",$username);
$e->set_var("title","Account Verification");
$e->load("header");
$e->load("verify_email");
$e->load("footer");
$e->send();

// Done C:
header("Location: //account.{SITE_URL}/?sent");
die();
?>
