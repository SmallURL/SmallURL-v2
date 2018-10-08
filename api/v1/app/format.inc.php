<?php
$api->addFormat("json","format_json");
$api->addFormat("php","format_serialize");
$api->addFormat("serialize","format_serialize");
$api->addFormat("xml","format_xml");
$api->addFormat("ini","format_ini");
$api->addFormat("simple","format_simple");
$api->addFormat("smallurl","format_smallurl");

function format_json($data) {
	header('Content-Type: text/plain');
	return json_encode($data);
}
function format_serialize($data) {
	header('Content-Type: text/plain');
	return serialize($data);
}
function format_xml($data) {
	// Our XML Structure. It sucks we know.
	header('Content-Type: application/xml');
	$ret = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n   <smallurl>";
	foreach ($data as $key => $val) {
		if ($key == "res") {
			if ($val) {
				$val = "true";
			}
			else {
				$val = "false";
			}
		}
		if (is_array($val)) {
			$ret .= to_xml($val);
		} else {
			$ret .= "\n      <{$key}>{$val}</{$key}>";
		}
	}
	$ret .= "\n   </smallurl>";
	return $ret;
}
function to_xml($arr) {
	$out = "";
	foreach ($arr as $key => $val) {
		if (is_numeric($key)) {
			$key = "extra";
		}
		$out .= "\n<{$key}>";
		if (is_array($val)) {
			$out .= to_xml($val);
		} else {
			$out .= $val;
		}
		$out .= "</{$key}>";
	}
	return $out;
}
function to_str($int) {
	$conv = array();
	$conv[0] = "zero";
	$conv[1] = "one";
	$conv[2] = "two";
	$conv[3] = "three";
	$conv[4] = "four";
	$conv[5] = "five";
}
function format_ini($data) {
	// INI Data! For teh lulz!
	header('Content-Type: text/plain');
	$ret = array("[SmallURL]");
	foreach ($data as $key => $val) {
		if (is_bool($val)) {
			if ($val === true) {
				$ret[] = "{$key}=true";
			} else if ($val === false) {
				$ret[] = "{$key}=false";
			} else {
				$ret[] = "{$key}=null";
			}
		} else {
			$ret[] = "{$key}={$val}";
		}
	}
	$ret = implode("\r\n",$ret);
	return $ret;
}
function format_simple($data) {
	// Simple Data, This is probably best for custom scripts.
	header('Content-Type: text/plain');
	$ret = array();
	foreach ($data as $key => $val) {
		$ret[] = "{$key}={$val}";
	}
	$ret = implode("|",$ret);
	return $ret;
}
function format_smallurl($data) {
	header('Content-Type: text/plain');
	$ret = array();
	$ret[] = "START";
	foreach ($data as $key => $val) {
		if (!is_array($val)) {
			$ret[] = "VAR ".strtoupper($key)." ".$val;
		}
	}
	$ret[] = "END";
	$ret = implode("\n",$ret);
	return $ret;
}
?>