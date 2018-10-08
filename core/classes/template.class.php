<?php
class template {
	function variable($name) {
		$tmp = new template_var($name);
		return $tmp;
	}
	function content($type = false) {
		global $_TMPL;
		if ($type != false) {
			$_TMPL['CONTENT'] = $type;
		} else {
			return $_TMPL['CONTENT'];
		}
	}
	function load($filename) {
		// Loads template files into the 'stack',
		// Order counts.
		global $_TMPL;
		if (file_exists($filename)) {
			$data = file_get_contents($filename);
			// Now the fun begins.
			$pats = array();
			$reps = array();
			foreach ($_TMPL['VARS'] as $vname => $vval) {
				if (!is_array($vval)) {
					$nm = strtoupper($vname);
					$pats[] = "/%%{$nm}%%/";
					$reps[] = $vval;
				}
			}
			$_TMPL['FILES'][] = preg_replace($pats,$reps,$data);
		} else {
			return false;
		}
	}
	function display() {
		global $_TMPL;
		if (isset($_TMPL['CONTENT'])) {
			header('Content-Type: '.$_TMPL['CONTENT']);
		}
		foreach ($_TMPL['FILES'] as $file) {
			echo $file;
		}
	}
}
class template_var {
	function __construct($name = false) {
		$this->name = $name;
	}
	function set($value) {
		global $_TMPL;
		if ($this->name != false) {
			$_TMPL['VARS'][strtoupper($this->name)] = $value;
			return true;
		} else {
			return false;
		}
	}
	function get() {
		global $_TMPL;
		if ($this->name != false) {
			if (isset($_TMPL['VARS'][strtoupper($this->name)])) {
				return $_TMPL['VARS'][strtoupper($this->name)];
			} else {
				return null;
			}
		} else {
			return $_TMPL['VARS'];
		}
	}
}
$tmpl = new template();
$_TMPL = array();
$_TMPL['VARS'] = array();
$_TMPL['FILES'] = array();
$_TMPL['CONTENT'] = "text/html";
?>