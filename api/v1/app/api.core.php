<?php
// SmallURL API for use with the SDK.
// Version 0.3
// Last revised 30/12/2013
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");

class api {
	function __construct() {
		$this->format = "json";
		$this->actions = array();
		$this->formats = array();
	}
	function addFormat($name,$callback) {
		$name = strtolower($name);
		$this->formats[$name] = $callback;
	}
	function runFormat($name = false,$data = array()) {
		
		// Call a format.
		$name = strtolower($name);
		$formats = $this->formats;
		
		if (isset($formats[$name])) {
			if (function_exists($formats[$name])) {
				return call_user_func($formats[$name],$data);
			} else {
				$data['extra'] = "Using JSON by default. Missing ".strtoupper($name)." FORMAT FUNC.";
				return json_encode($data);
			}
		} else {
			$data['extra'] = "Using JSON by default. No such FORMAT ".strtoupper($name);
			return json_encode($data);
		}
	}
	function addAction($name,$callback) {
		$name = strtolower($name);
		$this->actions[$name] = $callback;
	}
	function runAction($action) {
	
		// Run a function.
		$action = strtolower($action);
		$callbacks = $this->actions;
		
		if (!isset($callbacks[$action])) {
		
			// This action dont exist.
			return array("res"=>false,"msg"=>"Unknown ACTION, Please check your spelling and try again!");
			
		} else {
			if (isset($this->vars['type'])) {
				// Change output format.
				$this->format = $this->vars['type'];
			}
			
			// Attempt to call the function.
			if (function_exists($callbacks[$action])) {
			
				// Function exists. Run it.
				return call_user_func_array($callbacks[$action],array($this));
			} else {
			
				// No such function. Break!
				return array("res"=>false,"msg"=>"Internal ERROR, Unable to run ACTION ".strtoupper($action).", function not found internally.");
			}
		}
	}
	function vars($data) {
		$this->vars = $data;
	}
	function expected($vars) {
		$exist = $this->vars;
		if (is_array($vars)) {
			foreach ($vars as $var) {
				if (!isset($exist[$var])) {
					$exist[$var] = null;
				}
			}
		} else {
			if (!isset($exist[$vars])) {
				$exist[$vars] = null;
			}
		}
		$this->vars = $exist;
	}
	function get($vname) {
		if ($this->vars) {
			if (isset($vars[$vname])) {
				return $vars[$vname];
			} else {
				return null;
			}
		} else {
			return null;
		}
	}
	function end($data) {
	
		// End the API.
		$ret = $this->runFormat($this->format,$data);
		
		die($ret);
	}
}
$api = new api();
include('action.inc.php');
include('format.inc.php');
?>