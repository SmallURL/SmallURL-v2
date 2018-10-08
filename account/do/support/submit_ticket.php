<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if ($_SMALLURL['LOGGEDIN']) {
	// Check if the Parameters exist.
	if (!has_param('subject','POST')) {
		$smallurl->error("You forgot to type a Ticket Subject!"); // Add the error to the Stack!
		die(header('Location: /support/create'));
		die();
	}
	if (!has_param('message','POST')) {
		$smallurl->error("You forgot to type a Ticket Message!");
		header('Location: /support/create');
		die();
	}
	// Now we create the thread along with an ID.
	$thread_id = gen_id();
	
	$threads = db::get_array("support_thread",array("short"=>$thread_id));
	if (count($threads) == 0) {
		// Add it
		db::insert("support_thread",array("user"=>$_SMALLURL['UID'],"owner"=>$_SMALLURL['UID'],"stamp"=>time(),"message"=>get_param('message','POST'),"subject"=>get_param('subject','POST'),"short"=>$thread_id,"status"=>true));
		$smallurl->redirect("/support/view/".$thread_id);
	} else {
		// Eh. Okay.
	}
	
} else {
	// Not Logged In!
	header('Location: /');
}
?>