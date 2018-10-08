<?php
$html->AccPageTitle("View Application", "See whats up with this guy.");
if ($_SMALLURL['LOGGEDIN']) {
include("asset/nav.php");
}
$app = $application->get($application->hashtoid($id));
// Does it exist and do we own it?
if ($app && $_SMALLURL['UID'] === $app['user']) { ?>
<style>
.well {
	border-radius:0px;
}
.detail {
	width:100%;
}
.detail tr  td {
	line-height: 1.428571429;
	vertical-align: top;
	padding:8px;
}
hr {
	border-width:2px;
	border-color:silver;
}
.app-icon {
	max-width:128px;
	max-height:128px;
}
.icon-show {
	vertical-align:middle !important;
	max-width:150px;
	width:130px;
}
</style>
<div class="wrap">
	<div class='toppadding'></div>
	<table class="detail">
		<tr>
			<td class="icon-show" rowspan="2">
				<center>
					<?php
						if (file_exists($_SERVER['DOCUMENT_ROOT']."/../cache/apps/".$id.".png")) {
							$img = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../cache/apps/".$id.".png"));
						} else {
								$img = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../avatar/unknown.png"));
						}
						echo "	<img class='app-icon' src='data:image/png;base64,{$img}'/>";
					?>
				</center>
			</td>
		</tr>
		<tr>
			<td>
				<h3>
					<?php echo $app['title']; ?>
					<?php if ($app['official']) echo '<span class="label label-small label-success">Official App</span>'; ?>
					<small>
						By <a href="<?php echo $u->link($app['user']); ?>"><?php echo $u->display_name($app['user']); ?></a>
					</small>
				</h3>
				<p>
					<b>Description:</b>
					<br/>
					<?=$support->clean_body($app['desc']);?>
				</p>
				<p>
					<b>Created:</b>
					<br/>
					<?=dateify($app['stamp']);?>
				</p>
			</td>
		</tr>
	</table>
	<?php if ($_SMALLURL['LOGGEDIN']) echo "<hr/>"; ?>
	<?php
		if ($_SMALLURL['LOGGEDIN']) {
			if ($_SMALLURL['UID'] === $app['user']) {
				// This user owns it.
				?>
				<form action="/do/apps/update_app.php" method="post">
					<p><b>Application Name</b></p>
					<p><input class="form-control" readonly onclick="alert('Please open a support ticket if you wish to change your applications name.');" value="<?=$app['title'];?>"/></p>
					
					<p><b>Application Description</b></p>
					<p><input name="desc" class="form-control" value="<?=$app['desc'];?>"/></p>
					
					<p><b>Application CallBack URL</b></p>
					<p><input name="callback" class="form-control" value="<?=$app['callback'];?>"/></p>
					<button type="button" class="btn btn-danger pull-right">Delete Application</button>
					<h3>&nbsp;</h3>
					<hr/>
					
					<p><b>Application Public Token</b></p>
					<p><input id="pub-token" readonly class="form-control" value="<?=$app['pubtoken'];?>"/></p>
					<p><b>Application Private Token</b></p>
					<p><input id="priv-token" readonly class="form-control" value="<?=$app['privtoken'];?>"/></p>
					<button type="button" onclick="regen_tokens('<?=md5($app['id']);?>');" class="btn btn-danger pull-right">Regenerate Tokens</button>
					<h3>&nbsp;</h3>
					<hr/>
					<input type="hidden" name="appid" value="<?=$id;?>"/>
				</form>
				<?php
			}
			
		}
	?>
</div>
<?php
} else {
	// Ticket not found!
?>
<div class="wrap">
	<div class='toppadding'></div>
	<h4>&nbsp;</h4>
	<h3>Ru-Oh</h3>
	<hr>
	<p>The application you're looking for doesn't actually exist.</p>
	<p>Maybe you're after a deleted application, or you've mistyped a URL?</p>
	<hr>
</div>
<?php
}
?>