<?php global $_SMALLURL; global $u; ?>
<div id='navigation'>
    <div class='wrap'>
        <ul id='nav'>
            <li><a href='/' id='nav_logo'> &nbsp;<div style='display:none;'>Home</div> </a></li>
            <!--<li><a href='/list'> <?=_tr("smalltemplate/navigation", "trending")?></a></li> --> <!-- Dave (14-08-14), pulled due to errors, cba to fix -->
            <li><a href='/stats'> <?=_tr("smalltemplate/navigation", "stats")?></a></li>
			<li><a href='/search'> <?=_tr("smalltemplate/navigation", "search")?> </a></li>
            <li><a href='/download'> <?=_tr("smalltemplate/navigation", "download")?> </a></li>
            <li><a href='/privacy'> <?=_tr("smalltemplate/navigation", "privacy")?> </a></li>
			<li><a href='/about'> <?=_tr("smalltemplate/navigation", "about")?> </a></li>
            <?php if ($u->level($_SMALLURL['UID']) >= 70) { ?><li><a href="//admin.<?=SITE_URL?>/"><?=_tr("smalltemplate/navigation", "admin")?></a></li><?php } ?>

            <?php if ($_SMALLURL['LOGGEDIN']) { ?>
            <li class='nav_right'><a id='acc_hov' href='javascript:ToggleNav();'><?=_tr("smalltemplate/navigation", "hello")?>, <?php echo get_user($_SMALLURL['UID']);?> <?php echo "<img class=\"avatar\"width=\"25px\" src=\"".$u->avatar($_SMALLURL['UID'])."\"/>";?></a></li>
            <?php } else { ?>
            <li class='nav_right'><a href='//account.<?php echo $_SMALLURL['domain'];?>/login'><?=_tr("smalltemplate/navigation", "loginregister")?></a></li>
            <?php } ?>
        </ul>
        <?php if ($_SMALLURL['LOGGEDIN']) { ?>
        <div id='dropdownnav'>
            <ul id='navdown'>
            <li><a href='//account.<?php echo $_SMALLURL['domain']; ?>'><?=_tr("smalltemplate/navigation", "myaccount")?> </a></li>
            <li><a href='//account.<?php echo $_SMALLURL['domain']; ?>/urls'> <?=_tr("smalltemplate/navigation", "myurls")?> </a></li>
            <li><a href='//account.<?php echo $_SMALLURL['domain']; ?>/details'> <?=_tr("smalltemplate/navigation", "mydetails")?> </a></li>
            <li><a href='//account.<?php echo $_SMALLURL['domain']; ?>/support'> <b> <?=_tr("smalltemplate/navigation", "mysupport")?> </b></a></li>
            <li><a href='//account.<?php echo $_SMALLURL['domain'];?>/logout'> <?=_tr("smalltemplate/navigation", "logout")?> </a></li>
            </ul>
        </div>
        <?php } ?>
    </div>
</div>
