<?php
$html->AccPageTitle("View Application", "See whats up with this guy.");
if ($_SMALLURL['LOGGEDIN']) {
include("asset/nav.php");
}
$app = db::get_array("apps",array("MD5(`id`)"=>$id));
if (count($app) == 1) { ?>
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
.permissions {
	margin-left:50px;
	margin-top:10px;
	margin-bottom:10px;
}
.permissions li {
	margin-left:50px;
	list-style:initial !important;
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
					<?php echo $app[0]['title']; ?>
					<?php if ($app[0]['official']) echo '<span class="label label-small label-success">Official App</span>'; ?>
					<small>
						By <a href="<?php echo $u->link($app[0]['user']); ?>"><?php echo $u->display_name($app[0]['user']); ?></a>
					</small>
					<?php if ($_SMALLURL['LOGGEDIN']) { ?>
					<!--<button type="button" class="btn btn-xs btn-danger pull-right">Report</button>-->
					<?php } ?>
				</h3>
				<p>
					<b>Description:</b>
					<br/>
					<?=$support->clean_body($app[0]['desc']);?>
				</p>
				<p>
					<b>Created:</b>
					<br/>
					<?=dateify($app[0]['stamp']);?>
				</p>
			</td>
		</tr>
	</table>
	<?php if ($_SMALLURL['LOGGEDIN']) echo "<hr/>"; ?>
	<?php $smallurl->display_errors(); ?>
	<?php
		if ($_SMALLURL['LOGGEDIN']) {
			if ($_SMALLURL['UID'] === $app[0]['user']) {
				// This user owns it.
				?>
				<a class="btn btn-default btn-xs pull-right" href="/apps/edit/<?=$id?>">Edit</a>
				<h4><b><?=$app[0]['title']?></b> has been created by you.</h4>
				<br/>
				<p><b>Application Public Token</b></p>
				<p><input readonly class="form-control" value="<?=$app[0]['pubtoken'];?>"/></p>
				<p><b>Application Private Token</b></p>
				<p><input readonly class="form-control" value="<?=$app[0]['privtoken'];?>"/></p>
				<?php if (strlen($app[0]['callback']) > 0) { ?>
				<p><b>Application CallBack URL</b></p>
				<p><input readonly class="form-control" value="<?=$app[0]['callback'];?>"/></p>
				<?php } ?>
				<button class="btn btn-danger pull-right">Delete Application</button>
				<h3>&nbsp;</h3>
				<hr/>
				<?php
			}
			// Check if we've given this app Access.
			$auth = db::get_array("auth",array("app"=>$app[0]['id'],"user"=>$_SMALLURL['UID']));
			if (count($auth) > 0) {
				?>
				<h4>You've given <b><?=$app[0]['title'];?></b> access to:</h4>
				<ul class="permissions">
					<b>Your Account:</b>
					<li>Retrieve your Name</li>
					<li>Retrieve your Username</li>
					<li>Retrieve your E-Mail</li>
					<?php
						foreach (json_decode($app[0]['perms'],true) as $perm => $val) {
							if ($perm == "smallurl") {
								echo "<b>SmallURL Service:</b>";
								echo "<li>Retrieve your Shortened URLs</li>";
								echo "<li>Shorten URLs as you</li>";
							} else if ($perm == "smallpaste") {
								echo "<b>SmallPaste Service:</b>";
								echo "<li>Retrieve your Pasted Text</li>";
								echo "<li>Shorten Pastie URLs as you</li>";
							}
						}
					?>
				</ul>
				<button onclick="if(window.confirm('Revoke Authorisation? Are you sure?')) location.href='/do/apps/revoke_auth.php?appid=<?=$id;?>'" class="btn btn-default pull-right">Revoke Permissions</button>
				<h3>&nbsp;</h3>
				<?php
			} else {
				?>
					<center>
						<h3>&nbsp;</h3>
						<h4>You haven't authorised this Application to access your account.</h4>
						<h3>&nbsp;</h3>
					</center>
				<?php
			}
		}
	?>
</div>

<!-- Close ticket script/html -->

<!-- Modal -->
<div class="modal fade" id="close_ticket_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Close Ticket?</h4>
      </div>
      <div class="modal-body">
		Are you sure you wish to close this ticket?
		<br/>
		Closing the ticket marks the support issue as resolved.
      </div>
      <div class="modal-footer">
        <button type="button" onclick="close_ticket('<?php echo $id; ?>');" class="btn btn-primary">Close Ticket</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
// Cheeky hack to make it so people cant scroll when its open.
$('#close_ticket_modal').on('show.bs.modal', function () {
  $('html').css('overflow','hidden');
})
$('#close_ticket_modal').on('hide.bs.modal', function () {
  $('html').css('overflow','visible');
})
function close_ticket(tick_id) {
	$.getJSON("/do/support/close_ticket.php?id="+tick_id,function(data) {
		if (data.res == true) {
			// All done. Refresh :D
			document.location.href = document.location.href;
		} else {
			alert(data.msg);
		}
	});
}
</script>
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