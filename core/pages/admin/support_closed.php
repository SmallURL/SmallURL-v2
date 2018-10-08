<?php if ($_SMALLURL['LOGGEDIN'] && $u->is_admin()) { ?>
<?php $html->PageTitle("Closed Tickets", "All paste tickets on the system."); ?>
<?php include("asset/support_nav.php"); ?>
<div class="wrap">
	<div class='toppadding'></div>
	<h4>&nbsp;</h4>
	<h2>Closed Tickets</h2>
	<p>Below are all past tickets by users.</p>
	<hr>
	<?php $smallurl->display_errors(); ?>
	<table class="table">
		<tr>
			<thead>
				<td><b>Subject</b></td>
				<td width="200px"><b>Date</b></td>
				<td width="200px"><b>User</b></td>
				<td width="100px"><b>Status</b></td>
			</thead>
		</tr>
		<?php
		$tickets = db::get_array("support_thread",array("status"=>false));
		if (count($tickets) <= 0) {
			echo "<tr><td colspan=\"3\"><center><i>You havent got any closed Support Tickets! Yay?</i></center></td></tr>";
		} else {
			foreach ($tickets as $tick) {
				echo "<tr>";
				if ($tick['unread']) {
					echo "<td><a href=\"/admin/support/view/{$tick['short']}\"><strong>".htmlentities($tick['subject'])."</strong></a></td>";
				} else {
					echo "<td><a href=\"/admin/support/view/{$tick['short']}\">".htmlentities($tick['subject'])."</a></td>";
				}
				echo "<td>".dateify($tick['stamp'])."</td>";
				echo "<td>".get_user($tick['user'])."</td>";
				// We get the replies and the last one.
				$replies = db::get_array("support_reply",array("thread"=>$tick['id']));
				$replies = array_reverse($replies);
				if (count($replies) > 0) {
					$ureply = $replies[0]['user'];
					if ($ureply == $_SMALLURL['UID']) {
						$stat = "User Reply";
					} else {
						$stat = "<strong>Staff Reply</strong>";
					}
				} else {
					$stat = "User Reply";
				}
				echo "<td>{$stat}</td>";
				echo "</tr>";
			}
		}
		?>
	</table>
	<hr>
</div>
<?php } else { include('pages/404.php'); } ?>