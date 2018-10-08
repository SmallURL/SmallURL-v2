<?php if ($u->is_admin()) { ?>
<?php $html->PageTitle("Open Tickets", "Current User Tickets."); ?>
<?php include("asset/support_nav.php"); ?>
<div class="wrap">
	<div class='toppadding'></div>
	<h4>&nbsp;</h4>
	<h2>Open Tickets <button style="float:right" class="btn btn-success" onclick="document.location.href='/admin/support/create';"><span class="glyphicon glyphicon-plus"></span> Create Ticket</button></h2>
	<p>Below are all open tickets by users.</p>
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
		$tickets = db::get_array("support_thread",array("status"=>true));
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
				echo "<td>".get_user($tick['owner'])."</td>";
				// We get the replies and the last one.
				$replies = db::get_array("support_reply",array("thread"=>$tick['id']));
				$replies = array_reverse($replies);
				if (count($replies) > 0) {
					if ($replies[0]['user'] == $tick['owner']) {
						$stat = "<strong>User Reply</strong>";
					} else {
						$stat = "Staff Reply";
					}
				} else {
					if ($tick['user'] == $tick['owner']) {
						$stat = "<strong>User Ticket</strong>";
					} else {
						$stat = "Staff Ticket";
					}
				}
				echo "<td>{$stat}</td>";
				echo "</tr>";
			}
		}
		?>
	</table>
	<hr>
</div>
<?php } else { include('404.php'); } ?>