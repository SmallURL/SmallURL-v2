<?php $html->PageTitle("All Users", "The beautiful bunch"); ?>
<div class="wrap">
	<div class='toppadding'></div>
	<h3>SmallURL Users</h3>
	<?php
	$users = db::get_array("users",array("verified"=>true));
	?>
	<table class="table">
		<thead>
			<tr>
				<td width="27px"></td>
				<td><b>Username</b></td>
				<td><b>Shortened</b></td>
				<td><b>Registered</b></td>
			</tr>
		</thead>
		<?php
		foreach ($users as $usr) {
		$urls = db::get_array("entries",array("user"=>$usr['id'],"type"=>"0"),"id");
		if ($usr['name'] != "" && $usr['name'] !== $usr['username']) {
			$name = "{$usr['name']}";
			if ($usr['username'] != null && $usr['username'] != "") {
				$name .= " [{$usr['username']}]";
			}
		} else {
			$name = $usr['username'];
		}
		$online = "";
		if ($u->level() >= 10) {
			// Beta only.
			if ($u->online($usr['id'])) {
				$online = " <span class='label label-success'>Online</span>";
			}
		}
		echo "<tr>";
		if ($usr['username'] != null && $usr['username'] != "") {
			echo "	<td><center><a href='/user/{$usr['username']}'><img width='25px' height='25px' src='".$u->avatar($usr['id'])."'/></a></center></td>";
			echo "	<td><a href='/user/{$usr['username']}'>{$name}</a>".$online."</td>";
		} else {
			echo "	<td><center><img width='25px' height='25px' src='".$u->avatar($usr['id'])."'/></center></td>";
			echo "	<td>{$name}".$online."</td>";
		}
		echo "	<td>".count($urls)."</td>";
		echo "	<td>".dateify($usr['regstamp'])."</td>";
		echo "</tr>";
		}
		?>
	</table>
	</center>
</div>