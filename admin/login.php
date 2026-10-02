<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if (current_admin()) {
    redirect('dashboard.php');
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if (attempt_login($username, $password)) {
        redirect('dashboard.php');
    }
    $error = 'Incorrect username or password.';
}
$settings = get_settings();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Sign In — <?= e($settings['brand_name'] ?? 'Site Admin') ?></title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="login-wrap">
  <div class="login-box">
    <span class="brand-mark" style="display:flex; width:52px; height:52px;"><svg width="26" height="26" viewBox="0 0 24 24" fill="none"><path d="M3 21L9 9L13 15L16 10L21 21H3Z" fill="#F3ECDC"/><circle cx="12" cy="5" r="2" fill="#D6A13C"/></svg></span>
    <h2>Admin Sign In</h2>
    <p class="hint">Manage rooms, gallery, content and contact details.</p>
    <?php if (!empty($_GET['timeout'])): ?>
      <div class="alert error">Your session expired — please sign in again.</div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert error"><?= e($error) ?></div>
    <?php endif; ?>
    <form method="post" novalidate>
      <?= csrf_field() ?>
      <div class="field">
        <label>Username</label>
        <input type="text" name="username" autocomplete="username" required autofocus>
      </div>
      <div class="field">
        <label>Password</label>
        <input type="password" name="password" autocomplete="current-password" required>
      </div>
      <button type="submit" class="btn btn-clay" style="width:100%; justify-content:center;">Sign In</button>
    </form>
  </div>
</div>
</body>
</html>
