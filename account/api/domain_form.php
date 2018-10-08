<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if (!isset($_SESSION['uid']) || $_SESSION['uid'] == 0) {
	die('Not logged in.');
}
$uid = $_SESSION['uid'];
?>
<table class="table">
	<thead>
		<tr>
			<th>Page Name</th>
			<th>Domain</th>
		</tr>
	</thead>
	<?php
	$keys = $db->array_query("SELECT * FROM {$sql['prefix']}_pages WHERE user='{$uid}'");
	if (count($keys) > 0) {
		foreach ($keys as $key) {
			?>
			<tr>
				<td><a href='http://<?php echo $key['name']; ?>.smallurl.in' ><?php echo $key['name']; ?>.smallurl.in</a></td>
				<td><?php echo $key['domain']; ?></td>
				<td><button onclick="if (window.confirm('Delete this Domain?')) { del_domain('<?php echo $key['id']; ?>'); } else { return false; }" class="btn btn-default">Delete</button></td>
			</tr>
			<?php
		}
	}
	else {
	?>
	<tr>
		<td colspan="3"><center>You havent added any domains currently.</center></td>
	</tr>
	<?php
	}
	?>
	<tr>
		<td><input type="text" class="form-control" placeholder="Give this domain a page name." id="pagename"/></td>
		<td><input type="text" class="form-control" placeholder="What Domain will be pointed to it?" id="pagedomain"/></td>
		<td><button type="button" onclick="add_domain();" class="btn btn-default">Add</button></td>
	</tr>
</table>