<?php
class smallurl {
	public function error($text = false,$level = 'WARNING') {
		global $_SMALLURL;
		if ($text) {
			$_SESSION['ERRORS'][] = array("msg"=>$text,"type"=>$level);
		} else {
			if (isset($_SESSION['ERRORS'])) {
				$errors = $_SESSION['ERRORS'];
				$this->clear_errors();
				return $errors;
			} else {
				return array();
			}
		}
	}
	public function clear_errors() {
		global $_SMALLURL;
		$_SMALLURL['ERRORS'] = array();
		unset($_SESSION['ERRORS']);
	}
	public function get_errors() {
		global $_SMALLURL;
		return $_SMALLURL['ERRORS'];
	}
	public function variable($vname) {
		$tmp = new smallurl_var($vname);
		return $tmp;
	}
	public function display_errors() {
		// NEEDS replacing with a Modal Dialog.
		// Alerts are so last decade
		$errs = $this->error();
		if (count($errs) > 0) {
			?>
			<div class="alert alert-danger">
				There was an issue processing your request! Please report the following errors to SmallURL Staff:
				<br/>
				<ul>
			<?php
			foreach ($errs as $error) {
				if ($error['type'] == "FATAL") {
					echo "<li><strong>{$error['msg']}</strong></li>";
				} else {
					echo "<li>{$error['msg']}</li>";
				}
			}
			?>
				</ul>
			</div>
			<?php
		}
	}
	public function redirect($location) {
		// Needs replacing.
		header('Location: '.$location);
		die();
	}
	public function do_json($result,$data) {
		// Does the lazy stuff.
		if (is_array($data)) {
			$json = $data;
			$json['res'] = $result;
		} else {
			$json = array("res"=>$result,"msg"=>$data);
		}
		return json_encode($json);
	}
}
class smallurl_var {
	function __construct($varname = false) {
		$this->name = $varname;
	}
	public function get() {
		global $_SMALLURL;
		if ($this->name != false) {
			if (isset($_SMALLURL['VARS'][strtolower($this->name)])) {
				return $_SMALLURL['VARS'][strtolower($this->name)];
			} else {
				return null;
			}
		} else {
			return $_SMALLURL['VARS'];
		}
	}
	public function set($value) {
		global $_SMALLURL;
		if ($this->name != false) {
			$_SMALLURL['VARS'][strtolower($this->name)] = $value;
			return true;
		} else {
			return false;
		}
	}
}
// Set our variables
$_SMALLURL = array();
$_SMALLURL['VARS'] = array();

if (isset($_SESSION['ERRORS'])) {
	$_SMALLURL['ERRORS'] = $_SESSION['ERRORS'];
} else {
	$_SMALLURL['ERRORS'] = array();
}

// Assign class objects.
$smallurl = new smallurl;
?>