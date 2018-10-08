<?php
class controller extends controller_base {

	private $category = "ticket";

	function __construct() {
		parent::__construct();
	}

	public function home($title = "Support Tickets") {

		$this->theme->header($title);


		$this->theme->sidebar($this->category);

		$account = new account();

		include(LAYOUT."support_controller/home.tmpl");

		$this->theme->footer();

	}
}
?>