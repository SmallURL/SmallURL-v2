<?php
/*TODO: Flesh out the code.*/

/**
 * Applications Class, Direct access and management of User Applications
 * @package SmallURL
 * @subpackage Applications
 * @version SmallURL 2.1
 * @date
 */
class applications {
    /**
     * Creates and adds an Application to SQL
     * @param $data array Applications Data
     * @since SmallURL 2.1
     * @author Thomas Edwards (TMFKSOFT)
     */
    public function create($data) {
    }

    /**
     * Retrieves SQL ROW for the supplied APP
     * @param $app int Applications Internal ID
     * @return array Array() - Depends if Application Exists. Array upon success
     * @author Thomas Edwards (TMFKSOFT)
     */
    public function get($app) {
		$apps = db::get_array("apps",array("id"=>$app));
		if (count($apps) > 0) {
			return $apps[0];
		} else {
			return false;
		}
    }

    /**
     * Returns TRUE/FALSE if the Application has the Specified Permission
     * @param $app
     * @param $user
     * @param $perm
     * @return bool
     * @author Thomas Edwards (TMFKSOFT)
     * @since SmallURL 2.1
     */
    public function has_perm($appid,$uid,$perm) {
		// Check if the app has the requested permission over the user.
        $app = $this->get($appid);
		if ($app) {
			$auth = $this->has_auth($appid,$uid);
			if ($auth) {
				$perms = json_decode($app['perms'],true);
				if (in_array(strtolower($perm),$perms)) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
    }
	
	public function has_auth($appid,$uid) {
		// Checks if the User has Authorised the app.
		$app = $this->get($appid);
		if ($app) {
			$auth = db::get_array("auth",array("user"=>$uid,"app"=>$appid));
			if (count($auth) > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function send_callback($appid,$action,$data) {
		// Sends a callback to an application, That is if they have a callback :c
		$app = $this->get($appid);
		if ($app) {
			$callback = $this->get_callback($appid);
			if ($callback != null) {
				$post = array("action"=>strtoupper($action),"data"=>json_encode($data));
				// Now we use cURL to post this data to the callback URL.
				
				//url-ify the data for the POST
				$pstring = "";
				foreach($post as $key=>$value) { $pstring .= $key.'='.$value.'&'; }
				rtrim($pstring, '&');

				//open connection
				$ch = curl_init();

				//set the url, number of POST vars, POST data
				curl_setopt($ch,CURLOPT_URL, $callback);
				curl_setopt($ch,CURLOPT_POST, count($post));
				curl_setopt($ch,CURLOPT_POSTFIELDS, $pstring);

				//execute post
				$result = curl_exec($ch);

				//close connection
				curl_close($ch);
				
				if ($result) {
					return true;
				} else {
					return false;
				}
				
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function get_callback($app) {
		// Gets the callback URL of an App.
		$apps = db::get_array("apps",array("id"=>$app));
		if (count($apps) > 0) {
			if (strlen($apps[0]['callback']) > 0) {
				return $apps[0]['callback'];
			} else {
				return null;
			}
		} else {
			return false;
		}
	}
	
	public function hashtoid($id) {
		// Retrieves the numeric ID of an app based on its MD5 ID
		$app = db::get_array("apps",array("MD5(`id`)"=>$id));
		if (count($app) > 0) {
			return $app[0]['id'];
		} else {
			return false;
		}
	}
	
	public function regen_tokens($appid,$uid) {
		// Regenerate Application Tokens.
		$app = $this->get($appid);
		if ($app) {
			if ($app['user'] === $uid) {
				// User owns this app.
				$sekrit = md5(gen_id());
				$public = md5(gen_id());
				db::update("apps",array("pubtoken"=>$public,"privtoken"=>$sekrit),array("id"=>$appid));
				return array("pub"=>$public,"priv"=>$sekrit);
			} else {
				// nope.
				return false;
			}
		} else {
			return false;
		}
	}
	
	/* Auth */
	public function invoke_auth($appid,$uid) {
		// Adds authorisation for the app with the user.
		$app = $this->get($appid);
		if ($app) {
			// App exists, Does the user?
			$users = db::get_array("users",array("id"=>$uid,"verified"=>1,"enabled"=>1));
			if (count($users) > 0) {
				// Has the user already authenticated the application?
				if (!$this->has_auth($appid,$uid)) {
					$utoken = md5(gen_id());
					db::insert("auth",array("user"=>$uid,"app"=>$appid,"stamp"=>time(),"usertoken"=>$utoken));
					return true;
				} else {
					return false;
				}
			} else {
				// No such user
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function revoke_auth($appid,$uid = false) {
		// Removes the auth.
		$app = $this->get($appid);
		if ($app) {
			// App Exists, does the user exist?
			if ($uid) {
				$users = db::get_array("users",array("id"=>$uid,"verified"=>1,"enabled"=>1));
				if (count($users) > 0) {
					// Yay the user exists.
					db::delete("auth",array("app"=>$appid,"user"=>$uid));
					return true;
				} else {
					return false;
				}
			} else {
				// Drop all auth for an application.
				db::delete("auth",array("app"=>$appid));
				return true;
			}
		} else {
			return false;
		}
	}
	
}
$application = new applications();