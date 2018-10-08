<?php
$api = array();
$api['pre'] = "http://screenshot.smallurl.in:8556/?src=";
$api['end'] = "&webshot=1";

if (!isset($_SERVER['QUERY_STRING']) || $_SERVER['QUERY_STRING'] == "") {
	header('Content-Type: image/png');
	die(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../cache/not_found.png"));
}

include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
$urlid = htmlentities($_SERVER['QUERY_STRING']);


if (file_exists($_SERVER['DOCUMENT_ROOT']."/../cache/screenshot/{$urlid}.png")) {
	header('Content-Type: image/png');
	echo file_get_contents($_SERVER['DOCUMENT_ROOT']."/../cache/screenshot/{$urlid}.png");
} else {
	$long = get_url($urlid);
	if ($long) {
		$download = file_get_contents($api['pre'].urlencode($long).$api['end']);
		file_put_contents($_SERVER['DOCUMENT_ROOT']."/../cache/screenshot/{$urlid}.png",$download);
		$new = file_get_contents($_SERVER['DOCUMENT_ROOT']."/../cache/screenshot/{$urlid}.png");
	} else {
		$new = file_get_contents($_SERVER['DOCUMENT_ROOT']."/../cache/not_found.png");
	}
	header('Content-Type: image/png');
	echo $new;
}
?>