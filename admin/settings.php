<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$errors = [];
$admin = current_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $currentPassword = $_POST['current_password'] ?? '';
    $newUsername = trim($_POST['username'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $stmt = db()->prepare('SELECT * FROM admins WHERE id = ?');
    $stmt->execute([$admin['id']]);
    $row = $stmt->fetch();

    if (!$row || !password_verify($currentPassword, $row['password_hash'])) {
        $errors[] = 'Current password is incorrect.';
    }
    if ($newUsername === '') {
        $errors[] = 'Username cannot be empty.';
    }
    if ($newPassword !== '' && strlen($newPassword) < 6) {
        $errors[] = 'New password must be at least 6 characters.';
    }
    if ($newPassword !== '' && $newPassword !== $confirmPassword) {
        $errors[] = 'New password and confirmation do not match.';
    }

    if (!$errors) {
        if ($newPassword !== '') {
            $hash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = db()->prepare('UPDATE admins SET username=?, password_hash=? WHERE id=?');
            $stmt->execute([$newUsername, $hash, $admin['id']]);
        } else {
            $stmt = db()->prepare('UPDATE admins SET username=? WHERE id=?');
            $stmt->execute([$newUsername, $admin['id']]);
        }
        $_SESSION['admin']['username'] = $newUsername;
        flash_set('Account settings updated.');
        redirect('settings.php');
    }
}

$activeTab = 'settings';
require __DIR__ . '/includes/header.php';
?>
<div class="admin-topbar"><h1>Account Settings</h1></div>

<?php if ($errors): ?>
  <div class="alert error"><?= e(implode(' ', $errors)) ?></div>
<?php endif; ?>

<div class="admin-card">
  <form method="post" class="admin-form">
    <?= csrf_field() ?>
    <div class="field">
      <label>Username</label>
      <input type="text" name="username" value="<?= e($admin['username']) ?>" required>
    </div>
    <div class="field">
      <label>New Password <span style="text-transform:none; font-weight:400;">(leave blank to keep current password)</span></label>
      <input type="password" name="new_password" autocomplete="new-password">
    </div>
    <div class="field">
      <label>Confirm New Password</label>
      <input type="password" name="confirm_password" autocomplete="new-password">
    </div>
    <div class="field">
      <label>Current Password <span style="text-transform:none; font-weight:400;">(required to save any change)</span></label>
      <input type="password" name="current_password" autocomplete="current-password" required>
    </div>
    <button type="submit" class="abtn primary" style="padding:12px 22px;">Update Account</button>
  </form>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
