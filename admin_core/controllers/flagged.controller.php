<?php
class controller extends controller_base {

    private $category = "flagged";

   function __construct() {
       parent::__construct();
   }

   public function home($title = "Flagged URLs") {

        $this->theme->header($title);


        $this->theme->sidebar($this->category);

        $account = new account();

        include(LAYOUT."flagged_controller/home.tmpl");

        $this->theme->footer();

    }
}
?>