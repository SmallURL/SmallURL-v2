<?php
// Only gets the current clients urls.
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
$data = get_via_hash();
$end_dat = array();
foreach ($data as $url) {
	$add = array();
	if ($url['device'] === "1") {
		$add['longurl'] = '<img src="http://smallurl.in/img/mobile-icon.png" width="25px" title="Shortened on a mobile device."/>'.$url['long'];
	} else {
		$add['longurl'] = $url['content'];
	}
	$add['smallurl'] = "http://smallurl.in/".$url['short'];
	$add['safe'] = "http://smallurl.in/".$url['short']."/safe";
	$add['date'] = dateify($url['stamp']);
	$add['uses'] = url_uses($url['id']);
	$end_dat[] = $add;
}
header('Access-Control-Allow-Origin: http://smallurl.in');
echo json_encode(array_reverse($end_dat));
?>