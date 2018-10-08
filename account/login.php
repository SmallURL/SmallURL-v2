<?php
if($_SERVER['REQUEST_URI'] == '/login.php') {
    header("Location: /login");
}
if (isset($_SESSION['ERR']) && $_SESSION['ERR']) {
    $errid = $_SESSION['ERR_ID'];
    unset($_SESSION['ERR'],$_SESSION['ERR_ID']);
} else {
    $errid = 0;
}

// This is if a user needs to be logged in to continue,
// Continue is set to the destination URL.
if (isset($continue) && $continue) {
    echo "<center><b>Login to continue</b></center>";
    $dest = $continue;
} else {
    if (isset($_SERVER['HTTP_REFERER'])) {
        $dest = $_SERVER['HTTP_REFERER'];
    } else {
        $dest = "";
    }
}
//print_r($_SESSION);


?>
<!doctype HTML>
<html>

<head>
    <title> Login | SmallURL Connect </title>
    <link href='//static.<?php echo $_SMALLURL['domain'];?>/global/css/thirdparty/bootstrap.css' rel='stylesheet'>
    <style>
        html,
        body {
            background-color:#e7e7e7;
        }
        #container {
            position: absolute;
            width: 410px;
            height: 500px;
            z-index: 15;
            top: 50%;
            left: 50%;
            margin: -250px 0 0 -205px;
            text-align: center;
        }
        #content {
            width: 410px;
            height: 280px;
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
        #facebook {
            background-image: url("//static.<?php echo $_SMALLURL['domain'];?>/smallurl/img/social/login_facebook.png");
        }
        #twitter {
            background-image: url("//static.<?php echo $_SMALLURL['domain'];?>/smallurl/img/social/login_twitter.png");
        }
        #google {
            margin-left: 12px;
            margin-right: 12px;
            background-image: url("//static.<?php echo $_SMALLURL['domain'];?>/smallurl/img/social/login_google.png");
            background-color: rgba(255,255,255,0.5);
        }
        .socbutton {
            width: 107px;
            height: 40px;
            float: left;
        }
        .disabled {
        	width: 107px;
        	height: 40px;
        	background-color: rgba(255,255,255,0.7);
        }
        .socbutton:hover {
        	opacity: 0.7;
        }
        .required {
            color:red;
            font-weight:bold;
            float:right;
        }
    </style>
</head>

<body>
    <div id='container'>
        <img src='//static.<?php echo $_SMALLURL['domain'];?>/smallurl/img/style/small_logo_black.png' style='width: 282px;' alt='Logo' />
        <div id='content'>
            <div class='padding'>
                <h4 style='text-align:center; opacity:0.6'>Login to SmallURL</h4>
				<?php
				if (isset($_SESSION['ERR']) && $_SESSION['ERR']) {
					$ERR = true;
					$ERRNO = $_SESSION['ERR_ID'];
					unset($_SESSION['ERR'],$_SESSION['ERR_ID']);
					if (isset($_SESSION['ERR_MSG'])) {
						$ERRMSG = $_SESSION['ERR_MSG'];
						unset($_SESSION['ERR_MSG']);
					} else {
						$ERRMSG = false;
					}
				} else {
					$ERR = false;
					$ERRNO = 0;
				}
				if ($ERR && $ERRNO === 99) {
					show_alert('Registered!','Yay you\'ve just registered an account! Check your email for a verification link!');
				}
				?>
                <form role="form" method="post">
                    <div class="form-group">
                        <label for="exampleInputEmail1" style='width: 100%;'><span style='float:left;'>E-Mail or Username </span><?php if ($errid == 1 || $errid == 3) { echo ' <span class="required">(Required)</span>'; } ?></label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span>
                            </span>

                            <input class="form-control" id="user" name='user' placeholder="Enter E-Mail or Username">
                        </div>
                    </div>
                    <div class="form-group">

                        <label for="exampleInputPassword1" style='width: 100%;'><span style='float:left;'>Password</span> <?php if ($errid == 3 || $errid == 2) { echo ' <span class="required">(Required)</span>'; } elseif($errid == 4) { echo '<span class="required">Invalid Password</span>'; }?></label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-transfer"></span>
                            </span>

                            <input type="password" class="form-control" id="pass" name='pass' placeholder="Password">
                        </div>
                    </div>
                    <input type='hidden' name='go_auth' value='idk' />
                    <input type="hidden" name="ref" value="<?php echo $dest; ?>"/>
                    <span style='float:left;margin-top:7px'><a href='/forgot'>Forgot your password?</a></span>
                    <button type="submit" class="btn btn-default" style='float:right;'>Login</button>
                    <br />
                </form>
            </div>
        </div>

        <a href='/register'>Register @ SmallURL?</a>
    </div>
</body>

</html>
