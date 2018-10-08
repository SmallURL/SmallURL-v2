<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if ($_SMALLURL['LOGGEDIN']) {
	// Check if the Parameters exist.
	if (!has_param('appid','GET')) {
		$smallurl->error("Missing application ID!"); // Add the error to the Stack!
		die(header('Location: /apps'));
		die();
	}
	$appid = get_param("appid","GET");
	$id = $application->hashtoid($appid);
	if ($id) {
		$app = $application->get($id);
		$tokens = $application->regen_tokens($id,$_SMALLURL['UID']);
		if (is_array($tokens)) {
			$u->notifications($_SMALLURL['UID'])->create("Token Regenerated","You've just regenerated the tokens for <b>".$app['title']."</b>, ensure you update the tokens in any sites, apps or scripts that use the API!","http://account.smallurl.in/apps/view/".md5($appid));
			$tokens['msg'] = "Tokens Regenerated";
			$tokens['res'] = true;
			die(json_encode($tokens));
		} else {
			die(json_encode(array("res"=>false,"msg"=>"You do not own this application.")));
		}
	} else {
		die(json_encode(array("res"=>false,"msg"=>"No such application.")));
	}
} else {
	// Not Logged In!
	header('Location: /');
}
?>