<?php if ($_SMALLURL['LOGGEDIN']) { ?>
<?php $html->AccPageTitle("Account API", "Account API"); ?>
<?php include("asset/nav.php"); ?>
<div class="wrap">
	<div class='toppadding'></div>
	<div class="row-fluid">
		<h4>&nbsp;</h4>
		<div class='span10'>
		<div class="page-header">
			<h4 style="text-align:center;"><strong>My API Keys</strong></h4>
		</div>
		<h4 style='text-align:center;'>Hey <?php echo gettok(" ",get_user($_SMALLURL['UID']),0); ?>, these are your API Keys.</h4>
		<p style='text-align:center;'>You can add and remove API Keys from here, You are allowed a maxmium of 5 API Keys.</p>
		<hr>
		<div id="key_form">
			<h3 style='text-align:center;'>Loading your API Keys...</h3>
			<script>window.onload = function () { key_form(); }</script>
		</div>
		<hr>
		</div>
	</div>
</div>
<?php } else {
	// Not logged in.
	$continue = "//account.".$_SMALLURL['domain']."/api";
	header("Location: //account.".$_SMALLURL['domain']."/login");

} ?>