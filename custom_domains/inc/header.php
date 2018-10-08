<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo config('title'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SmallURL the Safe URL shortening service.">
    <meta name="author" content="SmallURL">
	<meta name="wot-verification" content="cfa066bf2855f671b762"/>

    <!-- Le styles -->
    <link href="/css/<?php echo "default"; ?>.css" rel="stylesheet">
    <link href="http://smallurl.in/css/smallurl.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>
    <link href="http://smallurl.in/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->
	<!-- SMALLURL JScript -->
	<script src="http://smallurl.in/js/smallurl.js"></script>
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
					alert('Okay! Remember you can shorten anytime via Alt+S!');
				}
				return false;
			}
		}
	</script>
	<?php } ?>
	<!--<script src="js/smallurl_validation.js"></script> Unimplemented -->
    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="favicon.ico">
  </head>

  <body>

	<nav class="navbar navbar-default navbar-inverse navbar-fixed-top" role="navigation">
	  <!-- Brand and toggle get grouped for better mobile display -->
	  <div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
		  <span class="sr-only">Toggle navigation</span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="/"><img src="http://smallurl.in/img/menu_logo.png" height="23px"/><!--<?php echo config('title'); ?>--></a>
	  </div>

	  <!-- Collect the nav links, forms, and other content for toggling -->
	  <div class="collapse navbar-collapse navbar-ex1-collapse">
		<ul class="nav navbar-nav">
              <li <?php active('home'); active('home_mobile'); ?>><a href="/?p=home">Shorten URL</a></li>
              <li <?php active('list'); active('home_mobile'); ?>><a href="/?p=list">URLS</a></li>
		</ul>
		<?php if (LOGGEDIN) { ?>
		<ul class="nav navbar-nav navbar-right">
			  <li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">	Logged in as <?php echo get_user(UID); ?>
					<b class="caret"></b>
				  </a>
				<ul class="dropdown-menu">
				  <li><a href="/?p=my_account">My account</a></li>
				  <li><a href="/?p=my_details">Edit my details.</a></li>
				  <li><a href="logout.php">Logout</a></li>
				</ul>
			  </li>
		</ul>
		<?php } else { ?>
			<!--<p id="login" class="navbar-text pull-right"><a class="navbar-link" href="/?p=login">Login</a></p>-->
		<?php } ?>
	  </div><!-- /.navbar-collapse -->
	</nav>

    <div class="container">
	<?php if ($wip) { ?>
	<div class="alert alert-error">
		<strong>Whoah!</strong>
		<br/>
		We're working on some core features as you're looking at this page.
		<br/>
		Some things could break! You've been warned!
	</div>
	<?php } ?>
	<?php if ($service_notice) { ?>
	<div class="alert alert-<?php echo $service_notice_level; ?>">
		<strong>Hey there!</strong>
		<br/>
		<?php echo str_replace("\n","<br/>",$service_notice_msg); ?>
	</div>
	<?php } ?>