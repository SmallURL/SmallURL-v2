<?php if ($_SMALLURL['LOGGEDIN']) { ?>
<?php $html->AccPageTitle("Account URLs", "Your SmallURLS"); ?>
<?php include("asset/nav.php"); ?>
<div class="wrap">
<div class='toppadding'></div>
	<h4>&nbsp;</h4>
	<h4 style='text-align:center;'><strong>Shortened URLs.</strong></h4>
	<p style='text-align:center;'>You have shortened a total of <strong><?php echo htmlentities(url_count_total($_SMALLURL['UID'])); ?></strong> URLs, <strong><?php echo htmlentities(url_count_custom($_SMALLURL['UID'])); ?></strong> of which are customly shortened.</p>
	<br /><hr />
	<script>
	load_page("#myurls","/ajax/all_urls.php",0);
	load_page("#customurls","/ajax/custom_urls.php",0);
	</script>
	<div id="myurls">
		<center>Loading...</center>
	</div>
	<hr />
	<h4 style='text-align:center;'> Custom URLs </h5>
	<p style='text-align:center'> These are the URLs that you set a custom URL for. </p>
	
	<div id="customurls">
		<center>Loading...</center>
	</div>
</div>

<?php } else {
	// Not logged in.
	$continue = "//account.".$_SMALLURL['domain']."/urls";
	header("Location: //account.".$_SMALLURL['domain']."/login");
} ?>