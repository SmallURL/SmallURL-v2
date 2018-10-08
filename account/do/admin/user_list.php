<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if (is_admin(UID)) {
$search = get_param("q");
if ($search != "") {
	echo "<h4>Search results for \"{$search}\":</h4>";
}
?>
		<table class="table">
			<thead>
				<tr>
					<td><b>Name</b></td>
					<td><b>Email</b></td>
					<td><b>Status</b></td>
					<td><b>SmallURLs</b></td>
					<td><b>Verified</b></td>
					<td></td>
				</tr>
			</thead>
			<?php
			$users = $db->array_query("SELECT * FROM {$sql['prefix']}_users WHERE name LIKE '%{$search}%';;");
			if (count($users) > 0) {
				foreach ($users as $usr) {
					$urlcount = count($db->array_query("SELECT * FROM {$sql['prefix']}_entries WHERE user='{$usr['id']}';"));
					if ($usr['admin']) {
						$status = "Administrator";
					}
					else {
						$status = "General User";
					}
					if ($usr['verified']) {
						$verified = "Yes";
					}
					else {
						$verified = "No";
					}
					echo "<tr>\n";
					echo "<td>{$usr['name']}</td>\n";
					echo "<td>{$usr['email']}</td>\n";
					echo "<td>{$status}</td>\n";
					echo "<td>{$urlcount}</td>\n";
					echo "<td>{$verified}</td>\n";
					echo "<td><button onclick=\"if(window.confirm('Delete this user?')) { user_delete('{$usr['id']}',load_users()); }\" class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-trash'></span> Delete</button>";
					if ($usr['verified'] === 0) { echo "<button onclick=\"unflag_url('{$usr['id']}');\" class='btn btn-primary btn-xs'><span class='glyphicon glyphicon-ok'></span> Verify</button></td>"; }
					echo "</tr>\n";
				}
			}
			else {
				echo "<tr><td colspan='5'><center><small><i>There are no URLS to display</i></small></center></td></tr>";
			}
			?>
		</table>
<?php } ?>