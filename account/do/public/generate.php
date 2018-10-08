<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if (isset($_GET['u']) && $_GET['u'] != "") {
	$url = $_GET['u'];
	if (get_short($url)) {
		// Sorry buddeh, no Generation!
		echo get_short($url);
		die();
	}
}
else {
	die('URL Required.');
}
$cut = strtolower(substr($url,0,7));
	if ($cut == "http://" || $cut == "https:/") {
		$url = $url;
	}
	else {
		$url = "http://".$url;
	}

// Now to generate a Custom URL.

// First we extract the components.
$parts = explode("/",$url);

// We retrieve the domain name, the first 5 Characters..
$dom = explode(".",$parts[2]);
if ($dom[0] == "www") {
	$domain = $dom[1];
}
else {
	$domain = $dom[0];
}
$domain = substr($domain,0,5);

$partstack = array();
foreach ($parts as $p) {
	$tmp = explode(".",$p);
	foreach ($tmp as $d) {
		$y = explode("?",$d);
		foreach ($y as $t) {
			$partstack[] = str_replace(array("-","+",".","#","=","_"),"",$t);
		}
	}
}
$parts = $partstack;

// Now we start to process the rest. We determine how many parts are in the URL and if we should continue.
$lchar = $url[strlen($url)-1];

if (count($parts) <= 3 || $lchar == "/" && count($parts) == 4) {
	// Only three parts. No sub dirs.
	// Generate an ID.
	$chars = "abcdefghijklmnopqrstuvwxyz1234567890";
	$len = "5"; // 5 Chars in the ID.
	$id = "";
	for ($i=0; $i < $len; $i++) {
		$letters = str_split($chars);
		$id .= $letters[array_rand($letters)];
	}
	$final = $domain."-".$id;
}
else {
	// We only need two parts, We'll use the last two parts if possible.
	if (count($parts) >= 6) {
		$cycles = 2;
		$custom = array();
		for ($i=0; $i != $cycles; $i++) {
			$part = $parts[count($parts)-1-$i];
			$part = explode("?",$part);
			$part = $part[0];
			$tmp = substr($part,0,5);
			if ($tmp != "") {
				$custom[] = $tmp;
			}
			else {
				$custom[] = gen_id();
			}
		}
		$final = $domain."-".implode("-",$custom);
	}
	else {
		// Only one last part.
		$custom = $parts[count($parts)-1];
		$final = $domain."-".$custom;
	}
}
if ($final[strlen($final)-1] == "-") {
	$final = substr($final,-1);
}
$new = strlen("http://smallurl.in/{$final}");
$old = strlen($url);
if ($new > $old) {
	$diff = $new - $old;
	$str = "The SmallURL is longer! :(";
}
else {
	$diff = $old - $new;
	$str = "The SmallURL is shorter! :D!";
}

echo $final."-".gen_id(2);
?>