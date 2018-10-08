<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");

if (!isset($_SESSION['uid']) || $_SESSION['uid'] == 0) {
	end_json(false,"You don't seem to be logged in!");
}
$uid = $_SESSION['uid'];

if (!isset($_GET['short']) || $_GET['short'] == "") {
	end_json(false,"Missing the short URL!");
}
$short = $_GET['short'];

// Check if the URL exists.
$urls = db::get_array("entries",array("short"=>$short));
if (count($urls) <= 0) {
	end_json(false,"The URL you tried to report doesn't exist!");
}
$short_id = $urls[0]['id'];

// Check if the user entered a reason.
if (!isset($_POST['reason']) || $_POST['reason'] == "") {
	end_json(false,"You haven't entered a reason!");
}
$reason = $_POST['reason'];

if (strlen($reason) < 50) {
	end_json(false,"The reason you have entered is below 50 Characters!");
}

// Now we check if the user has already reported it
$reports = db::get_array("report",array("user"=>$uid,"info"=>$short_id,"type"=>"0"));

if (count($reports) > 0) {
	end_json(true,"You have already reported this URL!");
}

// Not been reported before.
db::insert("report",array("user"=>$uid,"item"=>$short_id,"reason"=>$reason,"stamp"=>time(),"type"=>"0"));
end_json(true,"URL Reported!");
?>