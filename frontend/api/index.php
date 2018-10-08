<?php
// Call the official API instead of having a seperate one.
// We HAVE to do this due to cross-domain rules.
include($_SERVER['DOCUMENT_ROOT']."/../api/v1/index.php");
?>
