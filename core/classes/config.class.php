<?php
// Configuration Class
class config {
	static public function load() {
		// Loads configurations.
		global $_SMALLURL;
		$_SMALLURL['config'] = array();
		self::load_conf();
		self::load_conf('google'); // Googles Auth
		
		
		// ALWAYS Load Developer Config last for overrides.
		self::load_conf('developer');
	}
	static public function load_conf($file = "configuration") {
		// Loads from file.
		global $_SMALLURL;
		if (file_exists($_SERVER['DOCUMENT_ROOT']."/../core/config/{$file}.xml")) {
			$conf = file_get_contents($_SERVER['DOCUMENT_ROOT']."/../core/config/{$file}.xml");
			$xml = simplexml_load_string($conf);
			$dat = $_SMALLURL['config'];
			foreach ($xml->entry as $obj) {
				$stype = "string";
				if (isset($obj->type)) {
					$stype = (string)$obj->type;
				}
				if ($stype == "bool") {
					if ($obj->value == "true") {
						$dat[(string)$obj->item] = true;
					} else {
						$dat[(string)$obj->item] = false;
					}
				} else if ($stype == "int") {
					$dat[(string)$obj->item] = (int)$obj->value;
				} else {
					$dat[(string)$obj->item] = (string)$obj->value;
				}
			}
			$_SMALLURL['config'] = $dat;
		}
	}
	public static function get($item) {
		global $_SMALLURL;
		if (isset($_SMALLURL['config'][$item])) {
			return $_SMALLURL['config'][$item];
		} else {
			return false;
		}
	}
	public function set($item,$value = false) {
		// Empty Value to remove it.
		global $_SMALLURL;
		if ($value !== false) {
			$_SMALLURL['config'][$item] = $value;
		} else {
			unset($_SMALLURL['config'][$item]);
		}
		return true;
	}
}