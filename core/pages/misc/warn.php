<?php $html->PageTitle("Explicit URL", "That URL doesn't look good!"); ?>
<div class="wrap">
	<div class='toppadding'></div>
	<h4>&nbsp;</h4>
	<center>
	<strong>Whoa, the following URL has been flagged for containing explicit content:<strong>
	<h3 class="well"><?php echo htmlentities(get_url($id)); ?></h3>
	Only proceed if you are perfectly fine with the possbility of encountering unsafe content.
	<br/>
	<a href="<?php echo htmlentities(get_url($id)); ?>" class="btn btn-large btn-primary">Lets Go!</a> <button class="btn btn-large btn-default" onclick="show_screenshot();">Show a Screenshot</button> <a href="/<?php echo $id; ?>/more" class="btn btn-large btn-primary">Statistics</a>
	<p>
	<div id="screenie" style="display:none">
		<?php if (is_image(get_url($id))) { ?>
		<img class="thumbnail" width="70%" src="data:image/png;base64,<?php echo base64_encode(file_get_contents(get_url($id))); ?>"/>
		<?php } else { ?>
		<img class="thumbnail" width="70%" src="http://smallurl.in/api/get_screenshot.php?id=<?php echo $id; ?>"/>
		<?php } ?>
		<br/>
		<?php if (is_image(get_url($id))) { ?>
		<small>The screenshot above is an inline image using the <a href="http://en.wikipedia.org/wiki/Data_URI_scheme">Data URI Scheme</a>, if you cannot see the image then your browser doesn't support Data URI's!</small>
		<?php } ?>
	</div>
	</center>
</div>