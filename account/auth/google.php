<?php
// Include the Core.
include('../../core/smallurl.php');

// Include the Service Lib

// Is this a return from google!?
if (!isset($_GET['state'])) {
	// They can't access this directly anymore.
	header('Location: http://account.'.SITE_URL.'/');
	die();
} else {
	// Google sends its loves.
	
	if (isset($_SESSION['uid'])) {
		// Yup
		if (google::linked($_SESSION['uid'])) {
			header('Location: http://account.'.SITE_URL.'/');
			die();
		}
	}
	
	if (google::in_progress($_SESSION['uid'])) {
		//echo "You're already in the linking progress.";
		$saved_state = $_SESSION['google_state'];
		if ($_GET['state'] === $saved_state) {
			//echo "Proceeding";
			unset($_SESSION['google_state']); // Clean up.
			$code = $_GET['code'];
			
			
			
			//extract data from the post

			$token = google::retrieve_token($code);
			$_SESSION['google_token'] = $token;
			$google_data = google::get_userinfo($token);
			
			// Do we modify or register?
			if (isset($_SESSION['uid'])) {
				$u->core($_SESSION['uid'])->set("google_id",$google_data['id']);
				$u->core($_SESSION['uid'])->set("name",$google_data['given_name']." ".$google_data['family_name']);
				$u->core($_SESSION['uid'])->set("email",$google_data['email']);
				header('Location: http://account.'.SITE_URL.'/');
				echo "Updated your account";
				die();
			} else {
				// Does someone with that GID exist?
				$existing = db::get_array("users",array("google_id"=>$google_data['id']));
				if (count($existing) > 0) {
					// Yes. Sign them in
					$_SESSION['uid'] = $existing[0]['id'];
					$u->core($_SESSION['uid'])->set("password","");
					$u->core($_SESSION['uid'])->set("name",$google_data['given_name']." ".$google_data['family_name']);
					$u->core($_SESSION['uid'])->set("email",$google_data['email']);
					header('Location: http://account.'.SITE_URL.'/');
					die();
				} else {
					echo "Made account";
					$u_data = array();
					$u_data['google_id'] = $google_data['id'];
					$u_data['name'] = $google_data['given_name']." ".$google_data['family_name'];
					$u_data['email'] = $google_data['email'];
					$u_data['role'] = "4";
					$u_data['enabled'] = "1";
					
					// Handle Email.
					if ($google_data['verified_email']) {
						$u_data['verified'] = true;
					} else {
						$u_data['verified'] = false;
					}
					$r = $u->add($u_data);
					var_dump($r);
					if ($r['res']) {
						header('Location: http://account.'.SITE_URL.'/');
						die();
					} else {
						die($r['msg']);
					}
				}
			}
			//var_dump($google_data);
		} else {
			echo "Invalid State Hash - Are you trying to break this?";
		}
	} else {
		echo "The state expired. Please try signing in with google again!";
	}
}
?>