<?php if ($_SMALLURL['LOGGEDIN']) { ?>
<?php $html->AccPageTitle("Account Notifications", "What you've been missing all this time."); ?>
<?php include("asset/nav.php"); ?>
<div class="wrap">
<div class='toppadding'></div>
	<h4>&nbsp;</h4>
	<?php
	$notifs = array_reverse(db::get_array("notifications",array("user"=>$_SMALLURL['UID'],"read"=>false)));
	echo "<h3> Notifications <span class='label label-success pull-right'><span class=\"notif-count\">".count($notifs)."</span> Unread</span></h3><hr/>";
	if (count($notifs) > 0) {
		foreach ($notifs as $n) {

			echo "<div id=\"notification-{$n['id']}\" class='well'>
				<h4>
					{$n['title']}
					<small>".dateify($n['stamp'])."</small>
					<span class='pull-right'>
						<button onclick=\"notif_read('{$n['id']}');\">
							<span class='glyphicon glyphicon-eye-open tip' data-toggle='tooltip' data-placement='bottom' data-original-title='Mark Read'/>
						</button>
						<!--<button onclick=\"notif_delete('{$n['id']}');\">
							<span class='glyphicon glyphicon-remove tip' data-toggle='tooltip' data-placement='bottom' data-original-title='Delete'/>
						</button>-->
					</span>
				</h4>
				{$n['text']}";
			if ($n['url'] != "") {
				echo "<span class='pull-right'>[<a href='{$n['url']}'>Link</a>]</span></div>";
			} else {
				echo "</div>";
			}
		}
		echo "<span style='display:none;' id='none-alert'><center>There are no notifications to display</center></span>";
	} else {
		echo "<span id='none-alert'><center>There are no notifications to display</center></span>";
	}
	?>
	<br />
	<br />
</div>
<?php } else {
	// Not logged in.
	$continue = "//account.".$_SMALLURL['domain']."/notification";
	header("Location: //account.".$_SMALLURL['domain']."/login");
} ?>