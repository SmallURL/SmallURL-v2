<?php $mdata = "Oh No! We can't find this page :/"; ?>
<?php
if ($sub != "account") {
	$html->PageTitle(_tr("misc/404", "pagetitle"), _tr("misc/404", "pagesubtitle"));
} else {
	$html->AccPageTitle(_tr("misc/404", "pagetitle"), _tr("misc/404", "pagesubtitle"));
}
?>
<div class="wrap">
	<div class='toppadding'></div>
	<h4>&nbsp;</h4>
<div class="page-header">
	<h1><?=_tr("misc/404", "whoops");?></h1>
</div>
	<p><?=_tr("misc/404", "cantfind");?></p>
	<p><?=_tr("misc/404", "trychecking");?></p>
	<br />
	<br />
</div>