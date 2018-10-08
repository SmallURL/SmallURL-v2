<?php

/**
 * Primary User Class
 * @package SmallURL
 * @subpackage User
 */
class user
{
    /**
     * Access to Misc User Class
     * @param int $uid Internal User ID
     * @return user_misc
     */
    public function misc($uid = false)
    {
        $tmp = new user_misc($uid);
        return $tmp;
    }

    /**
     * Check if a user is pending a password reset.
     * @param int $uid Internal User ID
     * @return bool
     */
    public function reset_pending($uid) {
		global $db;
		// Check if a user is pending password reset.
		if ($uid > 0) {
			$dat = db::get_array("users",array("id"=>$uid));
			if (count($dat) > 0) {
				if ($dat['passreset'] == "1") {
					return true;
				} else {
					// Nope.
					return false;
				}
			} else {
				// No such user.
				return false;
			}
		} else {
			// Guest.
			return false;
		}
	}

    /**
     * Completes users reset. Deprecated
     * @param $key
     * @param $newpass
     */
    public function reset_complete($key,$newpass) {
		// Set the users new password and clears their reset status.
		$udata = db::get_array("users",array("passresetkey"=>$key));
	}

    /**
     * Updates a users password upon reset.
     * @param $uniquekey
     * @param $pass
     * @return bool
     */
    public function resetPass($uniquekey, $pass) {
        $users = db::get_array("users",array("passresetkey"=>$uniquekey));

        if(count($users) == 1) {
            $password = $this->salt($pass);
            db::update("users",array("password"=>$password, "passresetkey" => '', "passreset"=>0),array("passresetkey"=>htmlentities($uniquekey)));
            return true;
        }
        return false;
    }

    /**
     * Puts a user into password reset mode and sends them a Pass Reset URL
     * @param $username
     * @return array
     */
    public function reset($username) {
		global $db;
		// Resets a users password.
		if (strpos($username,'@') > 0) {
			// Its an Email.
			$udata = db::get_array("users",array("email"=>$username));
		} else {
			$udata = db::get_array("users",array("username"=>$username));
		}
		// Check if they exist.
		if (count($udata) > 0) {
			// Yup
			$rand = $this->gen_rand();
			$udata = $udata[0];
			$uid = $udata['id'];
			
			// Update their account.
			db::update("users",array("passreset"=>1,"passresetkey"=>$rand),array("id"=>$uid));
			
			// Send them an email.
			$e = new email_tmpl();
			$e->init($udata['email'],"Password Reset");
			$e->load("password_reset");
			$e->set_var("key",$rand);
			$e->set_var("username",$udata['username']);
			$eres = $e->send();
			
			// Add a notification too. C:
			if ($eres) {
				$this->notifications($uid)->create("Password Reset","You have requested a password reset, We've sent you an Email to you containing a link to reset your password. If this wasn't you contact support immediately.");
				return array("res"=>true,"msg"=>"Password has been reset and Emailed to {$udata['email']}","email"=>$udata['email']);
			} else {
				$this->notifications($uid)->create("Password Reset","You have requested a password reset. However we were unable to send you an Email. Please open a support ticket or contact us directly via IRC.");
				return array("res"=>true,"msg"=>"Password has been reset but could not be Emailed to {$udata['email']} please open a support ticket if you have account access, e.g. still logged in or talk to staff on IRC.","email"=>$udata['email']);
			}
		} else {
			// Nope.
			return array("res"=>false,"msg"=>"There was no such user with that Username/E-Mail!");
		}
	}

    /**
     * Inserts a user into the Database
     * @param $data Array of user data.
     * @return array Result Array
     */
    public function add($data)
    {
        // Adds a user into the database.
        global $db;
        // Get what we need.
        $stamp = time();
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $user_ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $user_ip = false;
        }
        if (!isset($data['admin'])) {
            $admin = false;
        } else {
            $admin = $data['admin'];
        }
        $name = $data['name'];
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];
        $key = md5($email . $stamp . rand(0, 999));

        // Play with our food.
        $salted = $this->salt($password);
        // We only want letters and numbers.
        $username = preg_replace("/[^A-Za-z0-9 ]/", '', $username);

        // Scrub up and clean up.
        $sql = array();
        $sql['name'] = $name;
        $sql['username'] = $username;
        $sql['email'] = $email;
        $sql['password'] = $salted;
        $sql['regstamp'] = $stamp;
        $sql['regip'] = $user_ip;
        $sql['verified'] = false;
        $sql['verifykey'] = $key;

        $users_uname = db::get_array("users", array("username" => $username));
        $users_email = db::get_array("users", array("email" => $email));
        $users = (count($users_uname) + count($users_email));

        if ($users > 0) {
            if (count($users_uname) > 0) {
                return array("res" => false, "msg" => "A user with that Username already exists!");
            } else {
                return array("res" => false, "msg" => "A user with that E-Mail already exists!");
            }
        } else {
            // Insert the data.
            db::insert("users", $sql);
            do_email($email, $username, "Email Validation", "To begin using your SmallURL account, you need your email verifying. <br /> Please visit <a href='http://account.smallurl.in/verify/{$key}'> http://account.smallurl.in/verify/{$key} </a> to validate your account.");
			return array("res" => true, "msg" => "Registered!");
        }
    }

    /**
     * Salts a password
     * @param $text string User Password
     * @param bool $salt Salt, False to generate one
     * @return string
     */
    public function salt($text, $salt = false)
    {
        if (!$salt) {
            $salt = gen_id();
        }
        return $salt . "$" . md5($salt . $text . $salt . $salt);
    }

    /**
     * Generates a random string with the specified length
     * @param int $length String Length, Default is 14 Characters
     * @return string
     */
    public function gen_rand($length = 14) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}

    /**
     * Supplies a Unique Hash relating to the user, used for public urls.
     * @param bool $user_id
     * @return string
     */
    public function hash($user_id = false)
    {
        // User Client HASH
        $user_ip = $_SERVER['REMOTE_ADDR'];
        if (!$user_id) {
            if (isset($_SESSION['uid'])) {
                $user_id = $_SESSION['uid'];
            }
        }
        $hash = md5($user_ip . $user_id); // Simple hash!
        return $hash;
    }

    /**
     * Returns the Avatar URL for the USer
     * @param bool $user_id
     * @return string
     */
    public function avatar($user_id = false)
    {
        // Returns avatar URL.
        global $db, $_SMALLURL;
        if (!$user_id) {
            if (isset($_SESSION['UID'])) {
                $user_id = $_SESSION['UID'];
            }
        }
        if ($user_id !== false) {
            $user_dat = db::get_array("users", array("id" => $user_id));
        } else {
            $user_dat = array();
        }
        if (count($user_dat) > 0) {
            return "//avatar.{$_SMALLURL['domain']}/" . md5(strtolower($user_dat[0]['email']));
        } else {
            return "//avatar.{$_SMALLURL['domain']}/";
        }
    }

    /**
     * Returns an array of badges for the user $uid
     * @param bool $uid
     * @return array
     */
    public function badges($uid = false)
    {
        global $db;
        if ($uid) {
            $badges = db::get_array("ubadges", array("user" => $uid));
            if (count($badges) <= 0) {
                $badges = array();
            }
            // Cycle all badges check if the user deserves one
            $all = db::get_array("badges");
            $lvl = $this->level($uid);
            foreach ($all as $b) {
                if ($b['level'] != "-1") {
                    if ($b['level'] <= $lvl) {
                        $badges[] = array("badge" => $b['id'], "stamp" => "0");
                    }
                }
            }

            return $badges;
        } else {
            return db::get_array("ubadges");
        }
    }

    /**
     * Returns the Users Level
     * @param int $uid
     * @return int
     */
    public function level($uid = 0)
    {
        global $db, $_SMALLURL;
        if ($uid === 0) {
            if (isset($_SMALLURL['UID'])) {
                $uid = $_SMALLURL['UID'];
            }
        }
        $udat = db::get_array("users", array("id" => $uid), "role");
        if (count($udat) > 0) {
            $role = $udat[0]['role'];
            $rdat = db::get_array("roles", array("id" => $role));
            if (count($rdat) > 0) {
                return (int)$rdat[0]['level'];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    /**
     * Returns the requested badge
     * @param $id
     * @return array
     */
    public function get_badge($id)
    {
        global $db;
        $badges = db::get_array("badges", array("id" => $id));
        if (count($badges) > 0) {
            return $badges[0];
        } else {
            return array();
        }
    }

    /**
     * Check if User qualifies as an Admin
     * @param bool $uid
     * @return bool
     */
    public function is_admin($uid = false)
    {
        global $db;
        if (!$uid) {
            if (isset($_SESSION['UID'])) {
                $uid = $_SESSION['UID'];
            } else {
				echo "Not loggedin";
				var_dump($_SESSION);
			}
        }
        if ($uid != false) {
			$level = $this->level($uid);
			if ($level == 100) {
				echo "is admin";
				return true;
			} else {
				echo "is not admin";
				return false;
			}
        } else {
            // Guest.
			echo "Quest";
            return false;
        }
    }

    /**
     * Gets a users role
     * @param bool $uid
     * @return array|bool
     */
    public function get_role($uid = false)
    {
        global $db;
        if (!$uid && isset($_SESSION['UID'])) {
            $uid = $_SESSION['UID'];
        }
        $udat = db::get_array("users", array("id" => $uid), "role");
        if (count($udat) > 0) {
            $role = $udat[0]['role'];
            $rdat = db::get_array("roles", array("id" => $role));
            if (count($rdat) > 0) {
                return $rdat[0];
            } else {
                return array("name" => "Guest User", "desc" => "Guest User", "level" => "0");
            }
        } else {
            return false;
        }
    }

    /**
     * Returns a users profile link
     * @param $uid
     * @return string
     */
    public function link($uid)
    {
        global $_SMALLURL;
        $username = $this->core($uid)->get("username");
        return "//{$_SMALLURL['domain']}/user/{$username}";
    }

    /**
     * Access Core User Data
     * @param bool $uid
     * @return user_core
     */
    public function core($uid = false)
    {
        $tmp = new user_core($uid);
        return $tmp;
    }

    /**
     * Returns a users Username
     * @param int $uid
     * @return null|string
     */
    public function username($uid = 0)
    {
        if ($uid > 0) {
            return $this->core($uid)->get("username");
        } else {
            return "Public User";
        }
    }

    /**
     * Returns a users Display Name, Real Name picked over Username.
     * @param int $uid
     * @return null|string
     */
    public function display_name($uid = 0) {
		if ($uid > 0) {
			$uname = $this->core($uid)->get("username");
			$rname = $this->core($uid)->get("name");
			if (strtolower($uname == $rname)) {
				return $uname;
			} else {
				return $rname;
			}
		} else {
			return $this->username(0);
		}
	}

    /**
     * Check if this user has verified their account via Email.
     * @param $uid
     * @return bool
     */
    public function verified($uid)
    {
        if ($uid > 0) {
            $verified = $this->core($uid)->get("verified");
            if ($verified == "1") {
                return true;
            } else {
                return false;
            }
        } else {
            // Guests dont need to verify so we mark them as already verified
            return true;
        }
    }

    /**
     * Access user Notifications Class
     * @param bool $uid
     * @return user_notifications
     */
    public function notifications($uid = false)
    {
        global $_SMALLURL;
        if (!$uid) {
            $uid = $_SMALLURL['UID'];
        }
        $n = new user_notifications($uid);
        return $n;
    }
	
	// Check if someones online
	public function online($uid = false) {
	       global $_SMALLURL;
        if (!$uid) {
            $uid = $_SMALLURL['UID'];
        }
		
		$seen = $this->misc($uid)->get("activity_stamp");
		if ($seen) {
			if ( (time() - $seen) <= 300) {
				return true;
			}
		}
		return false;
	}
}

/**
 * Access core user data aka SQL Columns from User Table
 */
class user_core
{
    function __construct($uid = false)
    {
        if ($uid !== false) {
            $this->uid = $uid;
        } else {
            global $_SMALLURL;
            $this->uid = $_SMALLURL['UID'];
        }
    }

    public function get($item = false)
    {
        global $db;
        $uid = $this->uid;
        $dat = db::get_array("users", array("id" => $uid));
        if ($item != false) {
            if (isset($dat[0][$item])) {
                return $dat[0][$item];
            } else {
                return null;
            }
        } else {
            return $dat[0];
        }
    }

    public function set($item, $value)
    {
        global $db;
        $uid = $this->uid;
        db::update("users", array($item => $value), array("id" => $uid));
    }
}

class user_misc
{
    function __construct($uid)
    {
        if ($uid !== false) {
            $this->uid = $uid;
        } else {
            global $_SMALLURL;
            $this->uid = $_SMALLURL['UID'];
        }
    }

    public function get($item = false)
    {
        global $db;
        $uid = $this->uid;
		$d = db::get_array("user_meta",array("name"=>$item,"user"=>$uid));
		if (count($d) > 0) {
			return $d[0]['value'];
		} else {
			return null;
		}
    }

    public function set($item, $value)
    {
        global $db;
        $uid = $this->uid;
		// Sets an item
		$d = db::get_array("user_meta",array("name"=>$item,"user"=>$uid));
		
		if (count($d) > 0) {
			// Exists. We'll update the existing field.
			db::update("user_meta",array("value"=>$value),array("id"=>$d[0]['id']));
		} else {
			// Insert.
			db::insert("user_meta",array("name"=>$item,"value"=>$value,"user"=>$uid));
		}
    }

    public function rem($item)
    {
        global $db;
        $uid = $this->uid;
		// Unsets an item.
		db::delete("user_meta",array("name"=>$item,"user"=>$uid));
    }
}

class user_notifications
{
    function __construct($uid)
    {
        $this->user_id = $uid;
    }

    function create($title, $text, $url = false) {
        global $db, $u;
        // The supplied UID is the UID it will be assigned to.
        $uid = $this->user_id;
        if ($uid != 0) {

            return db::insert("notifications",array("user"=>$uid,"title"=>$title,"text"=>$text,"url"=>$url,"stamp"=>time()));

        } else {
            return false;
        }
    }

    function all()
    {
        global $db, $u;
        // Return ALL notifications
        $uid = $this->user_id;
        if ($uid != 0) {
            $ns = db::get_array("notifications",array("user"=>$uid));
        } else {
            $ns = array();
        }
        return $ns;
    }

    function seen()
    {
        global $db, $u;
        // Return all SEEN Notifications
        $uid = $this->user_id;
        $ns = db::get_array("notifications",array("user"=>$uid,"read"=>true));
        return $ns;
    }

    function unread()
    {
        global $db, $u;
        // Return all UNREAD Notifications
        $uid = $this->user_id;
        $ns = db::get_array("notifications",array("user"=>$uid,"read"=>false));
        return $ns;
    }

    function markread($id)
    {
        global $db, $u;
        // Mark notification as seen.
        $uid = $this->user_id;
        db::update("notifications",array("read"=>"1"),array("user"=>$uid,"id"=>$id));
    }
}

$u = new user();
$user = $u;
