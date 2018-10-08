<?php 
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
define("UID",$_SESSION['uid']);
unset($_SESSION['details_id']);
//print_r($_POST);
if(isset($_POST['oldpassword'])) {
	if(isset($_POST['newpassword1']) && isset($_POST['newpassword2']) && $_POST['newpassword2'] != '' && $_POST['newpassword1'] != '') {
		$update = UpdatePassword(UID, $_POST['oldpassword'],$_POST['newpassword1'], $_POST['newpassword2']);
		if($update == 4) {
			// Passwords don't match
			$_SESSION['details_id'] = 4;
			header("Location: /details");
			die();
		} else if($update == 3) {
			// Current password is invalid
			$_SESSION['details_id'] = 3;
			header("Location: /details");
			die();
		} else if($update == 2) {
			// Form isn't completly filled out
			$_SESSION['details_id'] = 2;
			header("Location: /details");
			die();
		} else if($update == 1) {
			// Password changed, proceed.
		}
	}
}
if(isset($_POST['name']) && $_POST['name'] != '') {
	UpdateInfo(UID, $_POST['name']);
	$_SESSION['details_id'] = 0;
	header("Location: /details");
	die();
} else {
	$_SESSION['details_id'] = 1;
	header("Location: /details");
	die();
}
?>