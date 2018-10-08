<?php if ($_SMALLURL['LOGGEDIN']) { ?>
<?php $html->AccPageTitle("Account Preferences", "Account Preferences"); ?>

<div class="wrap">
<div class='toppadding'></div>

	<h4>&nbsp;</h4>
	<h4 style='text-align:center;'><strong>Account Preferences.</strong></h4>
	<p style='text-align:center;'>From here you can change different settings on your account!</p>
	<hr>

		<?php
			$info = db::get_array("users",array("id"=>$_SMALLURL['UID']));
			if (count($info) == 1) {
				if ($info[0]['passreset'] === "1") {
					show_alert('You have requested a password reset on your account and it has not been sorted!\nCheck your Email!');
				}
			}
			else {
				show_alert('INTERNAL ERROR LN:'.__LINE__);
			}
		?>
		<div style='width: 500px; margin:auto;'>
		<form onchange="change_prefs();">
			<h4>General Preferences:</h4>
			<?php
			$themes = array();
			$themes['smallurl'] = "SmallURL";
			$themes['terminal'] = "Green Terminal";
			$themes['mlp'] = "My Little Pony";
			?>
			<p>Default Theme: <select id="thm">
				<?php foreach ($themes as $tid => $tnm) {
					if ($tid == $user->misc()->get('theme')) {
						echo '<option SELECTED value="'.$tid.'">'.$tnm.'</option>';
					} else {
						echo '<option value="'.$tid.'">'.$tnm.'</option>';
					}
				} ?>
				</select></p>
			
			<h4>Account safety options:</h4>

			<p><input id="geoloc" <?php if ($u->core()->get('hidegeo')) { echo 'CHECKED'; } ?> type="checkbox"> Hide my GeoLocation</input></p>
			<p><input id="allsafe" <?php if ($u->core()->get('autosafe')) { echo 'CHECKED'; } ?> type="checkbox"> Display safe page on all URLs - Shows the Safepage when you click a SmallURL</input></p>
			<p><input id="allpriv" <?php if ($u->core()->get('autopriv')) { echo 'CHECKED'; } ?> type="checkbox"> Auto privatise new URLS - Make all URLs you shorten from this point on private</input></p>
			</form>
		<p><button id="savebtn" class="btn btn-primary" disabled="true" onclick="save_prefs();"><img id="saving" style="display:none;" src="//static.<?=SITE_URL?>/smallurl/img/icons/loading.gif"/> Save Changes</button></p>

		<p></p><br />
		</div>
</div>
<?php } else {
	// Not logged in.
	$continue = "//account.".$_SMALLURL['domain']."/preferences";
	header("Location: //account.".$_SMALLURL['domain']."/login");
} ?>