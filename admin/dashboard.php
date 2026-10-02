<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$roomCount    = db()->query('SELECT COUNT(*) c FROM rooms')->fetch()['c'];
$serviceCount = db()->query('SELECT COUNT(*) c FROM services')->fetch()['c'];
$galleryCount = db()->query('SELECT COUNT(*) c FROM gallery')->fetch()['c'];
$newBookings  = db()->query("SELECT COUNT(*) c FROM bookings WHERE status='new'")->fetch()['c'];
$settings     = get_settings();

$activeTab = 'dashboard';
require __DIR__ . '/includes/header.php';
?>
<div class="admin-topbar">
  <h1>Dashboard</h1>
  <span style="color:var(--ink-soft); font-size:14px;">Signed in as <b><?= e(current_admin()['username']) ?></b></span>
</div>

<div class="stat-grid">
  <div class="stat-card"><b><?= $newBookings ?></b><span>New Bookings</span></div>
  <div class="stat-card"><b><?= $roomCount ?></b><span>Rooms</span></div>
  <div class="stat-card"><b><?= $serviceCount ?></b><span>Services</span></div>
  <div class="stat-card"><b><?= $galleryCount ?></b><span>Gallery Photos</span></div>
</div>

<div class="admin-card">
  <h3 style="font-family:'Fraunces',serif; font-size:20px; margin-bottom:14px;">Quick links</h3>
  <div class="admin-actions" style="flex-wrap:wrap;">
    <a href="bookings.php" class="abtn clay">View Bookings<?= $newBookings ? " ({$newBookings} new)" : '' ?></a>
    <a href="rooms.php" class="abtn primary">Manage Rooms</a>
    <a href="services.php" class="abtn">Manage Services</a>
    <a href="gallery.php" class="abtn">Manage Gallery</a>
    <a href="content.php" class="abtn">Edit Hero & Text</a>
    <a href="contact.php" class="abtn">Edit Contact Info</a>
    <a href="../index.php" target="_blank" class="abtn clay">View Live Site ↗</a>
  </div>
</div>

<div class="admin-card">
  <h3 style="font-family:'Fraunces',serif; font-size:20px; margin-bottom:8px;">Welcome, <?= e(current_admin()['username']) ?> 👋</h3>
  <p style="color:var(--ink-soft); font-size:14.5px; max-width:600px;">
    Everything you change here updates <?= e($settings['brand_name']) ?>'s live website immediately —
    no need to re-upload files. If this is a fresh install, remember to change the default admin
    password from <a href="settings.php">Account Settings</a>.
  </p>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
