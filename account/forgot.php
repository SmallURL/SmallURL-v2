<?php
if($_SERVER['REQUEST_URI'] == '/forgot.php') {
    header("Location: /forgot");
}
?>
<!doctype HTML>
<html>

<head>
    <link href='//static.<?php echo $_SMALLURL['domain'];?>/global/css/thirdparty/bootstrap.css' rel='stylesheet'>
    <title> Password Reset @ SmallURL </title>
    <style>
        html,
        body {
            background-color:#e7e7e7;
        }
        #container {
            position: absolute;
            width: 410px;
            height: 280px;
            z-index: 15;
            top: 50%;
            left: 50%;
            margin: -140px 0 0 -205px;
            text-align: center;
        }
        #content {
            width: 410px;
            height: 180px;
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
    </style>
</head>

<body>
    <div id='container'>
        <img src='//static.<?php echo $_SMALLURL['domain'];?>/smallurl/img/style/small_logo_black.png' style='width: 282px;' alt='Logo' />
        <div id='content'>
            <div class='padding'>
                <h4 style='text-align:center; opacity:0.6'> Forgot your password?! </h4>
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
				?>
				<?php if (!isset($_POST['go_reset']) || $_POST['go_reset'] == "") { ?>
                <form role="form" method="post">
                    <div class="form-group">
                        <label for="email">E-Mail</label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span>
                            </span>

                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your E-Mail">
                        </div>
                    </div>
                    <input type='hidden' name='go_reset' value='<?php echo md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']) ?>' />
                    <button type="submit" class="btn btn-default" style='float:right;'>Reset</button>
                    <br />
                </form>
				<?php } else { ?>
					<?php
					$email = $_POST['email'];
					$ret = $u->reset($email);
					if ($ret['res']) {
						echo "<h3>Password Reset Email Sent!</h3>{$ret['msg']}";
					} else {
						echo "<h3>Error sending you an Email!</h3>{$ret['msg']}";
					}
					?>
				<?php } ?>
            </div>
        </div>

        <a href='/register'>Not got a SmallURL account?</a>
    </div>
</body>

</html>
