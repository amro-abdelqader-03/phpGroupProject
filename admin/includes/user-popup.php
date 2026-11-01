<?php
// =====================================================
// One popup with the Add User or the Edit User form (used by users.php).
// users.php sets these before including this file:
//   $popupId    - the popup's id, e.g. "add-user" or "edit-user-5"
//   $editUser   - the user being edited, or null for "Add User"
//   $values     - what to show in the fields: full_name, email, phone
//   $errors     - the mistakes to show (an empty array if there are none)
//   $formLink   - where the form is sent: the users list with the current filters
//
// How it opens without JavaScript: the popup is hidden, and CSS shows it
// when the URL ends with #<popupId> (the :target selector in users.css).
// The "Add User" button is just a link to #add-user.
// =====================================================

$isAdd = ($editUser === null);
?>
<div class="user-popup" id="<?php echo $popupId; ?>" role="dialog" aria-modal="true" aria-labelledby="<?php echo $popupId; ?>-title">

    <!-- The dark background: clicking it closes the popup (#close is not an id on the page, so nothing opens) -->
    <a href="#close" class="user-popup__backdrop" aria-label="Close" tabindex="-1"></a>

    <div class="user-popup__box">
        <div class="user-popup__header">
            <div>
                <?php if ($isAdd) { ?>
                    <h2 class="user-popup__title" id="<?php echo $popupId; ?>-title">Add User</h2>
                    <p class="user-popup__subtitle">Create a new customer account.</p>
                <?php } else { ?>
                    <h2 class="user-popup__title" id="<?php echo $popupId; ?>-title">Edit User</h2>
                    <p class="user-popup__subtitle">Change the details of <?php echo safe($editUser["full_name"]); ?>.</p>
                <?php } ?>
            </div>
            <a href="#close" class="user-popup__close" aria-label="Close"><i class="bi bi-x-lg"></i></a>
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

        <!-- #<popupId> at the end of the link: if there is a mistake, the page comes back with this popup open -->
        <form method="post" action="<?php echo safe($formLink); ?>#<?php echo $popupId; ?>">

            <!-- Which form this is, so users.php knows what to do -->
            <?php if ($isAdd) { ?>
                <input type="hidden" name="form" value="add">
            <?php } else { ?>
                <input type="hidden" name="form" value="edit">
                <input type="hidden" name="id" value="<?php echo $editUser["id"]; ?>">
            <?php } ?>

            <div class="row g-3">
                <!-- Every id starts with the popup id, because there are several popups on the page -->
                <div class="col-md-6">
                    <label for="<?php echo $popupId; ?>-name" class="form-label">Full name *</label>
                    <input type="text" id="<?php echo $popupId; ?>-name" name="full_name" class="form-control" maxlength="255" required
                           value="<?php echo safe($values["full_name"]); ?>">
                </div>

                <div class="col-md-6">
                    <label for="<?php echo $popupId; ?>-email" class="form-label">Email *</label>
                    <input type="email" id="<?php echo $popupId; ?>-email" name="email" class="form-control" maxlength="255" required
                           value="<?php echo safe($values["email"]); ?>">
                </div>

                <div class="col-md-6">
                    <label for="<?php echo $popupId; ?>-phone" class="form-label">Phone number</label>
                    <input type="text" id="<?php echo $popupId; ?>-phone" name="phone" class="form-control" maxlength="20"
                           value="<?php echo safe($values["phone"]); ?>" placeholder="+962 79 123 4567">
                </div>

                <?php if ($isAdd) { ?>
                    <div class="col-md-6"></div>

                    <!-- Passwords are never put back in the form after an error -->
                    <div class="col-md-6">
                        <label for="<?php echo $popupId; ?>-password" class="form-label">Password * <span class="text-muted">(at least 8 characters)</span></label>
                        <input type="password" id="<?php echo $popupId; ?>-password" name="password" class="form-control" minlength="8" required>
                    </div>

                    <div class="col-md-6">
                        <label for="<?php echo $popupId; ?>-confirm" class="form-label">Confirm password *</label>
                        <input type="password" id="<?php echo $popupId; ?>-confirm" name="confirm_password" class="form-control" minlength="8" required>
                    </div>
                <?php } else { ?>
                    <div class="col-12 text-muted small">
                        To change the status, use the Active / Inactive badge in the users list.
                    </div>
                <?php } ?>

                <div class="col-12 d-flex gap-2 justify-content-end">
                    <a href="#close" class="btn btn-outline-custom">Cancel</a>
                    <?php if ($isAdd) { ?>
                        <button type="submit" class="btn btn-brand"><i class="bi bi-person-plus me-1"></i> Add User</button>
                    <?php } else { ?>
                        <button type="submit" class="btn btn-brand"><i class="bi bi-check-lg me-1"></i> Save Changes</button>
                    <?php } ?>
                </div>
            </div>
        </form>
    </div>
</div>
