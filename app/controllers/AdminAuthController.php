
<?php

require_once __DIR__ . '/../models/admin.php';

class AdminAuthController
{
    private Admin $admin;

    public function __construct(PDO $db)
    {
        $this->admin = new Admin($db);
    }

    // Validate admin login
    public function login(string $email, string $password): string
    {
        $email = trim($email);

        // Check empty fields
        if ($email === '' || $password === '') {
            return "Email and password are required.";
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Please enter a valid email address.";
        }

        // Find admin by email
        $admin = $this->admin->findByEmail($email);

        // Verify password
        if (!$admin || !password_verify(
            $password,
            $admin['password_hash']
        )) {
            return "Invalid email or password.";
        }

        // Start session if needed
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Prevent session fixation
        session_regenerate_id(true);

        // Save admin session
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['full_name'];

        return "success";
    }
}
