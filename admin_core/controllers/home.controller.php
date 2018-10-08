<?php
class controller extends controller_base {

    private $category = "dashboard";

   function __construct() {
       parent::__construct();
   }

   public function home($title = "Home") {

        $this->theme->header($title);


        $this->theme->sidebar($this->category);

        $account = new account();

        include(LAYOUT."home_controller/home.tmpl");

        $this->theme->footer();

    }
}
?>