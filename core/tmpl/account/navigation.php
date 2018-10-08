<?php global $_SMALLURL; ?>

<div id='topbar'>
    <div class='wrap'>
        <div class='float-left' style='line-height: 50px;'>
            SmallURL Account Center
        </div>
        <div class='float-right' id='icplus' style='line-height: 50px;'>
            <style>
            #aa-account-logo {
                background-image:url("<?=$u->avatar($_SMALLURL['UID'])?>");
            }
			#aa-notifications {
				background: rgb(229, 57, 57);
				color: white;
				width: 15px;
				height: 15px;
				padding: 0;
				margin: 0;
				line-height: 1;
				text-align: center;
				margin-top: 23px;
				margin-left: 23px;
				border-radius: 15px;
				padding-right: 1px;
				font-size: 14px;
			}
            </style>
			<?php if ($_SMALLURL['UID'] > 0) { ?>
            <a onclick="if(!window.confirm('Are you sure you want to logout of your Account?')) return false;" href='//account.<?php echo $_SMALLURL['domain'];?>/logout'>
            <?php } else { ?>
			<a href='//account.<?php echo $_SMALLURL['domain'];?>/logout'>
			<?php } ?>
				<div id='iconplus' style='margin-top:-2px;'>
					<div id="aa-account-logo" title="Logout">
						<?php if (count($u->notifications($_SMALLURL['UID'])->unread()) > 0) { ?>
						<div id="aa-notifications"  onclick="location.href='/notification';">
							<span class="notif-count">
								<?php echo count($u->notifications($_SMALLURL['UID'])->unread()); ?>
							</span>
						</div>
						<?php } ?>
					</div>
					<div style="float:right">
						<div id="aa-account-name"> <?php echo get_user($_SMALLURL['UID']);?> </div>
						<div id="aa-account-plan"> <?php global $u; $role = $u->get_role($_SMALLURL['UID']); echo $role['name']; ?>     </div>
					</div>
				</div>
			</a>
        </div>
    </div>
</div>
<?php
if (!$u->verified($_SMALLURL['UID'])) {
if (!isset($_GET['sent'])) {
?>
<div class="alert alert-danger">
	<center>
	<b>Uh-nuh!</b>
	You've not verified your account! Check your Email's Spam/Junk folder for your Verfication Email! Or we can <a href="/do/public/resend_verification.php">Resend</a> you the Email!
	</center>
</div>
<?php } else { ?>
<div class="alert alert-warning">
	<center>
	<b>Sent!</b>
	We've sent you a new copy of your verification Email, check your Inbox and Spam/Junk folders for it.
	</center>
</div>
<?php } }?>

<div id='navigation'>
    <div class='wrap'>
        <a href='/' ><div id='logo'></div></a>
        <ul id='menubar'>
			<?php if ($_SMALLURL['LOGGEDIN']) { ?>
				<li><a href='/notification'> Notifications</a></li>
				<?php if ($u->verified($_SMALLURL['UID'])) { ?>
					<li><a href='/api'> API</a></li>
				<?php } else { ?>
					<?php global $modal; $m_you = $modal->create("API Keys Disabled","Please verify your account for access to adding and removing API Keys."); ?>
					<li><span data-toggle="modal" data-target="#<?php echo $m_you; ?>" class="disabled"> API</span></li>
					<li><a href='/verify'> <b>Verify</b></a></li>
				<?php } ?>
				<?php if ($u->verified($_SMALLURL['UID'])) { ?>
					<li><a href='/apps'> Apps</a></li>
				<?php } else { ?>
					<?php global $modal; $m_you = $modal->create("Applications Disabled","Please verify your account to again access to creating/managing accounts."); ?>
					<li><span data-toggle="modal" data-target="#<?php echo $m_you; ?>" class="disabled"> Apps</span></li>
					<li><a href='/verify'> <b>Verify</b></a></li>
				<?php } ?>
				<li><a href='/support'> Support</a></li>
				<li><a href='/urls'> URLS</a></li>
				<li><a href='/preferences'> Prefs</a></li>
				<li><a href='/details'> Details</a></li>
				<li><a href='/'> Home </a></li>
			<?php } else { ?>
				<li><a href='/login'> Register</a></li>
				<li><a href='/login'> Login</a></li>
			<?php } ?>
        </ul>
    </div>
</div>
