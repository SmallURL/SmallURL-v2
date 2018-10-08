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
			<th>Key Name</th>
			<th>Domain</th>
			<th>Key</th>
			<th><button onclick="key_form();" class="btn btn-small btn-success">Refresh</button></th>
		</tr>
	</thead>
	<?php
	$keys = db::get_array("api",array("user"=>$uid));
	if (count($keys) > 0) {
		foreach ($keys as $key) {
			?>
			<tr>
				<td><?php echo $key['name']; ?></td>
				<td><?php echo $key['domain']; ?></td>
				<td><?php echo $key['key']; ?></td>
				<td><button onclick="if (window.confirm('Delete this Key?')) { del_key('<?php echo $key['id']; ?>'); } else { return false; }" class="btn btn-default">Delete</button></td>
			</tr>
			<?php
		}
	}
	else {
	?>
	<tr>
		<td colspan="4"><center>You don't have any API Keys currently.</center></td>
	</tr>
	<?php
	}
	?>
	<tr>
		<td><input type="text" class="form-control" placeholder="Give this key a public name." id="keyname"/></td>
		<td><input type="text" class="form-control" placeholder="What host/ip will use this key?" id="keydomain"/></td>
		<td></td>
		<td><button type="button" onclick="add_key();" class="btn btn-default">Add</button></td>
	</tr>
</table>