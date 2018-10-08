<div id='footer'>
	<div id='footer_overlay'>
		<div class='wrap'>
			<div class="left">
				<div class="footerbox">
					<ul class="footerlinks">
						<li class="footertitle"> SmallURL <?=_tr("smalltemplate/footer", "network")?>: </li>
						<li> </li><li><a href="/">  SmallURL <?=_tr("smalltemplate/footer", "shortener")?></a></li>
						<li> </li><li><a href="http://smallirc.in/"> SmallIRC  <?=_tr("smalltemplate/footer", "chatnetwork")?></a></li>
						<li> </li><li><a href="http://smallimage.in/">  SmallImage <?=_tr("smalltemplate/footer", "hosting")?></a></li>
						<li> </li><li><a href="http://smallpaste.in/">  SmallPaste <?=_tr("smalltemplate/footer", "pastebin")?></a></li>
					</ul>
				</div>
				<div class="footerbox">
					<ul class="footerlinks">
						<li class="footertitle"> Contact Us:</li>
						<li> <a href="mailto:support@smallurl.in"> support@smallurl.in</a></li>
						<?php if ($_SMALLURL['LOGGEDIN']) { ?>
						<li> <a href="//account.<?=SITE_URL?>/support/"> <?=_tr("smalltemplate/footer", "supportcenter")?> </a></li>
						<?php } ?>
						<li> <a href="https://twitter.com/SURLNetwork">Twitter</a> </li>
						<li> <a href="https://facebook.com/SmallURL">Facebook </a></li>
						<li> <a href="https://plus.google.com/111403933600182088671" rel="publisher">Google+ </a></li>
					</ul>
				</div>
				<div class="footerbox">
					<ul class="footerlinks">
						<li class="footertitle"> <?=_tr("smalltemplate/footer", "information")?>:</li>
						<li><a href="/privacy"> <?=_tr("smalltemplate/footer", "privacypolicy")?> </a></li>
						<li><a href='http://blog.smallurl.in'> SmallURL <?=_tr("smalltemplate/footer", "blog")?> </a> </li>
					</ul>
				</div>
			</div>
			<div class='right'>
				<style type='text/css' scoped> #footer_logo { margin-top:55px;} </style>
				<div id='footer_logo'> </div>
				<?php
				if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
					$ver = 'N/A';
					$str = 'Windows Devel';
					$type = 'default';
				} else {
					if(SITE_MODE != "DEVEL") {
						$ver = SURL_VERSION;
						$type = 'success';
					} else {
						$ver = "DEVEL-".SURL_VERSION;
						$type = 'danger';
					}
					$str = substr(shell_exec("git rev-parse HEAD"),0,12);

				}?>
				<div style='margin-top:130px; text-align:right; opacity:0.9; color:white;'> <?=_tr("smalltemplate/footer", "servicestatus")?>:&nbsp; <span class="label label-<?=$type?>"><?=$ver?></span>&nbsp; Rev:&nbsp; <span class="label label-primary"><?=$str?>
</span></div>
			</div>
			</div>
		</div>
	</div>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-45810158-1', 'smallurl.in');
  ga('send', 'pageview');

</script>
