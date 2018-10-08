<?php include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");

$perpage = 15;

$page = 0;
if (isset($_GET['page']) && $_GET['page'] != "" && is_numeric($_GET['page'])) {
	$page = (int)$_GET['page'];
}
$rcount = db::get_array("entries",array("custom"=>false,"user"=>$_SMALLURL['UID']),"COUNT(*)");
$rcount = (int)$rcount[0]['COUNT(*)'];

$pcount = (int)floor($rcount/$perpage);
?>
<div class="pull-right">Page <?=($page+1)?> of <?=($pcount+1)?></div>
<table class="table">
	<thead>
		<tr>
			<th>URL</th>
			<th>Short URL</th>
			<th>Date</th>
			<th>Private</th>
			<th>Uses</th>
		</tr>
	</thead>
	<?php
	$randoms = $pagination->get("entries",array("custom"=>false,"user"=>$_SMALLURL['UID']),$page,$perpage);
	if (count($randoms) > 0) {
		foreach ($randoms as $url) {
			?>
			<tr>
				<td width="100px"><?php
				$long = $url['content'];
				$max = 70;
				if (strlen($long) > $max) {
					echo substr($long,0,$max)."...";
				}
				else {
					echo $long;
				}
				?></td>
				<td><a href="http://smallurl.in/<?php echo htmlentities($url['short']); ?>/safe">http://smallurl.in/<?php echo htmlentities($url['short']); ?></a></td>
				<td><?php echo htmlentities(dateify($url['stamp'])); ?></td>
				<td><?php if ($url['private']) { echo "<b>Private</b>"; } else { echo "Public"; } ?></td>
				<td><?php echo url_uses($url['id']); ?></td>
			</tr>
			<?php
		}
	}
	else {
		echo "<tr><td colspan=\"5\"><center>There aren't any SmallURLs to display!</center></td></tr>";
	}
	?>
</table>

<ul class="pagination">
	<?php if ($page != 0) { ?>
		<li><a href="javascript:load_page('#myurls','<?=$_SERVER['SCRIPT_NAME']?>',0);">&laquo;</a></li>
	<?php } else { ?>
		<li class="disabled"><a>&laquo;</a></li>
	<?php } ?>
	
<?php
if ($page > 0) {
	$prevpage = $page-1;
	$prevlink = "javascript:load_page('#myurls','{$_SERVER['SCRIPT_NAME']}',{$prevpage});";
	?>
	<li><a href="<?=$prevlink;?>">&lt;</a></li>
<?php } else { ?>
	<li class="disabled"><a href="<?=$prevlink;?>">&lt;</a></li>
<?php }?>
<?php
for ($i=$page-3; $i!=$page; $i++) {
	if ($i > 0) {
		$ln = "javascript:load_page('#myurls','{$_SERVER['SCRIPT_NAME']}',{$i});";
		echo '<li><a href="'.$ln.'">'.($i+1).'</a></li>';
	}
}
?>
<li class="active"><a><?=$page+1?></a></li>
<?php
for ($i=$page+1; $i!=$page+4; $i++) {
	if ($i < $pcount) {
		$ln = "javascript:load_page('#myurls','{$_SERVER['SCRIPT_NAME']}',{$i});";
		echo '<li><a href="'.$ln.'">'.($i+1).'</a></li>';
	}
}
?>
<?php
if ($page < $pcount) {
	$nextpage = $page+1;
	$nextlink = "javascript:load_page('#myurls','{$_SERVER['SCRIPT_NAME']}',{$nextpage});";
	?>
	<li><a href="<?=$nextlink?>">&gt;</a></li>
<?php } else {?>
	<li class="disabled"><a>&gt;</a></li>
<?php } ?>

	<?php if ($page != $pcount) { ?>
		<li><a href="javascript:load_page('#myurls','<?=$_SERVER['SCRIPT_NAME']?>',<?=$pcount?>);">&raquo;</a></li>
	<?php } else { ?>
		<li class="disabled"><a>&raquo;</a></li>
	<?php } ?>

</ul>

