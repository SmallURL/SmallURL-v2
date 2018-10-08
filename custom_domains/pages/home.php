      <!-- Main hero unit for a primary marketing message or call to action -->
	  <div class="page-header">
		<h2><?php echo htmlentities($_SERVER['HTTP_HOST']); ?></h2>
	</div>
      <div class="well">
			<div id="alert" class="alert alert-error" style="display:none">
				<small>
				<strong>There was an issue shortening your URL!</strong>
				<br/>
				<span id="alert_error">The API told us. NOTHING!</span>
				</small>
			</div>
			<span id="form">
				<p>The simple URL shortener, Pop your URL in the box below and hit Shorten.</p>
				<p><input id="long_url" type="text" class="form-control input-xxlarge" placeholder="Paste a url here"></input></p>
				<?php if (config('custom')) { echo "Hit customize to customize your URL!"; } ?>
				<span id="custom_form" style="display:none">
					<p><h4>Custom URL:</h4></p>
					<p>http://<?php echo htmlentities($_SERVER['HTTP_HOST']); ?>/<input class="form-control" type="text" id="custom_url" placeholder="Put a custom URL name in here..."/></p>
				</span>
				<p><input class="btn btn-primary btn-large" type="submit" value="Shorten" onclick="call_shortener();"/> <button class="btn btn-large btn-default" id="custom_btn" onclick="show_custom();">Customize</button> <?php if (LOGGEDIN) { ?><!--<input id="#private" type="checkbox"> Hide from SmallURL List</input>--><?php } ?></p>
			</span>
			<span id="shortened" style="display:none">
				<p>Heres your shiny and short URL:</p>
				<p>
				<input id="short_url" type="text" class="form-control input-xxlarge" READONLY />
				</p>
				<p><button id="again" onclick="reset_form();" class="btn btn-large btn-primary" >Again</button> <a class="btn btn-large btn-primary" href="#" id="tweet" target="_Blank">Tweet</a></p>
			</span>
      </div>

      <!-- Example row of columns -->
      <div class="row">
		<center>
        <div class="span4">
          <h2 class="stats_total"><?php echo htmlentities(url_count_total()); ?></h2>
          <p>URL's have been shortened!</p>
        </div>
        <div class="span4">
          <h2 class="stats_custom"><?php echo htmlentities(url_count_custom()); ?></h2>
          <p>URL's have been customly Shortened!</p>
       </div>
        <div class="span3">
          <h2 class="stats_random"><?php echo htmlentities(url_count_rand()); ?></h2>
          <p>URL's have been randomly shortened!</p>
        </div>
		</center>
      </div>