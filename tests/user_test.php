<?php

// Run from the project folder: php tests/user_test.php
// Or open in the browser: http://localhost/phpGroupProject/phpGroupProject/tests/user_test.php

require_once __DIR__ . '/../app/config/Database.php';
require_once __DIR__ . '/../app/models/user.php';

$isCli = PHP_SAPI === 'cli';
if (!$isCli) {
    echo '<pre>';
}

$passed = 0;
$failed = 0;

function check(string $name, bool $result): void
{
    global $passed, $failed;

    if ($result) {
        $passed++;
        echo "PASS  $name\n";
    } else {
        $failed++;
        echo "FAIL  $name\n";
    }
}

$db = (new Database())->connect();
$userModel = new User($db);

// Unique email so the test can run many times
$email = 'test_' . time() . '@example.com';
$id = null;

try {
    // Create
    $id = $userModel->create('Test User', $email, 'secret123', '0790000000');
    check('create() returns a new id', $id > 0);

    // Read
    $user = $userModel->getById($id);
    check('getById() finds the new user', $user !== null && $user['email'] === $email);
    check('getById() does not return the password', !isset($user['password_hash']));

    $found = $userModel->findByEmail($email);
    check('findByEmail() finds the user', $found !== null && (int) $found['id'] === $id);
    check('password is hashed, not plain text', $found['password_hash'] !== 'secret123');
    check('password_verify() accepts the right password', password_verify('secret123', $found['password_hash']));
    check('password_verify() rejects a wrong password', !password_verify('wrong', $found['password_hash']));

    $ids = array_column($userModel->getAll(), 'id');
    check('getAll() includes the new user', in_array($id, $ids));

    // Duplicate email
    try {
        $userModel->create('Copy', $email, 'secret123');
        check('create() rejects a duplicate email', false);
    } catch (PDOException $e) {
        check('create() rejects a duplicate email', true);
    }

    // Update
    check('update() changes the user', $userModel->update($id, 'Updated Name', $email, '0791111111'));
    $user = $userModel->getById($id);
    check('update() saved the new name and phone', $user['full_name'] === 'Updated Name' && $user['phone'] === '0791111111');

    // Update password
    check('updatePassword() changes the password', $userModel->updatePassword($id, 'newpass456'));
    $found = $userModel->findByEmail($email);
    check('new password works', password_verify('newpass456', $found['password_hash']));
    check('old password no longer works', !password_verify('secret123', $found['password_hash']));

    // Delete (soft)
    check('delete() deactivates the user', $userModel->delete($id));
    check('getById() hides the deleted user', $userModel->getById($id) === null);
    check('getAll() hides the deleted user', !in_array($id, array_column($userModel->getAll(), 'id')));
    check('findByEmail() still finds the deleted user', $userModel->findByEmail($email) !== null);
    check('findByEmail() shows is_active = 0', (int) $userModel->findByEmail($email)['is_active'] === 0);
    check('update() does nothing on a deleted user', !$userModel->update($id, 'X', $email));
    check('delete() twice returns false', !$userModel->delete($id));
} catch (Throwable $e) {
    $failed++;
    echo "ERROR " . $e->getMessage() . "\n";
} finally {
    // Remove the test row completely so the database stays clean
    if ($id) {
        $db->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
    }
}

echo "\n$passed passed, $failed failed\n";

if (!$isCli) {
    echo '</pre>';
}
