<?php
// Allow surl.im access.
header('Access-Control-Allow-Origin: http://surl.im');
error_reporting(0);

date_default_timezone_set("Europe/London");
$r = file_get_contents("smallurl_tweetdate");
if(strtotime('+1 min', $r) < time()) {

require_once('twitteroauth.php'); //Path to twitteroauth library
$consumerkey = "";
$consumersecret = "";
$accesstoken = "";
$accesstokensecret = "";

function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
  return $connection;
}

$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=SURLNetwork&exclude_replies=true&include_rts=false&count=50");
$temp = array();
$count = 3;
for ($i = 0; $i < $count; $i++) {
	$temp[] = $tweets[$i];
}
file_put_contents("smallurl_tweetdate", time());
file_put_contents("smallurl_lasttweet", json_encode($temp));
echo json_encode($temp);
} else {
    echo file_get_contents("smallurl_lasttweet");
}
?>
