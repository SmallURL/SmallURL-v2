<?php if ($_SMALLURL['LOGGEDIN'] && is_admin($_SMALLURL['UID'])) { ?>
<?php $html->PageTitle("Create Ticket", "Help is just around the corner."); ?>
<?php include("asset/support_nav.php"); ?>
<style>
.admin {
	font-weight:bold;
}
</style>
<div class="wrap">
	<div class='toppadding'></div>
	<h4>&nbsp;</h4>
	<h3>Open Support Ticket</h3>
	<hr>
	<?php $smallurl->display_errors(); ?>
	<form action="/do/admin/submit_ticket.php" method="post">
		<fieldset>
			<p><b>Target User</b></p>
			<p><select name="uid"><?php
			$users = db::get_array("users");
			foreach ($users as $u) {
				if ($u['id'] != $_SMALLURL['UID']) {
					$append = "";
					if ($u['admin'] == "1") {
						$append = "[Admin] ";
					}
					echo "<option class=\"admin\" value=\"{$u['id']}\">{$append}{$u['name']} ({$u['email']})</option>";
				}
			}
			?><option value="0">All Users</option></select></p>
			<p><b>Subject</b></p>
			<p><input name="subject" placeholder="Give the Ticket a good subject" type="text" class="form-control"></p>
			<p><b>Message</b></p>
			<p><textarea name="message" rows="12" class="form-control" style="width:100%"></textarea></p>
		</fieldset>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary">Submit Ticket</button>
			<button type="button" onclick="document.location.href='/support';" class="btn btn-default">Cancel</button>
		</div>
	</form>
	<hr>
</div>
<?php } else { include('pages/404.php'); } ?>