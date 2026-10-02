<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    csrf_check();
    try {
        $path = handle_upload('image', 'site');
        if (!$path) {
            $errors[] = 'Please choose a photo to upload.';
        } else {
            $maxOrder = db()->query('SELECT COALESCE(MAX(sort_order),0)+1 n FROM dining_slides')->fetch()['n'];
            db()->prepare('INSERT INTO dining_slides (image, sort_order) VALUES (?,?)')->execute([$path, $maxOrder]);
            flash_set('Slide added.');
            redirect('dining_slides.php');
        }
    } catch (RuntimeException $ex) {
        $errors[] = $ex->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'move') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $dir = $_POST['dir'] ?? '';
    $slides = db()->query('SELECT * FROM dining_slides ORDER BY sort_order ASC, id ASC')->fetchAll();
    $idx = null;
    foreach ($slides as $i => $s) { if ((int)$s['id'] === $id) { $idx = $i; break; } }
    if ($idx !== null) {
        $swapWith = $dir === 'up' ? $idx - 1 : $idx + 1;
        if ($swapWith >= 0 && $swapWith < count($slides)) {
            $a = $slides[$idx]; $b = $slides[$swapWith];
            db()->prepare('UPDATE dining_slides SET sort_order=? WHERE id=?')->execute([$b['sort_order'], $a['id']]);
            db()->prepare('UPDATE dining_slides SET sort_order=? WHERE id=?')->execute([$a['sort_order'], $b['id']]);
        }
    }
    redirect('dining_slides.php');
}

$slides = get_dining_slides();
$activeTab = 'menu';
require __DIR__ . '/includes/header.php';
?>
<div class="admin-topbar"><h1>Dining Page Slideshow</h1></div>

<?php if ($errors): ?>
  <div class="alert error"><?= e(implode(' ', $errors)) ?></div>
<?php endif; ?>

<div class="admin-card">
  <p style="color:var(--ink-soft); font-size:14.5px; margin-bottom:18px;">
    These photos rotate behind the "Our Menu" title, changing every few seconds — the same way the
    homepage banner works. Add as many as you like. If this list is empty, the single background
    photo set on the Dining Menu page is used instead.
  </p>
  <h3 style="font-family:'Fraunces',serif; font-size:19px; margin-bottom:16px;">Add a slide</h3>
  <form method="post" enctype="multipart/form-data" class="admin-form">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="add">
    <div class="form-row" style="align-items:flex-end;">
      <div class="field" style="flex:2;">
        <label>Photo</label>
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" required>
      </div>
      <div class="field" style="flex:0 0 auto;">
        <button type="submit" class="abtn primary" style="padding:12px 22px;">Upload</button>
      </div>
    </div>
  </form>
</div>

<div class="admin-card">
  <?php if (!$slides): ?>
    <div class="empty-state">No slideshow photos yet — add one above, or set a single photo from the Dining Menu page.</div>
  <?php else: ?>
  <table class="admin-table">
    <thead><tr><th>Order</th><th>Photo</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($slides as $i => $s): ?>
      <tr>
        <td>
          <div style="display:flex; gap:6px;">
            <form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="move"><input type="hidden" name="id" value="<?= (int)$s['id'] ?>"><input type="hidden" name="dir" value="up">
              <button type="submit" class="abtn" style="padding:6px 10px;" <?= $i===0?'disabled':'' ?>>↑</button>
            </form>
            <form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="move"><input type="hidden" name="id" value="<?= (int)$s['id'] ?>"><input type="hidden" name="dir" value="down">
              <button type="submit" class="abtn" style="padding:6px 10px;" <?= $i===count($slides)-1?'disabled':'' ?>>↓</button>
            </form>
          </div>
        </td>
        <td><img src="../<?= img_src($s['image']) ?>" alt=""></td>
        <td>
          <form method="post" action="dining_slide_delete.php">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
            <button type="submit" class="abtn danger" data-confirm="Remove this slide?">Delete</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
