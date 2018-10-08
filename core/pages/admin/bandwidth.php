<?php
if ($u->is_admin()) {
?>
<script src="js/smallurl_admin.js"></script>
<div class="row-fluid">
	<?php include("admin_navigation.php"); ?>
	<div class="span10" style='text-align: center;'>
		<div class="page-header">
			<h1>SmallURL Bandwidth</h1>
		</div>
		<ul class="nav nav-tabs">
	<li class='active'><a href="/?p=admin_status_main&d=1">SmallURL Core</a></li>
	<li class='disabled'><a href="/?p=admin_status_main&d=2">SmallURL Screenshot</a></li>
</ul>
<style>
 .ic{
 	margin-top: 10px;
 }
 </style>
<img class='ic'src='http://smallurl.in:8443/vnstat/vnstat.cgi?0-s'>
<img class='ic'src='http://smallurl.in:8443/vnstat/vnstat.cgi?0-h'>
<img class='ic'src='http://smallurl.in:8443/vnstat/vnstat.cgi?0-d'>
<img class='ic'src='http://smallurl.in:8443/vnstat/vnstat.cgi?0-m'>
<img class='ic'src='http://smallurl.in:8443/vnstat/vnstat.cgi?0-t'>
</div>
<?php
}
else {
	include("pages/404.php");
}
?>