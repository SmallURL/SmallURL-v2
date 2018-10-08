<?php $html->AccPageTitle("Logout", "Have a nice day!"); ?>
     <div class="wrap">
        <div class='toppadding'></div>
<h4> Logging you out.. </h4>
<?php session_destroy(); ?>
<script>
window.location = "/"
</script>
</div>