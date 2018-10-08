<meta charset="utf-8">
<title><?php if (isset($_SMALLURL['title'])) { echo $_SMALLURL['title']; } else { echo htmlentities(ucfirst(strtolower($title))); } ?> - SmallURL</title>

<!-- Remote Resources -->
<link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,900' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Raleway:400,200,100' rel='stylesheet' type='text/css'>

<link href='//static.<?php echo $_SMALLURL['domain']; ?>/global/css/thirdparty/reset.css' rel='stylesheet' type='text/css'>
<link href='//static.<?php echo $_SMALLURL['domain']; ?>/global/css/account.css' rel='stylesheet' type='text/css'>
<link href='//static.<?php echo $_SMALLURL['domain']; ?>/global/css/thirdparty/font-awesome.css' rel='stylesheet' type='text/css'>
<link href="//static.<?php echo $_SMALLURL['domain']; ?>/global/css/thirdparty/bootstrap.css" rel="stylesheet">
<!--<link href="//static.<?php echo $_SMALLURL['domain']; ?>/css/thirdparty/bootstrap-theme.min.css" rel="stylesheet">-->
<?php if (SITE_MODE === "LIVE") { ?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<?php } else { ?>
<!-- SmallURL is running in devel mode. All resources are sourced locally. -->
<script type='text/javascript' src='//static.<?php echo $_SMALLURL['domain']; ?>/global/js/thirdparty/jquery.js'></script>
<script type='text/javascript' src='//static.<?php echo $_SMALLURL['domain']; ?>/global/js/thirdparty/jquery-ui.js'></script>
<?php } ?>
<script type='text/javascript' src='//static.<?php echo $_SMALLURL['domain']; ?>/global/js/thirdparty/bootstrap.js'></script>


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
            alert('Okay! Remember you can shorten anytime via Alt+S!');
        }
        return false;
    }
}
</script>
<?php } ?>
<link rel="shortcut icon" href="favicon.ico">