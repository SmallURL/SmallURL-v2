<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");

$default = "unknown.png";
$err = "error.png";
$url = "http://www.gravatar.com/avatar/";

if (SITE_MODE === "LIVE") {
	$ext = "url";
} else {
	$ext = "dev";
}

// Set the header.
header('Content-Type: image/png');

if ($_SERVER['QUERY_STRING'] != "") {
	// Fudgie y u do dis.
	$v = $_SERVER['QUERY_STRING'];
	
	// Check its an MD5
	if(preg_match('/^[0-9a-f]{32}$/', $v)) {
		
		// Check if it's not already cached.
		if (!cache::exists("avatar_".$v)) {
			$avatar = url_get_contents($url.$_SERVER['QUERY_STRING']);
			if (strlen($avatar) > 0) {
				// Cache it.
				cache::store("avatar_".$v,$avatar,1440); // Cache for 24hrs
			}
		} else {
			// Read from cache
			$avatar = cache::read("avatar_".$v);
		}
		
		// Commit VERY clever dirty hack.
		if (md5($avatar) === md5(file_get_contents("none.jpg")) || strlen($avatar) <= 0) {
			// They're the same, This means default.
			if (strlen($avatar) <= 0) {
				echo file_get_contents($err);
			} else {
				echo file_get_contents($default);
			}
			// When the saved version of the default Gravatar IMG matches the image supplied,
			// That means theres no image. so we provide our own :D!
		} else {
			// Return the stored one
			echo $avatar;
		}
		
	} else {
		echo file_get_contents($default);
	}
} else {
	echo file_get_contents($default);
}
?>