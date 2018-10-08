<?php $mdata = "SmallURL is a URL shortner designed to bring a fully-featured experience to the end user."; ?>
       <div id="ineeda_hero" style='margin-top: 40px;'>
           <div id='ineeda_overlay'>
               <div id='hero_logo'>
               </div>
               <div style='width: 700px; height: 35px;margin:auto; margin-top: 40px;'>
                   <div style='float:left; width: 700px; z-index:20; height: 35px;'>
                       <div class="input-group input-lg" id='url_control_box'>
                          <input class="form-control" id="url_box" placeholder="<?=_tr("misc/home", "textbox_enterurl"); ?>">
                          <span class="input-group-addon"><input id="private_box" type="checkbox" <?php if ($u->core($_SMALLURL['UID'])->get('autopriv')) { echo "CHECKED DISABLED tite=\""._tr("misc/home", "private_enabled")."\""; } ?>> <?=_tr("misc/home", "private")?></span>
						   <span id="again_button" onclick="reset_form();" class="input-group-addon" style="cursor:pointer; display:none;">Again</span>
						  <div id="shorten_button" onclick="generate_smallurl();" class="input-group-addon urlinputs" style="cursor:pointer;"><div class='urlinput'></div></div>
                        </div>
                   </div>
                   <div style='float:left; width: 700px; z-index:20; height: 35px; margin-top: 80px;'>
                       <div style='margin-left: 20px;'>
                    <h3 style='color:white;'> <?=_tr("misc/home", "custom_url"); ?> </h3>
                        <h5 style='color:White;'> <?=_tr("misc/home", "custom_url_insert"); ?> </h5>
                           </div>
                        <div class="input-group input-lg">
                            <span class="input-group-addon">http://smallurl.in/</span>
                            <input class="form-control" type="text" id="custom_box" placeholder="<?=_tr("misc/home", "custom_url_textbox"); ?>">
                            <span class="input-group-addon"><a href="#"><span class="glyphicon glyphicon-random" onclick="generate_custom();"></span></a></span>
                       </div>
                    </div>
               </div>
               <div style='width: 150px; line-height:20px; font-size: 17px; color:white; height: 30px;  text-align: center; position:absolute; bottom:0; left:50%; margin-left:-75px;'>
               <div id='hovermessage'><a href='#' id="dash_link" onclick="expand_dash(); return false;"> <span id="dash_text"><span style='font-size:12px;'>&#x25BC;&nbsp;&nbsp;&nbsp;</span>  <?=_tr("misc/home", "customize"); ?> <span style='font-size:12px;'>&nbsp;&nbsp;&nbsp;&#x25BC;</span></span></a></div>
               </div>
           </div>
        </div>

        <div class="wrap">
            <div class='side'>
            <div id='tweetheader'>
            </div>
            <div id="twitter_update_list">
                <p> <?=_tr("misc/home", "twitter_error")?> </p>
            </div>
            </div>
            <div class='side' style='margin-top:25px;'>
                <h2 style='text-align:center;'> Statistics:</h2> <br />
                <div class='cblock'>
                    <div class='cblock_count'><?php echo htmlentities(url_count_total()); ?>
                    </div>
                    <div class='cblock_lower'>
                    <?=_tr("misc/home", "urls_shortened")?>
                    </div>
                </div>
                <div class='cblock'>
                    <div class='cblock_count'><?php echo htmlentities(url_count_custom()); ?>
                    </div>
                    <div class='cblock_lower'>
                    <?=_tr("misc/home", "custom_urls")?>
                    </div>
                </div>
                <div class='cblock'>
                    <div class='cblock_count'><?php echo count(db::get_array("users",array("verified"=>true))); ?>
                    </div>
                    <div class='cblock_lower'>
                    <?=_tr("misc/home", "active_users")?>
                    </div>
                </div>

               <h2 style='text-align:center; margin-top:140px;'> <?=_tr("misc/home", "smallurl_ontheweb")?></h2> <br /><br />

                <table style="text-align:center; width:100%;">
                    <tbody>
						<tr>
							<td>
								<a href="http://twitter.com/SURLNetwork/"><img alt='Twitter' width="128" src="//static.<?php echo $_SMALLURL['domain']; ?>/<?=$_SMALLURL['THEME'];?>/img/social/twitter.png"></a>
							</td>
							<td>
								<a href="http://blog.smallurl.in/"><img alt='Tumblr' width="128" src="//static.<?php echo $_SMALLURL['domain']; ?>/<?=$_SMALLURL['THEME'];?>/img/social/tumblr.png"></a>
							</td>
							<td>
								<a href="http://facebook.com/SmallURL/"><img alt='Facebook' width="128" src="//static.<?php echo $_SMALLURL['domain']; ?>/<?=$_SMALLURL['THEME'];?>/img/social/facebook.png"></a>
							</td>
						</tr>
						<tr>
							<td>
								<h4>@SURLNetwork</h4>
							</td>
							<td>
								<h4>SmallURL</h4>
							</td>
							<td>
								<h4>SmallURL</h4>
							</td>
						</tr>
					</tbody>
				</table>
            </div>
            <h4>&nbsp;</h4><h4>&nbsp;</h4>
        </div>
        <script src="//static.<?php echo $_SMALLURL['domain']; ?>/global/js/tweet.js"></script>