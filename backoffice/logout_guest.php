<?php
require '../backoffice/authentication.php';
$_SESSION = [];
session_unset();
session_destroy();
header('location: ../frontoffice/index.php');
?>