 <?php $html->PageTitle("Home", null,true); ?>
<center>
	<div class="page-header">
		<h2>SmallURL</h2><h4>Mobile</h4>
	</div>
      <div class="well">
			<div id="alert" class="alert alert-error" style="display:none">
				<small>
				<strong>Oopsie!</strong>
				<br/>
				There was an issue shortening your URL!
				</small>
			</div>
			<span id="form">
				<p>The simple URL shortener<p>
				<p>Pop your URL in the box below and hit Shorten.</p>
				<p><input id="long_url" type="text" class="input-xxlarge" placeholder="Paste a url here"></input></p>
				<p id="help_btn"><a href="#" onclick="$(this).parent().hide(); $('#help').show('slow');">How do I?</a></p>
				<p id="help" style="display:none;" ><i><small>On most devices you can do this by holding your finger down on the text box and hitting paste!</small></i></p>
				Hit customize to customize your URL!
				<span id="custom_form" style="display:none">
					<hr>
					<p><h4>Custom URL:</h4></p>
					<p><input type="text" id="custom_url" placeholder="Put a custom URL name in here..."/></p>
				</span>
				<p><input class="btn btn-primary btn-medium" type="submit" value="Shorten" onclick="call_shortener();"/> <button class="btn btn-medium" id="custom_btn" onclick="show_custom();">Customize</button></p>
			</span>
			<span id="shortened" style="display:none">
				<p>Heres your shiny and short URL:</p>
				<p><input id="short_url" type="text" class="input-xxlarge" READONLY /></p>
				<p><button id="again" onclick="reset_form();" class="btn btn-medium btn-primary" >Again</button></p>
			</span>
      </div>

      <!-- Example row of columns -->
      <div class="row">
		<center>
        <div class="span4">
          <h2 id="stats_total"><?php echo htmlentities(url_count_total()); ?></h2>
          <p>URL's have been shortened!</p>
        </div>
        <div class="span4">
          <h2 id="stats_custom"><?php echo htmlentities(url_count_custom()); ?></h2>
          <p>URL's have been customly Shortened!</p>
       </div>
        <div class="span4">
          <h2 id="stats_random"><?php echo htmlentities(url_count_rand()); ?></h2>
          <p>URL's have been randomly shortened!</p>
        </div>
		</center>
      </div>
	  </center>