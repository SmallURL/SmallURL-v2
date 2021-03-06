<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if ($_SMALLURL['LOGGEDIN'] && is_admin($_SMALLURL['UID'])) {
	// Check if the Parameters exist.
	if (!has_param('thread','POST')) {
		$smallurl->error("You can't reply to a non-existent ticket!"); // Add the error to the Stack!
		die(header('Location: /admin/support'));
		die();
	}
	if (!has_param('message','POST')) {
		$smallurl->error("You forgot to type a reply!");
		header('Location: /admin/support/view/'.get_param('thread','POST'));
		die();
	}
	// Now we create the thread along with an ID.
	$thread_short = htmlentities($_POST['thread']);
	
	if (is_admin($_SMALLURL['UID'])) {
		$thread = db::get_array("support_thread",array("short"=>$thread_short));
	} else {
		$thread = db::get_array("support_thread",array("short"=>$thread_short,"user"=>$_SMALLURL['UID']));
	}
	if (count($thread) == 1) {
		// Add it
		db::insert("support_reply",array("user"=>$_SMALLURL['UID'],"stamp"=>time(),"message"=>get_param('message','POST'),"thread"=>$thread[0]['id']));
		// Change status.
		db::update("support_thread",array("status"=>true),array("id"=>$thread[0]['id']));
		$smallurl->redirect("/admin/support/view/".$thread_short);
		
		// Drop a notification to the thread owner.
		if ($_SMALLURL['UID'] != $thread['owner']) {
			$u->notifications($thread['owner'])->create("Ticket Reply",$u->username($_SESSION['UID'])." has replied to your ticket <b>".htmlentities($thread['subject'])."</b>","//account.sitehere/support/view/{$thread['short']}");
		}
	} else {
		// Dont exist or isnt yours!
		$smallurl->error("You can't reply to a non-existent ticket!");
		$smallurl->redirect("/admin/support");
	}
	
} else {
	// Not Logged In!
	header('Location: /');
}
?>