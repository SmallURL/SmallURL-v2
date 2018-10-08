<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if (has_param('q') && get_param('q') != "") {
	// Search here.
	$term = get_param('q');
	$urls = db::get_array("entries",array("content"=>array("comp"=>"LIKE","val"=>"%{$term}%"),"type"=>"0"));
	echo "<h3>".count($urls)." results were found for '{$term}' <a href='/search/".urlencode($term)."'><span class=\"glyphicon glyphicon-link\"></span></a></h3>";
	echo "<table style='width-max:100%;' class='table'><tr><thead><td><b>SmallURL</b></td><td><b>Long URL</b></td></thead></tr>";
	if (has_param('i') && get_param('i') != "") {
		$current = get_param('i');
	} else {
		$current = 1;
	}
	$max = 30;
	
	if (count($urls) >= 1) {
		$page_count = count_pages($urls,$max);
		$pages = get_pages($urls,$max);
		$data = $pages[$current-1];
		foreach ($data as $u) {
			$long = $u['content'];
			$max_len = 90;
			if (strlen($long) > $max_len) {
				$long = substr($long,0,$max_len)."...";
			}
			echo "<tr><td><a href='http://smallurl.in/{$u['short']}/more'>http://smallurl.in/{$u['short']}</a></td><td>{$long}</td></tr>";
		}
		echo "</table>";
		if ($page_count > 1) {
			echo "<div>".gen_pagination($page_count,$current,"/search/{$term}/","")."</div>";
		}
	} else {
		echo "<tr><td colspan='2'><div STYLE='text-align:center'><i>There are no results to display.</i></div></td></tr></table>";
	}
}
else {
	echo "<div STYLE='text-align:center'><i>There are no results to display.</i></div>";
}
?>