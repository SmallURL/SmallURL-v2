<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");

if (!isset($_POST['hash']) || $_POST['hash'] == "") {
	// Missing hash!
	die('Houston! We have a problem!');
}
else {
	$correct_hash = md5(client_hash());
	$user_hash = htmlentities($_POST['hash']);
	if ($correct_hash !== $user_hash) {
		die('I call HAX. Incorrect Hash Supplied.');
	}
}
if (!isset($_POST['email']) || $_POST['email'] == "") {
	die('Wat. I cant reset if theres no email! :(');
}
$email = htmlentities($_POST['email']);

$data = $db->array_query("SELECT * FROM {$sql['prefix']}_users WHERE email='{$email}'");
if (count($data) == 1) {
	$user = $data[0];
	if ($user['passreset'] == "1") {
		// Its already been reqqed
		die('A Password Reset has already been requested');
	}
	else {
		$reset_hash = md5(client_hash().time().$user['email']); // Unique and Random - The Time makes prediction pretty difficult.
		$link = "http://smallurl.in/?p=reset_pass&i=".$reset_hash;
		$link = "<a href=\"{$link}\">{$link}</a>";
		do_email($user['email'],$user['name'],"Password Reset","You requested a password reset!\nTo fully reset your password hit the link below:\n{$link}\nDoing this will allow you to change your password!\n\nAre you not {$user['name']}?\nIf you're not ignore this EMail or notify SmallURL about the issue!");
		// Update the user.
		$db->bool_query("UPDATE {$sql['prefix']}_users SET passreset='1', passresetkey='{$reset_hash}' WHERE id='{$user['id']}'");
		echo "Password Reset Email Sent.";
	}
}
else {
	// User wasnt foundedededeeeededededededededededededede
}
?>