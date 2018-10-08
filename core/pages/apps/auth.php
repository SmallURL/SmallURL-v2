<?php if ($_SMALLURL['LOGGEDIN']) { ?>
<?php $html->AccPageTitle("Applications Home", "Got a service?"); ?>
<?php include("asset/nav.php"); ?>
<div class="wrap">
	<div class='toppadding'></div>
	<h2>Authorised Applications</h2>
	<p>Here's a list of applications you've given permission to access your account.</p>
	<hr>
	<?php $smallurl->display_errors(); ?>
	<style>
	.apps td {
		vertical-align:middle !important;
	}
	.clickable:hover {
		cursor:pointer;
		background-color:rgb(230,230,230);
	}
	</style>
	<table class="table apps">
		<tr>
			<thead>
				<td></td>
				<td><b>Title</b></td>
				<td><b>Desc</b></td>
				<td width="200px"><b>Auth Date</b></td>
				<td width="32px"></td>
			</thead>
		</tr>
		<?php
		$auths = db::get_array("auth",array("user"=>$_SMALLURL['UID']));
		if (count($auths) <= 0) {
			echo "<tr><td colspan=\"3\"><center><i>You havent given any applications permissions, nothing to worry about :)</i></center></td></tr>";
		} else {
			foreach ($auths as $auth) {
				$application = db::get_array("apps",array("id"=>$auth['app']));
				$application = $application[0];
				$desc = $application['desc'];
				if (strlen($desc) > 50) {
					$desc = substr($desc,0,50)."...";
				}
				if (file_exists($_SERVER['DOCUMENT_ROOT']."/../cache/apps/".md5($application['id']).".png")) {
					$img = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../cache/apps/".md5($application['id']).".png"));
				} else {
					$img = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../avatar/unknown.png"));
				}
				echo "<tr class='clickable' onclick='document.location.href = \"/apps/view/".md5($application['id'])."\";'>";
				echo "	<td><img width='32px' src='data:image/png;base64,{$img}'/></td>";
				echo "	<td>{$application['title']}</td>";
				echo "	<td>{$desc}</td>";
				echo "	<td>".dateify($auth['stamp'])."</td>";
				echo "	<td><center><a onclick=\"if(!window.confirm('Revoke Authorisation? Are you sure?')) return false;\" href=\"/do/apps/revoke_auth.php?appid=".md5($application['id'])."\"><i class='glyphicon glyphicon-remove'/></a></center></td>";
				echo "</tr>";
			}
		}
		?>
	</table>
	<hr>
</div>
<?php } else {
	// Make them login
	header('Location: /');
} ?>