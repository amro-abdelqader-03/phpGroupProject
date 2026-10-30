<?php
// =====================================================
// Admin - Add a new user
// The first time, this page shows an empty form.
// When the form is sent (POST), it checks the data and saves the user.
// =====================================================

// STEP 1: Load the files we need
require_once __DIR__ . '/../app/config/Database.php';
require_once __DIR__ . '/../app/models/user.php';
require_once __DIR__ . '/includes/helpers.php';


// STEP 2: Connect to the database and create the User model
$database = new Database();
$db = $database->connect();
$userModel = new User($db);


// STEP 3: Start with an empty form and no errors
$fullName = "";
$email = "";
$phone = "";
$errors = [];


// STEP 4: If the form was sent, check it and save the user
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Read the form fields
    // ?? "" means: if the field is missing, use "" instead
    // trim() removes spaces at the start and end
    $fullName = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";

    // Check name, email and phone (see validateUserForm in includes/helpers.php)
    $errors = validateUserForm($fullName, $email, $phone);

    // Check the password
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "The two passwords do not match.";
    }

    // Check the email is not already used by another user
    if (count($errors) === 0 && $userModel->findByEmail($email) !== null) {
        $errors[] = "This email is already registered.";
    }

    // No errors: save the user and go back to the list
    if (count($errors) === 0) {
        // An empty phone is saved as NULL (no phone)
        if ($phone === "") {
            $phone = null;
        }

        $userModel->create($fullName, $email, $password, $phone);

        header("Location: users.php?success=added");
        exit;
    }
}


// STEP 5: Show the page (the form, with errors if there are any)
$pageTitle = "Add User";
$breadcrumb = "Users / Add User";
require __DIR__ . '/includes/header.php';
?>

                <div class="page-header">
                    <div>
                        <h1 class="page-title">Add User</h1>
                        <p class="page-subtitle">Create a new customer account.</p>
                    </div>
                    <div>
                        <a href="users.php" class="btn btn-outline-custom">
                            <i class="bi bi-arrow-left me-1"></i> Back to Users
                        </a>
                    </div>
                </div>

                <!-- Show the errors, if there are any -->
                <?php if (count($errors) > 0) { ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error) { ?>
                                <li><?php echo safe($error); ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>

                <!-- The form sends the data to this same page (POST) -->
                <form method="post" action="user-add.php" class="form-card">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="full_name" class="form-label">Full name *</label>
                            <input type="text" id="full_name" name="full_name" class="form-control" maxlength="255" required
                                   value="<?php echo safe($fullName); ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" id="email" name="email" class="form-control" maxlength="255" required
                                   value="<?php echo safe($email); ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone number</label>
                            <input type="text" id="phone" name="phone" class="form-control" maxlength="20"
                                   value="<?php echo safe($phone); ?>" placeholder="+962 79 123 4567">
                        </div>

                        <div class="col-md-6"></div>

                        <!-- Passwords are never put back in the form after an error -->
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password * <span class="text-muted">(at least 8 characters)</span></label>
                            <input type="password" id="password" name="password" class="form-control" minlength="8" required>
                        </div>

                        <div class="col-md-6">
                            <label for="confirm_password" class="form-label">Confirm password *</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" minlength="8" required>
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end">
                            <a href="users.php" class="btn btn-outline-custom">Cancel</a>
                            <button type="submit" class="btn btn-brand">
                                <i class="bi bi-person-plus me-1"></i> Add User
                            </button>
                        </div>

                    </div>
                </form>

<?php require __DIR__ . '/includes/footer.php'; ?>
