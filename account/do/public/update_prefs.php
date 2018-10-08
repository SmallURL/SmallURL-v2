<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if ($_SMALLURL['LOGGEDIN']) {
	$userid = $_SMALLURL['UID'];
	if (!has_param('geoloc','POST') || !has_param('allsafe','POST') || !has_param('allpriv','POST')) {
		echo json_encode(array("res"=>false,"msg"=>"Missing all data required in preferences payload."));
	}
	else {
		// Save them!
		$geo = get_param('geoloc','POST');
		$priv = get_param('allpriv','POST');
		$safe = get_param('allsafe','POST');
		$theme = get_param('thm','POST');
		if (!is_numeric($geo) || !is_numeric($priv) || !is_numeric($safe)) {
			echo json_encode(array("res"=>false,"msg"=>"Bad data! Please check the data you submitted was in the correct format."));
		}
		else {
			db::update("users",array("hidegeo"=>$geo,"autopriv"=>$priv,"autosafe"=>$safe),array("id"=>$userid));
			$user->misc()->set('theme',$theme);
			echo json_encode(array("res"=>true,"msg"=>"User Preferences Saved!"));
		}
	}
}
else {
	echo json_encode(array("res"=>false,"msg"=>"You are not logged in!"));
}
?>