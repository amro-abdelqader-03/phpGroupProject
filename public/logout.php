<?php
require_once __DIR__ . '/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header('Allow: POST');
	http_response_code(405);
	exit('Method not allowed');
}

$csrfToken = (string) ($_POST['csrf_token'] ?? '');
if (empty($_SESSION['csrf_token']) || !hash_equals((string) $_SESSION['csrf_token'], $csrfToken)) {
	http_response_code(403);
	exit('Your session expired. Refresh the page and try again.');
}

$_SESSION = [];
if (ini_get('session.use_cookies')) {
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

header('Location: index.php');
exit;