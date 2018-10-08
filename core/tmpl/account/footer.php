<?php /*
<div id='footer'>
	<div id='footer_overlay'>
		<div class='wrap'>
			<div class="left">
				<div class="footerbox">
					<ul class="footerlinks">
						<li class="footertitle"> Important Links: </li>
						<li> </li><li><a href="/">  Shorten A URL </a></li>
						<li> </li><li><a href="/stats"> 	Statistics </a></li>
						<li> </li><li><a href="/account">  My Account </a></li>
						<li> </li><li><a href="/download">  Download </a></li>
					</ul>
				</div>
				<div class="footerbox">
					<ul class="footerlinks">
						<li class="footertitle"> Contact Us: </li>
						<li> <a href="mailto:hello@smallurl.in"> hello@smallurl.in</a></li>
						<li> <a href="/support"> Support Center</a></li>
						<li> <a href="https://small.com/twitter/">Twitter</a> </li>
					</ul>
				</div>
				<div class="footerbox">
					<ul class="footerlinks">
						<li class="footertitle"> Information:</li>
						<li><a href="http://status.smallurl.in"> Service Status </a></li>
						<li><a href="/privacy"> Privacy Policy </a></li>
						<li><a href='http://blog.smallurl.in'> SmallURL Blog </a> </li>
					</ul>
				</div>
			</div>
			<div class='right'>
				<style> #footer_logo { margin-top:55px;} </style>
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
						$ver = "DEVEL";
						$type = 'danger';
					}
					$str = substr(shell_exec("git rev-parse HEAD"),0,12);

				}?>
				<div style='margin-top:130px; text-align:right; opacity:0.9; color:white;'> Version:&nbsp; <span class="label label-<?=$type?>"><?=$ver?></span>&nbsp; Rev:&nbsp; <span class="label label-primary"><?=$str?>
</div>
			</div>
			</div>
		</div>
	</div>
</div>
*/?>
<div id='footer' >
        <div class='wrap' >
            <div style='width: 200px; height: 110px;float:left; margin-top: 20px; margin-left:15px; margin-right:10px;'>
                <div style='padding:10px; '>
                Our Services:
                <hr /><span style='font-size:14px;'>
                SmallURL: <a href='https://smallurl.in'>Shorten URL</a> <br />
                <span style='margin-top:10px;'><a href=''>More coming Soon!</a> <br /></span>
                <?php /*<span style='margin-top:10px;'>SmallImage: <a href=''>Upload Images</a> <br /></span>
                <span style='margin-top:10px;'>SmallPaste: <a href=''>Share Text</a> <br /><br /></span> */ ?>
                <br />
            </span>

                </div>
            </div>
            <div style='width: 200px; height: 110px;float:left; margin-top: 20px; margin-left:10px; margin-right:10px;'>
            	                <div style='padding:10px; '>
                Contact Info:
                <hr /><span style='font-size:14px;'>
                <a href='mailto:hello@smallurl.in'>hello@smallurl.in</a> <br />
				<span style='margin-top:10px;'><a href='/support'> Support Center</a> <br /></span> <br />
                <br />
            </div></div>


            <div style='width: 200px; height: 110px;float:left; margin-top: 20px; margin-left:10px; margin-right:10px; '>
            	                <div style='padding:10px; '>
                Information:
                <hr /><span style='font-size:14px;'>
                <a href='http://status.smallurl.in/'>Service Status</a> <br />
                <span style='margin-top:5px;'><a href='http://account.smalldev.dev/privacy'>Privacy Policy</a> <br /></span>
                <span style='margin-top:5px;'><a href='http://blog.smallurl.in/'>SmallURL Blog</a> <br /><br /></span>
                <br />
                </div>
            </div>
            <div style='width: 200px; height: 110px;float:left; margin-top: 20px; margin-left:10px; margin-right:10px;L'>
                <div style='padding:10px; line-height:100px;'>
                    <img style='width: 200px; margin-top:0px;' src='//static.<?=SITE_URL?>/smallurl/img/style/small_logo.png' style='margin-top:35px;'/>
                </div>
            </div>
        </div>
    </div>



<div id='foot'>
    <div class='wrap'>
        &copy; 2013 - 2014 All rights reserved | Part of the SmallURL Network</a>
        <div style='float:right'>
        	<a href='https://twitter.com/smallurlservice'>Twitter</a> | <a href='https://facebook.com/smallurl'>Facebook</a>
        </div>
    </div>
</div>






