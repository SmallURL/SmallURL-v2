<?php
// Obselete.
include("/home/smallurl/core/smallurl.php");


$page = "home";

$err = false;

if (isset($_GET['p']) && $_GET['p'] != "") {
	$page = htmlentities(strtolower($_GET['p']));
	$page = explode("/",$page);
	$page = $page[count($page)-1];
}
if (is_mobile() && $page == "home") {
	$page = "home_mobile"; // Redirect Mobile Devices to the right version.
}

if (!file_exists("pages/{$page}.php")) {
	$err = $page;
	$page = "404";
}
if (isset($_GET['i']) && $_GET['i'] != "") {
	$id = htmlentities(strtolower($_GET['i']));
}
else {
	$id = false;
}
include("inc/header.php");
include("pages/{$page}.php");
include("../public_html/inc/footer.php");
?>