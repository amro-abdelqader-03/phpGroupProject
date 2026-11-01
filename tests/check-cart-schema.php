<?php
require_once __DIR__ . '/../app/config/Database.php';

$pdo = (new Database())->connect();
$stmt = $pdo->query('SHOW CREATE TABLE carts');
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$definition = $row['Create Table'] ?? '';
$hasUniqueUser = preg_match('/UNIQUE KEY[^\n]*\(\s*`user_id`\s*\)/i', $definition) === 1;
$hasUserForeignKey = preg_match('/FOREIGN KEY\s*\(\s*`user_id`\s*\)\s*REFERENCES\s*`users`\s*\(\s*`id`\s*\)/i', $definition) === 1;

if (!$hasUniqueUser || !$hasUserForeignKey) {
    fwrite(STDERR, 'Cart ownership constraints are incomplete. Unique user mapping: '
        . ($hasUniqueUser ? 'yes' : 'no') . '; user foreign key: '
        . ($hasUserForeignKey ? 'yes' : 'no') . PHP_EOL);
    exit(1);
}

echo "Cart has a unique user mapping and a users foreign key.\n";