<?php
$api->addAction('shorten','action_shorten');
$api->addAction('check','action_check_key');
$api->addAction('notifications','action_notifications');
$api->addAction('inspect','action_inspect');
$api->addAction('what','action_what');

function action_shorten($api) {
	$api->expected(array("url","custom","private","device"));

	if (!isset($api->vars['url']) || $api->vars['url'] == "") {
		$ret = array('res'=>false,'msg'=>'The URL was not supplied.');
		return $ret;
		exit;
	}

	$pay = array();
	if ($api->vars['custom'] != null) {
		$pay['custom'] = get_param('custom');
	}
	if ($api->vars['private'] != null) {
		if (get_param('private') == "true") {
			$pay['private'] = true;
		}
	}
	if ($api->vars['device'] != null) {
		if (get_param('device') <= 4) {
			$pay['device'] = get_param('device');
		}
	}
	if(isset($api->vars['key'])) {
		$pay['API_KEY'] = $api->vars['key'];
	}

	$url = got_http($api->vars['url']);

	$api_ret = add_url($url,$pay);
	if ($api_ret['res']) {
		$api_ret['long'] = "http://surl.im/".$api_ret['short'];
		return $api_ret;
	}
	else {
		$ret = array('res'=>false,'msg'=>$api_ret['msg']);
		return $ret;
	}
}
function action_check_key($api) {
	$api->expected("key");

	if ($api->vars['key'] != null) {
		return check_key($api->vars['key']);
	} else {
		return array('res'=>false,'msg'=>"Missing KEY Parameter!");
	}
}
function action_notifications($api) {
	// Notification system.
	$notifications = array();
	// Add notifications to this array in the same format as the core notification.

	// Core notification, Every user recieves this.
	$core = array();
	$core['id'] = "core_5"; // Please change this when theres a new notifcation. It's fine unedited when theres a spelling edit,
	$core['msg'] = "Happy New Year! Welcome to 2014! Many greetings from the team!";
	$core['title'] = "SmallURL v2.0 (2014)";
	$core['timeout'] = 10000; // Set to false to ignore. This is how long the notification stays for before closing.
	$notifications[] = $core; // Add it

	// Dont edit below here.
	$data = array();
	$data['res'] = true;
	$data['notifications'] = $notifications;
	return $data;
}

function action_inspect($api) {
	$api->expected(array("short","key"));

	if ($api->vars['key'] == null) {
		$check = check_key($api->vars['key']);
		if (!$check['res']) {
			// Bad Key.
			$api->end(array('res'=>false,'msg'=>$check['msg']));
		}
	}

	if ($api->vars['short'] == null) {
		$api->end(array('res'=>false,'msg'=>'The SmallURL ID was not supplied.'));
	}
	// We can relax.
	$short = htmlentities($_GET['short']);

	global $db,$sql;
	$sql_data = db::get_array("entries",array("short"=>$short));
	if (count($sql_data) <= 0) {
		$url = false;
	}
	else {
		$sql_data = $sql_data[0];
		$url = $sql_data['content'];
	}
	if ($url != false) {
		$data = array();
		$data['res'] = true;
		$data['uses'] = url_uses($sql_data['id']);
		$data['short'] = $short;
		$data['url'] = $url;
		$data['date'] = $sql_data['stamp'];
		$data['nice_date'] = dateify($sql_data['stamp']);

		// If video!
		$yt_id = is_youtube($url);
		if ($yt_id) {
			// Youtube Stuffs.
			$vd = yt_get($yt_id);
			$data['video'] = true;
			$data['youtube-title'] = $vd['title'];
			$data['youtube-author'] = $vd['author'];
		}
		else {
			$data['video'] = false;
			$data['youtube'] = false;
		}

		if ($sql_data['custom'] == "1") {
			$data['custom'] = "true";
		}
		else {
			$data['custom'] = "false";
		}
		if ($sql_data['private'] == "1") {
			$data['private'] = "true";
		}
		else {
			$data['private'] = "false";
		}
		if ($sql_data['user'] == "0") {
			$data['user'] = "Public User";
		}
		else {
			$data['user'] = get_user($sql_data['user']);
		}
	}
	else {
		$data = array();
		$data['res'] = false;
		$data['msg'] = "The supplied SmallURL does not exist on the system.";
	}
	return $data;
}
function action_what($api) {
	$api->expected("is");
	if ($api->vars['is'] === "love") {
		return array("res"=>true,"msg"=>base64_encode(urldecode("What%20is%20love%20%0AOh%20baby%2C%20don%27t%20hurt%20me%20%0ADon%27t%20hurt%20me%20no%20more%20%0AOh%2C%20baby%20don%27t%20hurt%20me%20%0ADon%27t%20hurt%20me%20no%20more%20%0A%0AWhat%20is%20love%20%0AYeah%20%0A%0AOh%2C%20I%20don%27t%20know%20why%20you%27re%20not%20there%20%0AI%20give%20you%20my%20love%2C%20but%20you%20don%27t%20care%20%0ASo%20what%20is%20right%20and%20what%20is%20wrong%20%0AGimme%20a%20sign%20%0A%0AWhat%20is%20love%20%0AOh%20baby%2C%20don%27t%20hurt%20me%20%0ADon%27t%20hurt%20me%20no%20more%20%0AWhat%20is%20love%20%0AOh%20baby%2C%20don%27t%20hurt%20me%20%0ADon%27t%20hurt%20me%20no%20more%20%0A%0AWhoa%20whoa%20whoa%2C%20oooh%20oooh%20%0AWhoa%20whoa%20whoa%2C%20oooh%20oooh%20%0A%0AOh%2C%20I%20don%27t%20know%2C%20what%20can%20I%20do%20%0AWhat%20else%20can%20I%20say%2C%20it%27s%20up%20to%20you%20%0AI%20know%20we%27re%20one%2C%20just%20me%20and%20you%20%0AI%20can%27t%20go%20on%20%0A%0AWhat%20is%20love%20%0AOh%20baby%2C%20don%27t%20hurt%20me%20%0ADon%27t%20hurt%20me%20no%20more%20%0AWhat%20is%20love%20%0AOh%20baby%2C%20don%27t%20hurt%20me%20%0ADon%27t%20hurt%20me%20no%20more%20%0A%0AWhoa%20whoa%20whoa%2C%20oooh%20oooh%20%0AWhoa%20whoa%20whoa%2C%20oooh%20oooh%20%0A%0AWhat%20is%20love%2C%20oooh%2C%20oooh%2C%20oooh%20%0AWhat%20is%20love%2C%20oooh%2C%20oooh%2C%20oooh%20%0A%0AWhat%20is%20love%20%0AOh%20baby%2C%20don%27t%20hurt%20me%20%0ADon%27t%20hurt%20me%20no%20more%20%0A%0ADon%27t%20hurt%20me%20%0ADon%27t%20hurt%20me%20%0A%0AI%20want%20no%20other%2C%20no%20other%20lover%20%0AThis%20is%20your%20life%2C%20our%20time%20%0AWhen%20we%20are%20together%2C%20I%20need%20you%20forever%20%0AIs%20it%20love%20%0A%0AWhat%20is%20love%20%0AOh%20baby%2C%20don%27t%20hurt%20me%20%0ADon%27t%20hurt%20me%20no%20more%20%0AWhat%20is%20love%20%0AOh%20baby%2C%20don%27t%20hurt%20me%20%0ADon%27t%20hurt%20me%20no%20more%20(oooh%2C%20oooh)%20%0A%0AWhat%20is%20love%20%0AOh%20baby%2C%20don%27t%20hurt%20me%20%0ADon%27t%20hurt%20me%20no%20more%20%0AWhat%20is%20love%20%0AOh%20baby%2C%20don%27t%20hurt%20me%20%0ADon%27t%20hurt%20me%20no%20more%20(oooh%2C%20oooh)%20%0A%0AWhat%20is%20love%3F")),"extra"=>base64_encode("Haddaway - What Is Love"));
	} else {
		return array("res"=>false,"msg"=>"Unknown ACTION, Please check your spelling and try again!");
	}
}
?>