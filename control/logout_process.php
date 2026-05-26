<?php
include_once 'auth.php';
session_start();
$_SESSION = array();
clearRememberCookie();
session_destroy();
header("Location: ../view/login.php");
exit();
?>
