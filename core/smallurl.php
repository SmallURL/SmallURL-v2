<?php
// SmallURL Primary Core File.
// POINTLESS UPDATE BITCH
// Set timezone
date_default_timezone_set("Europe/London");

define("SURL_VERSION", "2.4.0");

if(isset($_SERVER['HTTP_X_REAL_IP'])) {
    $_SERVER['REMOTE_HOST'] = $_SERVER['HTTP_X_REAL_IP'];
    $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_REAL_IP'];
}

// Load our core class.
require_once($_SERVER['DOCUMENT_ROOT']."/../core/classes/core.class.php");

// Load our config class.
require_once($_SERVER['DOCUMENT_ROOT']."/../core/classes/config.class.php");

// Load our config
/* Load the configuration File */
config::load();

// Sentry.
include($_SERVER['DOCUMENT_ROOT']."/../core/lib/Raven/Autoloader.php");
Raven_Autoloader::register();
$client = new Raven_Client('');
$error_handler = new Raven_ErrorHandler($client);

// Register error handler callbacks
set_error_handler(array($error_handler, 'handleError'));
set_exception_handler(array($error_handler, 'handleException'));


/*

// Load RollBar
include($_SERVER['DOCUMENT_ROOT']."/../core/rollbar.php");
$rollbar = array(
    // required
    'access_token' => 'b28dc8866d274bc2ac6d94a66b76c825',
    // optional - environment name. any string will do.
    'environment' => 'Production',
    // optional - path to directory your code is in. used for linking stack traces.
    'root' => '/Users/brian/www/myapp'
);
if (config::get("debug")) {
	$rollbar['environment'] = "Development";
}
Rollbar::init($rollbar);
*/

// Load PREDIS
require_once($_SERVER['DOCUMENT_ROOT']."/../core/lib/predis/Autoloader.php");

// Load our PREDIS Wrapper
require_once($_SERVER['DOCUMENT_ROOT']."/../core/classes/xredis.class.php");

// Load our cache class.
require_once($_SERVER['DOCUMENT_ROOT']."/../core/classes/cache.class.php");

// Load our URLS Application Class
require_once($_SERVER['DOCUMENT_ROOT']."/../core/application/urls.app.php");

// Load our Style Class
require_once($_SERVER['DOCUMENT_ROOT']."/../core/application/style.app.php");

// Load Bootstrap Modal Class
require_once($_SERVER['DOCUMENT_ROOT']."/../core/application/modal.app.php");

// Load the status checking code
require_once($_SERVER['DOCUMENT_ROOT']."/../core/classes/devstatus.class.php");

// Load templating system
require_once($_SERVER['DOCUMENT_ROOT']."/../core/classes/template.class.php"); // SmallURL Static Templating System.

// Load JWT
include($_SERVER['DOCUMENT_ROOT']."/../core/lib/JWT.php");

$html = new HTML($_SMALLURL['domain']);
$rootDomain = ".".$_SMALLURL['domain'];

// Cookie and general Header Parameters.
header('Server: SmallURL2014'); // Useless presently, needs to be allowed at HTTP Level.
$load_start = microtime(); // To get loaf times. [Requires Hovis]

$currentCookieParams = session_get_cookie_params();
session_set_cookie_params(
    $currentCookieParams["lifetime"],
    $currentCookieParams["path"],
    $rootDomain,
    $currentCookieParams["secure"],
    $currentCookieParams["httponly"]
);

// Session params.
session_start();
session_name('SmallURL');
//session_set_cookie_params(0, '/', $rootDomain, false, false);

//setcookie("SmallURL", session_id(), time() + 31556926, '/', $rootDomain);

//session_regenerate_id(); // Regen each time we use the service, Keeps hack attempts down!

// OLD Code. Kept for compat.
if (!isset($_SESSION['safe'])) {
    $_SESSION['safe'] = false;
}

/* Init some Variables. */
$_SMALLURL['online'] = true;
if (isset($_SMALLURL['config']['online'])) {
	$_SMALLURL['online'] = $_SMALLURL['config']['online'];
}

// Maintenance mode.
if (!$_SMALLURL['online']) {
    $tmpl->load($_SERVER['DOCUMENT_ROOT']."/../core/tmpl/static/wip.tmpl");
    $tmpl->display();
    die();
}

/* Beginning of a Theme/Template system to easily theme SmallURL and maybe add user themes? */
$_THEME = array();
$_THEME['VARS'] = array();
$_THEME['JS'] = array();
$_THEME['CSS'] = array();

/* Clean the Variables we dont need */
unset($conf);

/* Load the Includes */
require_once($_SERVER['DOCUMENT_ROOT']."/../core/classes/db.class.php"); // Database Class
require_once($_SERVER['DOCUMENT_ROOT']."/../core/classes/functions.class.php"); // Functions and misc Classes [DEPRECATED]
require_once($_SERVER['DOCUMENT_ROOT']."/../core/classes/translation.class.php"); // SmallURL Translation System
require_once($_SERVER['DOCUMENT_ROOT']."/../core/classes/pagination.class.php"); // Pagination
require_once($_SERVER['DOCUMENT_ROOT']."/../core/classes/email.class.php"); // Email Class [WIP]

require_once($_SERVER['DOCUMENT_ROOT']."/../core/application/support.app.php"); // Support Class [WIP]
require_once($_SERVER['DOCUMENT_ROOT']."/../core/application/user.app.php"); // User Class [WIP]
require_once($_SERVER['DOCUMENT_ROOT']."/../core/application/apps.app.php"); // User Class [WIP]


// Init Database Connection - Dave ( Lets make this logical, we don't need to connect randomly )
// We do.
db::init();

// Check if the user exists!
if (isset($_SESSION['uid'])) {
    // Theyre logged in!
    $users = db::get_array("users",array("id"=>$_SESSION['uid']));
    if (count($users) <= 0) {
        unset($_SESSION['uid']);
        // Kick them out.
    }
    unset($users);
}

if (isset($_SESSION['uid'])) {
    // Logged in.
    $_SMALLURL['UID'] = $_SESSION['uid'];
    $_SMALLURL["LOGGEDIN"] = true;
}
else {
    $_SMALLURL['UID'] = 0;
    $_SMALLURL["LOGGEDIN"] = false;
}

// Pop this in.
$_SMALLURL['THEME'] = "smallurl";
if ($_SMALLURL['UID'] > 0) {
	$thm = $user->misc($_SMALLURL['UID'])->get('theme');
	if ($thm != null) {
		$_SMALLURL['THEME'] = $thm;
	}
}

// Update their last seen stamp if they're logged in
if ($_SMALLURL['UID']) {
	$u->misc()->set("activity_stamp",time());
}

// Black Listed IPs. Still WIP.
// Blacklist page needs to be made.
// Maybe use some fancy hacks to make it dynamic.

$users_ip = $_SERVER['REMOTE_ADDR'];

if($r = xRedis::get("ipbl:".md5($users_ip))) {
    $blr = json_decode($r);
	$bl = '';
}
else 
{
    $blr = '';
    $bl = db::get_array("ipbl",array("ip"=>$users_ip));
}
#var_dump($bl);
#var_dump($blr);
if (count($bl) > 0 && $blr != "") {
    xRedis::set("ipbl:".md5($users_ip), json_encode($bl));
    // put $bl into redis ;D
    $tmpl->variable("DOMAIN")->set($domain);
    $tmpl->variable("IP")->set($_SERVER['REMOTE_ADDR']);
    $tmpl->variable("REASON")->set($bl[0]['reason']);
    $tmpl->load($_SERVER['DOCUMENT_ROOT']."/../core/tmpl/static/blacklisted.tmpl");
    $tmpl->display();
    die();
}
else 
{
    xRedis::set("ipbl:".md5($users_ip), "n/a");
}
unset($users_ip,$bl);

function crawlerDetect($USER_AGENT)
{
    $crawlers = array(
    array('Google', 'Google'),
    array('msnbot', 'MSN'),
    array('Rambler', 'Rambler'),
    array('Yahoo', 'Yahoo'),
    array('AbachoBOT', 'AbachoBOT'),
    array('accoona', 'Accoona'),
    array('AcoiRobot', 'AcoiRobot'),
    array('ASPSeek', 'ASPSeek'),
    array('CrocCrawler', 'CrocCrawler'),
    array('Dumbot', 'Dumbot'),
    array('FAST-WebCrawler', 'FAST-WebCrawler'),
    array('GeonaBot', 'GeonaBot'),
    array('Gigabot', 'Gigabot'),
    array('Lycos', 'Lycos spider'),
    array('MSRBOT', 'MSRBOT'),
    array('Scooter', 'Altavista robot'),
    array('AltaVista', 'Altavista robot'),
    array('IDBot', 'ID-Search Bot'),
    array('eStyle', 'eStyle Bot'),
    array('Scrubby', 'Scrubby robot')
    );

    foreach ($crawlers as $c)
    {
        if (stristr($USER_AGENT, $c[0]))
        {
            return($c[1]);
        }
    }

    return false;
}
?>
