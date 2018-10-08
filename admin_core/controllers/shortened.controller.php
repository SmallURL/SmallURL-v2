<?php
class controller extends controller_base {

    private $category = "urls";

   function __construct() {
       parent::__construct();
   }

   public function home($title = "Shortened URLs") {
        $this->theme->header($title);


        $this->theme->sidebar($this->category);

        $account = new account();

        include(LAYOUT."shortened_controller/home.tmpl");

        $this->theme->footer();

    }
    }
?>