<?php
$s_id = db::get_array("entries",array("short"=>$id));
if (count($s_id) <= 0) {
	include("not_found.php");
}
else {

$url_dat = db::get_array("entries",array("short"=>$id));
$url_dat = $url_dat[0];

if ($url_dat['suspended'] == 0 || $u->level() >= 50) {

$html->PageTitle("Safety Torch", "Playing it safe since.. 2014!");
if (isset($_VAR['t']) && $_VAR['t'] != "") {
	$tab = strtolower($_VAR['t']);
} else {
	$tab = "info";
}
?>

<div class="wrap">
<div class='toppadding'></div>
<?php
	$s_id = $s_id[0]['id'];
	click_check($s_id);
?>
<!-- Heatmap Javascript -->
<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="//static.<?php echo $_SMALLURL['domain']; ?>/global/js/thirdparty/heatmap.js"></script>
<script type="text/javascript" src="//static.<?php echo $_SMALLURL['domain']; ?>/global/js/thirdparty/heatmap-gmaps.js"></script>
<style>
	#heatmapArea {
		position:relative;
		width:100%;
		height:500px;
		border:1px #111;
	}
	.info {
		vertical-align:top;
		padding-left:10px;
	}
	#screenie {
		margin-top:10px;
	}
</style>
<?php
$data = array();
$clicks = db::get_array("clicks",array("smallurl"=>$s_id,"useragent"=>array("comp"=>"NOT LIKE","val"=>"%BOT%")));
foreach ($clicks as $ck) {
	if ($ck['lat'] != "0" && $ck['lng'] != "0") {
		$data[] = array("lat"=>$ck['lat'],"lng"=>$ck['lng']);
	}
}
$heatmap = $data;

$today_stamp = time()-86400;
$todays_clicks = db::get_array("clicks",array("smallurl"=>$s_id,"stamp"=>array("comp"=>">=","val"=>$today_stamp),"useragent"=>array("comp"=>"NOT LIKE","val"=>"%BOT%")));
?>

<!-- Page content below -->
<center>
	<h2>http://SmallURL.in/<?php echo $id; ?> <?php if ($url_dat['flagged']) { echo "<button class='btn btn-danger' onclick=\"$('#flagged').toggle();\" title='This URL has been caught by our system and is flagged as being potentionally dangerous.'><span class='glyphicon glyphicon-exclamation-sign'></span></button>"; } ?></h2>
</center>
<div id="flagged" class="alert alert-danger" style="display:none;">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<strong>Auto Flagged!</strong>
	<br/>
	This URL has been caught by the System to have been clicked excessively in the past two hours.
	<Br/>
	The URL has been flagged and SmallURL Staff have been notified!
</div>
<?php if ($url_dat['suspended'] && $u->level() >= 50) { ?>
	<div id="suspended" class="alert alert-danger">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<strong>Suspended!</strong>
		<br/>
		This URL has been suspended and cannot be navigated to. You are able to see this as you are an authority figure on our system.
		<br/>
		Please do NOT leak the destination of this URL. It has been suspended for a reason.
	</div>
<?php } ?>
<!-- URL Tabbing. -->
<ul class="nav nav-tabs">
  <li<?php if ($tab == "info") { echo ' class="active"'; } ?>><a href="#url_info" data-toggle="tab">Info</a></li>
  <li<?php if ($tab == "heatmap") { echo ' class="active"'; } ?>><a href="#url_hmap" data-toggle="tab">HeatMap</a></li>
  <?php if ($url_dat['suspended'] == 0 || $u->level() >= 50) { ?>
	<li<?php if ($tab == "screenshot" || $tab == "preview") { echo ' class="active"'; } ?>><a href="#url_preview" data-toggle="tab">Preview</a></li>
  <?php } ?>
  <li<?php if ($tab == "stats") { echo ' class="active"'; } ?>><a href="#url_stats" data-toggle="tab">Stats</a></li>
</ul>

<script type="text/javascript">
var map;
var heatmap;

$('a[href="#url_hmap"]').click(function() {
	setTimeout(function() {
		var myLatlng = new google.maps.LatLng(<?php if (strlen($url_dat['lat']) > 0) { echo $url_dat['lat'].",".$url_dat['lng']; } else { echo "0,0"; }?>);
		var myOptions = {
		  zoom: 2,
		  center: myLatlng,
		  mapTypeId: google.maps.MapTypeId.ROADMAP,
		  disableDefaultUI: false,
		  scrollwheel: false,
		  draggable: true,
		  navigationControl: true,
		  mapTypeControl: false,
		  scaleControl: true,
		  disableDoubleClickZoom: false
		};
		map = new google.maps.Map(document.getElementById("heatmapArea"), myOptions);

		heatmap = new HeatmapOverlay(map, {"radius":15, "visible":true, "opacity":40});


		var statData={
				max: 45,
				data: <?php $hm = json_encode($heatmap); echo $hm; ?>
			};


		// this is important, because if you set the data set too early, the latlng/pixel projection doesn't work
		google.maps.event.addListenerOnce(map, "idle", function(){
			heatmap.setDataSet(statData);
		});
	}, 500);
});
</script>

<!-- Tab panes -->
<div class="tab-content">
	<div class="tab-pane fade in<?php if ($tab == "info") { echo ' active'; } ?>" id="url_info" style="padding:5px;">
		<!-- Information about this URL -->
		<?php
			$browser = browser_name($url_dat['useragent']);
		?>
		<p><b>Destination URL:</b><br/><?php echo $url_dat['content']; ?></p>
		<p><b>Country of creation:</b><br/><?php if ($url_dat['country'] != "") { echo $url_dat['country']; } else { echo "Unknown"; } ?></p>
		<p><b>Date of creation:</b><br/><?php echo dateify($url_dat['stamp']); ?></b>
		<p><b>Smallifier:</b><br/><?php
		if ($url_dat['user']) {
			echo "<a href=\"{$u->link($url_dat['user'])}\">{$u->display_name($url_dat['user'])}</a>";
		} else {
			echo $u->username($url_dat['user']);
		}
		?></b>
		<p><b>Created via:</b><br/><?php if ($url_dat['device'] === "1") { echo "Mobile"; } else { echo "Browser"; } ?> (<?php echo $browser['name']." on ".$browser['os']; ?>)</p>
		<p><b>SmallURL Clicks:</b><br/><?php echo url_uses($url_dat['id']); ?></p>
		<p><b>Clicks in past 24hrs:</b><br/><?php echo count($todays_clicks); ?></p>
		<p><b>Explicit:</b><br/><?php $explicit = screen_url($url_dat['content']); if ($explicit===null) { echo "N/A; Link is a file. Won't be scanned."; } else { if ($explicit) { echo "Yes"; } else { echo "No"; } } ?></p>
		<p>
			<b>Web of Trust (WoT) Rating:</b><br/>
			<?php
				$wot_rating = weboftrust($url_dat['content']);
			?>
			<a href="<?php echo $wot_rating['scorecard'];?>">
				<img src="//static.<?php echo $_SMALLURL['domain']; ?>/smallurl/img/icons/<?php echo $wot_rating['image']; ?>"/>
			</a>
			<?php echo $wot_rating['text']; ?>
		</p>
		<p><b>Visibility:</b><br/><?php if ($url_dat['private'] === "1") { echo "Private"; } else { echo "Public"; } ?></p>
	</div>
	<div class="tab-pane fade in<?php if ($tab == "heatmap") { echo ' active'; } ?>" id="url_hmap">
		<!-- URL Heatmap -->
		<center>
			<div id="heatmapArea"><img style="margin-top:247px" src="//static.<?php echo $_SMALLURL['domain']; ?>/<?php echo $_SMALLURL['THEME']; ?>/img/icons/loading.gif"/></div>
		</center>
	</div>
	<?php if ($url_dat['suspended'] == 0 || $u->level() >= 50) { ?>
		<div class="tab-pane fade in<?php if ($tab == "screenshot" || $tab == "preview") { echo ' active'; } ?>" id="url_preview">
			<!-- URL Preview -->
			<center>
			<?php
			$yt_id = is_youtube($url_dat['content']);
			if ($yt_id) {
				// Youtube Stuffs.
				$data = yt_get($yt_id);
				if ($data != false && $data['res']) {
			?>
				<table width="80%">
					<tr>
						<td>
							<center>
								<h4><?php echo $data['title']; ?></h4>
							</center>
						</td>
					</tr>
					<tr>
						<td>
							<iframe width="100%" height="415" src="//www.youtube.com/embed/<?php echo $yt_id; ?>" frameborder="0"></iframe>
						</td>
					</tr>
					<tr>
						<td>
						<div class="well well-small" style="border-radius:0px;padding-top:5px;margin-top:-7px;">
							<h4>
								<b><span class="label label-success"><?php echo $data['author']; ?></span></b>
								<a href="http://surl.im/<?php echo $id; ?>" class="label label-danger pull-right">Watch on Youtube</a>
							</h4>
							<?php
								$desc = explode("\n",$data['desc']);
								foreach ($desc as $line) {
									echo $line."<br/>";
								}
							?>
						</div>
						</td>
					</tr>
				</table>
			<?php } else {
					if (!$data) {
						echo "<h2>Unable to generate preview!</h2>";
					} else {
						echo "<h2>Error generating preview!</h2><h3>".$data['msg']."</h3>";
					}
				}
			} else { ?>
				<!-- URL Screenshot -->
				<div id="screenie">
					<?php if (is_image($url_dat['content'])) { ?>
						<img class="thumbnail" width="70%" src="data:image/png;base64,<?php echo base64_encode(url_get_contents($url_dat['content'])); ?>"/>
					<?php } else { ?>
						<img class="thumbnail" width="70%" src="//<?php echo $_SERVER['HTTP_HOST']; ?>/screenshot/?<?php echo $id; ?>"/>
					<?php } ?>
					<br/>
					<?php if (is_image($url_dat['content'])) { ?>
						<small>The screenshot above is an inline image using the <a href="http://en.wikipedia.org/wiki/Data_URI_scheme">Data URI Scheme</a>, if you cannot see the image then your browser doesn't support Data URI's!</small>
					<?php } ?>
				</div>
			<?php } ?>
			</center>
		</div>
	<?php } ?>
	
	
	
	<?php
	$top = array();
	$top['countries'] = array();
	$top['browsers'] = array();
	
	// Get dem stats
	$countries = db::query('SELECT `country`,COUNT(*) FROM `smallurl_clicks` WHERE `smallurl`='.$url_dat['id'].' AND `country` != "" GROUP BY `country` ORDER BY COUNT(*) DESC LIMIT 0,10');
	while ($row = $countries->fetch_array(MYSQL_ASSOC)) {
		$top['countries'][$row['country']] = $row['COUNT(*)'];
	}
	
	$countries = db::query('SELECT `useragent`,COUNT(*) FROM `smallurl_clicks` WHERE `smallurl`='.$url_dat['id'].' AND `useragent`!="" GROUP BY `useragent` ORDER BY COUNT(*) DESC');
	while ($row = $countries->fetch_array(MYSQL_ASSOC)) {
		$ua = browser_name($row['useragent']);
		$md5 = $ua['name'];
		if (isset($top['browsers'][$md5])) {
			$top['browsers'][$md5]['clicks'] = $top['browsers'][$md5]['clicks'] + (int)$row['COUNT(*)'];
		} else {
			$top['browsers'][$md5] = array("name"=>$ua['name'],"clicks"=>(int)$row['COUNT(*)']);
		}
	}
	?>
	<div class="tab-pane fade in<?php if ($tab == "stats") { echo ' active'; } ?>" id="url_stats">
		<!-- In depth statistics! -->
			<?php
			$posted = array();
			$posted['countries'] = 0;
			$posted['websites'] = 0;
			
			$countries = db::query('SELECT COUNT(*) FROM `smallurl_clicks` WHERE `smallurl`='.$url_dat['id'].' AND `country` != "" GROUP BY `country`');
			while ($row = $countries->fetch_array(MYSQL_ASSOC)) {
				$posted['countries']++;
			}
			
			$websites = db::query('SELECT COUNT(*) FROM `smallurl_clicks` WHERE `smallurl`='.$url_dat['id'].' AND `ref` != "" GROUP BY `ref`');
			while ($row = $websites->fetch_array(MYSQL_ASSOC)) {
				$posted['websites']++;
			}
			?>
			<center>
				<h2>This SmallURL has been...</h3>
			</center>
			<table style="width:100%;text-align:center;">
				<tr>
					<td>Seen in</td>
					<td>Posted on</td>
				</tr>
				<tr>
					<td><h2><?=$posted['countries']?></h2></td>
					<td><h2><?=$posted['websites']?></h2></td>
				</tr>
				<tr>
					<td>Countries</td>
					<td>Websites</td>
				</tr>
			</table>
			<hr>
			<center>
				<h2>Top 10</h2>
			<table width="100%" valign="top">
				<tr>
					<td>
						<b>Top 10 Countries</b>
						<ul>
							<?php
								if (count($top['countries']) > 0) {
									foreach ($top['countries'] as $cnt => $cct) {
										echo "<li>{$cnt} ({$cct} clicks)</li>";
									}
								} else {
									echo "No data to show!";
								}
							?>
						</ul>
					</td>
					<td>
						<b>Top 10 Browsers</b>
						<ul>
							<?php
								if (count($top['browsers']) > 0) {
									foreach ($top['browsers'] as $brc) {
										echo "<li>{$brc['name']} ({$brc['clicks']} clicks)</li>";
									}
								} else {
									echo "No data to show!";
								}
							?>
						</ul>
					</td>
				</tr>
			</table>
	</div>
</div>

<!-- Extra Buttons -->
<hr/>
<center>
	<a href="http://surl.im/<?php echo $id; ?>" class="btn btn-primary">Let's Go!</a>
	<?php
	if ($_SMALLURL['LOGGEDIN']) {
		if (count(db::get_array("report",array("user"=>$_SMALLURL['UID'],"item"=>$s_id))) <= 0) {
			$report_modal = $modal->create("Report URL",'Please enter a reason for you reporting this URL:<br/><br/><textarea class="form-control" placeholder="Enter your reason here, please enter a valid reason why this URL has offended you. For an urgent response open a ticket!" id="report_text" style="margin: 0px; width: 529px; height: 118px;" onkeyup="$(\'#len\').html($(\'#report_text\').val().length)"></textarea><br/><span id="len">0</span> Characters',array("danger%Report"=>"report_url('%id%','{$id}')","primary%Cancel"=>"close"));
			echo '<input type="button" class="btn btn-danger" value="Report URL" data-toggle="modal" data-target="#'.$report_modal.'"/>';
		} else {
			echo '<input type="button" class="btn btn-danger" disabled value="Report URL"/>';
		}
	}
	?>
</center>
<?php
} else { include("suspended.php"); }

}

?>