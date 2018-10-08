<?php include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php"); ?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<style>
	body {
		margin:0;
		padding:0;
	}
	h3 {
		font-size:24px;
	}
	h1,
	h2,
	h3,
	h4,
	h5,
	h6,
	.h1,
	.h2,
	.h3,
	.h4,
	.h5,
	.h6 {
	  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	  font-weight: 500;
	  line-height: 1.1;
	}
</style>
<?php
		if (!cache::exists("browser_stats")) {
			$clicks = db::query("select `useragent`, count(*) from smallurl_clicks WHERE `useragent` NOT LIKE '%BOT%' group by `useragent` order by count(*) DESC");
			$data = array();
			if ($clicks) {
				while ($row = $clicks->fetch_array(MYSQL_ASSOC)) {
					if ($row['useragent'] != "") {
						$ua = browser_name($row['useragent']);
						$strname = $ua['name'];
						if ($strname == "AppleWebKit") {
							$strname = "Safari";
						} else if ($strname == "IEMobile") {
							$strname = "MSIE";
						} else if ($strname == "NetFront") {
							$strname = "NetFront [{$ua['os']}]";
						}
						$md5 = md5(strtolower($ua['name']));
						if (isset($data[$md5])) {
							$data[$md5] = array("name"=>$ua['name'],"clicks"=>((int)$row['count(*)']+(int)$data[$md5]['clicks']));
						} else {
							$data[$md5] = array("name"=>$ua['name'],"clicks"=>(int)$row['count(*)']);
						}
					}
				}
			} else {
				die("Bad SQL");
			}
			cache::store("browser_stats",$data,30);
		} else {
			$data = cache::read("browser_stats");
		}
		?>
	<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
        ['Browser', 'Clicks'],
		<?php
		$total = 0;
		foreach ($data as $key => $dat) {
			$total += $dat['clicks'];
			echo "['{$dat['name']} ({$dat['clicks']})', {$dat['clicks']}],";
		}
		?>
        ]);
        var options = {
          is3D: true
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
    </script>
	<div id="piechart_3d" style="width: 100%; height: 400px;">
		<center>
			<h3>Loading heatmap...</h3>
		</center>
	</div>
	<center>
		<h3>A total of <?php echo $total; ?> clicks!</h3>
	</center>