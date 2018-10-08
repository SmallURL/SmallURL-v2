<?php if ($_SMALLURL['LOGGEDIN']) { ?>
<?php $html->AccPageTitle("Create Application", "Making your own Application has never been easier!"); ?>
<?php include("asset/nav.php"); ?>
<div class="wrap">
	<div class='toppadding'></div>
	<h3>Create Application</h3>
	<hr>
	<?php $smallurl->display_errors(); ?>
	<form action="/do/apps/create_application.php" method="post" enctype="multipart/form-data">
		<fieldset>
			<p><b>Title</b></p>
			<p><input name="title" placeholder="A fancy App needs a Fancy Name!" type="text" class="form-control"></p>
			<p><b>Description</b></p>
			<p><input name="desc" placeholder="What's your application do?" type="text" class="form-control"></p>
			<p><b>Callback URL (Optional)</b></p>
			<p><input name="callback" placeholder="Upon auth the User is redirected here along POST containing the user token." type="text" class="form-control"></p>
			<p><b>Application Icon</b></p>
			<p>
				<img id="img_prev" src="http://avatar.smallurl.in/" alt="Application Icon" />
				<p/>
				<input type='file' name="icon" onload="readURL(this);" onchange="readURL(this);" /><br/>
			</p>
		</fieldset>
		<div class="form-actions pull-right">
			<button type="submit" class="btn btn-primary">Create</button>
			<button type="button" onclick="document.location.href='/apps';" class="btn btn-default">Cancel</button>
		</div>
		<h4>&nbsp;</h4>
		<h4>&nbsp;</h4>
	</form>
	<hr>
</div>
<style>
	#img_prev {
		max-width:128px;
		max-height:128px;
	}
</style>
<script>
var blank="http://avatar.smallurl.in/";
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#img_prev')
            .attr('src', e.target.result)
            .height(200);
        };

        reader.readAsDataURL(input.files[0]);
    }
    else {
      var img = input.value;
        $('#img_prev').attr('src',img).height(200);
    }
    $("#x").show().css("margin-right","10px");
}
$(document).ready(function() {
  $("#x").click(function() {
    $("#img_prev").attr("src",blank);
    $("#x").hide();  
  });
});
</script>
<?php } else { header('Location: /'); } ?>