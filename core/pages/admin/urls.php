<?php
if ($u->is_admin()) {
?>
<script src="js/smallurl_admin.js"></script>
<div class="row-fluid">
	<?php include("admin_navigation.php"); ?>
	<div class="span10">
		<div class="page-header">
			<h1>SmallURL URLS</h1>
		</div>
		<div class="input-group">
		  <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
		  <input type="text" onkeyup="load_urls($(this).val());" class="form-control" placeholder="URL Destination">
		</div>
		<br/>
		<div id="smallurl_urls">
		<h3>Loading list.</h3>
		<script>window.onload = function () { load_urls(''); }</script>
		</div>
</div>
<?php
}
else {
	include("pages/404.php");
}
?>