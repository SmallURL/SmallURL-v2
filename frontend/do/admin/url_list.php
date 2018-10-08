<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if (is_admin(UID)) {
$search = get_param("q");
$urls = db::array_query("SELECT * FROM {$sql['prefix']}_entries WHERE content LIKE '%{$search}%' AND type='0' AND suspended='0';");
if ($search != "") {
	echo "<h4>Search results for \"{$search}\":</h4><br>The search returned ".count($urls)." results.";
}
?>
		<table class="table">
			<thead>
				<tr>
					<td>SmallURL</td>
					<td style='width:100px;'>Destination</td>
					<td>Clicks</td>
					<td>Creation Date</td>
					<td>Creator</td>
					<td></td>
				</tr>
			</thead>
			<?php
			if (count($urls) > 0) {
				foreach ($urls as $u) {
					echo "<tr>\n";
					echo "<td><a href='http://smallurl.in/{$u['short']}'>http://smallurl.in/{$u['short']}</a></td>\n";
					echo "<td style='width:100px;'>{$u['content']}</td>\n";
					echo "<td>".url_uses($u['id'])."</td>\n";
					echo "<td>".dateify($u['stamp'])."</td>\n";
					echo "<td>".get_user($u['user'])."</td>\n";
					echo "<td><button onclick=\"if(window.confirm('Delete this URL?')) { url_delete('{$u['id']}',load_flagged()); }\" class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-trash'></span> Delete</button></td>";
					echo "</tr>\n";
				}
			}
			else {
				echo "<tr><td colspan='5'><center><small><i>There are no users to display</i></small></center></td></tr>";
			}
			?>
		</table>
<?php } ?>