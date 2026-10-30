

<?php

require_once __DIR__ . '/../app/config/Database.php';
require_once __DIR__ . '/../app/controllers/AdminAuthController.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $database = new Database();
    $db = $database->connect();

    $controller = new AdminAuthController($db);

    $result = $controller->login($email, $password);

    if ($result === 'success') {
        header('Location: dashboard.php');
        exit;
    }

    $error = $result;
}
?>











<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Login | Wedding Store</title>

  
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

   
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

   
    <link
        rel="stylesheet"
        href="../public/assets/css/adminLogin.css"
    >
</head>

<body>

    <main class="login-page">

        <div class="login-card">

            
            <div class="brand">
                <div class="brand-icon">
                    <i class="bi bi-layers-fill"></i>
                </div>
                <span>CoreAdmin</span>
            </div>

          
            <div class="login-heading">
                <h1>Welcome Back</h1>
                <p>
                    Sign in to your Wedding Store
                    admin account
                </p>
            </div>

            
            <form method="POST" action="login.php">


            <?php if ($error !== ''): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

                <div class="mb-3">
                    <label for="email" class="form-label">
                        Email Address
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>

                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="admin@example.com"
                            autocomplete="username"
                            required
                        >
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">
                        Password
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>

                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >
                    </div>
                </div>

                <button
                    type="submit"
                    class="btn login-btn w-100"
                >
                    Sign In
                    <i class="bi bi-arrow-right ms-2"></i>
                </button>

            </form>

            <div class="login-footer">
                Wedding Store &copy; 2026
                <br>
                Admin Portal
            </div>

        </div>

    </main>

  
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
    </script>

    <script src="../public/assets/js/adminLogin.js"></script>

</body>
</html>
