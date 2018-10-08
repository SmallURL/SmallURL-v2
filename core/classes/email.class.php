<?php
class email_tmpl {
	public function init($dest,$subject,$from = "noreply@smallurl.in") {
		// Spring cleaning
		$this->vars = array("year"=>date('Y'));
		$this->files = array();
		// Email related.
		$this->destination = $dest;
		$this->subject = $subject;
		$this->from = $from;
		// Template File related.
		$this->dir = $_SERVER['DOCUMENT_ROOT']."/../core/tmpl/email/";
		$this->ext = ".tmpl";
	}
	public function set_var($name,$val) {
		// Set a variable
		$vars = $this->vars;
		$vars[strtolower($name)] = $val;
		$this->vars = $vars;
		return true;
	}
	public function unset_var($name) {
		// Unset a variable
		$vars = $this->vars;
		if (isset($vars[strtolower($name)])) {
			unset($vars[strtolower($name)]);
			$this->vars = $vars;
			return true;
		} else {
			return false;
		}
	}
	public function load($tmpl) {
		$f = $this->files;
		
		$tmpldir = $this->dir;
		$ext = $this->ext;
		
		if (file_exists($tmpldir.$tmpl.$ext)) {
			$f[] = $tmpl;
		}
		
		$this->files = $f;
	}
	public function send() {
		// Sends the Email
		
		// Some Variables.
		$loaded = $this->files;
		$vars = $this->vars;
		$dir = $this->dir;
		$ext = $this->ext;
		
		$to = $this->destination;
		$subject = $this->subject;
		$from = $this->from;
		
		// Setup replacement regex.
		$preg = array();
		$vals = array();
		// Add stuff to the replacement preg.
		foreach ($vars as $vn => $vv) {
			$preg[] = "/%%{$vn}%%/i";
			$vals[] = $vv;
		}
		
		// Read template files into the "stack" with the variables replaced.
		$stack = array();
		foreach ($loaded as $file) {
			if (file_exists($dir.$file.$ext)) {
				$stack[] = preg_replace($preg,$vals,file_get_contents($dir.$file.$ext));
			}
		}
		$html = implode($stack);
		
		// Headers
		$header = array();
		$header['To'] = $to;
		$header['From'] = "SmallURL <".$from.">";
		$header['Content-Type'] = "text/html; charset=iso-8859-1";
		
		$heads = array();
		foreach ($header as $hn => $hv) {
			$heads[] = "{$hn}: {$hv}";
		}
		
		$header = "From: SmallURL <{$from}>\r\nContent-type: text/html; charset=iso-8859-1";
		$result = mail($to, $subject, $html, $header);
		//echo $html;
		$this->vars = array();
		$this->files = array();
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
	public function preview() {
		// Sends the Email
		$loaded = $this->files;
		$vars = $this->vars;
		$dir = $this->dir;
		$ext = $this->ext;

		// Setup replacement regex.
		$preg = array();
		$vals = array();
		// Add stuff to the replacement preg.
		foreach ($vars as $vn => $vv) {
			$preg[] = "/%%{$vn}%%/i";
			$vals[] = $vv;
		}
		
		// Read template files into the "stack" with the variables replaced.
		$stack = array();
		foreach ($loaded as $file) {
			if (file_exists($dir.$file.$ext)) {
				$stack[] = preg_replace($preg,$vals,file_get_contents($dir.$file.$ext));
			}
		}
		$html = implode($stack);
		return $html;
	}
}
$e = new email_tmpl();
?>