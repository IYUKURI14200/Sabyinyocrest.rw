<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$settings = get_settings();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save_contact') {
    csrf_check();
    $stmt = db()->prepare('UPDATE settings SET reception_phone=?, manager_phone=?, whatsapp=?, email=?, address=?, map_query=?, facebook=?, twitter=?, instagram=?, reviews_count=?, reviews_url=? WHERE id=1');
    $stmt->execute([
        trim($_POST['reception_phone'] ?? ''),
        trim($_POST['manager_phone'] ?? ''),
        preg_replace('/[^0-9]/', '', $_POST['whatsapp'] ?? ''),
        trim($_POST['email'] ?? ''),
        trim($_POST['address'] ?? ''),
        trim($_POST['map_query'] ?? ''),
        trim($_POST['facebook'] ?? ''),
        trim($_POST['twitter'] ?? ''),
        trim($_POST['instagram'] ?? ''),
        (int)($_POST['reviews_count'] ?? 0),
        trim($_POST['reviews_url'] ?? ''),
    ]);
    flash_set('Contact details updated.');
    redirect('contact.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_facility') {
    csrf_check();
    $label = trim($_POST['label'] ?? '');
    if ($label !== '') {
        $maxOrder = db()->query('SELECT COALESCE(MAX(sort_order),0)+1 n FROM facilities')->fetch()['n'];
        db()->prepare('INSERT INTO facilities (label, sort_order) VALUES (?,?)')->execute([$label, $maxOrder]);
    }
    redirect('contact.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'del_facility') {
    csrf_check();
    db()->prepare('DELETE FROM facilities WHERE id=?')->execute([(int)$_POST['id']]);
    redirect('contact.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_amenity') {
    csrf_check();
    $label = trim($_POST['label'] ?? '');
    if ($label !== '') {
        $maxOrder = db()->query('SELECT COALESCE(MAX(sort_order),0)+1 n FROM amenities')->fetch()['n'];
        db()->prepare('INSERT INTO amenities (label, sort_order) VALUES (?,?)')->execute([$label, $maxOrder]);
    }
    redirect('contact.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'del_amenity') {
    csrf_check();
    db()->prepare('DELETE FROM amenities WHERE id=?')->execute([(int)$_POST['id']]);
    redirect('contact.php');
}

$facilities = get_facilities();
$amenities = get_amenities();
$settings = get_settings();
$activeTab = 'contact';
require __DIR__ . '/includes/header.php';
?>
<div class="admin-topbar"><h1>Contact & Footer</h1></div>

<div class="admin-card">
  <h3 style="font-family:'Fraunces',serif; font-size:19px; margin-bottom:16px;">Contact Details</h3>
  <form method="post" class="admin-form">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="save_contact">
    <div class="form-row">
      <div class="field"><label>Reception Phone</label><input type="text" name="reception_phone" value="<?= e($settings['reception_phone']) ?>"></div>
      <div class="field"><label>Manager Phone</label><input type="text" name="manager_phone" value="<?= e($settings['manager_phone']) ?>"></div>
    </div>
    <div class="form-row">
      <div class="field"><label>WhatsApp Number (digits + country code)</label><input type="text" name="whatsapp" value="<?= e($settings['whatsapp']) ?>"></div>
      <div class="field"><label>Email</label><input type="email" name="email" value="<?= e($settings['email']) ?>"></div>
    </div>
    <div class="form-row">
      <div class="field"><label>Address</label><input type="text" name="address" value="<?= e($settings['address']) ?>"></div>
      <div class="field"><label>Map Search Text</label><input type="text" name="map_query" value="<?= e($settings['map_query']) ?>"></div>
    </div>
    <div class="form-row">
      <div class="field"><label>Facebook URL</label><input type="text" name="facebook" value="<?= e($settings['facebook']) ?>"></div>
      <div class="field"><label>Twitter / X URL</label><input type="text" name="twitter" value="<?= e($settings['twitter']) ?>"></div>
      <div class="field"><label>Instagram URL</label><input type="text" name="instagram" value="<?= e($settings['instagram']) ?>"></div>
    </div>
    <div class="form-row">
      <div class="field"><label>Google Reviews Count</label><input type="number" name="reviews_count" value="<?= (int)$settings['reviews_count'] ?>"></div>
      <div class="field"><label>Google Reviews URL</label><input type="text" name="reviews_url" value="<?= e($settings['reviews_url']) ?>"></div>
    </div>
    <button type="submit" class="abtn primary" style="padding:12px 22px;">Save Contact Details</button>
  </form>
</div>

<div class="admin-card">
  <h3 style="font-family:'Fraunces',serif; font-size:19px; margin-bottom:16px;">Hotel Facilities (footer list)</h3>
  <?php foreach ($facilities as $f): ?>
    <form method="post" style="display:flex; gap:10px; align-items:center; margin-bottom:8px;">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="del_facility">
      <input type="hidden" name="id" value="<?= (int)$f['id'] ?>">
      <span style="flex:1;"><?= e($f['label']) ?></span>
      <button type="submit" class="abtn danger" style="padding:6px 12px;" data-confirm="Remove this facility?">Remove</button>
    </form>
  <?php endforeach; ?>
  <form method="post" style="display:flex; gap:10px; margin-top:14px;">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="add_facility">
    <input type="text" name="label" placeholder="e.g. Airport Shuttle" style="flex:1; padding:10px 12px; border-radius:8px; border:1.5px solid var(--line);" required>
    <button type="submit" class="abtn primary" style="padding:10px 18px;">Add</button>
  </form>
</div>

<div class="admin-card">
  <h3 style="font-family:'Fraunces',serif; font-size:19px; margin-bottom:16px;">General Amenities (footer list)</h3>
  <?php foreach ($amenities as $a): ?>
    <form method="post" style="display:flex; gap:10px; align-items:center; margin-bottom:8px;">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="del_amenity">
      <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
      <span style="flex:1;"><?= e($a['label']) ?></span>
      <button type="submit" class="abtn danger" style="padding:6px 12px;" data-confirm="Remove this amenity?">Remove</button>
    </form>
  <?php endforeach; ?>
  <form method="post" style="display:flex; gap:10px; margin-top:14px;">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="add_amenity">
    <input type="text" name="label" placeholder="e.g. Swimming Pool" style="flex:1; padding:10px 12px; border-radius:8px; border:1.5px solid var(--line);" required>
    <button type="submit" class="abtn primary" style="padding:10px 18px;">Add</button>
  </form>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
