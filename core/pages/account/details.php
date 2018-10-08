<?php if ($_SMALLURL['LOGGEDIN']) { ?>
<?php $html->AccPageTitle("Account Details", "Account Details"); ?>
<?php include("asset/nav.php"); ?>
<?php if(isset($_SESSION['details_id'])) {
        $id = $_SESSION['details_id'];
    } else {
        $id = null;
    }
?>
<?php unset($_SESSION['details_id']); ?>
<div class="wrap">
<div class='toppadding'></div>
	<?php if($id == '0') { ?>
    <h4>&nbsp;</h4>
		<div class="alert alert-success">
        	<strong>Complete!</strong> <br> I have successfully updated your account details
        </div>
	<?php } ?>
	<h4>&nbsp;</h4>
	<h4 style='text-align:center;'><strong>Account Details.</strong></h4>
	<p style='text-align:center;'>From here you can update your display name and change your password!</p>
	<hr>


    <form class="form-horizontal" action="/do/public/check_details.php" method='post' style='width: 450px; margin:auto;'>
        <div class="control-group">
            <label class="control-label" for="inputEmail">Name</label>
            <div class="controls">
                <input type="text" class="form-control" id="inputEmail" name='name' placeholder="Email" value="<?php echo htmlentities(get_user($_SMALLURL['UID'])); ?>">  <?php if($id == '1') { ?> <span style='color: red;'> This needs a value! </span> <?php } ?>
            </div>
        </div>

        <hr>

        <h5> Change Password: </h5>
        <div class="control-group">
            <label class="control-label" for="inputPassword">Old Password</label>
            <div class="controls">
                <input type="password" class="form-control" id="inputPassword" name='oldpassword' placeholder="Password"> <?php if($id == '3')  { ?> <span style='color: red;'> Please retype the password. </span> <?php } ?>
            </div>
        </div>
        <br />
        <div class="control-group">
            <label class="control-label" for="inputPassword">New Password</label>
            <div class="controls">
                <input type="password" class="form-control" id="inputPassword" name='newpassword1' placeholder="Password">
            </div>
        </div>
        <br />
        <div class="control-group">
            <label class="control-label" for="inputPassword">Repeat New Password</label>
            <div class="controls">
                <input type="password"class="form-control" id="inputPassword" name='newpassword2' placeholder="Repeat Password">  <?php if($id == '4')  { ?> <span style='color: red;'> Please retype the password. </span> <?php } ?>
            </div>
        </div>
        <br />
		<h4>Set Username</h4>
		<div class="control-group">
            <label class="control-label" for="inputPassword">Username</label>
            <div class="controls">
                <input type="text" class="form-control" id="inputUsername" <?php if ($u->core($_SESSION['uid'])->get("username") != "") { echo "readonly"; } ?> name='username' placeholder="Username" value="<?=$u->core($_SESSION['uid'])->get("username")?>">  <?php if($id == '5')  { ?> <span style='color: red;'> That username already exists</span> <?php } ?>
            </div>
        </div>
		<br/>
		<?php
		if ($u->core($_SESSION['uid'])->get("username") != "") {
			echo "You cannot change your username as you've already set it. Contact staff if you wish to change it.";
		} else {
			echo "Setting up a username will give you a profile page that can be access by anyone.";
		}
		?>
		<br/>
        <div class="control-group">
                <button type="submit" class="btn btn-primary" style='width: 75px; margin:auto; float:right;'>Update</button>
		</div>
    </form>
    <h4>&nbsp;</h4>
    <h4>&nbsp;</h4>
</div>
<?php } else {
	// Not logged in.
	$continue = "//account.".$_SMALLURL['domain']."/details";
	header("Location: //account.".$_SMALLURL['domain']."/login");
} ?>