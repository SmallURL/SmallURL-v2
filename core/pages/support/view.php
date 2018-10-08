<?php if ($_SMALLURL['LOGGEDIN']) {
$html->AccPageTitle("View Ticket", "Sorting your problem out quickly.");
include("asset/nav.php");

$html->addJS('/js/extra/support.js');

// Now for the Nitty Gritty!
if (!is_admin($_SMALLURL['UID'])) {
	$ticket = db::get_array("support_thread",array("short"=>$id,"owner"=>$_SMALLURL['UID']));
} else {
	$ticket = db::get_array("support_thread",array("short"=>$id));
}
if (count($ticket) == 1) { ?>
<?php
// Set the ticket as read.
if ($ticket[0]['unread']) {
	db::update("support_thread",array("unread"=>false),array("id"=>$ticket[0]['id']));
}
?>
<style>
.well {
	border-radius:0px;
}
</style>
<div class="wrap">
	<div class='toppadding'></div>
	<h4>&nbsp;</h4>
	<h3>Ticket #<?php echo $ticket[0]['short']; ?> - <?php echo htmlentities($ticket[0]['subject']); ?>
		<span data-toggle="dropdown" class="pull-right btn btn-<?php if ($ticket[0]['status']) { echo "success"; } else { echo "danger"; } ?>" <?php if ($ticket[0]['status']) { echo "onclick=\"close_ticket('{$id}');\" data-toggle=\"modal\" data-target=\"#close_ticket_modal\""; } ?>><strong><?php if ($ticket[0]['status']) { echo "OPEN"; } else { echo "CLOSED"; } ?></strong></span>
	</h3>
	<hr>
	<?php $smallurl->display_errors(); ?>
	<div class="well" <?php if ($ticket[0]['owner'] != $ticket[0]['user']) { echo 'style="background:#EDFFFF;border:1px solid silver;"'; } ?>>
		<strong><?php echo $support->badge($ticket[0]['user']).chr(32).$support->name($ticket[0]['user']); ?></strong>
		<span style="float:right"><?php echo dateify($ticket[0]['stamp']); ?></span>
		<hr>
		<?php
		// Thread Message
		echo $support->clean_body($ticket[0]['message']);
		$sig = $support->get_sig($ticket[0]['user']);
		if ($sig) { echo $sig; }
		?>
	</div>
	<?php
		// Replies go here soon.
		$replies = db::get_array("support_reply",array("thread"=>$ticket[0]['id']));
		if (count($replies) > 0) {
			foreach ($replies as $reply) {
			?>
	<div class="well" <?php if ($ticket[0]['owner'] != $reply['user']) { echo 'style="background:#EDFFFF;border:1px solid silver;"'; } ?>>
		<strong><?php echo $support->badge($reply['user']).chr(32).$support->name($reply['user']); ?></strong>
		<span style="float:right"><?php echo dateify($reply['stamp']); ?></span>
		<hr>
		<?php
		/*$message = explode("\n",str_replace("\n\r","<br/>",$reply['message']));
		foreach ($message as $str) {
			echo htmlentities($str)."<br/>";
		}*/
		echo $support->clean_body($reply['message']);
		$sig = $support->get_sig($reply['user']);
		if ($sig) { echo $sig; }
		?>
	</div>
			<?php
			}
		}
	?>
	<div id="reply_form" class="well">
		<form action="/do/support/reply_ticket.php" method="POST">
			<input type="hidden" name="thread" value="<?php echo $id; ?>"/>
			<fieldset>
				<p><b>Message</b></p>
				<p><textarea name="message" rows="6" class="form-control" style="width:100%"></textarea></p>
			</fieldset>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary">Submit Reply</button> <?php if ($ticket[0]['status']) { ?><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#close_ticket_modal">Close Ticket</button><?php } ?>
			</div>
		</form>
	</div>
	<hr>
</div>

<!-- Close ticket script/html -->

<!-- Modal -->
<div class="modal fade" id="close_ticket_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Close Ticket?</h4>
      </div>
      <div class="modal-body">
		Are you sure you wish to close this ticket?
		<br/>
		Closing the ticket marks the support issue as resolved.
      </div>
      <div class="modal-footer">
        <button type="button" onclick="close_ticket('<?php echo $id; ?>');" class="btn btn-primary">Close Ticket</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
// Cheeky hack to make it so people cant scroll when its open.
$('#close_ticket_modal').on('show.bs.modal', function () {
  $('html').css('overflow','hidden');
})
$('#close_ticket_modal').on('hide.bs.modal', function () {
  $('html').css('overflow','visible');
})
function close_ticket(tick_id) {
	$.getJSON("/do/support/close_ticket.php?id="+tick_id,function(data) {
		if (data.res == true) {
			// All done. Refresh :D
			document.location.href = document.location.href;
		} else {
			alert(data.msg);
		}
	});
}
</script>
<?php
} else {
	// Ticket not found!
?>
<div class="wrap">
	<div class='toppadding'></div>
	<h4>&nbsp;</h4>
	<h3>Whoopsie</h3>
	<hr>
	<p>The ticket <b>#<?php echo $id; ?></b> does not exist.</p>
	<p>Maybe you're after a ticket that dont belong to you, or you've mistyped a URL?</p>
	<hr>
</div>
<?php
}

} else {
	// Whoopsie. Page not found because you're not logged in.
	include('../core/pages/misc/404.php');
}
?>