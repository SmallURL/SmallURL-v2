<?php
$dat = db::get_array("users",array("username"=>$id));
if (count($dat) == 1) {
	$user_id = $dat[0]['id'];
$html->PageTitle($dat[0]['username'], "View a users stats");
?>
<div class="wrap">
		<div class='toppadding'></div>
		<h4>&nbsp;</h4>
	<center>
<style>
.table-user-information > tbody > tr {
    border-top: 1px solid rgb(221, 221, 221);
}
.table-user-information > tbody > tr:first-child {
    border-top: 0;
}
.table-user-information > tbody > tr > td {
    border-top: 0;
}
.page-avatar {
	margin-top:20px;
}
.label:hover {
	cursor:hand;
}
</style>
        <div class="row user-infos">
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xs-offset-0 col-sm-offset-0 col-md-offset-1 col-lg-offset-1">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $dat[0]['username']."'s profile"; ?></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3 col-lg-3">
                                <img class="img-circle page-avatar <?php if ($user_id == 3) { echo "Dave"; } ?>"
									width="100"
									<?php if ($user_id == 1) { echo "onclick=\"bounce_thomas();\" id=\"thomas\""; } ?>
                                     src="<?php echo $u->avatar($user_id);?>"
                                     alt="User Pic">
                            </div>
                            <div class=" col-md-9 col-lg-9 hidden-xs hidden-sm">
                                <strong><?php echo $dat[0]['username']; ?></strong><br>
                                <table class="table table-user-information">
                                    <tbody>
									<tr>
                                        <td>Name</td>
                                        <td><?php $name = $user->core($user_id)->get("name"); if ($name != NULL) { echo $name; } ?></td>
                                    </tr>
                                    <tr>
                                        <td>User level:</td>
                                        <td><?php
										$role = $u->get_role($user_id);
										if ($role) {
											echo $role['name'];
										}
										?></td>
                                    </tr>
                                    <tr>
                                        <td>Registered:</td>
                                        <td><?php $date = $user->core($user_id)->get("regstamp"); if ($name != NULL) { echo dateify($date); } else { echo "N/A"; } ?></td>
                                    </tr>
									<tr>
										<td>Last Seen:</td>
										<td><?php
										$seen = $user->misc($user_id)->get("activity_stamp");
										if ( (time() - $seen) <= 300) {
											echo "<span class='label label-success'>Online</span>";
										} else {
											if ($seen) {
												echo dateify($seen);
											} else {
												echo "Never";
											}
										}
										?></td>
                                    <tr>
                                        <td>SmallURL's</td>
                                        <td><?php $dat = db::get_array("entries",array("user"=>$user_id),"COUNT(*)"); echo $dat[0]['COUNT(*)']; ?></td>
                                    </tr>
									<?php
									$badges = $u->badges($user_id);
									if (count($badges) > 0) {
										echo "<tr>";
                                        echo "<td>Badges</td><td><center>";
                                        foreach ($badges as $badge) {
											$bdat  = $u->get_badge($badge['badge']);
											if (count($bdat) > 0) {
												$col = $bdat['colour'];
												if ($badge['stamp'] != "0") {
													$append = "<br/><small>[".dateify($badge['stamp'])."]</small>";
												} else {
													$append = "";
												}
												echo "<span class='label label-{$col} tip' data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"{$bdat['desc']}{$append}\">{$bdat['text']}</span> ";
											}
										}
										echo "</center></td></tr>";
									}
									?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
						<?php
						if (count(db::get_array("report",array("user"=>$_SMALLURL['UID'],"user"=>$user_id))) <= 0) {
							$report_modal = $modal->create("Report URL",'Please enter a reason for you reporting this user:<br/><br/><textarea class="form-control" placeholder="Enter your reason here, please enter a valid reason why this user has offended you. For an urgent response open a ticket!" id="report_text" style="margin: 0px; width: 529px; height: 118px;" onkeyup="$(\'#len\').html($(\'#report_text\').val().length)"></textarea><br/><span id="len">0</span> Characters',array("danger%Report"=>"report_user('%id%','{$user_id}')","primary%Cancel"=>"close"));
							echo '<input type="button" class="btn btn-danger" value="Report" data-toggle="modal" data-target="#'.$report_modal.'"/>';
						} else {
							echo '<input type="button" class="btn btn-danger" disabled value="Report"/>';
						}
						?>
						<!--<button class="btn btn-sm btn-danger" type="button"
                                    data-toggle="tooltip"
                                    data-original-title="Report this user" id="btn-report-user"><i class="glyphicon glyphicon-remove"></i> Report
						</button>-->
                    </div>
                </div>
            </div>
        </div>
<h4>&nbsp;</h4>
	</center>
</div>
<?php } else {
$html->PageTitle("No such user!", "View a users stats");
?>
<div class="wrap">
	<div class='toppadding'></div>
	<center>
		<h2>Ru-Oh!</h2>
		<hr/>
		The user you're looking for isn't here!
		<br/>
		Maybe they don't exist or have had their username changed!
		<hr/>
		<a href="/user/" class="btn btn-default">User List</a>
	<h4>&nbsp;</h4>
	</center>
</div>
<?php } ?>
