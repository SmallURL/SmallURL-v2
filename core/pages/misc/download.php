<?php $mdata = "SmallURL has had many different add-ons or extensions built by US or our community, here you can download them!"; ?>
     <?php $html->PageTitle(_tr("misc/download", "pagetitle"), _tr("misc/download", "pagesubtitle")); ?>

        <div class="wrap">
        <div class='toppadding'></div>
        <h4> <?=_tr("misc/download", "notjusttheweb")?> </h4>
        <p> <?=_tr("misc/download", "extensions")?> </p>
        <div class='app_box' onmouseup="javascript:document.location = 'https://chrome.google.com/webstore/detail/smallurl/mennajeomepgpgpmflimicendgghiegg?hl=en';">
            <div class='app_icon chrome'>
            </div>
            <div class='app_seperator'>
            </div>
            <div class='app_name'>
            <b> <?=_tr("misc/download", "type")?> </b> Web Browser / Google Chrome
            </div>
            <div class='app_creator'>
            <b> <?=_tr("misc/download", "creator")?> </b> SmallURL
            </div>
            <p></p>
                <?=_tr("misc/download", "chrome_about")?>
            <p><b><?=_tr("misc/download", "install_instructions")?></b><?=_tr("misc/download", "chrome_install")?> </p>
        </div>
        <br />

        <div class='app_box' onmouseup="javascript:document.location = 'http://smallurl.in/downloadables/smallurl.scpt';">
            <div class='app_icon textual'>
            </div>
            <div class='app_seperator'>
            </div>
            <div class='app_name'>
            <b> <?=_tr("misc/download", "type")?> </b> IRC Client Add-on / Textual IRC
            </div>
            <div class='app_creator'>
            <b> <?=_tr("misc/download", "creator")?> </b> Dave Daveson
            </div>
            <p><?=_tr("misc/download", "textual_about")?></p>
            <p><b> <?=_tr("misc/download", "install_instructions")?></b> <?=_tr("misc/download", "textual_install")?></p>
        </div>
        <br />
        <div class='app_box' onmouseup="javascript:document.location = 'https://play.google.com/store/apps/details?id=in.smallurl';">
            <div class='app_icon android'>
            </div>
            <div class='app_seperator'>
            </div>
            <div class='app_name'>
            <b> <?=_tr("misc/download", "type")?> </b> Mobile OS / Android
            </div>
            <div class='app_creator'>
            <b> <?=_tr("misc/download", "creator")?> </b> SmallURL
            </div>
            <p><?=_tr("misc/download", "android_about")?></p>

            <p><b><?=_tr("misc/download", "install_instructions")?></b> <?=_tr("misc/download", "android_install")?></p>

        </div>
<hr />
        </div>