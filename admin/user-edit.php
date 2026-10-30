<?php
// =====================================================
// Admin - Edit a user
// Opened from the pencil icon: user-edit.php?id=5
// The first time, the form shows the user's current details.
// When the form is sent (POST), it checks the data and saves it.
// =====================================================

// STEP 1: Load the files we need
require_once __DIR__ . '/../app/config/Database.php';
require_once __DIR__ . '/../app/models/user.php';
require_once __DIR__ . '/includes/helpers.php';


// STEP 2: Connect to the database and create the User model
$database = new Database();
$db = $database->connect();
$userModel = new User($db);


// STEP 3: Find the user we are editing
// The id comes from the URL (?id=5) the first time, and from a hidden field when the form is sent
$id = 0;
if (isset($_POST["id"])) {
    $id = (int) $_POST["id"];
} elseif (isset($_GET["id"])) {
    $id = (int) $_GET["id"];
}

$user = $userModel->getById($id);

if ($user === null) {
    header("Location: users.php?error=notfound");
    exit;
}


// STEP 4: Fill the form with the user's current details
$fullName = $user["full_name"];
$email = $user["email"];
$phone = $user["phone"] ?? "";   // ?? "" means: if there is no phone, use ""
$errors = [];


// STEP 5: If the form was sent, check it and save the changes
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Read the form fields (trim removes spaces at the start and end)
    $fullName = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");

    // Check name, email and phone (see validateUserForm in includes/helpers.php)
    $errors = validateUserForm($fullName, $email, $phone);

    // Check the email is not used by a DIFFERENT user (keeping your own email is fine)
    if (count($errors) === 0) {
        $userWithEmail = $userModel->findByEmail($email);

        if ($userWithEmail !== null && (int) $userWithEmail["id"] !== $id) {
            $errors[] = "This email is already used by another user.";
        }
    }

    // No errors: save the changes and go back to the list
    if (count($errors) === 0) {
        $phoneToSave = $phone;
        if ($phoneToSave === "") {
            $phoneToSave = null;   // an empty phone is saved as NULL (no phone)
        }

        // update() returns false when nothing changed, which is not an error,
        // so we go back to the list either way
        $userModel->update($id, $fullName, $email, $phoneToSave);

        header("Location: users.php?success=updated");
        exit;
    }
}


// STEP 6: Show the page
$pageTitle = "Edit User";
$breadcrumb = "Users / Edit User";
require __DIR__ . '/includes/header.php';
?>

                <div class="page-header">
                    <div>
                        <h1 class="page-title">Edit User</h1>
                        <p class="page-subtitle">Change the details of <?php echo safe($user["full_name"]); ?>.</p>
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
                <form method="post" action="user-edit.php" class="form-card">

                    <!-- Which user we are editing -->
                    <input type="hidden" name="id" value="<?php echo $id; ?>">

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
                                   value="<?php echo safe($phone); ?>">
                        </div>

                        <div class="col-12 text-muted small">
                            To change the status, use the Active / Inactive badge in the users list.
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end">
                            <a href="users.php" class="btn btn-outline-custom">Cancel</a>
                            <button type="submit" class="btn btn-brand">
                                <i class="bi bi-check-lg me-1"></i> Save Changes
                            </button>
                        </div>

                    </div>
                </form>

<?php require __DIR__ . '/includes/footer.php'; ?>
