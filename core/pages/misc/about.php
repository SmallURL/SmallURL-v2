<?php $mdata = "A little bit of information about the team behind SmallURL, and how we began in the first place."; ?>
	<script src="//static.<?php echo $_SMALLURL['domain']; ?>/js/smallurl_extra.js"></script>
	<script src="//static.<?php echo $_SMALLURL['domain']; ?>/js/smallurl_extra_extra.js"></script>
     <?php $html->PageTitle(_tr("misc/about", "pagetitle"), _tr("misc/about", "pagesubtitle")); ?>

        <div class="wrap">
        <div class='toppadding'></div>

		<p>
			<h3> <?= _tr("misc/about", "whatis_smallurl") ?> </h3>
			<?= _tr("misc/about", "whatis_smallurl_p1") ?>
			<br/>
			<?= _tr("misc/about", "whatis_smallurl_p2") ?>
			<br/>
			<?= _tr("misc/about", "whatis_smallurl_p3") ?>
		</p>

		<hr/>

        <p>
			<h3> <?= _tr("misc/about", "features") ?> </h3>
			<?= _tr("misc/about", "features_p1") ?>
			<br/>
			<?= _tr("misc/about", "features_p2") ?>
			<br/>
			<?= _tr("misc/about", "features_p3") ?>
			<br/>
			<?= _tr("misc/about", "features_p4") ?>
		</p>

		<hr/>

		<p>
			<h3>The Team</h3>
			<!-- Thomas Edwards -->
			<div class='team_box'>
				<div class='team_icon thomas' id="thomas" onclick="bounce_thomas();">
				</div>
				<div class='team_seperator'>
				</div>
				<div class='team_name'>
				<b> Name: </b>Thomas Edwards [<a href="/user/<?php echo $u->core("1")->get("username"); ?>"><?php echo $u->core("1")->get("username"); ?></a>]
				</div>
				<div class='team_role'>
				<b> Role: </b> Founder and Head Developer of the project.
				</div>
				<b> So, who are you? </b>
				<p>I'm Thomas, The founder and creator of SmallURL. I spend my spare time hacking together PHP Scripts to do allsorts of useless things. I spend most of my spare time on IRC chatting to friends and testing PHP Scripts I've made.</p>
			</div>
		</p>

		<hr/>

		<p>
			<h3><?= _tr("misc/about", "tools_weuse") ?></h3>
			<p>
				<?= _tr("misc/about", "tools_weuse_p1") ?>
				<br/>
				<?=_tr("misc/about", "tools_weuse_p2")?>
			</p>
			<div style="margin-left:30px; margin-top:30px;">
				<p>
					<h4><?=_tr("misc/about", "tools_heatmap_name")?> <small><?=_tr("misc/about", "tools_heatmap_by")?></small></h4>
					<?=_tr("misc/about", "tools_heatmap_about")?>
					<br/>
					<a href='http://www.patrick-wied.at/static/heatmapjs/index.html'>http://www.patrick-wied.at/static/heatmapjs/index.html</a>
				</p>
				<br/>
				<p>
					<h4><?=_tr("misc/about", "tools_useragent_name")?> <small><?=_tr("misc/about", "tools_useragent_by")?></small></h4>
					<?=_tr("misc/about", "tools_useragent_about")?>
					<br/>
					<a href='http://donatstudios.com/PHP-Parser-HTTP_USER_AGENT'>http://donatstudios.com/PHP-Parser-HTTP_USER_AGENT</a>
				</p>
				<br/>
				<p>
					<h4><?=_tr("misc/about", "tools_boopstrap_name")?> <small><?=_tr("misc/about", "tools_bootstrap_by")?></small></h4>
					<?=_tr("misc/about", "tools_bootstrap_about")?>
					<br/>
					<a href='http://getbootstrap.com/'>http://getbootstrap.com/</a>
				</p>
				<br/>
				<p>
					<h4><?=_tr("misc/about", "tools_jquery_name")?> <small><?=_tr("misc/about", "tools_jquery_by")?></small></h4>
					<?=_tr("misc/about", "tools_jquery_about")?>
					<br/>
					<a href='http://jquery.com/'>http://jquery.com/</a>
				</p>
				<br/>
				<p>
					<h4><?=_tr("misc/about", "tools_phonegap_name")?> <small><?=_tr("misc/about", "tools_phonegap_by")?></small></h4>
					<?=_tr("misc/about", "tools_phonegap_about")?>
					<br/>
					<a href='http://phonegap.com/'>http://phonegap.com/</a>
				</p>
			</div>
		</p>

		<hr/>

		<p>
			<h3><?=_tr("misc/about", "contactus")?></h3>
			<?=_tr("misc/about", "gotabug")?>
			<br/>
			<?=_tr("misc/about", "youareinluck")?>
			<br/>
			<?=_tr("misc/about", "tweetus")?> <a href="//twitter.com/SURLNetwork">@SURLNetwork</a> <?=_tr("misc/about", "openticket")?> <a href="//account.<?=SITE_URL?>/support"><?=_tr("misc/about", "support")?></a>.
		</p>

		<h4>&nbsp;</h4>
	</div>
		<script>
		$(".Dave").mouseover(function(){
			if ($("#spin").length == 0) {
				$("<audio></audio>").attr({
					'id':'spin',
					'src':'http://smallurl.in/ajax/youspinme.mp3',
					'volume':0.4,
					'autoplay':'autoplay'
				}).appendTo("body");
			} else {
				$('#spin')[0].currentTime = 0;
				$('#spin')[0].play();
			}
		});
		</script>
