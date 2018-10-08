<?php
class theme extends core {

    function __construct() {
        $this->account = new account();
		parent::__construct();
    }

    public function Header($title) {
        include(APPPATH."/theme/header.tmpl");
    }
    public function Footer() {
        include(APPPATH."/theme/footer.tmpl");
    }
    public function Sidebar($category) {
        include(APPPATH."/theme/sidebar.tmpl");
    }
    public function title($title, $tag, $location) {
        echo "<div class=\"row\">";
        echo "<div class=\"col-lg-12\">";
        echo "<h1> ".htmlentities($title)." <small>".htmlentities($tag)."</small></h1>";
        echo "<ol class=\"breadcrumb\">";

        $max = count($location);
        $c = 1;
        echo "<li><i class=\"fa fa-dashboard\"></i> Dashboard </li>";
        foreach ($location as $place) {
            if($c == $max) {
                $q = ' class=\"active\"';
            } else {$q = ''; }
            echo "<li ".$q."><i class=\"fa fa-asterisk\"></i> ".$place." </li>";
            $c++;
        }

        echo "</ol>";
        echo "</div>";
        echo "</div>";
    }
}
class controller_base extends core {
    function __construct() {
        $this->theme = new theme();
		parent::__construct();
    }
}
?>