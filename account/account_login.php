<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
// Auth system
if (!has_param('username')) {

}
//die($_SMALLURL['domain']);
if(isset($_SERVER['PATH_INFO'])) {
	$r = explode("/", $_SERVER['PATH_INFO']);

	if($r[1] == 'logout') {
		session_destroy();
		header("Location: /");
	}

	if($r[1] == 'login') {
	
		if(isset($_SESSION['uid'])) {
			if(is_numeric($_SESSION['uid'])) {
				header("Location: //account.".$_SMALLURL['domain']);
			}
		}
	
		if(isset($_POST['go_auth'])) {
			if ((!isset($_POST['user']) || $_POST['user'] == "") && (!isset($_POST['pass']) || $_POST['pass'] == "")) {
				// Missing user credents.
				$_SESSION['ERR'] = true;
				$_SESSION['ERR_ID'] = "3";
				header('Location: /login');
				die('3');
			}
			if (!isset($_POST['user']) || $_POST['user'] == "") {
				$_SESSION['ERR'] = true;
				$_SESSION['ERR_ID'] = "1";
				// Missing user credents.
				header('Location: /login');
				die('1');
			} else if (!isset($_POST['pass']) || $_POST['pass'] == "") {
				// Missing user credents.
				$_SESSION['ERR'] = true;
				$_SESSION['ERR_ID'] = "2";
				header('Location: /login');
				die('2');
			}

			$email = $_POST['user'];
			$password = $_POST['pass'];
			$uid = check_login($email,$password);

			if (!$uid) {
				// Bad credentials.
				$_SESSION['ERR'] = true;
				$_SESSION['ERR_ID'] = "4";
				header('Location: /login');
				die('4');
			}
			else {
				$_SMALLURL['LOGGINED'] = true;
				$_SESSION['uid'] = $uid;
				$_SESSION['timeout'] = time();
				$_SESSION['crsf'] = md5(md5(time())."SWAGYOLOYOLOSWAG".md5(time()."SmallHacked")."INB4SMALLURL");
				$u->misc($uid)->set("last_ip",$_SERVER['REMOTE_ADDR']);
				$u->misc($uid)->set("activity_stamp",time());
				if (!isset($_POST['ref']) || $_POST['ref'] == "") {
					header("Location: //".$_SMALLURL['domain']."/");
				} else {
					header('Location: '.$_POST['ref']);
				}

			}
		}
		include("login.php");
	} else if($r[1] == 'register') {
		
		if(isset($_SESSION['uid'])) {
			if(is_numeric($_SESSION['uid'])) {
				header("Location: //account.".$_SMALLURL['domain']);
			}
		}
	
		if(isset($_POST['go_reg']) && $_POST['go_reg'] === md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'])) {

			// Check if we have everything required.
			
			if (!isset($_POST['username']) || $_POST['username'] == "") {
				$_SESSION['ERR'] = true;
				$_SESSION['ERR_ID'] = "1";
				// Missing Username
				header('Location: /register');
				die();
			}
			if (!isset($_POST['email']) || $_POST['email'] == "") {
				// Missing Email
				$_SESSION['ERR'] = true;
				$_SESSION['ERR_ID'] = "2";
				header('Location: /register');
				die();
			}
			if (!isset($_POST['password']) || $_POST['password'] == "") {
				// Missing Password.
				$_SESSION['ERR'] = true;
				$_SESSION['ERR_ID'] = "3";
				header('Location: /register');
				die();
			}
			if (!isset($_POST['password_confirm']) || $_POST['password_confirm'] == "") {
				// Missing Email
				$_SESSION['ERR'] = true;
				$_SESSION['ERR_ID'] = "4";
				header('Location: /register');
				die();
			}
			
			// Check our Email is valid too!
			if (!(strpos($_POST['email'],'@') && strpos($_POST['email'],'.')) && strlen($_POST['email']) < 6) {
				// Invalid Email Length or Format!
				$_SESSION['ERR'] = true;
				$_SESSION['ERR_ID'] = "5";
				header('Location: /register');
				die();
			}
			
			// Check the passwords match
			if (!($_POST['password'] === $_POST['password_confirm'])) {
				// Password mismatched
				$_SESSION['ERR'] = true;
				$_SESSION['ERR_ID'] = "6";
				header('Location: /register');
				die();
			}
			
			// Now Populate!
			$dat = array();
			$dat['username'] = $_POST['username'];
			$dat['name'] = $_POST['username'];
			$dat['email'] = $_POST['email'];
			$dat['password'] = $_POST['password'];
			
			// Attempt to register the user.
			$res = $u->add($dat);
			
			$_SESSION['ERR'] = true;
			$_SESSION['ERR_ID'] = "99";

			
			if ($res['res'] == true) {
				// Registered!
				header('Location: /login');
				die();
			} else {
				$_SESSION['ERR_MSG'] = $res['msg'];
				header('Location: /register');
				die();
			}
		}
		include("register.php");
		//header("Location: /login");
	} else if($r[1] == 'forgot') {
		include("forgot.php");
	} else if($r[1] == 'auth') {
		$token = $r[2];
		include("auth.php");
	} else if ($r[1] == 'reset') {

		if(isset($_POST['password1']) && isset($_POST['password2'])) {
			if($_POST['password1'] != '' && $_POST['password2'] != '') {

				if($_POST['password1'] == $_POST['password2']) {
						
						$a = $u->resetPass($r[2], $_POST['password1']);

						if($a === true) 
						{
							header("Location: /");
						} else {
							$err = 1;
						}


					} else {
						$err = 1;
					}
			} else {
				$err = 1;
			}
		} else {
			$err = 2;
		}

		include("reset.php");
	} else {
		die(__LINE__);
		header("Location: /login");
		die();
	}
} else {
	die(__LINE__);
	header("Location: /login");
	die();
}
?>
