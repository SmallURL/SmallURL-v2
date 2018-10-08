<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
$cstamp = date("d_G");

$cont = true;
if (cache::exists("heatmap_stats")) {
	$list = cache::read("heatmap_stats");
	$cont = false;
}


if ($cont) {
	$d = db::query('select `country`, count(*) from smallurl_clicks group by `country` order by count(*) DESC');
	$ret = array("Country"=>"Clicks");
	if ($d) {
		while ($row = $d->fetch_array(MYSQL_ASSOC)) {
			if ($row['country'] != "") {
				$ret[$row['country']] = (int)$row['count(*)'];
			}
		}
	}
	$list = array_to_list($ret);
	cache::store("heatmap_stats",$list,30);
}
?>
<script type="text/javascript" src="//www.google.com/jsapi"></script>
<style>
	body {
		margin:0;
		padding:0;
	}
	#geomap {
		position:relative;
		width:100%;
		height:100%;
		border:1px #111;
		font-family:"Arial";
	}
</style>
<div id="geomap">
	<center>
		<h3>Loading map...</h3>
	</center>
</div>
<!-- Geo Location Script -->
<script type='text/javascript'>
 google.load('visualization', '1', {'packages': ['geochart']});
 google.setOnLoadCallback(drawRegionsMap);

  function drawRegionsMap() {
	var data = google.visualization.arrayToDataTable(<?=$list?>);

	var options = {colorAxis: {colors: ['#88BAFF', '#0056CC']}};

	var chart = new google.visualization.GeoChart(document.getElementById('geomap'));
	chart.draw(data, options);
};
</script>