<?php $mdata = "A peek into what really happens with SmallURl, view the latest URLs or see what's currently trending on SmallURL!"; ?>
 <?php $html->PageTitle(_tr("misc/list", "pagetitle"), _tr("misc/list", "pagesubtitle")); ?>

    <div class="wrap">
    <div class='toppadding'></div>
<?php
$page_num = array();
$perpage = 20; // Links per Page
if (isset($_GET['rand']) && $_GET['rand'] != "") {
	$page_num['rand'] = htmlentities($_GET['rand']);
}
else {
	$page_num['rand'] = 1;
}

if (isset($_GET['custom']) && $_GET['custom'] != "") {
	$page_num['custom'] = htmlentities($_GET['custom']);
}
else {
	$page_num['custom'] = 1;
}
?>
<div class="page-header">
	<h3><?=_tr("misc/list", "trendingurls")?></h3>
	<?=_tr("misc/list", "trending_desc")?>
	<br/>
	<p>
		<b><?=_tr("misc/list", "didyouknow")?></b>
		<br/>
		<?=_tr("misc/list", "didyou_1")?> <strong><?php echo htmlentities(url_count_total()); ?></strong> <?=_tr("misc/list", "didyou_2")?> <strong><?php echo htmlentities(url_count_custom()); ?></strong> <?=_tr("misc/list", "didyou_3")?>
	</p>
</div>

<p>
<h3><?=_tr("misc/list", "top10")?><br/><small><?=_tr("misc/list", "top10_desc")?></small></h3>
<table class="table">
	<thead>
		<tr>
			<th><?=_tr("misc/list", "table_url")?></th>
			<th><?=_tr("misc/list", "table_short")?></th>
			<th><?=_tr("misc/list", "table_date")?></th>
			<th><?=_tr("misc/list", "table_shortened")?></th>
			<th><?=_tr("misc/list", "table_users")?></th>
		</tr>
	</thead>
	<?php
	if (cache::exists("trending_top10")) {
		$top = cache::read("trending_top10");
	} else {
		$top = trending_urls();
		cache::store("trending_top10",$top,15);
	}

	foreach ($top as $url) {
		?>
		<tr>
			<td style="width: 100px;">
			<?php if ($url['device'] === "1") { ?> <img alt="Mobile" src="//static.<?php echo SITE_URL; ?>/smallurl/img/icons/mobile-icon.png" width="25" title="Shortened on a mobile device."/><?php } ?>
			<?php echo htmlentities(url_trim($url['content']));?></td>
			<td><a href="http://<?=SITE_URL;?>/<?php echo htmlentities($url['short']); ?>/more"><?php echo url_trim("http://".SITE_URL."/".htmlentities($url['short'])); ?></a></td>
			<td><?php echo htmlentities(dateify($url['stamp'])); ?></td>
			<?php
			$uname = $u->username($url['user']);
			if ($url['user'] > 0) {
				$link = $u->link($url['user']);
				echo "<td><a href=\"{$link}\">".$uname."</a></td>";
			} else {
				echo "<td>{$uname}</td>";
			}
			?>
			<td><?php echo url_uses($url['id']); ?></td>
		</tr>
		<?php
	}
	?>
</table>
<hr>
<h3><?=_tr("misc/list", "rand20")?> <br/><small><?=_tr("misc/list", "rand20_desc")?> </small></h3>
<table class="table">
	<thead>
		<tr>
			<th><?=_tr("misc/list", "table_url")?></th>
			<th><?=_tr("misc/list", "table_short")?></th>
			<th><?=_tr("misc/list", "table_date")?></th>
			<th><?=_tr("misc/list", "table_shortened")?></th>
			<th><?=_tr("misc/list", "table_users")?></th>
		</tr>
	</thead>
	<?php
	if (cache::exists("trending_random")) {
		$data = cache::read("trending_random");
	} else {
		$data = $pagination->get("entries",array("suspended"=>false,"custom"=>false,"private"=>false,"type"=>"0"),$page_num['rand']-1,$perpage);
		cache::store("trending_random",$data,15);
	}
	foreach ($data as $url) {
		?>
		<tr>
			<td style="width: 100px;">
			<?php if ($url['device'] === "1") { ?> <img alt="Mobile" src="//static.<?php echo SITE_URL; ?>/smallurl/img/icons/mobile-icon.png" width="25" title="Shortened on a mobile device."/><?php } ?>
			<?php echo htmlentities(url_trim($url['content']));?></td>
			<td><a href="http://<?=SITE_URL?>/<?php echo htmlentities($url['short']); ?>/more"><?php echo url_trim("http://surl.im/".htmlentities($url['short'])); ?></a></td>
			<td><?php echo htmlentities(dateify($url['stamp'])); ?></td>
			<?php
			$uname = $u->username($url['user']);
			if ($url['user'] > 0) {
				$link = $u->link($url['user']);
				echo "<td><a href=\"{$link}\">".$uname."</a></td>";
			} else {
				echo "<td>{$uname}</td>";
			}
			?>
			<td><?php echo url_uses($url['id']); ?></td>
		</tr>
		<?php
	}
	?>
	<!--<tr>
		<td style="text-align:right;" colspan="5">
			<?php
				$pcount = $pagination->amount("entries",array("suspended"=>false,"custom"=>false,"private"=>false,"type"=>"0"),$perpage);
				echo $pagination->html($pcount,$page_num['rand'],"list","custom={$page_num['custom']}&rand");
			?>
		</td>
	<tr>-->
</table>
<h3><?=_tr("misc/list", "cust20")?> <br/><small><?=_tr("misc/list", "cust20_desc")?> </small></h3>
<table class="table">
	<thead>
		<tr>
			<th><?=_tr("misc/list", "table_url")?></th>
			<th><?=_tr("misc/list", "table_short")?></th>
			<th><?=_tr("misc/list", "table_date")?></th>
			<th><?=_tr("misc/list", "table_shortened")?></th>
			<th><?=_tr("misc/list", "table_users")?></th>
		</tr>
	</thead>
	<?php
	if (cache::exists("trending_custom")) {
		$data = cache::read("trending_custom");
	} else {
		$data = $pagination->get("entries",array("suspended"=>false,"custom"=>"1","private"=>"0","type"=>"0"),$page_num['custom']-1,$perpage);
		cache::store("trending_custom",$data,15);
	}
	foreach ($data as $url) {
		?>
		<tr>
			<td style="width: 100px;">
			<?php if ($url['device'] === "1") { ?> <img alt="Mobile" src="//static.<?php echo SITE_URL; ?>/smallurl/img/icons/mobile-icon.png" width="25" title="Shortened on a mobile device."/><?php } ?>
			<?php echo htmlentities(url_trim($url['content']));?></td>
			<td><a href="http://<?=SITE_URL?>/<?php echo htmlentities($url['short']); ?>/more"><?php echo url_trim("http://surl.im/".htmlentities($url['short'])); ?></a></td>
			<td><?php echo htmlentities(dateify($url['stamp'])); ?></td>
			<?php
			$uname = $u->username($url['user']);
			if ($url['user'] > 0) {
				$link = $u->link($url['user']);
				echo "<td><a href=\"{$link}\">".$uname."</a></td>";
			} else {
				echo "<td>{$uname}</td>";
			}
			?>
			<td><?php echo url_uses($url['id']); ?></td>
		</tr>
		<?php
	}
	?>
	<!--<tr>
		<td style="text-align:right;" colspan="5">
			<?php
				$pcount = $pagination->amount("entries",array("suspended"=>false,"custom"=>"1","private"=>"0","type"=>"0"),$perpage);
				echo $pagination->html($pcount,$page_num['custom'],"list/?rand={$page_num['rand']}&custom=","#custom");
			?>
		</td>
	</tr>-->
</table>
<h3><?=_tr("misc/list", "mob20")?> <br/><small><?=_tr("misc/list", "mob20_desc")?> </small></h3>
<table class="table">
	<thead>
		<tr>
			<th>URL</th>
			<th>Short URL</th>
			<th>Date</th>
			<th>Shortened By</th>
			<th>Uses</th>
		</tr>
	</thead>
	<?php
	$page_number = $page_num['custom'];
	$custom = array_reverse(db::get_array("entries",array("device"=>"1","private"=>false,"type"=>false)));

	$pcount = count_pages($custom,$perpage);
	$pages = get_pages($custom,$perpage);

	if (cache::exists("trending_mobile")) {
		$data = cache::read("trending_mobile");
	} else {
		$data = $pages[$page_num['custom']-1];
		cache::store("trending_mobile",$data,15);
	}
	foreach ($data as $url) {
		?>
		<tr>
			<td style="width: 100px;">
			<?php if ($url['device'] === "1") { ?> <img alt="Mobile" src="//static.<?php echo SITE_URL; ?>/smallurl/img/icons/mobile-icon.png" width="25" title="Shortened on a mobile device."/><?php } ?>
			<?php echo htmlentities(url_trim($url['content']));?></td>
			<td><a href="http://<?=SITE_URL?>/<?php echo htmlentities($url['short']); ?>/more"><?php echo url_trim("http://surl.im/".htmlentities($url['short'])); ?></a></td>
			<td><?php echo htmlentities(dateify($url['stamp'])); ?></td>
			<?php
			$uname = $u->username($url['user']);
			if ($url['user'] > 0) {
				$link = $u->link($url['user']);
				echo "<td><a href=\"{$link}\">".$uname."</a></td>";
			} else {
				echo "<td>{$uname}</td>";
			}
			?>
			<td><?php echo url_uses($url['id']); ?></td>
		</tr>
		<?php
	}
	?>
	<!--<tr>
		<td style="text-align:right" colspan="5">
			<?php echo gen_pagination($pcount,$page_num['custom'],"list","rand={$page_num['rand']}&custom"); ?>
		</td>
	</tr>-->
</table></tr></div>