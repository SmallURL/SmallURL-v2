<?php
class core {

    //private $theme;
	function __construct() {
		global $db,$_ADMIN;
		$this->db = $db;
		$this->env = $_ADMIN;
	}
    public function genString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public function getDomain() {
        return $_SERVER['SERVER_NAME'];
    }

    public function parseURL() {
        $r = $_SERVER['REDIRECT_URL'];
        $q = explode("/", $r);
        array_shift($q);
        return $q;
    }

    public function getIP() {
        if(filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)) {
            return $_SERVER['REMOTE_ADDR'];
        } else {
            return false;
        }
    }
	public function dateify($stamp = null) {
		$current = time();
		$differ = $current-$stamp;
		if ($differ < 86400 && $differ > 30) {
			// Below 24hrs.
			$mins = round($differ/60);
			if ($mins > 60) { $hours = round($mins/60); } else { $hours = "0"; }
			if ($hours > 1) { $word_h = "hours"; } else { $word_h = "hour"; }
			if ($mins > 1) { $word_m = "mins"; } else { $word_m = "min"; }
			if ($hours > 0) { $mins = $mins - ($hours * 60); }

			if ($hours <= 0) {
				$scentence = "{$mins} {$word_m} ago.";
			}
			else if ($mins <= 0) {
				$scentence = "{$hours} {$word_h} ago.";
			}
			else {
				$scentence = "{$hours} {$word_h} {$mins} {$word_m} ago.";
			}
			return $scentence;
		}
		else if ($differ <= 30) {
			// 30 Seconds ago.
			return $differ." seconds ago.";
		}
		else {
			return substr(date('F',$stamp),0,3).chr(32).date('j, Y',$stamp);
		}
	}
}
$core = new core();


?>