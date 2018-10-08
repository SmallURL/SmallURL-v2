<?php
require_once($_SERVER['DOCUMENT_ROOT']."/../core/classes/config.class.php");
// Uses new configuration system.
if (config::get("debug")) {
	define('SITE_MODE','DEVEL');
	// We're in development mode.
	if (config::get("dev_domain") != NULL) {
		$_SMALLURL['domain'] = config::get("dev_domain");
		define('SITE_URL',config::get("dev_domain"));
	} else {
		$_SMALLURL['domain'] = "smallurl.in";
		define('SITE_URL','smallurl.in');
	}
} else {
	define('SITE_MODE','LIVE');
	define('SITE_URL','smallurl.in');
	$_SMALLURL['domain'] = 'smallurl.in';
}
?>
