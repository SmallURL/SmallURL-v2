<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
error_reporting(E_ALL);
ini_set('display_errors','on');

if (!isset($_SESSION['uid']) || $_SESSION['uid'] == 0) {
	end_json(false,"You don't seem to be logged in!");
}
$uid = $_SESSION['uid'];

if (!isset($_GET['id']) || $_GET['id'] == "") {
	end_json(false,"Missing the User ID!");
}
$id = $_GET['id'];

// Check if the URL exists.
$users = db::get_array("users",array("id"=>$id));
if (count($users) <= 0) {
	end_json(false,"The user you tried to report doesn't exist!");
}

// Check if the user entered a reason.
if (!isset($_POST['reason']) || $_POST['reason'] == "") {
	end_json(false,"You haven't entered a reason!");
}
$reason = $_POST['reason'];

if (strlen($reason) < 50) {
	end_json(false,"The reason you have entered is below 50 Characters!");
}

// Now we check if the user has already reported it
$reports = db::get_array("report",array("type"=>"2","user"=>$uid,"item"=>$id));

if (count($reports) > 0) {
	end_json(true,"You have already reported this user!");
}

// Not been reported before.
db::insert("report",array("type"=>"2","user"=>$uid,"item"=>$id,"reason"=>$reason,"stamp"=>time()));
end_json(true,"User Reported!");
?>