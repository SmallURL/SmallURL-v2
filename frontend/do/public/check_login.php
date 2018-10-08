<?php
if ((!isset($_POST['user']) || $_POST['user'] == "") && (!isset($_POST['pass']) || $_POST['pass'] == "")) {
	// Missing user credents.
	$_SESSION['ERR'] = true;
	$_SESSION['ERR_ID'] = "3";
	header('Location: /login');
	die('3');
}
if (!isset($_POST['user']) || $_POST['user'] == "") {
	$_SESSION['ERR'] = true;
	$_SESSION['ERR_ID'] = "1";
	// Missing user credents.
	header('Location: /login');
	die('1');
}
else if (!isset($_POST['pass']) || $_POST['pass'] == "") {
	// Missing user credents.
	$_SESSION['ERR'] = true;
	$_SESSION['ERR_ID'] = "2";
	header('Location: /login');
	die('2');
}

include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
$email = $_POST['user'];
$password = $_POST['pass'];
$uid = check_login($email,$password);

if (!$uid) {
	// Bad credentials.
	$_SESSION['ERR'] = true;
	$_SESSION['ERR_ID'] = "4";
	header('Location: /login');
}
else {
	$_SESSION['uid'] = $uid;
	$_SESSION['timeout'] = time();
	if (!isset($_POST['ref']) || $_POST['ref'] == "") {
		header('Location: /');
	} else {
		header('Location: '.$_POST['ref']);
	}
}
?>