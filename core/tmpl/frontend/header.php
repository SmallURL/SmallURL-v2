<meta charset="utf-8">
	<title><?php if (isset($_SMALLURL['title'])) { echo $_SMALLURL['title']; } else { echo htmlentities(ucfirst(strtolower($title))) ; } ?> - SmallURL</title>

	<?php if(isset($this->meta) && $this->meta != '') { ?>
<meta name="description" content="<?=htmlentities($this->meta);?>">
	<?php } ?>
<meta name="revisit-after" content="30 days">
	<meta name="keywords" content="SmallURL,URL,Short,Simple,Easy,Tiny,Quick,Fast" >
	<meta name="author" content="SmallURL">
	<meta name="nibbler-site-verification" content="97ff9f6a4401ef456747e796045c4dcb24345b92" />
	<link href="//static.<?php echo $_SMALLURL['domain']; ?>/<?=$_SMALLURL['THEME'];?>/css/style.css" rel="stylesheet">
	
	<link href="//static.<?php echo $_SMALLURL['domain']; ?>/global/css/thirdparty/bootstrap.css" rel="stylesheet">
	<link href="//static.<?php echo $_SMALLURL['domain']; ?>/global/css/thirdparty/bootstrap-theme.min.css" rel="stylesheet">
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<script type='text/javascript' src='//static.<?php echo $_SMALLURL['domain']; ?>/global/js/thirdparty/bootstrap.js'></script>
	 <link rel="search" type="application/opensearchdescription+xml" title="SmallURL" href="/opensearch.xml" />
	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<![endif]-->
	<!-- SMALLURL JScript -->
	<script src="//static.<?php echo $_SMALLURL['domain']; ?>/global/js/smallurl.js"></script>
	<?php if ($page != "home") { ?>
	<script>
	var isShift = false;
	document.onkeyup=function(e){
		if(e.which == 18) isAlt=false;
	}
	document.onkeydown=function(e){
		if(e.which == 18) isAlt=true;
		if(e.which == 83 && isAlt == true) {
			//Someone wants to shorten.
			if (confirm('Want to shorten?')) {
				window.location.href = '/?p=home';
			}
			else {
				alert('<?=_tr("smalltemplate/header", "always_can_shorten")?>');
			}
			return false;
		}
	}

	</script>
	<?php } ?>
	<link rel="shortcut icon" href="favicon.ico">