<?php if ($_SMALLURL['LOGGEDIN']) { ?>
<?php $html->AccPageTitle("Account Home", "Welcome Home."); ?>
<div class="wrap">
	<div class='toppadding'></div>
	<h4>&nbsp;</h4>
	<?php
	$info = db::get_array("users",array("id"=>$_SMALLURL['UID']));
	if (count($info) == 1) {
		if ($info[0]['passreset'] === "1") {
			show_alert('You have requested a password reset on your account and it has not been sorted!\nCheck your Email!');
		}
	}
	else {
		show_alert('INTERNAL ERROR LN:'.__LINE__);
	}
	?>
	<h4  style='text-align:center;'><strong>Hey <?php echo htmlentities(gettok(" ",get_user($_SMALLURL['UID']),0)); ?>, welcome to your account panel!</strong></h4>
	<p style='text-align:center;'>From here you can see your statistics, edit your details and manage your API keys!</p>
	<hr>
	<div class="row" style='width: 1000px;'>
		<center>
		<div style='width: 333px; float:left;'>
				<h2 class="stats_total"><?php echo htmlentities(url_count_total($_SMALLURL['UID'])); ?></h2>
				<p>URL's have been shortened!</p>
			</div>
			<div style='width: 333px; float:left;'>
				<h2 class="stats_custom"><?php echo htmlentities(url_count_custom($_SMALLURL['UID'])); ?></h2>
				<p>URL's have been custom shortened!</p>
			</div>
			<div style='width: 333px; float:left;'>
				<h2 class="stats_random"><?php echo htmlentities(url_count_rand($_SMALLURL['UID'])); ?></h2>
				<p>URL's have been randomly shortened!</p>
			</div>
		</center>
	</div>
	<h4>&nbsp;</h4>
</div>

<?php } else {
	// Not logged in.
	$continue = "//account.".$_SMALLURL['domain']."/";
	header("Location: //account.".$_SMALLURL['domain']."/login");
} ?>