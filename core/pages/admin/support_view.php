<?php if ($_SMALLURL['LOGGEDIN']) {
$html->PageTitle("View Ticket", "Helping users helps you apparently.");
include("asset/support_nav.php");

// Now for the Nitty Gritty!
if (!$u->is_admin()) {
	$ticket = db::get_array("support_thread",array("short"=>$id,"user"=>$_SMALLURL['UID']));
} else {
	$ticket = db::get_array("support_thread",array("short"=>$id));
}
if (count($ticket) == 1) { ?>
<div class="wrap">
	<div class='toppadding'></div>
	<h4>&nbsp;</h4>
	<h3>Ticket #<?php echo $id; ?> - <?php echo htmlentities($ticket[0]['subject']); ?> [<?php if ($ticket[0]['status']) { echo "OPEN"; } else { echo "CLOSED"; } ?>]</h3>
	<hr>
	<strong>Ticket User Link:</strong>
	<br/>
	<div class="well well-sm">
		<a href="http://account.<?php echo $_SERVER["HTTP_HOST"]; ?>/support/view/<?php echo $id; ?>">http://account.<?php echo $_SERVER["HTTP_HOST"]; ?>/support/view/<?php echo $id; ?></a>
	</div>
	<hr/>
	<?php $smallurl->display_errors(); ?>
	<div class="well">
		<strong><?php echo get_user($ticket[0]['user']); ?></strong>
		<span style="float:right"><?php echo dateify($ticket[0]['stamp']); ?></span>
		<hr>
		<?php
		// Thread Message
		echo str_replace('\r\n',"<br/>",htmlentities($ticket[0]['message']));
		/*
		$message = explode("\n",$ticket[0]['message']);
		foreach ($message as $str) {
			echo htmlentities($str)."<br/>";
		}
		*/
		?>
	</div>
	<?php
		// Replies go here soon.
		$replies = db::get_array("support_reply",array("thread"=>$ticket[0]['id']));
		if (count($replies) > 0) {
			foreach ($replies as $reply) {
			?>
	<div class="well" <?php if ($reply['user'] != $_SMALLURL['UID']) { echo 'style="background:#EDFFFF;border:1px solid silver;"'; } ?>">
		<strong><?php echo get_user($reply['user']); ?></strong>
		<span style="float:right"><?php echo dateify($reply['stamp']); ?></span>
		<hr>
		<?php
		// Reply Body
		echo str_replace('\r\n',"<br/>",htmlentities($reply['message']));
		/*
		$message = explode("\n",$reply['message']);
		foreach ($message as $str) {
			echo htmlentities($str)."<br/>";
		}
		*/
		?>
	</div>
			<?php
			}
		}
	?>
	<div id="reply_form" class="well">
		<form action="/do/admin/reply_ticket.php" method="POST">
			<input type="hidden" name="thread" value="<?php echo $id; ?>"/>
			<fieldset>
				<p><b>Message</b></p>
				<p><textarea name="message" rows="6" class="form-control" style="width:100%"></textarea></p>
			</fieldset>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary">Submit Reply</button>
			</div>
		</form>
	</div>
	<hr>
</div>
<?php
} else {
	// Ticket not found!
?>
<div class="wrap">
	<div class='toppadding'></div>
	<h4>&nbsp;</h4>
	<h3>Whoops</h3>
	<hr>
	<p>The ticket <b>#<?php echo $id; ?></b> does not exist.</p>
	<p>Maybe you're after a ticket that dont belong to you, or you've mistyped a URL?</p>
</div>
<?php
}

} else {
	// Whoopsie. Page not found because you're not logged in.
	include('pages/404.php');
}
?>