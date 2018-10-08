<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");

if (!isset($_SMALLURL['UID']) || $_SMALLURL['UID'] == 0) {
	$id = md5("planned"); // Please change this when theres a new notifcation. It's fine unedited when theres a spelling edit,
	$message = "Calling all Smallifiers! We're taking our main site down for a while today to move some stuff around! We'll let you know when its back online!";
	$title = "SmallURL Notice";
	$count = 1;
	$delay = 100000; // Set to false to ignore.
	$loggedin = false;
} else {
	$last = $u->notifications($_SMALLURL['UID'])->unread();
	$last = array_reverse($last);
	$count = count($last);
	$loggedin = true;
	if (count($last) > 0) {
		$notif = $last[0];
		$id = md5($notif['id']."-".$_SMALLURL['UID']); // Please change this when theres a new notifcation. It's fine unedited when theres a spelling edit,
		$message = $notif['text'];
		$title = $notif['title'];

		$delay = 10000; // Set to false to ignore.
	}
}

// Dont edit below here.
$data = array();
$data['res'] = true;
$data['loggedin'] = $loggedin;
if ($count > 0) {
	$data['id'] = $id; // Looks fancy. okay?
	$data['title'] = $title;
	$data['msg'] = $message;
	if ($delay != false) {
		$dat['timeout'] = $delay;
	}
}
$data['count'] = $count;
die(json_encode($data));
?>