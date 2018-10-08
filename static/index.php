<?php
require_once($_SERVER['DOCUMENT_ROOT']."/../core/classes/config.class.php");
config::load();
require_once($_SERVER['DOCUMENT_ROOT']."/../core/classes/devstatus.class.php");

if (isset($_SERVER['HTTP_ORIGIN']) && preg_match("/(http:\/\/)+(static|account)?(\.)?({$_SMALLURL['domain']})(.*)/i", $_SERVER['HTTP_ORIGIN'])) {
	header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
}
header('X-Accel-Buffering: no');
// v3.0 - Rewrite by Thomas Edwards - 21/03/14
// p.s. Dave will murder me for the rewrite, The rewrite however will allow full control over file access.

// What's the domain name?

class CDN {

	public function __construct($file) {
		$this->path = $file;
		$this->config = simplexml_load_string(file_get_contents("../assets/allowed.xml"));
		// For the sake of chickens. We'll do EVERYTHING here.
		$allowed = false;
		$exists = false;
		$header = "text/plain";
		$data = '';
		foreach ($this->config->file as $f) {
			if (strtolower($file) === strtolower($f->path)) {
				if (file_exists("../assets".strtolower($f->path))) {
					$exists = true;
					if ($f->access == "ALL") {
						if (isset($f->execute) && $f->execute == "TRUE") {
							ob_start();
								include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
								include("../assets".strtolower($f->path));
								$data = ob_get_contents();
							ob_end_clean();
						} else {
							$data = file_get_contents("../assets".strtolower($f->path));
						}
						$allowed = true;
					} else {
						$allowed = false;
					}
					$header = $f->content;
				}
			}
		}
		
		// If it doesn't exist we fallback to the default option.
		if (!$exists) {
			if ($this->config->default->code == "404") {
				$allowed = true;
				$exists = false;
			} else if ($this->config->default->code == "403") {
				$exists = false;
				$allowed = false;
			}
		}
		
		// Now we setup stuff.
		$this->exists = $exists;
		$this->allowed = $allowed;
		$this->content = $header;
		$this->data = $data;
	}

	public function error($type) {
		$err = array("ERROR"=>array("CODE"=>'',"MESSAGE"=>''));
	
		if($type == '404') {
			header('HTTP/1.1 404 Not Found');
			$err['ERROR']['CODE'] = "404 Not Found";
			$err['ERROR']['MESSAGE'] = "The file requested could not be found on the server.";
		} else if ($type == '403') {
			header('HTTP/1.1 403 Forbidden');
			$err['ERROR']['CODE'] = "403 Access Denied";
			$err['ERROR']['MESSAGE'] = "You do not have access to the file you requested.";
		}
		if ($this->path) {
			$err['ERROR']['PATH'] = $this->path;
		}
		$this->xml($err);
	}
	public function xml($data) {
		header("Content-Type: text/xml");
		$xml = '<?xml version="1.0" encoding="UTF-8"?><SMALLURL>'.$this->array_to_xml($data).'</SMALLURL>';
		echo $xml;
	}
	public function array_to_xml($data) {
		// recursively converts an array to XML.
		$xml = "";
		foreach ($data as $key => $val) {
			if (!is_array($val)) {
				$xml .= "<{$key}>{$val}</{$key}>";
			} else {
				$xml .= "<{$key}>".$this->array_to_xml($val)."</{$key}>";
			}
		}
		return $xml;
	}
	public function header() {
		header("Content-Type: ".$this->content);
	}
}
$cdn = new CDN($_SERVER['PATH_INFO']);

if ($cdn->exists) {
    if ($cdn->allowed) {
		// Okie dokie.
		$cdn->header();
		echo $cdn->data;
	} else {
		$cdn->error('403');
	}
} else {
	$cdn->error('404');
}

?>