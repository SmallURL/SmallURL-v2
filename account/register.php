<?php if($_SERVER['REQUEST_URI'] == '/register.php') {
    header("Location: /login");
}
?>
<!doctype HTML>
<html>

<head>
    <link href='//static.<?php echo $_SMALLURL['domain'];?>/global/css/thirdparty/bootstrap.css' rel='stylesheet'>
    <title> Register @ SmallURL </title>
    <style>
        html,
        body {
            background-color:#e7e7e7;
        }
        #container {
            position: absolute;
            width: 410px;
            height: 600px;
            z-index: 15;
            top: 50%;
            left: 50%;
            margin: -300px 0 0 -205px;
            text-align: center;
        }
        #content {
            width: 410px;
            height: 410px;
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
    </style>
</head>

<body>
    <div id='container'>
        <img src='//static.<?php echo $_SMALLURL['domain'];?>/smallurl/img/style/small_logo_black.png' style='width: 282px;' alt='Logo' />
        <div id='content'>
            <div class='padding'>
                <h4 style='text-align:center; opacity:0.6'> Register at SmallURL! </h4>
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
				if ($ERR) {
					if ($ERRNO === 99) {
						echo '<span class="required">'.$ERRMSG.'</span>';
					}
				}
				
				// if (($ERR) && ($ERRNO === 00)) { echo '<span class="required"> Message </span>'; }
				?>
                <form role="form" method="POST" action="/register">
					<div class="form-group">
                        <label for="exampleInputPassword1">Username <?php if (($ERR) && ($ERRNO == 1)) { echo '<span class="required"> (Required) </span>'; } ?></label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-star"></span>
                            </span>

                            <input class="form-control" id="exampleInputPassword1" name="username" placeholder="Username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">E-Mail <?php if (($ERR) && ($ERRNO == 2)) { echo '<span class="required"> (Required) </span>'; } if (($ERR) && ($ERRNO == 5)) { echo '<span class="required"> Invalid EMail! </span>'; } ?></label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span>
                            </span>

                            <input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="Enter E-Mail">
                        </div>
                    </div>
                    <div class="form-group">

                        <label for="exampleInputPassword1">Password <?php if (($ERR) && ($ERRNO == 3)) { echo '<span class="required"> (Required) </span>'; } ?></label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-transfer"></span>
                            </span>

                            <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group">

                        <label for="exampleInputPassword1">Password (Again) <?php if (($ERR) && ($ERRNO == 4)) { echo '<span class="required"> (Required) </span>'; } if (($ERR) && ($ERRNO == 6)) { echo '<span class="required"> (Passwords must match) </span>'; } ?></label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-transfer"></span>
                            </span>

                            <input type="password" class="form-control" id="exampleInputPassword1" name="password_confirm" placeholder="Password">
                        </div>
                    </div>
                    <input type='hidden' name='go_reg' value='<?php echo md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']) ?>' />
                    <button type="submit" class="btn btn-default" style='float:right;'>Register</button>
                    <br />
                </form>
            </div>
        </div>

        <a href='/login'>Got a SmallURL account?</a>
    </div>
</body>

</html>
