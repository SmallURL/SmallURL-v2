<?php if ($_SMALLURL['LOGGEDIN'] && $u->is_admin()) { ?>
<?php $html->PageTitle("Admin Dash", "Someones gotta manage it!"); ?>
<?php include("asset/nav.php"); ?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<style>
	#heatmapArea {
		position:relative;
		width:700px;
		height:500px;
		border:1px #111;
	}
</style>
<div class="wrap">
	<div class='toppadding'></div>
	<h4>&nbsp;</h4>
	<h5 style='text-align:center;'><strong>Hey <?php echo htmlentities(gettok(" ",get_user($_SMALLURL['UID']),0)); ?>, welcome to the new and shiny Administration Dashboard!</strong></h5>
	<p style='text-align:center;'>This dash allows access to manage SmallURL and view statistics.</p>
	<hr>
	<?php
	$day_stamp = time()-86400;
	$week_stamp = time()-604800;
	$month_stamp = time()-2592000;

	// No we need to collect the URLS In question, Using one SQL Query and some PHP.
	$day_entries = array();
	$week_entries = array();

	$all = db::get_array("entries",array("stamp"=>array("comp"=>">=","val"=>$month_stamp)));

	if (count($all) > 0) {
		// You never know. The Service could just DIE for a month!

		foreach ($all as $url) {
			if ($url['stamp'] >= $day_stamp) {
				$day_entries[] = $url;
			}
			if ($url['stamp'] >= $week_stamp) {
				$week_entries[] = $url;
			}
		}
	}
	?>
	<div style='text-align:center;'>
	 <h4 >Simple Stats</h4>
	<div style="display:block; width:100%;">
	<center>
		<div style='width: 500px; margin:auto'>
		<div class="cblock">
			<div class="cblock_count"><?php echo count($day_entries); ?></div>
			<div class="cblock_lower">
			URLs Today
			</div>
         </div>
	   	<div class="cblock">
			<div class="cblock_count"><?php echo count($week_entries); ?></div>
			<div class="cblock_lower">
			URLs this week
			</div>
         </div>
	   	<div class="cblock">
			<div class="cblock_count"><?php echo count($all); ?></div>
			<div class="cblock_lower">
			URLs this month
			</div>
        </div>
    </div>
	</center>
	</div>
	<br /><br /><br /><br /><br /><br />
	</p>
	<?php
	// No we need to collect the URLS In question, Using one SQL Query and some PHP.
	$day_entries = array();
	$week_entries = array();

	$all = db::get_array("clicks",array("stamp"=>array("comp"=>">=","val"=>$month_stamp)));

	if (count($all) > 0) {
		// You never know. The Service could just DIE for a month!

		foreach ($all as $url) {
			if ($url['stamp'] >= $day_stamp) {
				$day_entries[] = $url;
			}
			if ($url['stamp'] >= $week_stamp) {
				$week_entries[] = $url;
			}
		}
	}
	?>
	<h4>Click Stats</h4>
	<center>
		<div style='width: 500px; margin:auto'>
		<div class="cblock">
			<div class="cblock_count"><?php echo count($day_entries); ?></div>
			<div class="cblock_lower">
			Clicks Today
			</div>
         </div>
	   	<div class="cblock">
			<div class="cblock_count"><?php echo count($week_entries); ?></div>
			<div class="cblock_lower">
			Clicks this week
			</div>
         </div>
	   	<div class="cblock">
			<div class="cblock_count"><?php echo count($all); ?></div>
			<div class="cblock_lower">
			Clicks this month
			</div>
        </div>
    </div>
	</center>
	</p>
	<br /><br /><br /><br /><br /><br />
	<h4>All time Stats</h4>
	<center>
		<div style='width: 500px; margin:auto'>
		<div class="cblock">
			<div class="cblock_count"><?php echo htmlentities(url_count_total()); ?></div>
			<div class="cblock_lower">
			SmallURLs
			</div>
         </div>
	   	<div class="cblock">
			<div class="cblock_count"><?php echo htmlentities(url_count_custom()); ?></div>
			<div class="cblock_lower">
			Custom SmallURLs
			</div>
         </div>
	   	<div class="cblock">
			<div class="cblock_count"><?php echo htmlentities(url_count_rand()); ?></div>
			<div class="cblock_lower">
			Random SmallURLs
			</div>
		</div>
        </div>
	</center>
	<br /><br /><br /><br /><br /><br /><br />
	<h4>SmallURL Heatmap</h4>
	<hr/>
	<center><div id="heatmapArea"></div></center>
	<h4> &nbsp; </h4>
	<div style='display:none;'>
		<?php print_r($_SERVER); ?>
	</div>
</div>
</div>
<?php
}
else {
	header("Location: /404");
}
?>