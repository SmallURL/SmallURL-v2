<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
$r = array();
if ($_SMALLURL['LOGGEDIN']) {
	if (has_param('id') && get_param('id') != "") {
		$id = get_param('id');
		if (!is_admin($_SMALLURL['UID'])) {
			$ticket = db::get_array("support_thread",array("short"=>$id,"owner"=>$_SMALLURL['UID']));
		} else {
			$ticket = db::get_array("support_thread",array("short"=>$id));
		}
		if (count($ticket) == 1) {
			// Close the ticket.
			$id = $ticket[0]['id'];
			db::update("support_thread",array("status"=>false),array("id"=>$id));
			$r['res'] = true;
			$r['msg'] = "Ticket close. PARTY HARD!";
		} else {
			$r['res'] = false;
			$r['msg'] = "This ticket does not belong to you or does not exist!";
		}
	} else {
		$r['res'] = false;
		$r['msg'] = "Missing Ticket ID!";
	}
} else {
	$r['res'] = false;
	$r['msg'] = "You are not logged in!";
}
die(json_encode($r));
?>