<?php
if($_SERVER['REQUEST_URI'] == '/auth.php') {
    header("Location: /auth");
}

$apps = db::get_array("apps",array("pubtoken"=>$token));
if (count($apps) <= 0) {
	// No such app :(
	header('Location: /apps/');
	die();
}
$app = $apps[0];

// Check if we've already authorised.
$auth = db::get_array("auth",array("user"=>$_SMALLURL['UID'],"app"=>$app['id']));
?>
<!doctype HTML>
<html>
<head>
    <link href='//static.<?php echo $_SMALLURL['domain'];?>/global/css/thirdparty/bootstrap.css' rel='stylesheet'>
    <title> Authorise Access @ SmallURL </title>
    <style>
        html,
        body {
            background-color:#e7e7e7;
        }
        #container {
            position: absolute;
            width: 410px;
            height: 515px;
            z-index: 15;
            top: 50%;
            left: 50%;
            margin: -280px 0 0 -205px;
            text-align: center;
        }
        #content {
            width: 410px;
            height: 515px;
            background-color: white;
            margin-top: 25px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .padding {
            padding: 30px;
            text-align: left;
            padding-top: 15px;
        }
		.permissions {
			margin-left:30px;
			margin-top:10px;
			margin-bottom:10px;
		}
		.permissions li {
			margin-left:50px;
			list-style:initial !important;
		}
		.app-icon {
			max-height:65px;
			max-width:65px;
			
			min-height:64px;
			min-width:64px;
		}
		.app-icon-container {
			border-radius:32px;
			width:64px;
			height:64px;
			border-color: rgb(230,230,230);
			border-style: solid;
			border-width: 1px;
			overflow:hidden;
			display:inline-block;
		}
		.app-title {
			margin-bottom:30px;
			display:block;
		}
    </style>
</head>

<body>
    <div id='container'>
        <!--<img src='http://static.<?=SITE_URL;?>/img/small_logo_black.png' style='width: 282px;' alt='Logo' />-->
        <div id='content'>
            <div class='padding'>
				<span style="text-align:center;">
					<h2>
						<?php
							if (file_exists($_SERVER['DOCUMENT_ROOT']."/../cache/apps/".md5($app['id']).".png")) {
								$img = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../cache/apps/".md5($app['id']).".png"));
							} else {
									$img = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../avatar/unknown.png"));
							}
							echo "<div class='app-icon-container'><img class='app-icon' src='data:image/png;base64,{$img}'/></div>";
						?>
						<span class='app-title'><?=$app['title'];?></span>
					</h2>
					<h3><small>
						By <a href="<?php echo $u->link($app['user']); ?>"><?php echo $u->display_name($app['user']); ?></a>
					</small></h3>
				</span>
				<?php $smallurl->display_errors(); ?>
				<hr/>
                <h4 style='text-align:center;6'><b><?=$app['title'];?></b> <?php if (count($auth) > 0) { echo "already has"; } else { echo "wants"; } ?> access to: </h4>
				<hr/>
				<ul class="permissions">
					<b>Your Account:</b>
					<li>Retrieve your Name</li>
					<li>Retrieve your Username</li>
					<li>Retrieve your E-Mail</li>
					<?php
						foreach (json_decode($app['perms'],true) as $perm => $val) {
							if ($perm == "smallurl") {
								echo "<b>SmallURL Service:</b>";
								echo "<li>Retrieve your Shortened URLs</li>";
								echo "<li>Shorten URLs as you</li>";
							}
						}
					?>
				</ul>
				<hr/>
				<?php
					if(isset($_SESSION['uid'])) {
						if(is_numeric($_SESSION['uid'])) {
							if (count($auth) <= 0) {
				?>
                <form role="form" action="/do/apps/authorise.php" method="post">
                    <input type='hidden' name='app_token' value='<?php echo $token; ?>' />
                    <input type='hidden' name='auth_token' value='<?php echo md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']) ?>' />
                    <span style="float:right">
						<button type="submit" class="btn btn-primary">Authorise</button>&nbsp;
						<button type="button" class="btn btn-default">Cancel</button>
					</span>
                    <br />
                </form>
				<?php } else { ?>
					<center>
						<h4>You've already granted <b><?=$app['title'];?></b> <a href="/apps/view/<?=md5($app['id']);?>">access</a>.</h4>
					</center>	
				<?php } } } else { ?>
					<center>
						<h4><a href="/login">Login</a> to authorise this application.</h4>
					</center>
				<?php } ?>
            </div>
        </div>
    </div>
</body>

</html>
