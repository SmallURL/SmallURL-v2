<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if (is_admin(UID)) {
?>
		<table class="table">
			<thead>
				<tr>
					<td>SmallURL</td>
					<td>Clicks</td>
					<td>Creation Date</td>
					<td>Creator</td>
					<td></td>
				</tr>
			</thead>
			<?php
			$urls = db::array_query("SELECT * FROM {$sql['prefix']}_entries WHERE flagged='1' AND suspended='0';");
			if (count($urls) > 0) {
				foreach ($urls as $flagged) {
					echo "<tr>\n";
					echo "<td><a href='http://smallurl.in/{$flagged['short']}'>http://smallurl.in/{$flagged['short']}</a></td>\n";
					echo "<td>".url_uses($flagged['id'])."</td>\n";
					echo "<td>".dateify($flagged['stamp'])."</td>\n";
					echo "<td>".get_user($flagged['user'])."</td>\n";
					echo "<td><button onclick=\"if(window.confirm('Delete this URL?')) { url_delete('{$flagged['id']}',load_flagged()); }\" class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-trash'></span> Delete</button> <button onclick=\"unflag_url('{$flagged['id']}');\" class='btn btn-primary btn-xs'><span class='glyphicon glyphicon-ok'></span> Unflag</button></td>";
					echo "</tr>\n";
				}
			}
			else {
				echo "<tr><td colspan='5'><center><small><i>There are no flagged URLs to display</i></small></center></td></tr>";
			}
			?>
		</table>
<?php } ?>