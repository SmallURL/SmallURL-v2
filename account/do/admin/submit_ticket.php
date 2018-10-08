<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if ($_SMALLURL['LOGGEDIN'] && is_admin($_SMALLURL['UID'])) {
	// Check if the Parameters exist.
	if (!has_param('subject','POST')) {
		$smallurl->error("You forgot to type a Ticket Subject!"); // Add the error to the Stack!
		die(header('Location: /admin/support/create'));
		die();
	}
	if (!has_param('uid','POST')) {
		$smallurl->error("You can't make a ticket for nobody!"); // Add the error to the Stack!
		die(header('Location: /admin/support/create'));
		die();
	}
	if (!has_param('message','POST')) {
		$smallurl->error("You forgot to type a Ticket Message!");
		header('Location: /admin/support/create');
		die();
	}
	if (get_param('uid','POST') == $_SMALLURL['UID']) {
		$smallurl->error("You can't cheat the system! You're not able to make your own tickets!"); // Add the error to the Stack!
		die(header('Location: /admin/support/create'));
		die();
	}
	// Now we create the thread along with an ID.
	$thread_id = gen_id();
	if (get_param('uid','POST') == "0") {
		$users = $db->get_array('users');
		foreach ($users as $u) {
			$thread_id = gen_id();
			$threads = $db->get_array("support_thread",array("short"=>$thread_id));
			if (count($threads) == 0) {
				// Add it
				$db->insert("support_thread",array("user"=>$_SMALLURL['UID'],"owner"=>$u['id'],"stamp"=>time(),"message"=>get_param('message','POST'),"subject"=>get_param('subject','POST'),"short"=>$thread_id,"status"=>true,"unread"=>true));
			}
		}
		$smallurl->redirect("/admin/support/");
	} else {
		$threads = $db->get_array("support_thread",array("short"=>$thread_id));
		if (count($threads) == 0) {
			// Add it
			$db->insert("support_thread",array("user"=>$_SMALLURL['UID'],"owner"=>get_param('uid','POST'),"stamp"=>time(),"message"=>get_param('message','POST'),"subject"=>get_param('subject','POST'),"short"=>$thread_id,"status"=>true,"unread"=>true));
			$smallurl->redirect("/admin/support/view/".$thread_id);
		}
	}
	
} else {
	// Not Logged In!
	header('Location: /');
}
?>