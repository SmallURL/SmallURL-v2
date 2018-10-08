<?php $mdata = "Oh, Well. Somthing appears to be broken :/"; ?>
<?php $html->PageTitle(_tr("misc/500", "pagetitle"), _tr("misc/500", "pagesubtitle")); ?>
<div class="wrap">
	<div class='toppadding'></div>
	<h4>&nbsp;</h4>
<div class="page-header">
	<h1><?=_tr("misc/500", "whoops")?></h1>
</div>
	<p><?=_tr("misc/500", "itsbroken")?></p>
	<p><?=_tr("misc/500", "tryreload")?></p>
	<p>
		<div class="well">
			<center>
			<?php
			$err = error_get_last();
			echo "<b>{$err['message']}</b><br/>";
			echo "<b>Encountered in:</b> {$err['file']}<br/>";
			echo "<b>On line:</b> {$err['line']}";
			?>
			</center>
		</div>
	</p>
	<br />
	<br />
</div>