<?php
if (is_admin(UID)) {
?>
<script src="js/smallurl_admin.js"></script>
<div class="row-fluid">
	<?php include("admin_navigation.php"); ?>
	<div class="span10">
		<div class="page-header">
			<h1>Flagged URLs</h1>
		</div>
		<p>These are URL's that have exceeded 500 Clicks in 2hours.</p>
		<div id="flagged_urls" onload="alert('Kay');">
		<h3>Loading list.</h3>
		<script>window.onload = function () { load_flagged(); }</script>
		</div>
</div>
<?php
}
else {
	include("pages/404.php");
}
?>