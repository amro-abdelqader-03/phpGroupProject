<?php
if (session_status() === PHP_SESSION_NONE) {
     session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $_SESSION['user_name'] ?? '';
?>