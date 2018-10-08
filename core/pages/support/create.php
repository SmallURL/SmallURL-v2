<?php if ($_SMALLURL['LOGGEDIN']) { ?>
<?php $html->AccPageTitle("Create Ticket", "Help is just around the corner."); ?>
<?php include("asset/nav.php"); ?>
<div class="wrap">
	<div class='toppadding'></div>
	<h4>&nbsp;</h4>
	<h3>Open Support Ticket</h3>
	<hr>
	<?php $smallurl->display_errors(); ?>
	<form action="/do/support/submit_ticket.php" method="post">
		<fieldset>
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
<?php } else { include('../core/pages/misc/404.php'); } ?>