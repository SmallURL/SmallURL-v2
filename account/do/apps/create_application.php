<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if ($_SMALLURL['LOGGEDIN']) {
	// Check if the Parameters exist.
	if (!has_param('title','POST')) {
		$smallurl->error("You forgot to give the Application a title!"); // Add the error to the Stack!
		die(header('Location: /apps/create'));
		die();
	}
	if (!has_param('desc','POST')) {
		$smallurl->error("You forgot to give the Application a description!");
		header('Location: /apps/create');
		die();
	}
	// Pop some data into variables.
	$title = htmlentities(get_param('title','POST'));
	$desc = htmlentities(get_param('desc','POST'));
	$callback = get_param('callback','POST');
	$perms = array("smallurl");
	
	$apps = db::get_array("apps",array("title"=>$title));
	
	// Check if the app doesn't already exist.
	if (count($apps) > 0) {
		// App with that title already exists.
		$smallurl->error("An Application with that title already eixsts!");
		$smallurl->redirect("/apps/create");
		die();
	}
	
	// Generate some random keys.
	$pubkey = md5(gen_id());
	$privkey = md5(gen_id());
	
	// We can now add it in.
	db::insert("apps",array("user"=>$_SMALLURL['UID'],"title"=>$title,"stamp"=>time(),"desc"=>$desc,"callback"=>$callback,"privtoken"=>$privkey,"pubtoken"=>$pubkey,"perms"=>json_encode($perms)));
	
	$id = mysqli_insert_id($_SMALLURL['SQL']);
	
	// Move the uploaded image to the cache.
	if (isset($_FILES['icon']) && $_FILES['icon']['error'] <= 0) {
		// Move it over.
		move_uploaded_file($_FILES["icon"]["tmp_name"],"../../../cache/apps/".md5($id).".png");
	}
	$smallurl->redirect("/apps/view/".md5($id));
	
} else {
	// Not Logged In!
	header('Location: /');
}
?>