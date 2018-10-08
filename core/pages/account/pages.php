<?php if ($_SMALLURL['LOGGEDIN']) { ?>
<?php $html->AccPageTitle("Account Domains", "Account Domains"); ?>
<?php include("asset/nav.php"); ?>
<div class="wrap">
<div class='toppadding'></div>
	<h4>&nbsp;</h4>
	<?php /*
	<h4 style='text-align:center;'><strong>My Domains</strong></h4>
	<p style='text-align:center;'>Hey <?php echo gettok(" ",get_user($_SMALLURL['UID']),0); ?>, these are your domains!</p>
	<hr />
		<div style='text-align:center'>
		<p>You can add and remove domains from here, You are allowed a maxmium of 3 domains.</p>
		<p>Pointing a domain to SmallURL and adding it here allows you to use your own domain as a SmallURL!</p>
		<p>To use this feature point a domain at <b>domains.smallurl.in</b> and wait for at least 6hrs for DNS to take effect!</p>

		<hr>
			<div id="domain_form">
				<h3>Loading your domains...</h3>
				<script>window.onload = function () { domain_form(); }</script>
			</div>
		</div>
	<?php */?>
	<h2 style='text-align:center'> This feature is currently not implemented right now! :-( </h2>
	<br />
	<br />
</div>
<?php } else {
	// Not logged in.
	$continue = "//account.".$_SMALLURL['domain']."/pages";
	header("Location: //account.".$_SMALLURL['domain']."/login");
} ?>