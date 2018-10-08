<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");

$crawler = crawlerDetect($_SERVER['HTTP_USER_AGENT']);
// We really don't need crawlers crawling our URLS :3
if(isset($crawler) && $crawler != false) {
	header('HTTP/1.1 403 Forbidden');
	die("<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"> <html><head> <title>403 Forbidden</title> </head><body> <h1>Forbidden</h1> <p>You don't have permission to access this short URL</p><small> Please tweet us, @smallurlservice or message us on facebook, fb.me/smallurl if this seems to be an error. </body></html>");
}
if (!isset($_GET['url']) || $_GET['url'] == "") {
	header('Location: ./');
}
$short = $_GET['url'];
$url = get_url($_GET['url']);

// Just before we redirect lets update the usage, Now precise!
$dat = db::get_array("entries",array("short"=>$_GET['url']));
$id = $dat[0]['id'];
if (isset($_SERVER['HTTP_REFERER'])) {
	$ref = $_SERVER['HTTP_REFERER'];
} else {
	$ref = false;
}
if ($dat[0]['suspended'] == "1") {
	header("Location: /suspended/{$short}");
	die();
}
increase_use($id,$ref);


// URL Checker. Check's if the url is dangerous or is pornographic.
if ($url && $dat[0]['type'] == 0) {
	$result = screen_url($url);
	if ($result) {
		// Bad URL
		header("Location: ./warn/{$short}");
		die();
	}
}
if ($url && !is_loop($url)) {
	click_check($id);
	header("Location: {$url}");
}
else {
	header('Location: ./invalid');
}
?>
