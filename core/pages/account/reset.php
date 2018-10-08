<?php $html->AccPageTitle("Reset Password", "Reset your account password"); ?>
     <div class="wrap">
	<div class='toppadding'></div>
<div class="page-header">
	<h3>Password Reset</h3>
</div>
<?php
if ($id == "") {
	?>
<p>You cannot reset your password without your password reset hash!</p>
<p>Did you click the link in your Email?</p>
<p>If you did try copying and pasting the link manually!</p>
<p/>
<p>If this still does not work contact our staff team!</p>
	<?php
}
else {
	$users = db::get_array("users",array("passresetkey"=>$id));
	if (count($users) < 1) {
		// Invalid
?>
<p>We couldn't find anyone requesting a password reset with that key (<?=$id?>)!</p>
<p>Check you're at the full URL Supplied in your Email and try again.</p>
<?php
	}
	else if (count($users) == 1) {
		// Valid
		if ($users[0]['passreset'] === "1") {

if (get_param('e','GET') == "1") {
	show_alert('You need to type in the first password!');
	$id = $_SESSION['resethash'];
	unset($_SESSION['resethash']);
}
else if (get_param('e','GET') == "2") {
	show_alert('You need to confirm your password!');
	$id = $_SESSION['resethash'];
	unset($_SESSION['resethash']);
}
else if (get_param('e','GET') == "3") {
	show_alert('The passwords you entered didnt match!');
	$id = $_SESSION['resethash'];
	unset($_SESSION['resethash']);
}
?>
<p>Hey <?php echo gettok(chr(32),$users[0]['name'],0); ?>,</p>
<p>So you forgot your password and had to reset it?</p>
<p>Don't worry! It happens to the best of us!</p>
<p>Drop in your new password below and once again to confirm it then hit the 'Update Password' button!</p>
<div class="well">
<form action="do/update_pass.php" method="post">
<p>Your new password:</p>
<p><input type="password" class="form-control" name="pass_one" placeholder="Type a new password here.."/></p>
<p>Once again:</p>
<p><input type="password" class="form-control" name="pass_two" placeholder="Once again to confirm.."/></p>
<p><input type="hidden" name="hash" value="<?php echo md5($u->hash()); ?>"/></p>
<p><input type="hidden" name="reset_hash" value="<?php echo $id; ?>"/></p>
<p><button class="btn btn-success" type="submit">Update Password</button></p>
</form>
</div>
	<?php
		}
		else {
		// Play dead!

?>
<p>We couldn't find anyone requesting a password reset with that key (<?=$id?>)!</p>
<p>Check you're at the full URL Supplied in your Email and try again.</p>
<?php
		}
	}
	else {
		// More than one user. Eh, Wat?
		echo "Internal ERROR!<br>Contact Staff";
	}
}
?>
</div>