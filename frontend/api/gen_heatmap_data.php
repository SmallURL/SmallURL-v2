<?php
// http://freegeoip.net/json/adsl-75-28-130-231.dsl.irvnca.sbcglobal.net
header("Content-Type: text/plain");
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
echo gen_heatmap();
?>