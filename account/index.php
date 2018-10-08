<?php
// Start the frontend.
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
$page_directory = $_SERVER['DOCUMENT_ROOT']."/../core/pages";

// Check if we're in devel mode.
if (SITE_MODE === 'DEVEL') {
	error_reporting(E_ALL);
}

// Eh, No clue.
$err = false;

if (isset($_GET['p']) && $_GET['p'] != "") {
	$page = htmlentities(strtolower($_GET['p']));
	$page = explode("/",$page);
	$page = $page[count($page)-1];
} else {
	$page = "home";
}

$section = "account";
if (isset($_GET['s']) && $_GET['s'] != "") {
	if (preg_match("/(account|support|apps)/i",$_GET['s'])) {
		$section = htmlentities(strtolower($_GET['s']));
	}
}

if (is_mobile()) {
	if (file_exists($page_directory."/{$section}/{$page}_mobile.php")) {
		$page = $page."_mobile";
	}
}
if (!file_exists($page_directory."/{$section}/{$page}.php")) {
	$err = $page;
	$page = "404";
	$section = "misc";
}

// Replaces $_GET so we have more control over it.
$_VAR = array();
foreach ($_GET as $g_key => $g_val) {
	if (preg_match("/([\w\-]+)/",$g_val)) {
		$_VAR[$g_key] = $g_val;
	}
}

if (isset($_VAR['i'])) {
	$id = htmlentities($_VAR['i']);
}
else {
	$id = false;
}


// This needs MOVING to the Template class
// Also needs an option to turn templating off within a page.
$sub = "account";
// Run the page code before, Gives it access to set variables
// Before the rest is loaded. Even header() redirect if needed.
ob_start();
include($page_directory."/{$section}/{$page}.php");
$page_content = ob_get_contents();
ob_end_clean();

// Check for an internal error.
if (error_get_last() != NULL) {
	ob_start();
	include($page_directory."/misc/500.php");
	$page_content = ob_get_contents();
	ob_end_clean();
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<?php $html->account()->Header($page);?>
	<?php $modal->javascript(); ?>
	</head>
<?php if (count($_THEME['JS']) > 0) {
	echo "\t<!-- Extra Javascript -->\n";
	foreach ($_THEME['JS'] as $js) {
		echo "\t<script src=\"{$js}\"></script>\n";
	}
}
 if (count($_THEME['CSS']) > 0) {
	echo "\t<!-- Extra CSS -->\n";
	foreach ($_THEME['CSS'] as $css) {
		echo "\t<link href=\"{$css}\" rel=\"stylesheet\">\n";
	}
} ?>
		<body>
			<div id='wrapper'>
<?php
if (SITE_MODE === "DEVEL") {
	?>
	<style>
	.devel {
		height:55px;
		background-color:#9E0000;
		border-bottom:3px solid #FF0000;
		z-index:999999;
		position:fixed;
		top:0;
		left:0;
		padding:5px;
		color:white;
		font-size:28px;
	}
	</style>
	<div class="devel">Dev</div>
	<?php
}
?>
				<?php $html->account()->Navigation(); ?>
				<div id='content'>
					<?php echo $page_content; ?>
				</div>
				<div class='push'></div>
			</div>
	<?php
		$modal->html();
		$html->account()->Footer();
	?>
	</body>
</html>