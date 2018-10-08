<?php
$tweets = json_decode(file_get_contents("smallurl_lasttweet",true));
foreach ($tweets as $tweet) {
	echo "{$tweet->text}<hr>";
}
?>