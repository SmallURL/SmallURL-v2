<?php
// Pagination,
// Old code is left in for the sake of not breaking EVERYTHING
function gen_pagination($count,$current,$prefix = "",$affix = "",$anchor = "") {
	$return = "<ul class=\"pagination\">";
	if ($current >= 3) {
		$return .= "<li><a href=\"{$prefix}1{$affix}{$anchor}\">&amp;laquo;</a></li>";
		$return .= "<li><a href=\"{$prefix}".($current-1)."{$affix}{$anchor}\">&amp;lt;</a></li>";
		$return .= "<li><a href=\"{$prefix}".($current-3)."{$affix}{$anchor}\">".($current-3)."</a></li>";
		$return .= "<li><a href=\"{$prefix}".($current-2)."{$affix}{$anchor}\">".($current-2)."</a></li>";
		$return .= "<li><a href=\"{$prefix}".($current-1)."{$affix}{$anchor}\">".($current-1)."</a></li>";
	} else {
		$return .= "<li class=\"disabled\"><a href=\"{$anchor}\">&amp;laquo;</a></li>";
		$return .= "<li class=\"disabled\"><a href=\"{$anchor}\">&amp;lt;</a></li>";
	}
	$return.= "<li class=\"active\"><a href=\"#\">{$current}</a></li>";
	if ($current <= $count-3) {
		$return .= "<li><a href=\"{$prefix}".($current+1)."{$affix}{$anchor}\">".($current+1)."</a></li>";
		$return .= "<li><a href=\"{$prefix}".($current+2)."{$affix}{$anchor}\">".($current+2)."</a></li>";
		$return .= "<li><a href=\"{$prefix}".($current+3)."{$affix}{$anchor}\">".($current+3)."</a></li>";
		$return .= "<li><a href=\"{$prefix}".($current+1)."{$affix}{$anchor}\">&amp;gt;</a></li>";
		$return .= "<li><a href=\"{$prefix}{$count}{$anchor}\">&amp;raquo;</a></li>";
	} else {
		$return .= "<li class=\"disabled\"><a href=\"{$anchor}\">&amp;gt;</a></li>";
		$return .= "<li class=\"disabled\"><a href=\"{$anchor}\">&amp;raquo;</a></li>";
	}
	$return .= "</ul>";
	return $return;
}

function count_pages($array,$perpage) {
	// Returns how many pages you'd get out of the array.
	if (count($array) < $perpage) {
		return 1;
	}
	else {
		return ceil(count($array)/$perpage);
	}
}
function get_pages($array,$perpage) {
	return array_chunk($array,$perpage,true);
}

class pagination {
	public function amount($table,$conditional = false,$per = 20) {
		global $db;
		$amount = db::get_array($table,$conditional,"COUNT(*)");
		$amnt = intval($amount[0]['COUNT(*)']);
		if ($amnt < $per) {
			return 1;
		}
		else {
			return ceil($amnt/$per);
		}
	}
	public function get($table,$conditional,$page = 1,$perpage = 20) {
		global $db,$config;
		$prefix = config::get('db_pref');
		$cond = db::conditional($conditional);
		$start_page = $perpage * $page;
		$data = db::query("SELECT * FROM {$prefix}_{$table} WHERE {$cond} ORDER BY `id` DESC LIMIT {$start_page},{$perpage}");
		
		$ret = array();
		if ($data) {
			while ($row = $data->fetch_array(MYSQL_ASSOC)) {
				$ret[] = $row;
			}
		}
		return $ret;
	}
	public function html($count,$current,$prefix = "",$affix = "",$anchor = "") {
		$return = "<ul class=\"pagination\">";
		if ($current >= 3) {
			$return .= "<li><a href=\"{$prefix}1{$affix}{$anchor}\">&amp;laquo;</a></li>";
			$return .= "<li><a href=\"{$prefix}".($current-1)."{$affix}{$anchor}\">&amp;lt;</a></li>";
			$return .= "<li><a href=\"{$prefix}".($current-3)."{$affix}{$anchor}\">".($current-3)."</a></li>";
			$return .= "<li><a href=\"{$prefix}".($current-2)."{$affix}{$anchor}\">".($current-2)."</a></li>";
			$return .= "<li><a href=\"{$prefix}".($current-1)."{$affix}{$anchor}\">".($current-1)."</a></li>";
		} else {
			$return .= "<li class=\"disabled\"><a href=\"{$anchor}\">&amp;laquo;</a></li>";
			$return .= "<li class=\"disabled\"><a href=\"{$anchor}\">&amp;lt;</a></li>";
		}
		$return.= "<li class=\"active\"><a href=\"#\">{$current}</a></li>";
		if ($current <= $count-3) {
			$return .= "<li><a href=\"{$prefix}".($current+1)."{$affix}{$anchor}\">".($current+1)."</a></li>";
			$return .= "<li><a href=\"{$prefix}".($current+2)."{$affix}{$anchor}\">".($current+2)."</a></li>";
			$return .= "<li><a href=\"{$prefix}".($current+3)."{$affix}{$anchor}\">".($current+3)."</a></li>";
			$return .= "<li><a href=\"{$prefix}".($current+1)."{$affix}{$anchor}\">&&amp;gt;</a></li>";
			$return .= "<li><a href=\"{$prefix}{$count}{$anchor}\">&amp;raquo;</a></li>";
		} else {
			$return .= "<li class=\"disabled\"><a href=\"{$anchor}\">&amp;gt;</a></li>";
			$return .= "<li class=\"disabled\"><a href=\"{$anchor}\">&amp;raquo;</a></li>";
		}
		$return .= "</ul>";
		return $return;
	}
}
$pagination = new pagination();
?>