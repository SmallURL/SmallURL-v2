<?php
include($_SERVER['DOCUMENT_ROOT']."/../core/smallurl.php");
if (isset($_POST['username']) && $_POST['username'] != '' && isset($_POST['email']) && $_POST['email'] != '' && isset($_POST['password']) && $_POST['password'] != '') {
        if(adduser($_POST['username'], $_POST['email'], $_POST['password']) === true) {
        header("Location: /?p=register&id=2");
    } else {
    header("Location: /?p=register&id=99");   
    }
} else {
    header("Location: /?p=register&id=1");
}
?>