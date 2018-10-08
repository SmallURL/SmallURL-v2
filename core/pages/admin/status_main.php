<?php
if ($u->is_admin()) {
?>
<script src="js/smallurl_admin.js"></script>
<div class="row-fluid">
	<?php include("admin_navigation.php"); ?>
	<div class="span10" style='text-align: center;'>



<div class="page-header">
	<h1>SmallURL Core Server Statistics</h1>
</div>
<?php if(isset($_GET['d']) && $_GET['d'] == '1') {
	$ts = 'day';
} else if (isset($_GET['d']) && $_GET['d'] == '2') {
	$ts = 'week';
} else if (isset($_GET['d']) && $_GET['d'] == '3') {
	$ts = 'month';
} else if (isset($_GET['d']) && $_GET['d'] == '4') {
	$ts = 'year';
} else {
	$ts = 'day';
}
?>

<ul class="nav nav-tabs">
	<li <?php if($ts == 'day') { echo "class='active'"; } ?>><a href="/?p=admin_status_main&d=1">Last 24 Hours</a></li>
	<li <?php if($ts == 'week') { echo "class='active'";}?>><a href="/?p=admin_status_main&d=2">Last 7 Days</a></li>
	<li <?php if($ts == 'month') { echo "class='active'";}?>><a href="/?p=admin_status_main&d=3">Last 30 Days</a></li>
	<li <?php if($ts == 'year') { echo "class='active'";}?>><a href="/?p=admin_status_main&d=4">Last Year</a></li>
</ul>
<div class="list-group" style='width: 150px; margin-top: 20px; position:fixed'>
<a class="list-group-item active">
Graphs:
</a>
<a href="#disk" class="list-group-item">Disk Usage</a>
<a href="#ram" class="list-group-item">Ram Usage</a>
<a href="#cpu" class="list-group-item">CPU Usage</a>
<a href="#net" class="list-group-item">Network Usage</a>
<a href="#fw" class="list-group-item">Firewall Exceptions</a>
<a href="#varnish" class="list-group-item">Varnish Stats</a>
<a href="#mysql" class="list-group-item">MySQL Stats</a>
</div>
<h4 id='disk'> Disk Usage: </h4>
<img src='http://smallurl.in:8443/graphs/SmallCore/SmallCore/df-<?php echo $ts; ?>.png'>
<h4 id='ram'> Ram Usage: </h4>
<img src='http://smallurl.in:8443/graphs/SmallCore/SmallCore/memory-<?php echo $ts; ?>.png'>
<h4 id='cpu'> CPU Usage: </h4>
<img src='http://smallurl.in:8443/graphs/SmallCore/SmallCore/cpu-<?php echo $ts; ?>.png'>
<h4 id='net'> Network Usage: </h4>
<img src='http://smallurl.in:8443/graphs/SmallCore/SmallCore/if_venet0-<?php echo $ts; ?>.png'>
<h4 id='fw'> Firewall Connections: </h4>
<img src='http://smallurl.in:8443/graphs/SmallCore/SmallCore/fw_conntrack-<?php echo $ts; ?>.png'>
<img src='http://smallurl.in:8443/graphs/SmallCore/SmallCore/fw_forwarded_local-<?php echo $ts; ?>.png'>
<h4 id='varnish'> Varnish Statistics: </h4>
<img src='http://smallurl.in:8443/graphs/SmallCore/SmallCore/varnish_backend_traffic-<?php echo $ts; ?>.png'>
<img src='http://smallurl.in:8443/graphs/SmallCore/SmallCore/varnish_hitrate-<?php echo $ts; ?>.png'>
<img src='http://smallurl.in:8443/graphs/SmallCore/SmallCore/varnish_request_rate-<?php echo $ts; ?>.png'>
<img src='http://smallurl.in:8443/graphs/SmallCore/SmallCore/varnish_transfer_rates-<?php echo $ts; ?>.png'>
<img src='http://smallurl.in:8443/graphs/SmallCore/SmallCore/varnish_allocated-<?php echo $ts; ?>.png'>
<h4 id='mysql'> MySQL Statistics: </h4>
<img src='http://smallurl.in:8443/graphs/SmallCore/SmallCore/mysql_bytes-<?php echo $ts; ?>.png'>
<img src='http://smallurl.in:8443/graphs/SmallCore/SmallCore/mysql_queries-<?php echo $ts; ?>.png'>
<img src='http://smallurl.in:8443/graphs/SmallCore/SmallCore/mysql_threads-<?php echo $ts; ?>.png'>
</div>



</div>
<?php
} else {
	include("pages/404.php");
}
?>