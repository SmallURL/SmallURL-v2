<?php
// SmallURLs Library of tools for Googles Authentication.
class google {
	public static function auth_url() {
		// Returns an authentication URL for the user.
		global $_SMALLURL;
		
		if (!isset($_SESSION['google_state'])) {
			$state = md5(rand());
			$_SESSION['google_state'] = $state;
		} else {
			$state = $_SESSION["google_state"];
		}
		
		$url = "https://accounts.google.com/o/oauth2/auth?client_id={$_SMALLURL['config']['google_client_id']}&response_type=code&scope=openid%20email%20profile&redirect_uri=".urlencode($_SMALLURL['config']['google_redirect_uri'])."&state={$state}";
		
		/*
		if ($uid && $u->exists($uid)) {
			$email = $u->core($uid)->get("email");
			$url .= "&login_hint={$email}";
		}
		*/
		
		return $url;
	}
	public static function auth_button() {
		$url = google::auth_url();
		return "<a style='margin-left:70px;' href='{$url}'><img width='200px' src='https://developers.google.com/accounts/images/sign-in-with-google.png'/></a>";
	}
	public static function linked($uid = false) {
		// Has the user linked their google account?
		global $u;
		if ($u->core($uid)->get("google_id") == null) {
			return false;
		} else {
			return true;
		}
	}
	public static function in_progress() {
		// Is auth in progress?
		if (isset($_SESSION['google_state'])) {
			return $_SESSION['google_state'];
		} else {
			return false;
		}
	}
	public static function retrieve_token($code) {
		// Get the Access Token
		global $_SMALLURL;
		
		$url = 'https://accounts.google.com/o/oauth2/token';
		$fields = array(
			'code' => $code,
			'client_id' => $_SMALLURL['config']['google_client_id'],
			'client_secret' => $_SMALLURL['config']['google_client_secret'],
			'redirect_uri' => urlencode($_SMALLURL['config']['google_redirect_uri']),
			'grant_type' => 'authorization_code'
		);

		//url-ify the data for the POST
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);
		$data = json_decode($result,true);
		
		if (!isset($data['error'])) {
			return $data['access_token'];
		} else {
			return false;
		}
	}
	public static function get_userinfo($token) {
		$url = 'https://www.googleapis.com/userinfo/v2/me';

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));

		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);
		return json_decode($result,true);
	}
}
?>