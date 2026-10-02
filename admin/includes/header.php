<?php
/**
 * admin/includes/header.php
 * Expects $activeTab to be set by the including page (e.g. 'dashboard', 'rooms', ...)
 */
$settings = get_settings();
$flash = flash_get();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — <?= e($settings['brand_name'] ?? 'Site Admin') ?></title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-body">
<div class="admin-shell">
  <aside class="admin-sidebar">
    <div class="footer-brand" style="margin-bottom:2px;">
      <span class="brand-mark"><svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M3 21L9 9L13 15L16 10L21 21H3Z" fill="#F3ECDC"/><circle cx="12" cy="5" r="2" fill="#D6A13C"/></svg></span>
      <span>
        <span class="brand-name" style="display:block;"><?= e($settings['brand_name'] ?? '') ?></span>
        <span class="brand-sub">Admin Panel</span>
      </span>
    </div>
    <ul class="admin-nav">
      <li><a href="dashboard.php" class="<?= $activeTab==='dashboard'?'active':'' ?>">Dashboard</a></li>
      <li><a href="bookings.php" class="<?= $activeTab==='bookings'?'active':'' ?>">Bookings</a></li>
      <li><a href="rooms.php" class="<?= $activeTab==='rooms'?'active':'' ?>">Rooms</a></li>
      <li><a href="menu.php" class="<?= $activeTab==='menu'?'active':'' ?>">Dining Menu</a></li>
      <li><a href="services.php" class="<?= $activeTab==='services'?'active':'' ?>">Services</a></li>
      <li><a href="gallery.php" class="<?= $activeTab==='gallery'?'active':'' ?>">Gallery</a></li>
      <li><a href="content.php" class="<?= $activeTab==='content'?'active':'' ?>">Site Content</a></li>
      <li><a href="hero_slides.php" class="<?= $activeTab==='hero'?'active':'' ?>">Hero Slideshow</a></li>
      <li><a href="video.php" class="<?= $activeTab==='video'?'active':'' ?>">Featured Video</a></li>
      <li><a href="contact.php" class="<?= $activeTab==='contact'?'active':'' ?>">Contact & Footer</a></li>
      <li><a href="settings.php" class="<?= $activeTab==='settings'?'active':'' ?>">Account Settings</a></li>
    </ul>
    <ul class="admin-nav" style="margin-top:26px; border-top:1px solid rgba(251,248,241,0.12); padding-top:16px;">
      <li><a href="../index.php" target="_blank">View Website ↗</a></li>
      <li><a href="logout.php">Log Out</a></li>
    </ul>
  </aside>
  <main class="admin-main">
    <?php if ($flash): ?>
      <div class="alert <?= e($flash['type']) ?>" data-autohide><?= e($flash['msg']) ?></div>
    <?php endif; ?>
