<?php
// Hashing/Salting Algorithm to help prevent against most attacks.
$password = "Hello123";
$_S = array();
function salt($pass,$salt_a = false,$salt_b = false) {
	// We'll generate the salt then salt it depending on different things in the password.
	$sep = "";
	$pad = "0";
	if (strlen($pass) % 2 != 0) {
		$pass .= $pad;
	}
	if (!$salt_a && !$salt_b) {
		$salt_a = ranstr(5);
		$salt_b = ranstr(5);
		global $_S;
		$_S[] = $salt_a;
		$_S[] = $salt_b;
	}
	
	$patt = strlen($pass) / 4;
	
	// Now we split it.
	$parts = str_split($pass,$patt);
	$hash = $salt_a.$parts[0].$salt_b.$parts[1].$patt.$salt_a.$parts[2].$salt_b.$parts[3];
	return str_rot13(implode(array_reverse(str_split(str_rot13($salt_a).$sep.str_rot13($salt_b).$sep.implode(array_reverse(str_split(str_rot13(md5(implode(array_reverse(str_split($hash))))))))))));
}
function pass_check($hash,$password) {
	$temp = implode(array_reverse(str_split(str_rot13($hash))));
	$sa = str_rot13(substr($temp,0,5));
	$sb = str_rot13(substr($temp,5,5));
	echo $sa." _ ".$sb;
	$nhash = salt($password,$sa,$sb);
	if ($nhash === $hash) {
		return true;
	} else {
		return false;
	}
}
function ranstr($len = 5) {
	$chars = str_split("123567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");
	$str = "";
	for ($i = 0; $i != $len; $i++ ) {
		$str .= $chars[array_rand($chars)];
	}
	return $str;
}
header('Content-Type: text/plain');
$salt = salt($password);
echo $salt."\n";
var_dump(pass_check($salt,$password));
?>