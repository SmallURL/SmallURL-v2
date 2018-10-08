<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if ($_SMALLURL['LOGGEDIN']) {
	// Check if the Parameters exist.
	if (!has_param('thread','POST')) {
		$smallurl->error("You can't reply to a non-existent ticket!"); // Add the error to the Stack!
		die(header('Location: /support'));
		die();
	}
	if (!has_param('message','POST')) {
		$smallurl->error("You forgot to type a reply!");
		header('Location: /support/view/'.get_param('thread','POST'));
		die();
	}
	// Now we create the thread along with an ID.
	$thread_short = get_param('thread','POST');
	
	if (is_admin($_SMALLURL['UID'])) {
		$thread = db::get_array("support_thread",array("short"=>$thread_short));
	} else {
		$thread = db::get_array("support_thread",array("short"=>$thread_short,"owner"=>$_SMALLURL['UID']));
	}
	if (count($thread) == 1) {
		// Add it
		db::insert("support_reply",array("user"=>$_SMALLURL['UID'],"stamp"=>time(),"message"=>get_param('message','POST'),"thread"=>$thread[0]['id']));
		// Change status.
		db::update("support_thread",array("status"=>true),array("id"=>$thread[0]['id']));
		$smallurl->redirect("/support/view/".$thread_short);
	} else {
		// Dont exist or isnt yours!
		$smallurl->error("You can't reply to a non-existent ticket!");
		$smallurl->redirect("/support");
	}
	
} else {
	// Not Logged In!
	header('Location: /');
}
?>