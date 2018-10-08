<?php if ($_SMALLURL['LOGGEDIN']) { ?>
<?php $html->AccPageTitle("Applications Home", "Got a service?"); ?>
<?php include("asset/nav.php"); ?>
<div class="wrap">
	<div class='toppadding'></div>
	<h4>&nbsp;</h4>
	<h2>My Applications <button style="float:right" class="btn btn-success" onclick="document.location.href='/apps/create';"><span class="glyphicon glyphicon-plus"></span> Create Application</button></h2>
	<p>Here's all the applications you've made for SmallURL</p>
	<hr>
	<?php $smallurl->display_errors(); ?>
	<style>
	.apps td {
		vertical-align:middle !important;
	}
	</style>
	<table class="table apps">
		<tr>
			<thead>
				<td></td>
				<td><b>Title</b></td>
				<td><b>Desc</b></td>
				<td><b>Users</b></td>
				<td width="200px"><b>Creation</b></td>
			</thead>
		</tr>
		<?php
		$apps = db::get_array("apps",array("user"=>$_SMALLURL['UID']));
		if (count($apps) <= 0) {
			echo "<tr><td colspan=\"3\"><center><i>You've not made any applications, nothing to worry about :)</i></center></td></tr>";
		} else {
			foreach ($apps as $application) {
				$ucount = count(db::get_array("auth",array("app"=>$application['id'])));
				$desc = $application['desc'];
				if (strlen($desc) > 50) {
					$desc = substr($desc,0,50)."...";
				}
				if (file_exists($_SERVER['DOCUMENT_ROOT']."/../cache/apps/".md5($application['id']).".png")) {
					$img = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../cache/apps/".md5($application['id']).".png"));
				} else {
					$img = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../avatar/unknown.png"));
				}
				echo "<tr onclick='document.location.href = \"/apps/view/".md5($application['id']."\";'>";
				echo "	<td><img width='32px' src='data:image/png;base64,{$img}'/></td>";
				echo "	<td>{$application['title']}</td>";
				echo "	<td>{$desc}</td>";
				echo "	<td>{$ucount} Users</td>";
				echo "	<td>".dateify($application['stamp'])."</td>";
				echo "</tr>";
			}
		}
		?>
	</table>
	<hr>
</div>
<?php } else { include('pages/404.php'); } ?>