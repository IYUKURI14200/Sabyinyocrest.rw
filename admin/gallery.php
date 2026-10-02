<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    csrf_check();
    $caption = trim($_POST['caption'] ?? '');
    try {
        $path = handle_upload('image', 'gallery');
        if (!$path) {
            $errors[] = 'Please choose a photo to upload.';
        } else {
            $maxOrder = db()->query('SELECT COALESCE(MAX(sort_order),0)+1 n FROM gallery')->fetch()['n'];
            $stmt = db()->prepare('INSERT INTO gallery (image, caption, sort_order) VALUES (?,?,?)');
            $stmt->execute([$path, $caption, $maxOrder]);
            flash_set('Photo added to gallery.');
            redirect('gallery.php');
        }
    } catch (RuntimeException $ex) {
        $errors[] = $ex->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'caption') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $caption = trim($_POST['caption'] ?? '');
    if ($id) {
        $stmt = db()->prepare('UPDATE gallery SET caption=? WHERE id=?');
        $stmt->execute([$caption, $id]);
        flash_set('Caption updated.');
    }
    redirect('gallery.php');
}

$gallery = get_gallery();
$activeTab = 'gallery';
require __DIR__ . '/includes/header.php';
?>
<div class="admin-topbar"><h1>Gallery</h1></div>

<?php if ($errors): ?>
  <div class="alert error"><?= e(implode(' ', $errors)) ?></div>
<?php endif; ?>

<div class="admin-card">
  <h3 style="font-family:'Fraunces',serif; font-size:19px; margin-bottom:16px;">Add a new photo</h3>
  <form method="post" enctype="multipart/form-data" class="admin-form">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="add">
    <div class="form-row" style="align-items:flex-end;">
      <div class="field" style="flex:2;">
        <label>Photo</label>
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" required>
      </div>
      <div class="field" style="flex:2;">
        <label>Caption</label>
        <input type="text" name="caption" placeholder="e.g. Garden lounge">
      </div>
      <div class="field" style="flex:0 0 auto;">
        <button type="submit" class="abtn primary" style="padding:12px 22px;">Upload</button>
      </div>
    </div>
  </form>
</div>

<div class="admin-card">
  <?php if (!$gallery): ?>
    <div class="empty-state">No gallery photos yet — add one above.</div>
  <?php else: ?>
  <table class="admin-table">
    <thead><tr><th>Photo</th><th>Caption</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($gallery as $g): ?>
      <tr>
        <td><img src="../<?= img_src($g['image']) ?>" alt=""></td>
        <td>
          <form method="post" style="display:flex; gap:8px; align-items:center;">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="caption">
            <input type="hidden" name="id" value="<?= (int)$g['id'] ?>">
            <input type="text" name="caption" value="<?= e($g['caption']) ?>" style="padding:8px 10px; border-radius:6px; border:1px solid var(--line);">
            <button type="submit" class="abtn" style="padding:8px 12px;">Save</button>
          </form>
        </td>
        <td>
          <form method="post" action="gallery_delete.php">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int)$g['id'] ?>">
            <button type="submit" class="abtn danger" data-confirm="Remove this photo from the gallery?">Delete</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
