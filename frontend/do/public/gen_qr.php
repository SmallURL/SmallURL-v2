<?php
if (isset($_GET['u']) && $_GET['u'] != "") {
	$qr = file_get_contents("http://chart.apis.google.com/chart?cht=qr&chs=300x300&chl={$_GET['u']}&chld=H|0");
	header('Content-Type: image/png');
	echo $qr;
}
?>