<?php
class support {
	/* Support Center Stuffs */
	function unread_tick_count($uid = false) {
		return 20;
	}
	function clean_body($text) {
		$clean = htmlentities($text);
		$clean = str_replace('\r\n',"<br/>",$clean);
		$clean = str_replace("\'","'",$clean);
		return $clean;
	}
	function get_sig($uid) {
		global $db,$u;
		if ($u->level($uid) >= 70) {
			$name = get_user($uid);
			$role = $u->get_role($uid);
			return "<p>--</p>{$name},</br>SmallURL ".$role['name'];
		} else {
			return false;
		}
	}
	function name($uid) {
		global $u;
		// Gets a nicely formatted name.
		$name = $u->core($uid)->get("name");
		$username = $u->core($uid)->get("username");
		if ($username === $name) {
			return "<a href='{$u->link($uid)}'>".$username."</a>";
		} else {
			return $name." [<a href='{$u->link($uid)}'>{$username}</a>]";
		}
	}
	function badge($uid) {
		global $db,$u;
		// Gets the closest level related badge.
		$level = $u->level($uid);
		$badges = db::get_array("badges");
		$got = false;
		$bl = -1;
		foreach ($badges as $b) {
			if ($b['level'] != "-1") {
				if ($b['level'] <= $level) {
					if ($b['level'] > $bl) {
						$bl = $b['level'];
						$got = "<span class='label label-{$b['colour']}'>{$b['text']}</span>";
					}
				}
			}
		}
		if ($got) {
			return $got;
		} else {
			return "<span class='label label-default'>User</span>";
		}
	}
}
$support = new support();
?>