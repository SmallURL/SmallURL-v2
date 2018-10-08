<?php
class html {

	private $meta;
	public function __construct($staticURL) {
		$this->static_url = $staticURL;
	}
	public function Meta($v) {
		$this->meta = $v;
	}

    public function Start($title) {
		global $_SMALLURL,$rootDomain,$domain,$page;
		if($this->meta === null || !isset($this->meta)) {
			$this->meta = '';
		}
        include($_SERVER['DOCUMENT_ROOT']."/../core/tmpl/frontend/header.php");
    }

    public function Navigation() {
		global $rootDomain,$u;
        include($_SERVER['DOCUMENT_ROOT']."/../core/tmpl/frontend/navigation.php");
    }

    public function PageTitle ($title, $subtitle,$sup = false, $acc = "frontend") {
		global $_SMALLURL,$u;
		global $rootDomain;
		$_SMALLURL['title'] = $title;
		if (!$sup) {
			include($_SERVER['DOCUMENT_ROOT']."/../core/tmpl/".$acc."/pagetitle.php");
		}
    }
    public function AccPageTitle ($title, $subtitle,$sup = false, $acc = "account") {
		global $_SMALLURL,$u;
		global $rootDomain;
		$_SMALLURL['title'] = $title;
		if (!$sup) {
			include($_SERVER['DOCUMENT_ROOT']."/../core/tmpl/".$acc."/pagetitle.php");
		}
    }

    public function Footer() {
		global $rootDomain,$u;
        include($_SERVER['DOCUMENT_ROOT']."/../core/tmpl/frontend/footer.php");
    }

    public function InsertMedia($type, $item) {
    	$StaticURL = $static;
    	if($type == "js") {
    		echo "<script src=/".$StaticURL."/js/".$item."\" type='text/javascript'>";
    	} else if ($type == "css") {
    		echo "<link href=\"".$StaticURL."/css/".$item."\" media=\"screen\" rel=\"stylesheet\" type=\"text/css\" />";
    	}
    }
    public function IMGurl($loc) {
    	echo $this->static_url."/".$loc;

    }
	/* Some Extra stuff */
	public function set($var,$val) {
		global $_THEME;
		$_THEME[strtoupper($var)] = $val;
		return true;
	}
	public function get($var) {
		global $_THEME;
		if (isset($_THEME[strtoupper($var)])) {
			return $_THEME[strtoupper($var)];
		} else {
			return null;
		}
	}
	public function addJS($fname) {
		global $_THEME;
		$_THEME['JS'][] = $fname;
		return true;
	}
	public function addCSS($fname) {
		global $_THEME;
		$_THEME['CSS'][] = $fname;
		return true;
	}
	public function invoke($func,$args = false) {
		if (!$args) {
			return call_user_func($func);
		} else {
			if (is_array($args)) {
				return call_user_func_array($func,$args);
			} else {
				return call_user_func($func,$args);
			}
		}
	}

	public function account() {
		$tmp = new html_account();
		return $tmp;
	}
}
class html_account {
	/* Account Styling System */
	public function Header($title) {
		global $_SMALLURL,$rootDomain,$domain,$page,$u;
        include($_SERVER['DOCUMENT_ROOT']."/../core/tmpl/account/header.php");
    }

    public function Navigation() {
		global $rootDomain,$u;
        include($_SERVER['DOCUMENT_ROOT']."/../core/tmpl/account/navigation.php");
    }

    public function PageTitle ($title, $subtitle,$sup = false) {
		global $_SMALLURL,$u;
		global $rootDomain;
		$_SMALLURL['title'] = $title;
		if (!$sup) {
			die("SWAG");
			include($_SERVER['DOCUMENT_ROOT']."/../core/tmpl/account/pagetitle.php");
		}
    }

    public function Footer() {
		global $rootDomain,$u;
        include($_SERVER['DOCUMENT_ROOT']."/../core/tmpl/account/footer.php");
    }
}
?>