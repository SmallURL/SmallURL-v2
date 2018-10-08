<?php if ($_SMALLURL['LOGGEDIN']) { ?>
<?php $html->AccPageTitle("Closed Tickets", "These aren't going anywhere."); ?>
<?php include("asset/nav.php"); ?>
<div class="wrap">
	<div class='toppadding'></div>
	<h4>&nbsp;</h4>
	<h2>Closed Tickets <button style="float:right" class="btn btn-success" onclick="document.location.href='/support/create';"><span class="glyphicon glyphicon-plus"></span> Create Ticket</button></h2>
	<p>Below are all closed tickets you've made. Click their Subject to view them.</p>
	<hr>
	<?php $smallurl->display_errors(); ?>
	<table class="table">
		<tr>
			<thead>
				<td><b>Subject</b></td>
				<td width="200px"><b>Date</b></td>
				<td width="100px"><b>Status</b></td>
			</thead>
		</tr>
		<?php
		$tickets = db::get_array("support_thread",array("owner"=>$_SMALLURL['UID'],"status"=>false));
		if (count($tickets) <= 0) {
			echo "<tr><td colspan=\"3\"><center><i>You havent got any closed Support Tickets! Yay?</i></center></td></tr>";
		} else {
			foreach ($tickets as $tick) {
				echo "<tr>";
				if ($tick['unread']) {
					echo "<td><a href=\"/support/view/{$tick['short']}\"><strong>".htmlentities($tick['subject'])."</strong></a></td>";
				} else {
					echo "<td><a href=\"/support/view/{$tick['short']}\">".htmlentities($tick['subject'])."</a></td>";
				}
				echo "<td>".dateify($tick['stamp'])."</td>";
				// We get the replies and the last one.
				$replies = db::get_array("support_reply",array("thread"=>$tick['id']));
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
<?php } else { include('../core/pages/misc/404.php'); } ?>