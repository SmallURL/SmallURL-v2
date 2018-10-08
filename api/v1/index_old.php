<?php

// SmallURL API for use with the SDK.
// Version 0.3
// Last revised 28/10/2013
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");

$action = "shorten";
$type = "json";

if (isset($_GET['action']) && $_GET['action'] != "") {
	$action = strtolower(htmlentities($_GET['action']));
}
if (isset($_GET['type']) && $_GET['type'] != "") {
	$type = strtolower(htmlentities($_GET['type']));
}
if (!isset($_GET['key']) || $_GET['key'] == "") {
	if ($action != "shorten") {
		finish(array('res'=>false,'msg'=>'No key was supplied!'));
	}
}
else {
	$key = htmlentities($_GET['key']);
}

if ($action == "shorten") {
//lol
	// Shorten the URL
	if (!isset($_GET['url']) || $_GET['url'] == "") {
		$ret = array('res'=>false,'msg'=>'The URL was not supplied.');
		finish($ret);
	}

	$pay = array();
	if (has_param('custom')) {
		$pay['custom'] = get_param('custom');
	}
	if (has_param('private')) {
		if (get_param('private') == "true") {
			$pay['private'] = true;
		}
	}
	if (has_param('device')) {
		if (get_param('device') <= 4) {
			$pay['device'] = get_param('device');
		}
	}

	$url = got_http(get_param('url'));

	$api_ret = add_url($url,$pay);
	if ($api_ret['res']) {
		finish($api_ret);
	}
	else {
		$ret = array('res'=>false,'msg'=>$api_ret['msg']);
		finish($ret);
	}
	// lol
}
else if ($action == "inspect") {
	$check = check_key($key);
	if (!$check['res']) {
		// Bad Key.
		finish(array('res'=>false,'msg'=>$check['msg']));
	}
	
	if (!isset($_GET['short']) || $_GET['short'] == "") {
		finish(array('res'=>false,'msg'=>'The SmallURL ID was not supplied.'));
	}
	// We can relax.
	$short = htmlentities($_GET['short']);
	
	global $db,$sql;
	$sql_data = $db->get_array("entries",array("short"=>$short));
	if (count($sql_data) <= 0) {
		$url = false;
	}
	else {
		$sql_data = $sql_data[0];
		$url = $sql_data['content'];
	}
	if ($url != false) {
		$data = array();
		$data['res'] = true;
		$data['uses'] = url_uses($sql_data['id']);
		$data['short'] = $short;
		$data['url'] = $url;
		$data['date'] = $sql_data['stamp'];
		$data['nice_date'] = dateify($sql_data['stamp']);
		
		// If video!
		$yt_id = is_youtube($url);
		if ($yt_id) {
			// Youtube Stuffs.
			$vd = yt_get($yt_id);
			$data['video'] = true;
			$data['youtube-title'] = $vd['title'];
			$data['youtube-author'] = $vd['author'];
		}
		else {
			$data['video'] = false;
			$data['youtube'] = false;
		}
		
		if ($sql_data['custom'] == "1") {
			$data['custom'] = "true";
		}
		else {
			$data['custom'] = "false";
		}
		if ($sql_data['private'] == "1") {
			$data['private'] = "true";
		}
		else {
			$data['private'] = "false";
		}
		if ($sql_data['user'] == "0") {
			$data['user'] = "Public User";
		}
		else {
			$data['user'] = get_user($sql_data['user']);
		}
	}
	else {
		$data = array();
		$data['res'] = false;
		$data['msg'] = "The supplied SmallURL does not exist on the system.";
	}
	finish($data);
}
else if ($action = "check") {
	$keycheck = check_key($key);
	finish($keycheck);
} else if ($action = "notifications") {
	// Notification system.
	$notifications = array();
	// Add notifications to this array in the same format as the core notification.
	
	// Core notification, Every user recieves this.
	$core = array();
	$core['id'] = "core_5"; // Please change this when theres a new notifcation. It's fine unedited when theres a spelling edit,
	$core['msg'] = "Hey user! SmallURL 2014 has launched! Thanks for using SmallURL! We <3 you!";
	$core['title'] = "SmallURL v2.0 (2014)";
	$core['timeout'] = 10000; // Set to false to ignore. This is how long the notification stays for before closing.
	$notifications[] = $core; // Add it
	
	// Dont edit below here.
	$data = array();
	$data['res'] = true;
	$data['notifications'] = $notifications;
	finish($data);
}

function finish($dat) {
	global $type;
	if ($type == "json") {
		$death = json_encode($dat);
	}
	else if ($type == "php") {
		$death = serialize($dat);
	}
	else if ($type == "xml") {
		// Our XML Structure. It sucks we know.
		header('Content-Type: application/xml');
		$death = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n   <smallurl>";
		foreach ($dat as $key => $val) {
			if ($key == "res") {
				if ($val) {
					$val = "true";
				}
				else {
					$val = "false";
				}
			}
			$death .= "\n      <{$key}>{$val}</{$key}>";
		}
		$death .= "\n   </smallurl>";
	}
	else if ($type == "simple") {
		// Simple Data, This is probably best for custom scripts.
		$death = array();
		foreach ($dat as $key => $val) {
			$death[] = "{$key}={$val}";
		}
		$death = implode("|",$death);
	}
	die($death);
}
?>
