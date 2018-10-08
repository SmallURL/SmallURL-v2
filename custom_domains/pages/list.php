<?php

$domain = $_SERVER['HTTP_HOST'];
$doms = explode(".",$domain);
$lol = array_slice($doms,0,-2);
$page = implode(".",$lol);

$user = $db->array_query("SELECT * FROM {$sql['prefix']}_pages WHERE name='{$page}' OR domain='{$_SERVER['HTTP_HOST']}';");
if (count($user) > 0) {
	$uid = $user[0]['user'];
	$page = $user[0]['name'];
	$user = get_user($uid);
	$fname = htmlentities(gettok(" ",get_user($uid),0)); // First name of the user.
?>
<h1>
	<?php echo $page; ?>
</h1>
<hr>
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
<p>
<h3><?php echo $fname."'"; ?> Top 10 Shortened URLs<br/><small>These SmallURL's are the most used!</small></h3>
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
	$top = $db->array_query("SELECT * FROM {$sql['prefix']}_entries WHERE private='0' AND user='{$uid}' ORDER BY `uses` DESC LIMIT 10;");

	foreach ($top as $url) {
		?>
		<tr>
			<td style="width:100px">
			<?php if ($url['device'] === "1") { ?> <img src="img/mobile-icon.png" width="25px" title="Shortened on a mobile device."/><?php } ?>
			<?php
			$content = $url['content'];
			$max = 80;
			if (strlen($content) > $max) {
				echo substr($content,0,$max)."...";
			}
			else {
				echo $content;
			}
			?></td>
			<td><a href="http://smallurl.in/<?php echo htmlentities($url['short']); ?>/more">http://smallurl.in/<?php echo htmlentities($url['short']); ?></a></td>
			<td><?php echo htmlentities(dateify($url['stamp'])); ?></td>
			<td><?php echo htmlentities(get_user($url['user'])); ?></td>
			<td><?php echo url_uses($url['id']); ?></td>
		</tr>
		<?php
	}
	?>
</table>
<hr>
<h3><?php echo $fname."'"; ?> Randomly Shortened URLs <br/><small>These SmallURL's have been randomly generated!</small></h3>
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
	$randoms = array_reverse($db->array_query("SELECT * FROM {$sql['prefix']}_entries WHERE custom='0' AND private='0' AND user='{$uid}';"));
	if (count($randoms) > 0) {
	
	$pcount = count_pages($randoms,$perpage);
	$pages = get_pages($randoms,$perpage);
	
	$data = $pages[$page_num['rand']-1];
	
		foreach ($data as $url) {
		?>
		<tr>
			<td style="width: 100px">
			<?php if ($url['device'] === "1") { ?> <img src="img/mobile-icon.png" width="25px" title="Shortened on a mobile device."/><?php } ?>
			<?php
			$content = $url['content'];
			$max = 80;
			if (strlen($content) > $max) {
				echo substr($content,0,$max)."...";
			}
			else {
				echo $content;
			}
			?></td>
			<td><a href="http://smallurl.in/<?php echo htmlentities($url['short']); ?>/more">http://smallurl.in/<?php echo htmlentities($url['short']); ?></a></td>
			<td><?php echo htmlentities(dateify($url['stamp'])); ?></td>
			<td><?php echo htmlentities(get_user($url['user'])); ?></td>
			<td><?php echo url_uses($url['id']); ?></td>
		</tr>
		<?php
		}
	?>
		<tr>
		<td style="text-align:right;" colspan="5">
			<?php echo gen_pagination($pcount,$page_num['rand'],"p=list&amp;custom={$page_num['custom']}&amp;rand"); ?>
		</td>
	<tr>
	<?php
	}
	else {
		echo "<tr><td colspan='5'><center><i><small>There isn't a single SmallURL to display!</small></i></center></td></tr>";
	}
	?>
</table>
<h3><?php echo $fname."'"; ?> Custom Shortened URLs <br/><small>These SmallURL's have been customised!</small></h3>
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
	$custom = array_reverse($db->array_query("SELECT * FROM {$sql['prefix']}_entries WHERE custom='1' AND private='0' AND user='{$uid}';"));
	
	if (count($custom) > 0) {
	
	$pcount = count_pages($custom,$perpage);
	$pages = get_pages($custom,$perpage);
	
	$data = $pages[$page_num['custom']-1];
	
	foreach ($data as $url) {
		?>
		<tr>
			<td style="width:100px">
			<?php if ($url['device'] === "1") { ?> <img src="img/mobile-icon.png" width="25px" title="Shortened on a mobile device."/><?php } ?>
			<?php
			$content = $url['content'];
			$max = 80;
			if (strlen($content) > $max) {
				echo substr($content,0,$max)."...";
			}
			else {
				echo $content;
			}
			?></td>
			<td><a href="http://smallurl.in/<?php echo htmlentities($url['short']); ?>/more">http://smallurl.in/<?php echo htmlentities($url['short']); ?></a></td>
			<td><?php echo htmlentities(dateify($url['stamp'])); ?></td>
			<td><?php echo htmlentities(get_user($url['user'])); ?></td>
			<td><?php echo url_uses($url['id']); ?></td>
		</tr>
		<?php
	}
	?>
	<tr>
		<td style="text-align:right;" colspan="5">
			<?php echo gen_pagination($pcount,$page_num['custom'],"p=list&amp;rand={$page_num['rand']}&amp;custom"); ?>
		</td>
	</tr>
		<?php
	}
	else {
		echo "<tr><td colspan='5'><center><i><small>There isn't a single SmallURL to display!</small></i></center></td></tr>";
	}
	?>
</table>
<h3>URL's shortened by <?php echo $fname; ?> on a mobile!<br/><small>These SmallURL's have been shortened via Mobile!</small></h3>
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
	$custom = array_reverse($db->array_query("SELECT * FROM {$sql['prefix']}_entries WHERE device='1' AND private='0' AND user='{$uid}';"));
	
	if (count($custom) > 0) {
	
	$pcount = count_pages($custom,$perpage);
	$pages = get_pages($custom,$perpage);
	
	$data = $pages[$page_num['custom']-1];
	
	foreach ($data as $url) {
		?>
		<tr>
			<td style="width:100px">
			<?php if ($url['device'] === "1") { ?> <img src="img/mobile-icon.png" width="25px" title="Shortened on a mobile device."/><?php } ?>
			<?php
			$content = $url['content'];
			$max = 80;
			if (strlen($content) > $max) {
				echo substr($content,0,$max)."...";
			}
			else {
				echo $content;
			}
			?></td>
			<td><a href="http://smallurl.in/<?php echo htmlentities($url['short']); ?>/more">http://smallurl.in/<?php echo htmlentities($url['short']); ?></a></td>
			<td><?php echo htmlentities(dateify($url['stamp'])); ?></td>
			<td><?php echo htmlentities(get_user($url['user'])); ?></td>
			<td><?php echo url_uses($url['id']); ?></td>
		</tr>
		<?php
	}
	?>
	<tr>
		<td style="text-align:right" colspan="5">
			<?php echo gen_pagination($pcount,$page_num['custom'],"p=list&amp;rand={$page_num['rand']}&amp;custom"); ?>
		</td>
	</tr>
	<?php
	} else {
	echo "<tr><td colspan='5'><center><i><small>There isn't a single SmallURL to display!</small></i></center></td></tr>";
	} ?>
</table>
<?php
}
else {
	header('Location: http://smallurl.in/');
}
?>