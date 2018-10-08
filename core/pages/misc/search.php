<?php $mdata = "SmallURL has many urls, why not have a search for one!"; ?>
     <?php $html->PageTitle(_tr("misc/search", "pagetitle"), _tr("misc/search", "pagesubtitle")); ?>

        <div class="wrap">
        <div class='toppadding'></div>
        <br /><br/>
	<?php if (has_param('q') && get_param('q') != "") {
		$term = get_param('q');
		if (has_param('i') && get_param('i') != "") {
			$page = get_param('i');
		} else {
			$page = 1;
		}
		?>
		<body onload="load_search_results('<?php echo $term; ?>','<?php echo $page; ?>');"/>
		<?php
	}
	else {
		$term = "";
	}
	?>
<div class="input-group">
  <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
  <input type="text" value="<?php echo $term; ?>" onkeyup="load_search_results($(this).val(),'1');" class="form-control" placeholder="<?=_tr("misc/search", "typesomething")?>">
</div>
<hr/>
<div id="smallurl_search">
	<div style='text-align:center;'><i><?=_tr("misc/search", "type")?></i></div>
</div>
</div>
<br /><br />