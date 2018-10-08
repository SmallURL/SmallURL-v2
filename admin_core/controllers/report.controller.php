<?php
class controller extends controller_base {

	private $category = "report";

	function __construct() {
		parent::__construct();
	}

	public function home($title = "Reported Users and URLs") {
		$this->loadPage("home",$title);
	}
	
	public function users($title = "Reported Users and URLs") {
		$this->loadPage("users",$title);
	}
	
	public function urls($title = "Reported Users and URLs") {
		$this->loadPage("urls",$title);
	}
	public function apps($title = "Reported Users and URLs") {
		$this->loadPage("apps",$title);
	}
	
	private function loadPage($page,$title = false) {
		$this->theme->header($title);
		$this->theme->sidebar($this->category);
		$account = new account();
		include(LAYOUT."report_controller/{$page}.tmpl");
		$this->theme->footer();
	}
}
?>