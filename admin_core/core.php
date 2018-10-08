<?php
$_ADMIN = array();

include("classes/core.class.php");

// Load our configuration.
include("classes/config.class.php");
config::load();

// Connect to DB
$db = new PDO("mysql:host=".config::get("db_host").";dbname=".config::get("db_name").";charset=utf8", config::get("db_user"), config::get("db_pass"), array(PDO::ATTR_EMULATE_PREPARES => true));
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


// Development environment detection
include("classes/devstatus.class.php");

// Setup Vars

define("BASEPATH", $_SERVER['DOCUMENT_ROOT']."/..");
define("APPPATH", BASEPATH."/admin_core/");
define("LAYOUT", APPPATH."/html/");

$currentCookieParams = session_get_cookie_params();
session_set_cookie_params( $currentCookieParams["lifetime"],$currentCookieParams["path"],SITE_URL,$currentCookieParams["secure"],$currentCookieParams["httponly"] );
session_name('SmallURL');
session_start();
setcookie("SmallURL", session_id(), time() + 31556926, '/', SITE_URL);

// Extra stuff
include("classes/theme.class.php");
include("classes/account.class.php");
include("classes/routing.class.php");

// IDK
$route->addDomain("admin.".$_ADMIN['domain'], "frontend");
/* $route->addController([DOMAINALIAS], [controller_name], [default_endpoint], [do404], [reqlogin]); */
$route->addController("frontend", "home", "home", true, true);
$route->addController("frontend", "users", "home", true, true);
$route->addController("frontend", "shortened", "home", true, true);
$route->addController("frontend",  "flagged", "home", true, true);
$route->addController("frontend",  "report", "home", true, true);

// Add routes for support center
$route->addController("frontend", "support", "home", true, true);

$route->defaultController("frontend", "home");
$route->handleRoute();

//print_r($_SESSION);
?>
