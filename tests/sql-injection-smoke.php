<?php
require_once __DIR__ . '/../app/config/Database.php';
require_once __DIR__ . '/../app/models/user.php';

$pdo = (new Database())->connect();
$user = (new User($pdo))->findByEmail("' OR 1=1 -- ");

if ($user !== null) {
    fwrite(STDERR, "SQL injection payload returned a user record.\n");
    exit(1);
}

echo "SQL injection payload was treated as a literal email value.\n";