<?php
require_once __DIR__ . '/session.php';

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store, private');
$_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));

echo json_encode([
    'logged_in' => $isLoggedIn,
    'user_id' => $isLoggedIn ? (int) $_SESSION['user_id'] : null,
    'csrf_token' => $_SESSION['csrf_token'],
], JSON_THROW_ON_ERROR);