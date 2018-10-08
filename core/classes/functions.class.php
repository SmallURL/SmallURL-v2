<?php
// SmallURL Functions File
/**
 * Checks SmallURL User Login Details
 * @param str $email Users Email or Username
 * @param str $password Users Password
 */
function check_login($email,$password) {
    global $db,$sql;
	// Uses MD5 Password Salting.
	// Check if username or email!
	if (strpos($email,'@')) {
		$data = db::get_array("users",array("email"=>$email));
	} else {
		$data = db::get_array("users",array("username"=>$email));
	}
	if (count($data) <= 0) {
		// No such user.
		return false;
	}
	$pass_dat = explode("$",$data[0]['password']);
	$salt = $pass_dat[0]; // This is the users Salt.
	$hash = $pass_dat[1]; // This is the users Password.

	$our_hash = md5($salt.$password.$salt.$salt);
	if ($our_hash === $hash) {

		// Update table with info
		return $data[0]['id']; // Returns the users Identification number.
	}
	else {
		return false; // Bad Password.
	}

}

function salt_password($text,$salt = false) {
	if (!$salt) {
		$salt = gen_id();
	}
	return $salt."$".md5($salt.$text.$salt.$salt);
}
function url_count_total($id = false) {
	if (!$id) {
		return url_count_rand()+url_count_custom();
	}
	else {
		return url_count_rand($id)+url_count_custom($id);
	}
}

function url_count_rand($id = false) {
	global $db,$sql;
	if (!$id) {
		$random_entries = db::get_array("entries",array("custom"=>"0"),"COUNT(*)");
	}
	else {
		$random_entries = db::get_array("entries",array("custom"=>"0","user"=>$id),"COUNT(*)");
	}
	if ($random_entries) {
		return $random_entries[0]['COUNT(*)'];
	} else {
		return false;
	}
}
function url_count_custom($id = false) {
	global $db,$sql;
	if (!$id) {
		$random_entries = db::get_array("entries",array("custom"=>"1"),"COUNT(*)");
	}
	else {
		$random_entries = db::get_array("entries",array("custom"=>"1","user"=>$id),"COUNT(*)");
	}
	if ($random_entries) {
		return $random_entries[0]['COUNT(*)'];
	} else {
		return false;
	}
}
function get_url($id) {
	global $db,$sql;
	$url = db::get_array("entries",array("short"=>$id,"type"=>"0"));
	if (count($url) > 0) {
		return $url[0]['content'];
	}
	else {
		return false;
	}
}
function gen_id() {
	global $conf;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < 5; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
function get_short($content) {
	// Does the oppsite of get_url
	global $db,$sql;
	$short = db::get_array("entries",array("content"=>$content));
	if (count($short) > 0) {
		return $short[0]['short'];
	}
	else {
		return false;
	}
}
function check_real($url) {
	// Checks if a URL is real or not.
	if($url == NULL) return false;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($httpcode>=200 && $httpcode<=400){
        return array('res'=>true,'msg'=>$httpcode);
    } else {
        return array('res'=>false,'msg'=>$httpcode);
    }
}
function add_url($url,$data = array()) {
	// SmallURL 2013
	// Last Revised 28/10/2013
	global $_SMALLURL,$db,$u;
	// First check if the URL is real.
	if (check_real($url)) {

		// Its real. Continue.
		$sql = array();
		if (isset($data['custom'])) {
			// Replace Spaces with dashes.
			$sql['short'] = str_replace(chr(32),"-",$data['custom']);
			$sql['custom'] = 1;
		} else {
			$short = gen_id();
			while (get_url($short) != false) {
				// Repeats till it generates an ID that dont exist
				$short = gen_id();
			}
			$sql['short'] = $short;
		}
		// Obtain required information.

		// Logged in?
		if ($_SMALLURL['LOGGEDIN']) {
			// Set the user to the UID.
			$sql['user'] = $_SMALLURL['UID'];
		} else {
			// Not Logged in, Public User.
			$sql['user'] = "0";
		}

		if(isset($data['API_KEY'])) {
			$v = db::get_array("api",array("key"=>$data['API_KEY']));
			if(isset($v[0]['user']) && is_numeric($v[0]['user'])) {
				$sql['user'] = $v[0]['user'];
			}
		}


		// Requesting to be a certain device?
		if (!isset($data['device'])) {
			if (is_mobile()) {
				$sql['device'] = 2;
			}
		} else {
			if ($data['device'] <= 4) {
				$sql['device'] = $data['device'];
			}
		}

		// Private URL?
		if (isset($data['private']) && $data['private']) {
			$sql['private'] = 1;
		} else {
			// Not set to private. But what about User Prefs?
			if ($u->core($sql['user'])->get('autopriv')) {
				$sql['private'] = 1;
			}
		}

		// Some Variables
		$sql['content'] = $url;
		$sql['ipaddr'] = $_SERVER['REMOTE_ADDR'];
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$sql['useragent'] = $_SERVER['HTTP_USER_AGENT'];
		}
		$sql['stamp'] = time();
		$sql['client_hash'] = $u->hash();
		$sql['type'] = 0;

		// Geo Location
		if ($sql['ipaddr'] != "127.0.0.1" && $sql['ipaddr'] != "localhost") {
			$geo = geo_locate($sql['ipaddr']);
			$sql['lat'] = $geo['lat'];
			$sql['lng'] = $geo['lng'];
			$sql['country'] = $geo['country'];
		} else {
			$sql['lat'] = "0 ";
			$sql['lng'] = "0";
			$sql['country'] = "N/A";
		}

		// Misc
		$shrt = get_short($url);

		// Check if the person wants it short.
		if ($shrt) {
			// It already exists, Return it C:!
			return array('res'=>true,'short'=>$shrt);

		} else if (is_loop($url,$shrt)) {
			// The URL points to it's self. STAHP THE REDIRECT!
			return array('res'=>false,'msg'=>'SmallURL\'s cannot redirect to themselves or others!');
		} else {
			// Add the Data
			$end = db::insert("entries",$sql);
			if ($end) {
				return array('res'=>true,'short'=>$sql['short']);
			} else {
				return array('res'=>false,'msg'=>'Error Shortening URL. Unknown! Report Error 500');
			}
		}

	} else {
		return array('res'=>false,'msg'=>'The URL you supplied doesn\'t seem to be valid! Did you paste it correctly?');
	}
}
function url_trim($url,$max = 50) {
	if (strlen($url) > $max) {
		// Trim
		$url = substr($url,0,$max-3)."...";
	}
	return $url;
}
// Misc Functions
function dateify($stamp = null) {
	$current = time();
	$differ = $current-$stamp;
	if ($differ < 86400 && $differ > 30) {
		// Below 24hrs.
		$mins = round($differ/60);
		if ($mins > 60) { $hours = round($mins/60); } else { $hours = "0"; }
		if ($hours > 1) { $word_h = "hours"; } else { $word_h = "hour"; }
		if ($mins > 1) { $word_m = "mins"; } else { $word_m = "min"; }
		if ($hours > 0) { $mins = $mins - ($hours * 60); }

		if ($hours <= 0) {
			$scentence = "{$mins} {$word_m} ago.";
		}
		else if ($mins <= 0) {
			$scentence = "{$hours} {$word_h} ago.";
		}
		else {
			$scentence = "{$hours} {$word_h} {$mins} {$word_m} ago.";
		}
		return $scentence;
	}
	else if ($differ <= 30) {
		// 30 Seconds ago.
		return $differ." seconds ago.";
	}
	else {
		return substr(date('F',$stamp),0,3).chr(32).date('j, Y',$stamp);
	}
}
function get_user($id) {
	if ($id == 0) {
		return "Public User";
	}
	else if ($id < 0) {
		global $db,$sql;
		$id = substr($id,1); // No actual Clue why this is like this.. But oh well.
		$name = db::get_array("api",array("id"=>$id));
		if (count($name) > 0) {
			return $name[0]['name'];
		}
		else {
			return "Unknown";
		}
	}
	else {
		// Derp.
		global $db,$sql;
		$name = db::get_array("users",array("id"=>$id));
		if (count($name) > 0) {
			return $name[0]['name'];
		}
		else {
			return "Unknown";
		}
	}
}
function get_info($id, $param) {
	if ($id == 0) {
		return "Public User";
	}
	else {
		// Derp.
		global $db,$sql;
		$name = db::get_array("users",array("id"=>$id));
		if (count($name) > 0) {
			return $name[0][$param];
		}
		else {
			return "Unknown";
		}
	}

}
function increase_use($id,$ref) {
	// Patch: 01/09/13 - Modified to collect stats
	global $db, $sql, $u;

	$ua = $_SERVER['HTTP_USER_AGENT'];
	$uip = $_SERVER['REMOTE_ADDR'];
	$clienthash = $u->hash();
	$stamp = time();

	// Check to see if this URL has been navigated to before
	$clicks = db::get_array("clicks",array("smallurl"=>$id,"clienthash"=>$clienthash));
	if (count($clicks) <= 0) {
		// Not been clicked before
		$geo = geo_locate($uip);
		db::insert("clicks",array("smallurl"=>$id,"useragent"=>$ua,"ip"=>$uip,"clienthash"=>$clienthash,"stamp"=>$stamp,"ref"=>$ref,"lat"=>$geo['lat'],"lng"=>$geo['lng'],"country"=>$geo['country']));
		// Done.
	}
	else {
		// Has been clicked before!
	}
}

function screen_url($url) {
	global $sql,$db;
	$matches = 0;
	$words = db::get_array("blacklist");
	$badwords = array();
	foreach ($words as $w) {
		$badwords[] = $w['phrase'];
	}
	// Check if its a real URL
	
	// Lets get headers
	$headers = url_get_headers($url);
	$proceed = false;
	if (isset($headers['Content-Type'])) {
		if (is_array($headers['Content-Type'])) {
			if ($headers['Content-Type'][0] == "text/html") {
				$proceed = true;
			}
		} else {
			if ($headers['Content-Type'] == "text/html") {
				$proceed = true;
			}
		}
	}
	if ($proceed) {
		$data = url_get_contents($url);

		$word_match = array();
		foreach ($badwords as $word) {
			if ($w['type'] == 0) {
				if (preg_match("/{$word}/i", $data)) {
					$matches++;
					$word_match[] = $word;
				}
			}
			else {
				// URL Filters
				if (preg_match("/{$word}/i", $url)) {
					$matches = 4; // Straight out Block.
					$word_match[] = $word;
				}
			}
		}

		$bad = false;

		if ($matches >= 4) {
			$bad = true;
		}
		return $bad;
	} else {
		return null;
	}
}
function is_image($fname) {
	// Core function to check if the destination URL is an image.
	// The Snapito API seems to bug up on Images.
	$ext = explode(".",$fname);
	$ext = $ext[count($ext)-1];

	// Just incase theres a ? on the image.
	$misc = explode("?",$ext);
	$ext = $misc[0];

	$image = false;
	$image_exts = array('PNG','BMP','GIF','JPG','JPEG');

	foreach ($image_exts as $check) {
		if (strtoupper($check) == strtoupper($ext)) {
			$image = true;
		}
	}
	return $image;
}
function active($current) {
	global $page;
	if (strtolower($page) === strtolower($current)) {
		echo 'class="active"';
	}
}
// Similar to mIRC
function gettok($delim,$string,$token) {
	$dat = explode($delim,$string);
	return $dat[$token];
}
function iif($a,$b,$c) {
	// Just like mIRC!
	if ($$a) {
		return $b;
	}
	else {
		return $c;
	}
}
function check_key($key) {
	global $db, $sql;
	$ret = array();
	$data = db::get_array("api",array("key"=>$key));
	if (count($data) <= 0) {
		$ret['res'] = false;
		$ret['msg'] = "The supplied key does not exist on the system. ERR KEY '{$key}' NOT FOUND.";
		return $ret;
		exit;
	}
	// data from the user.
	$user_ip = gethostbyname($_SERVER['REMOTE_ADDR']);
	$api_ip = gethostbyname($data[0]['domain']);
	if ($user_ip != $api_ip) {
		$ret['res'] = false;
		$ret['msg'] = "Domain name on record does match the one using SmallURL. Your IP: {$user_ip}";
		return $ret;
		exit;
	}
	if ($key != $data[0]['key']) {
		$ret['res'] = false;
		$ret['msg'] = "Supplied Key does not match the user key.";
		return $ret;
		exit;
	}
	$ret['res'] = true;
	$ret['msg'] = "Key and Domain match.";
	return $ret;
}
function got_http($url) {
	$cut = strtolower(substr($url,0,7));
	if ($cut == "http://" || $cut == "https:/") {
		$url = $url;
	}
	else {
		$url = "http://".$url;
	}
	return $url;
}
function UpdateInfo($id, $name) {
	global $sql,$db;
	$name = htmlentities($name);
	if(isset($id) && isset($name) && $name != '') {
		db::update("users",array("name"=>$name),array("id"=>$id));
		return true;
	}
}
function UpdatePassword($id, $currentpassword, $newpassword, $newpassword1) {
	global $db, $sql;
	if(isset($currentpassword) && $currentpassword != '' && isset($newpassword)  && $newpassword != '' && isset($newpassword1)  && $newpassword1 != '') {
		if(check_login(get_info($id, "email"), $currentpassword) != false) {
			if($newpassword == $newpassword1) {
				$password =  salt_password($newpassword);
				db::update("users",array("password"=>$password),array("id"=>$id));
				return 1;
			} else {
				return 4;
			}
		} else {
			return 3;
		}
	} else {
		return 2;
	}
}
function getTimeline($count, $username) {
	$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name='.$username.'&count='.$count;
	$tweets = json_decode(file_get_contents($url),TRUE);

	return $tweets;
}

// API Keys functions.
function add_key($name,$domain) {
	global $db,$sql;
	if (strtolower($name) == "press z or r twice") {
		$ret = array();
		$ret['res'] = false;
		$ret['msg'] = "DO A BARREL ROLL.";
		$ret['roll'] = true;
		return $ret;
		exit;
	}
	$uid = $_SESSION['uid'];
	$keys = db::get_array("api",array("user"=>$uid));
	if (count($keys) >= 5) {
		$ret = array();
		$ret['res'] = false;
		$ret['msg'] = "You have hit your API Key Limit.";
		return $ret;
		exit;
	}
	$key = md5($name.$domain.gen_id());
	db::insert("api",array("user"=>$uid,"name"=>$name,"domain"=>$domain,"key"=>$key));
	$ret = array();
	$ret['res'] = true;
	return $ret;
}
function del_key($key_id) {
	global $db,$sql;
	$uid = $_SESSION['uid'];
	// Find if a key owned by this user has that ID, No key means it dont exist or belongs to someone else.
	$keys = db::get_array("api",array("id"=>$key_id,"user"=>$uid));
	if (count($keys) == 0) {
		// Dont exist or aint yours!
		$ret = array('res'=>false,'msg'=>'API Key doesn\'t exist or its not yours to remove!');
	}
	else {
		// Whoa, we got results. We own it! PEW PEW VROOOOM!
		db::delete("api",array("id"=>$key_id,"user"=>$uid));
		$ret = array('res'=>true,'msg'=>'Key removed ^~^!'); // Little easter egg!
	}
	return $ret;
}


// Domains Functions (OBSELETE)
function add_domain($name,$domain = false) {
	global $db,$sql;
	$uid = $_SESSION['uid'];

	$disallowed = array('domains','root','screenshot','status');

	if (in_array(strtolower($name),$disallowed)) {
			$ret = array();
			$ret['res'] = false;
			$ret['msg'] = "The chosen page name is disallowed!";
			return $ret;
			exit;
	}

	$doms = db::array_query("pages",array("name"=>$name));
	if (count($doms) > 0) {
		$ret = array();
		$ret['res'] = false;
		$ret['msg'] = "A Domain page with that name already exists!";
		return $ret;
		exit;
	}


	$keys = db::array_query("pages",array("user"=>$uid));
	if (count($keys) >= 3) {
		$ret = array();
		$ret['res'] = false;
		$ret['msg'] = "You have hit your domain Limit.";
		return $ret;
		exit;
	}
	if ($domain) {
		db::insert("pages",array("user"=>$uid,"name"=>$name,"domain"=>$domain));
	} else {
		db::insert("pages",array("user"=>$uid,"name"=>$name));
	}
	$ret = array();
	$ret['res'] = true;
	return $ret;
}
function del_domain($domain_id) {
	global $db,$sql;
	$uid = $_SESSION['uid'];
	// Find if a key owned by this user has that ID, No key means it dont exist or belongs to someone else.
	$keys = db::get_array("pages",array("id"=>$domain_id,"user"=>$uid));
	if (count($keys) == 0) {
		// Dont exist or aint yours!
		$ret = array('res'=>false,'msg'=>'Domain page doesn\'t exist or its not yours to remove!');
	}
	else {
		// Whoa, we got results. We own it!
		db::delete("pages",array("id"=>$domain_id,"user"=>$uid));
		$ret = array('res'=>true,'msg'=>'Doamin page removed!');
	}
	return $ret;
}


// Register Function
function adduser($username, $email, $password) {
    global $sql,$db;
    $username = mysql_real_escape_String($username);
    $email = mysql_real_escape_String($email);
    $password = mysql_real_escape_String($password);
	if(filter_var($email, FILTER_VALIDATE_EMAIL) == true) {
	   $users = db::get_array("users",array("email"=>$email));
        if(count($users) == 0) {
        	$privkey = md5($email.time().range(0,999));
        	$salt = salt_password($password);
        	db::bool_query("INSERT INTO `smallurl`.`{$sql['prefix']}_users` (`id`, `name`, `email`, `password`, `admin`, `verified`, `verifykey`, `passreset`, `passresetkey`,`regstamp`) VALUES (NULL, '{$username}', '{$email}', '{$salt}', '0', '0', '{$privkey}', '', '','".time()."');");
        	do_email($email, $username, "Email Validation","To begin using your SmallURL account, you need your email verifying. <br /> Please visit <a href='http://smallurl.in/?p=verifyemail&email={$email}&verifykey={$privkey}'> http://smallurl.in/?p=verifyemail&email={$email}&verifykey={$privkey} </a> to validate your account.");
        	return true;
    	} else {
        	return false;
    	}
	} else {
		return false;
	}
}
function CheckEmailValidation($email, $pkey) {
    global $sql,$db;
    $users = db::get_array("users",array("email"=>$email,"verifykey"=>$pkey));
    if(count($users) == 1) {
        db::update("users",array("verified"=>"1","verifykey"=>""),array("email"=>$email));
        return true;
    } else {
        return false;
    }
}
// Youtube Magic
function is_youtube($url) {
	$is = false;
	if (preg_match("/(youtube.com|youtu.be|youtube.co.uk)/i", $url)) {
		$is = true;
	}
	/*
	if (preg_match("/youtu.be/i", $url)) {
		$is = true;
	}
	*/
	if ($is) {
		parse_str(parse_url($url,PHP_URL_QUERY),$vars);
		if (isset($vars['v'])) {
			return $vars['v'];
		} else {
			return false;
		}
	}
	else {
		return false;
	}
}
function yt_get($id) {
	$feed = url_get_contents("http://gdata.youtube.com/feeds/api/videos/{$id}?alt=rss&v=1");
	if ($feed = "Private video") {
		return array("res"=>false,"msg"=>$feed);
	} else if ($feed != "") {
		$dom = new DOMDocument;
		$dom->loadXML($feed);
		if (!$dom) {
			echo 'Error while parsing the document';
			exit;
		}
		$xml = simplexml_import_dom($dom);
		if (!$xml) {
			echo 'Error while importing the document:'.$feed.':';
			exit;
		}
		$rdata = array();
		$rdata['res'] = true;
		$rdata['title'] = $xml->title;
		$rdata['id'] = $id;
		$rdata['link'] = $xml->link;
		$rdata['author'] = $xml->author;
		$rdata['desc'] = $xml->description;
		$rdata['thumb'] = "http://i.ytimg.com/vi/{$id}/0.jpg";
		return $rdata;
	} else {
		return false;
	}
}
function youtube_id_from_url($url) {
    $pattern =
        '%^# Match any YouTube URL
        (?:https?://)?  # Optional scheme. Either http or https
        (?:www\.)?      # Optional www subdomain
        (?:             # Group host alternatives
          youtu\.be/    # Either youtu.be,
        |youtube(?:-nocookie)?\.com  # or youtube.com and youtube-nocookie
          (?:           # Group path alternatives
            /embed/     # Either /embed/
          | /v/         # or /v/
          | /watch\?v=  # or /watch\?v=
          )             # End path alternatives.
        )               # End host alternatives.
        ([\w-]{10,12})  # Allow 10-12 for 11 char YouTube id.
        %x'
        ;
    $result = preg_match($pattern, $url, $matches);
    if (false !== $result) {
        return $matches[1];
    }
    return $url;
}
// [RED] Now resides in the user class. DEPRECATED.
function get_via_hash() {
	global $sql,$db,$u;
	$hash = $u->hash();
	$urls = db::get_array("entries",array("client_hash"=>$hash));
	return $urls;
}
function is_mobile() {
	if (isset($_SERVER['HTTP_USER_AGENT'])) {
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$is = false;
		if (preg_match("/Android/i",$agent)) {
			$is = true;
		}
		if (preg_match("/Blackberry/i",$agent)) {
			$is = true;
		}
		if (preg_match("/SymbianOS/i",$agent)) {
			$is = true;
		}
		if (preg_match("/MIDP/i",$agent)) {
			$is = true; // Old ass browser much?
		}
		if (preg_match("/Opera Mini/i",$agent)) {
			$is = true;
		}
		if (preg_match("/iPad/i",$agent)) {
			$is = true;
		}
		if (preg_match("/iPhone/i",$agent)) {
			$is = true;
		}
		if (preg_match("/iPod/i",$agent)) {
			$is = true;
		}
		return $is;
	} else {
		return false;
	}
}
function is_loop($url,$custom = false) {
	// NEEDS REWRITING! [RED]
	$url = strtolower($url);
	$custom = strtolower($custom);
	
	$match = preg_match("/http[s]?:\/\/(smallurl.in|surl.im)\/([A-z0-9]+)/i",$url,$matches);
	// Check if the URL is a SmallURL!
	if ($match) {
		$dest = $matches[2];
		if (strtolower($dest) === strtolower($custom)) {
			// Loop back! NUH!
			return true;
		} else {
			// Find if the URL exists.
			if (get_url($dest)) {
				// Destination exists! DENY!
				return true;
			}
		}
	} else {
		// Continue.
		return false;
	}
}

function do_email($to,$name,$subject,$message) {
	// NEEDS CLEANING UP [RED]
    $msg_body = implode("<br/>",explode("\n",$message));
    $subject = 'SmallURL - '.$subject;
    $body = '
<style>
body {
	font-family:"Arial";
}
</style>
<table>
<tr>
<td>
<img width="64px" src="http://i.imgur.com/LETACuB.png"/>
</td>
<td>
<h1>SmallURL</h1>
</td>
</tr>
</table>
<hr color="#11111"></hr>
<p>Hi there '.$name.',</p>
<p>'.$msg_body.'</p>
<p/>
Thanks,
<br/>
The SmallURL Team
<hr color="#111111"></hr>
<center>&copy; SmallURL 2013 - <i>Smallifying URL\'s with safety and precaution in mind.</i></center>';
    mail($to, $subject, $body,"From: SmallURL <noreply@smallurl.in>\r\nContent-type: text/html; charset=iso-8859-1");
}
function numeric_string($check,$for,$text = true) {
	if ($check === $for) {
		return $text;
	}
	else {
		return true;
	}
}
function show_alert($text) {
	eval('$text = "'.$text.'";');
	$mytext = str_replace("\n","<br/>",$text);
	echo "<div class=\"alert alert-dismissable alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>".$mytext."</div>";
}
// Needs pulling up to date [RED]
function has_param($index,$type = "get") {
	$type = strtolower($type);
	if ($type == "get") {
		if (isset($_GET[$index]) && $_GET[$index] != "") {
			return true;
		}
		else {
			return false;
		}
	}
	else if ($type == "post") {
		if (isset($_POST[$index]) && $_POST[$index] != "") {
			return true;
		}
		else {
			return false;
		}
	}
}
function get_param($index,$type = "get") {
	global $_SMALLURL;
	$type = strtolower($type);
	if ($type == "get") {
		if (has_param($index,$type)) {
			return $_SMALLURL['SQL']->real_escape_string(htmlentities($_GET[$index]));
		}
		else {
			return false;
		}
	}
	else if ($type == "post") {
		if (has_param($index,$type)) {
			return $_SMALLURL['SQL']->real_escape_string(htmlentities($_POST[$index]));
		}
		else {
			return false;
		}
	}
}
function url_uses($id) {
	global $db,$sql;
	$uses = db::get_array("entries",array("id"=>$id));
	$clicks = db::get_array("clicks",array("smallurl"=>$id,"useragent"=>array("comp"=>"NOT LIKE","val"=>"%BOT%")),"COUNT(*)");
	if ($clicks[0]['COUNT(*)'] <=0) {
		return $uses[0]['uses'];
	}
	else {
		$res = $clicks[0]['COUNT(*)'] + $uses[0]['uses'];
		return $res;
	}
}
function data_count() {
	return click_count()+url_count_total();
}
function click_count() {
	global $db,$sql;
	$clicks = db::get_array("clicks",array("useragent"=>array("comp"=>"NOT LIKE","val"=>"%BOT%")),"count(*)");
	return $clicks[0]['count(*)'];
}
function geo_url_count() {
	$urls = db::get_array("entries",array("country"=>array("comp"=>"!=","val"=>"")),"count(*)");
	return $urls[0]['count(*)'];
}
function array_to_list($array) {
	$char = "[";
	$dat = array();
	foreach ($array as $key => $val) {
		$tmp = "[";
		if (is_bool($key)) {
			if ($key) {
				$tmp .= "1";
			} else {
				$tmp .= "0";
			}
		} else if (is_string($key)) {
			$tmp .= '"'.$key.'"';
		} else if (is_numeric($key)) {
			$tmp .= "{$key}";
		}
		$tmp .= ",";
		if (is_bool($val)) {
			if ($val) {
				$tmp .= "1";
			} else {
				$tmp .= "0";
			}
		} else if (is_string($val)) {
			$tmp .= '"'.$val.'"';
		} else if (is_numeric($val)) {
			$tmp .= "{$val}";
		}
		$tmp .= "]";
		$dat[] = $tmp;
	}
	$char .= implode(",",$dat);
	$char .= "]";
	return $char;
}
function gen_geomap() {
	$clicks = gen_click_heatmap();
	$urls = gen_url_heatmap();
	$data = array_merge($clicks,$urls);
	
	$geo = array("Country"=>"Clicks");
	
	foreach ($data as $ck) {
		if ($ck['lat'] != "0" && $ck['lng'] != "0") {
			if (isset($ck['country']) && $ck['country'] != "") {
				if (isset($geo[$ck['country']])) {
					$geo[$ck['country']] += 1;
				} else {
					$geo[$ck['country']] = 1;
				}
			}
		}
	}
	return $geo;
}
function browser_name($string) {
	$browser = parse_user_agent($string);
	if ($browser['browser'] == "") {
		return array("name"=>"N/A","version"=>"N/A","os"=>"N/A");
	}
	else {
		return array("name"=>$browser['browser'],"version"=>$browser['version'],"os"=>$browser['platform']);
		//return $browser['browser']." v".$browser['version']." [".$browser['platform']."]";
	}
}


/**
 * Parses a user agent string into its important parts
 *
 * @author Jesse G. Donat <donatj@gmail.com>
 * @link https://github.com/donatj/PhpUserAgent
 * @link http://donatstudios.com/PHP-Parser-HTTP_USER_AGENT
 * @param string $u_agent
 * @return array an array with browser, version and platform keys
 */
function parse_user_agent( $u_agent = null ) {
    if(is_null($u_agent) && isset($_SERVER['HTTP_USER_AGENT'])) $u_agent = $_SERVER['HTTP_USER_AGENT'];

    $empty = array(
        'platform' => null,
        'browser'  => null,
        'version'  => null,
    );

    $data = $empty;

    if(!$u_agent) return $data;

    if( preg_match('/\((.*?)\)/im', $u_agent, $parent_matches) ) {

        preg_match_all('/(?P<platform>Android|CrOS|iPhone|iPad|Linux|Macintosh|Windows(\ Phone\ OS)?|Silk|linux-gnu|BlackBerry|PlayBook|Nintendo\ (WiiU?|3DS)|Xbox)
            (?:\ [^;]*)?
            (?:;|$)/imx', $parent_matches[1], $result, PREG_PATTERN_ORDER);

        $priority = array('Android', 'Xbox');
        $result['platform'] = array_unique($result['platform']);
        if( count($result['platform']) > 1 ) {
            if( $keys = array_intersect($priority, $result['platform']) ) {
                $data['platform'] = reset($keys);
            }else{
                $data['platform'] = $result['platform'][0];
            }
        }elseif(isset($result['platform'][0])){
            $data['platform'] = $result['platform'][0];
        }
    }

    if( $data['platform'] == 'linux-gnu' ) { $data['platform'] = 'Linux'; }
    if( $data['platform'] == 'CrOS' ) { $data['platform'] = 'Chrome OS'; }

    preg_match_all('%(?P<browser>Camino|Kindle(\ Fire\ Build)?|Firefox|Safari|MSIE|AppleWebKit|Chrome|IEMobile|Opera|OPR|Silk|Lynx|Version|Wget|curl|NintendoBrowser|PLAYSTATION\ (\d|Vita)+)
            (?:;?)
            (?:(?:[/ ])(?P<version>[0-9A-Z.]+)|/(?:[A-Z]*))%ix',
    $u_agent, $result, PREG_PATTERN_ORDER);

    $key = 0;

    // If nothing matched, return null (to avoid undefined index errors)
    if (!isset($result['browser'][0]) || !isset($result['version'][0])) {
        return $empty;
    }

    $data['browser'] = $result['browser'][0];
    $data['version'] = $result['version'][0];

    if( $key = array_search( 'Playstation Vita', $result['browser'] ) !== false ) {
        $data['platform'] = 'PlayStation Vita';
        $data['browser'] = 'Browser';
    }elseif( ($key = array_search( 'Kindle Fire Build', $result['browser'] )) !== false || ($key = array_search( 'Silk', $result['browser'] )) !== false ) {
        $data['browser']  = $result['browser'][$key] == 'Silk' ? 'Silk' : 'Kindle';
        $data['platform'] = 'Kindle Fire';
        if( !($data['version'] = $result['version'][$key]) || !is_numeric($data['version'][0]) ) {
            $data['version'] = $result['version'][array_search( 'Version', $result['browser'] )];
        }
    }elseif( ($key = array_search( 'NintendoBrowser', $result['browser'] )) !== false || $data['platform'] == 'Nintendo 3DS' ) {
        $data['browser']  = 'NintendoBrowser';
        $data['version']  = $result['version'][$key];
    }elseif( ($key = array_search( 'Kindle', $result['browser'] )) !== false ) {
        $data['browser']  = $result['browser'][$key];
        $data['platform'] = 'Kindle';
        $data['version']  = $result['version'][$key];
    }elseif( ($key = array_search( 'OPR', $result['browser'] )) !== false || ($key = array_search( 'Opera', $result['browser'] )) !== false ) {
        $data['browser'] = 'Opera';
        $data['version'] = $result['version'][$key];
        if( ($key = array_search( 'Version', $result['browser'] )) !== false ) { $data['version'] = $result['version'][$key]; }
    }elseif( $result['browser'][0] == 'AppleWebKit' ) {
        if( ( $data['platform'] == 'Android' && !($key = 0) ) || $key = array_search( 'Chrome', $result['browser'] ) ) {
            $data['browser'] = 'Chrome';
            if( ($vkey = array_search( 'Version', $result['browser'] )) !== false ) { $key = $vkey; }
        }elseif( $data['platform'] == 'BlackBerry' || $data['platform'] == 'PlayBook' ) {
            $data['browser'] = 'BlackBerry Browser';
            if( ($vkey = array_search( 'Version', $result['browser'] )) !== false ) { $key = $vkey; }
        }elseif( $key = array_search( 'Safari', $result['browser'] ) ) {
            $data['browser'] = 'Safari';
            if( ($vkey = array_search( 'Version', $result['browser'] )) !== false ) { $key = $vkey; }
        }

        $data['version'] = $result['version'][$key];
    }elseif( $result['browser'][0] == 'MSIE' ){
        if( $key = array_search( 'IEMobile', $result['browser'] ) ) {
            $data['browser'] = 'IEMobile';
        }else{
            $data['browser'] = 'MSIE';
            $key = 0;
        }
        $data['version'] = $result['version'][$key];
    }elseif( $key = array_search( 'PLAYSTATION 3', $result['browser'] ) !== false ) {
        $data['platform'] = 'PlayStation 3';
        $data['browser']  = 'NetFront';
    }

    return $data;

}
// Replaces get_wot and returns an array of data instead of HTML.
// NEEDS 24HR CACHING [RED]
function weboftrust($url) {
	global $_SMALLURL;
	$url = urlencode($url);

	// Need's replacing with daily caching.
	$json = file_get_contents("http://api.mywot.com/0.4/public_link_json2?hosts={$url}&key=fe604a2f6975757a606ecb4d0a0e19b25ec29dfa");
	$data = json_decode($json,true);
	$domain = key($data);

	$out = array();
	// Set the fallback
	$out['text'] = "Unknown";
	$out['image'] = "wot_unknown.png";
	$out['scorecard'] = "http://www.mywot.com/en/scorecard/".$url;

	if (count($data) > 0) {
		if (isset($data[$domain][0])) {
			// Get the trust level.
			$trust = $data[$domain][0][0];
			$out['domain'] = $domain;
			$out['scorecard'] = "http://www.mywot.com/en/scorecard/".$domain;

			// Now check the levels.
			if ($trust >= 0 && $trust < 20) {
				// Very Poor
				$out['text'] = "Very Poor";
				$out['image'] = "wot_verypoor.png";
			} else if ($trust >= 20 && $trust < 40) {
				// Poor
				$out['text'] = "Poor";
				$out['image'] = "wot_poor.png";
			} else if ($trust >= 40 && $trust < 60) {
				// Unsatisfactory
				$out['text'] = "Unsatisfactory";
				$out['image'] = "wot_unsatisfactory.png";
			} else if ($trust >= 60 && $trust < 80) {
				// Good.
				$out['text'] = "Good";
				$out['image'] = "wot_good.png";
			} else if ($trust >= 80) {
				// Excellent
				$out['text'] = "Excellent";
				$out['image'] = "wot_excellent.png";
			} else {
				// Unknown Level.
				$out['text'] = "Unknown";
				$out['image'] = "wot_unknown.png";
			}
		}
	}

	return $out;
}

function trending_urls() {
	global $db, $sql;
	// Get's all URLs and clicks this week
	$all = array();
	$data = array();

	$week = 604800;
	$time = time()-$week;

	$clicks = db::get_array("clicks",array("useragent"=>array("suspended"=>false,"comp"=>"NOT LIKE","val"=>"%BOT%"),"stamp"=>array("comp"=>">","val"=>$time)));
	$url_clicks = array();

	foreach ($clicks as $click) {
		if (!isset($url_clicks[$click['smallurl']])) {
			$url_clicks[$click['smallurl']] = 1;
		} else {
			$url_clicks[$click['smallurl']] = $url_clicks[$click['smallurl']] + 1;
		}
	}

	$urls = array();
	foreach ($url_clicks as $uid => $uses) {
		$udat = db::get_array("entries",array("id"=>$uid));
		if (count($udat) == 1) {
			$urls[] = $udat[0];
		}
	}
	arsort($urls);
	$urls = array_slice($urls, 0, 10, true);

	return $urls;
}

function click_check($id) {
	global $sql,$db;
	// Checks if a URL has more clicks than it should have in 24hrs.
	$boundry = time() - 7200; // Past two hours.
	$max = 500;
	$clicks = db::get_array("clicks",array("smallurl"=>$id,"stamp"=>array("comp"=>">=","val"=>$boundry)));
	if (count($clicks) > $max) {
		// Too many clicks, Insert a warning.
		db::update("entries",array("flagged"=>"1"),array("id"=>$id));
	}
}
function url_get_contents($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
// Headers! :D
function url_get_headers($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL,            $url);
	curl_setopt($ch, CURLOPT_HEADER,         true);
	curl_setopt($ch, CURLOPT_NOBODY,         true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT,        $timeout);
	$r = curl_exec($ch);
	curl_close($ch);
	
	// Parse headers. :<
	$headers = array();
	$lines = explode("\r\n",$r);
	foreach ($lines as $l) {
		$tmp = explode(":",$l);
		if (count($tmp) > 1) {
			$headers[$tmp[0]] = substr(implode(":",array_slice($tmp,1)),1);
			$val = substr(implode(":",array_slice($tmp,1)),1);
			$tmpd = explode(";",$val);
			if (count($tmpd) > 1) {
				$headers[$tmp[0]] = $tmpd;
			}
		}
	}
	
	return $headers;
}

// BOTH DO THE SAME [RED]
function do_result($type,$msg) {
	$res = array("res"=>$type,"msg"=>$msg);
	return $res;
}
function end_json($res,$msg) {
	die(json_encode(array("res"=>$res,"msg"=>$msg)));
}
function geo_locate($ip) {
	global $db;
	$geo = db::get_array("geo_cache",array("ip"=>$ip));
	if (count($geo) >= 1) {
		// Return Cache.
		$geo[0]['cache'] = true;
		return $geo[0];
	} else {
		// Cache.
		$new = json_decode(file_get_contents("http://geo.thomas-edwards.me/{$ip}"),true);
		$sql_stack = array();
		$sql_stack['ip'] = $ip;
		$sql_stack['lat'] = $new['latitude'];
		$sql_stack['lng'] = $new['longitude'];
		$sql_stack['country'] = $new['country'];
		if (is_array($new['timezone'])) {
			$sql_stack['timezone'] = $new['timezone'][0][0];
		} else {
			$sql_stack['timezone'] = $new['timezone'];
		}
		if ($ip != "127.0.0.1" && $ip != "localhost") {
			db::insert("geo_cache",$sql_stack);
		}
		$sql_stack['cache'] = false;
		return $sql_stack;
	}
}
// NEEDS REMOVING AND NEEDS TO USE user_extra [RED]
function haspriv($node, $uid = "are") {
    global $db, $sql, $_SMALLURL;
    if($uid == "are") {
        $uid = $_SMALLURL['UID'];
    }

    $data = db::get_array("access",array("uid"=>$uid));

    if(isset($data[0][$node]) && $data[0][$node] == '1') {
        return true;
    } else {
        return false;
    }
}
?>