<?php
$mdata = "Have a look at SmallURL and a break down of its statistics.";
$html->PageTitle("Statistics", "What SmallURL Really is!");
?>
	<div class="wrap">
	<div class='toppadding'></div>

	<?php
	$day_stamp = time()-86400;
	$week_stamp = time()-604800;
	$month_stamp = time()-2592000;

	// Use COUNT(*) to save getting a TON of Data.
	$all = db::get_array("entries",array("stamp"=>array("comp"=>">=","val"=>$month_stamp)),"COUNT(*)");
	$day_entries = db::get_array("entries",array("stamp"=>array("comp"=>">=","val"=>$day_stamp)),"COUNT(*)");
	$week_entries = db::get_array("entries",array("stamp"=>array("comp"=>">=","val"=>$week_stamp)),"COUNT(*)");

	?>
	<div class="page-header">
		<h3>Statistics<br/><small>What's going on in SmallURL land?<br/><i>Stats may be up to 30mins out of date due to caching for speed!</i></small></h3>
	</div>

	<h4>URL Shortening Stats</h4>
	<table style="width:100%;text-align:center;">
		<tr>
			<td><h2 class="stats_total"><?php echo $day_entries[0]['COUNT(*)']; ?></h2></td>
			<td><h2 id="stats_custom"><?php echo $week_entries[0]['COUNT(*)']; ?></h2></td>
			<td><h2 id="stats_random"><?php echo $all[0]['COUNT(*)']; ?></h2></td>
		</tr>
		<tr>
			<td>Shortened in the past 24hrs</td>
			<td>Shortened in the past week</td>
			<td>Shortened in the past 30 days</td>
		</tr>
	</table>

	<?php
	$day_stamp = time()-86400;
	$week_stamp = time()-604800;
	$month_stamp = time()-2592000;

	// Use COUNT(*) to save getting a TON of Data.
	$all = db::get_array("clicks",array("stamp"=>array("comp"=>">=","val"=>$month_stamp)),"COUNT(*)");
	$day_clicks = db::get_array("clicks",array("stamp"=>array("comp"=>">=","val"=>$day_stamp)),"COUNT(*)");
	$week_clicks = db::get_array("clicks",array("stamp"=>array("comp"=>">=","val"=>$week_stamp)),"COUNT(*)");

	?>
	<h4>URL Click Stats</h4>
	<table style="width:100%;text-align:center;">
		<tr>
			<td><h2 class="stats_total"><?php echo $day_clicks[0]['COUNT(*)']; ?></h2></td>
			<td><h2 class="stats_custom"><?php echo $week_clicks[0]['COUNT(*)']; ?></h2></td>
			<td><h2 class="stats_random"><?php echo $all[0]['COUNT(*)']; ?></h2></td>
		</tr>
		<tr>
			<td>Clicks in the past 24hrs</td>
			<td>Clicks in the past week</td>
			<td>Clicks in the past 30 days</td>
		</tr>
	</table>

	<h4>All time URL stats</h4>
	<table style="width:100%;text-align:center;">
		<tr>
			<td><h2 class="stats_total"><?php echo htmlentities(url_count_total()); ?></h2></td>
			<td><h2 class="stats_custom"><?php echo htmlentities(url_count_custom()); ?></h2></td>
			<td><h2 class="stats_random"><?php echo htmlentities(url_count_rand()); ?></h2></td>
		</tr>
		<tr>
			<td>Total Shortened</td>
			<td>Custom Shortened</td>
			<td>Random Shortened</td>
		</tr>
	</table>

	<hr/>

	<div class="page-header">
	<a id="browser"></a>
	<h3>Browsers Graph<br/><small>What's hot and whats not in SmallURL land?</small></h3>
	</div>
	<div style='text-align:center'>
		<iframe id="browser_graph" src="/ajax/stat_browsers.php">
			<div style='text-align:center'>
				<img id="loading" src="http://static.<?php echo $_SMALLURL['domain']; ?>/<?php echo $_SMALLURL['THEME']; ?>/img/icons/loading.gif" alt='Loading'/>
			</div>
		</iframe>
	</div>
	
	<h3>Usage Heatmap<br/><small>A cool heatmap showing how popular SmallURL is and where!</small></h3>
	<?php
	// Cached Screenshots
	$cachecount = count(scandir($_SERVER['DOCUMENT_ROOT']."/../cache/screenshot/"))-2;

	if (!cache::exists("usage_stats")) {
		// Click count
		$clicks = db::get_array("clicks",false,"COUNT(*)");
		$clicks = $clicks[0]['COUNT(*)'];

		$usage = array();
		$usage['clicks'] = $clicks;
		$usage['clicks_geo'] = click_count();
		$usage['urls_geo'] = geo_url_count();
		$usage['urls_total'] = url_count_total();
		$usage['data_count'] = data_count();
		cache::store("usage_stats",$usage);
	} else {
		$usage = cache::read("usage_stats");
	}
	?>
	<hr>
	<table style="width:100%; text-align:center; line-height:0px;">
		<tr>
			<td>
				<h2><?php echo $usage['clicks']; /* Clicks */ ?></h2>
			</td>
			<td>
				<h2><?php echo $usage['clicks_geo']; /* Clicks with Geo Data */ ?></h2>
			</td>
			<td>
				<h2><?php echo $usage['urls_geo']; /* URLs have Geo Data */ ?></h2>
			</td>
			<td>
				<h2><?php echo $usage['urls_total']; /* Total URLS */ ?></h2>
			</td>
			<td>
				<h2><?php echo $usage['data_count']; /* Points of data */ ?></h2>
			</td>
			<td>
				<h2><?php echo $cachecount; /* Cached screenshots */ ?></h2>
			</td>
		</tr>
		<tr>
			<td>
				Clicks
			</td>
			<td>
				Clicks have Geo Data
			</td>
			<td>
				URLs have Geo Data
			</td>
			<td>
				URLs
			</td>
			<td>
				Data Points
			</td>
			<td>
				Cached Screenshots
			</td>
		</tr>
	</table>
		<hr>
	<div style='text-align:center'>
		<iframe id="heat_map" src="/ajax/stat_heatmap.php">
			<div style='text-align:center'>
				<img id="loading" src="http://static.<?php echo $_SMALLURL['domain']; ?>/<?php echo $_SMALLURL['THEME']; ?>/img/icons/loading.gif" alt='Loading' />
			</div>
		</iframe>
	</div>
	<div style="height:70px;"></div>
</div>