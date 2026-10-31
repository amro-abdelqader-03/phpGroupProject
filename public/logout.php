<?php
session_start();

$_SESSION = [];

session_destroy();

header("Location: /phpGroupProject/public/index.php");
exit;