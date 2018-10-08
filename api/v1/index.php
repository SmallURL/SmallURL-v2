<?php
header('Access-Control-Allow-Origin: thomas-edwards.me');
include('api.core.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (!isset($_POST['action']) || $_POST['action'] == "") {
		$action = "shorten";
	} else {
		$action = $_GET['action'];
	}
	$_VARS = $_POST;
} else {
	if (!isset($_GET['action']) || $_GET['action'] == "") {
		$action = "shorten";
	} else {
		$action = $_GET['action'];
	}
	$_VARS = $_GET;
}

// Run the action :D
$api->vars($_VARS);
$api->end($api->runAction($action));
?>