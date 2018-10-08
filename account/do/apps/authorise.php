<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if ($_SMALLURL['LOGGEDIN']) {
	// Check if the Parameters exist.
	if (!has_param('app_token','POST')) {
		$smallurl->error("Missing application token!"); // Add the error to the Stack!
		die(header('Location: /apps/create'));
		die();
	}
	if (!has_param('auth_token','POST')) {
		$smallurl->error("Missing auth token!");
		header('Location: /support/create');
		die();
	}
	// Pop some data into variables.
	$token = get_param('app_token','POST');
	$auth_token = get_param('auth_token','POST');
	
	if ($auth_token !== md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'])) {
		// Authorisation token dont match.
		$smallurl->error("Invalid Security Token");
		$smallurl->redirect("/apps/".$token);
		die();
	}
	
	$apps = db::get_array("apps",array("pubtoken"=>$token));
	// Check if the app exists
	if (count($apps) <= 0) {
		// App doesn't exist.
		$smallurl->error("That Application doesn't exist.");
		$smallurl->redirect("/apps/".$token);
		die();
	}
	
	// Check if the user has already authorised.
	$auths = db::get_array("auth",array("app"=>$apps[0]['id'],"user"=>$_SMALLURL['UID']));
	if (count($auths) > 0) {
		// Already authed
		$smallurl->error("You have already Authorised this application.");
		$smallurl->redirect("/auth/".$token);
		die();
	}
	
	
	// Generate a random user token
	$usertoken = md5($_SMALLURL['UID'].gen_id());
	
	// We can now add it in.
	db::insert("auth",array("user"=>$_SMALLURL['UID'],"app"=>$apps[0]['id'],"stamp"=>time(),"usertoken"=>$usertoken));

	$u->notifications($_SMALLURL['UID'])->create("Application Authorised","You have just authorised <b>".$apps[0]['title']."</b> to access your account. Revoke access at any time via the App Center","http://account.smallurl.in/apps/view/".md5($apps[0]['id']));
	
	// Now we direct the user to the app page.
	$smallurl->redirect("/apps/view/".md5($apps[0]['id']));
	
} else {
	// Not Logged In!
	header('Location: /');
}
?>