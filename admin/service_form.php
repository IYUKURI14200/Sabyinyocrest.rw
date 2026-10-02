<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$icons = ['bed','glass','users','car','wifi','shield'];

$id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : null);
$service = ['id'=>null,'icon'=>'bed','title'=>'','description'=>''];
if ($id) {
    $stmt = db()->prepare('SELECT * FROM services WHERE id = ?');
    $stmt->execute([$id]);
    $found = $stmt->fetch();
    if ($found) $service = $found;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $icon = in_array($_POST['icon'] ?? '', $icons, true) ? $_POST['icon'] : 'bed';

    if ($title === '') $errors[] = 'Title is required.';

    if (!$errors) {
        if ($id) {
            $stmt = db()->prepare('UPDATE services SET icon=?, title=?, description=? WHERE id=?');
            $stmt->execute([$icon, $title, $description, $id]);
            flash_set('Service updated.');
        } else {
            $maxOrder = db()->query('SELECT COALESCE(MAX(sort_order),0)+1 n FROM services')->fetch()['n'];
            $stmt = db()->prepare('INSERT INTO services (icon, title, description, sort_order) VALUES (?,?,?,?)');
            $stmt->execute([$icon, $title, $description, $maxOrder]);
            flash_set('Service added.');
        }
        redirect('services.php');
    }
    $service = ['id'=>$id,'icon'=>$icon,'title'=>$title,'description'=>$description];
}

$activeTab = 'services';
require __DIR__ . '/includes/header.php';
?>
<div class="admin-topbar">
  <h1><?= $id ? 'Edit Service' : 'Add New Service' ?></h1>
  <a href="services.php" class="abtn">← Back to Services</a>
</div>

<?php if ($errors): ?>
  <div class="alert error"><?= e(implode(' ', $errors)) ?></div>
<?php endif; ?>

<div class="admin-card">
  <form method="post" class="admin-form">
    <?= csrf_field() ?>
    <?php if ($id): ?><input type="hidden" name="id" value="<?= (int)$id ?>"><?php endif; ?>

    <div class="form-row">
      <div class="field">
        <label>Title</label>
        <input type="text" name="title" value="<?= e($service['title']) ?>" required>
      </div>
      <div class="field">
        <label>Icon</label>
        <select name="icon">
          <?php foreach ($icons as $ic): ?>
            <option value="<?= e($ic) ?>" <?= $service['icon']===$ic?'selected':'' ?>><?= ucfirst($ic) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="field">
      <label>Description</label>
      <textarea name="description" rows="4"><?= e($service['description']) ?></textarea>
    </div>

    <button type="submit" class="abtn primary" style="padding:12px 22px;"><?= $id ? 'Save Changes' : 'Add Service' ?></button>
  </form>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
