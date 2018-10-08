<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if ($_SMALLURL['LOGGEDIN']) {
	// Check if the Parameters exist.
	if (!has_param('appid','GET')) {
		$smallurl->error("Missing application id!"); // Add the error to the Stack!
		die(header('Location: /apps/'));
		die();
	}
	
	// Pop some data into variables.
	$id = get_param('appid','GET');
	$appid = $application->hashtoid($id);
	
	if (!$appid) {
		$smallurl->error("No such app!"); // Add the error to the Stack!
		die(header('Location: /apps/'));
		die();
	}
	
	// Check if the user has auth in the first place
	if (!$application->has_auth($appid,$_SMALLURL['UID'])) {
		$smallurl->error("You're not even authorised with this app."); // Add the error to the Stack!
		die(header('Location: /apps/view/'.$id));
		die();
	}
	
	$app = $application->get($appid);
	
	if ($application->revoke_auth($appid,$_SMALLURL['UID'])) {

		$u->notifications($_SMALLURL['UID'])->create("Application Authorisation Revoked","You have just revoked authorisation for <b>".$app['title']."</b>, You can reuthorise the App any time by using it via the App's Site.","http://account.".SITE_URL."/apps/view/".md5($app['id']));
		
		// Now we direct the user to the app page.
		$smallurl->redirect("/apps/view/".md5($app['id']));
	} else {
		$smallurl->error("Unknown ERROR while revoking authorisation."); // Add the error to the Stack!
		die(header('Location: /apps/view/'.md5($app['id'])));
		die();
	}
	
} else {
	// Not Logged In!
	header('Location: /');
}
?>