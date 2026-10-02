<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$errors = [];

// ---- Category actions ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_category') {
    csrf_check();
    $name = trim($_POST['name'] ?? '');
    $icon = trim($_POST['icon'] ?? '');
    $type = ($_POST['menu_type'] ?? '') === 'breakfast' ? 'breakfast' : 'lunch_dinner';
    if ($name !== '') {
        $maxOrder = db()->prepare('SELECT COALESCE(MAX(sort_order),0)+1 n FROM menu_categories WHERE menu_type=?');
        $maxOrder->execute([$type]);
        $n = $maxOrder->fetch()['n'];
        db()->prepare('INSERT INTO menu_categories (menu_type, name, icon, sort_order) VALUES (?,?,?,?)')->execute([$type, $name, $icon, $n]);
        flash_set('Category added.');
    }
    redirect('menu.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit_category') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $icon = trim($_POST['icon'] ?? '');
    if ($id && $name !== '') {
        db()->prepare('UPDATE menu_categories SET name=?, icon=? WHERE id=?')->execute([$name, $icon, $id]);
        flash_set('Category updated.');
    }
    redirect('menu.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_category') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
        db()->prepare('DELETE FROM menu_categories WHERE id=?')->execute([$id]); // cascades to items
        flash_set('Category and its dishes removed.');
    }
    redirect('menu.php');
}

// ---- Item actions ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_item') {
    csrf_check();
    $categoryId = (int)($_POST['category_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $priceRaw = trim($_POST['price'] ?? '');
    $price = $priceRaw === '' ? null : (float)$priceRaw;
    if ($categoryId && $name !== '') {
        $maxOrder = db()->prepare('SELECT COALESCE(MAX(sort_order),0)+1 n FROM menu_items WHERE category_id=?');
        $maxOrder->execute([$categoryId]);
        $n = $maxOrder->fetch()['n'];
        db()->prepare('INSERT INTO menu_items (category_id, name, description, price, sort_order) VALUES (?,?,?,?,?)')->execute([$categoryId, $name, $desc, $price, $n]);
        flash_set('Dish added.');
    }
    redirect('menu.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit_item') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $priceRaw = trim($_POST['price'] ?? '');
    $price = $priceRaw === '' ? null : (float)$priceRaw;
    if ($id && $name !== '') {
        db()->prepare('UPDATE menu_items SET name=?, description=?, price=? WHERE id=?')->execute([$name, $desc, $price, $id]);
        flash_set('Dish updated.');
    }
    redirect('menu.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_item') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
        db()->prepare('DELETE FROM menu_items WHERE id=?')->execute([$id]);
        flash_set('Dish removed.');
    }
    redirect('menu.php');
}

$breakfast = get_menu('breakfast');
$lunchDinner = get_menu('lunch_dinner');
$settings = get_settings();
$bgError = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'set_bg_image') {
    csrf_check();
    try {
        $uploaded = handle_upload('dining_hero_image', 'site');
        if ($uploaded) {
            db()->prepare('UPDATE settings SET dining_hero_image=? WHERE id=1')->execute([$uploaded]);
            flash_set('Menu page background updated.');
        } else {
            $bgError = 'Please choose a photo to upload.';
        }
    } catch (RuntimeException $ex) {
        $bgError = $ex->getMessage();
    }
    if (!$bgError) redirect('menu.php');
}

$activeTab = 'menu';
require __DIR__ . '/includes/header.php';

function render_category_block(array $cat): void {
    ?>
    <div class="admin-item" style="margin-bottom:22px;">
      <div class="admin-item-top">
        <b><?= e($cat['icon']) ?> <?= e($cat['name']) ?></b>
        <div style="display:flex; gap:6px;">
          <button type="button" class="mini-btn" onclick="document.getElementById('editCat<?= $cat['id'] ?>').style.display='flex'">Edit</button>
          <form method="post" style="display:inline;">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="delete_category">
            <input type="hidden" name="id" value="<?= (int)$cat['id'] ?>">
            <button type="submit" class="mini-btn" data-confirm="Delete “<?= e($cat['name']) ?>” and all its dishes?">Delete Category</button>
          </form>
        </div>
      </div>

      <form method="post" id="editCat<?= $cat['id'] ?>" style="display:none; gap:8px; margin-bottom:14px;">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="edit_category">
        <input type="hidden" name="id" value="<?= (int)$cat['id'] ?>">
        <input type="text" name="icon" value="<?= e($cat['icon']) ?>" placeholder="🍽️" style="width:60px; padding:8px; border-radius:6px; border:1px solid var(--line);">
        <input type="text" name="name" value="<?= e($cat['name']) ?>" style="flex:1; padding:8px; border-radius:6px; border:1px solid var(--line);">
        <button type="submit" class="mini-btn save-btn">Save</button>
      </form>

      <table class="admin-table" style="margin-bottom:10px;">
        <?php foreach ($cat['items'] as $item): ?>
        <tr>
          <td style="width:40%;">
            <b><?= e($item['name']) ?></b>
            <?php if ($item['description']): ?><div style="color:var(--ink-soft); font-size:12.5px; margin-top:3px;"><?= e($item['description']) ?></div><?php endif; ?>
          </td>
          <td style="width:15%; font-family:'JetBrains Mono',monospace;"><?= $item['price'] !== null ? '$'.number_format((float)$item['price'],2) : '—' ?></td>
          <td>
            <div class="admin-actions">
              <button type="button" class="mini-btn" onclick="document.getElementById('editItem<?= $item['id'] ?>').style.display='block'">Edit</button>
              <form method="post" style="display:inline;">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete_item">
                <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
                <button type="submit" class="mini-btn" data-confirm="Remove “<?= e($item['name']) ?>”?">Delete</button>
              </form>
            </div>
          </td>
        </tr>
        <tr id="editItem<?= $item['id'] ?>" style="display:none;">
          <td colspan="3">
            <form method="post" style="padding:10px; background:var(--sand); border-radius:8px;">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="edit_item">
              <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
              <div class="form-row">
                <div class="field"><label>Name</label><input type="text" name="name" value="<?= e($item['name']) ?>"></div>
                <div class="field"><label>Price (optional)</label><input type="number" step="0.01" name="price" value="<?= $item['price'] !== null ? e((string)$item['price']) : '' ?>" placeholder="Leave blank to hide price"></div>
              </div>
              <div class="field"><label>Description</label><textarea name="description" rows="2"><?= e($item['description']) ?></textarea></div>
              <button type="submit" class="mini-btn save-btn">Save Dish</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>

      <details>
        <summary style="cursor:pointer; color:var(--canopy); font-size:13.5px; font-weight:600;">+ Add a dish to this category</summary>
        <form method="post" style="margin-top:12px; padding:12px; background:var(--sand); border-radius:8px;">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="add_item">
          <input type="hidden" name="category_id" value="<?= (int)$cat['id'] ?>">
          <div class="form-row">
            <div class="field"><label>Dish Name</label><input type="text" name="name" required></div>
            <div class="field"><label>Price (optional)</label><input type="number" step="0.01" name="price" placeholder="Leave blank to hide price"></div>
          </div>
          <div class="field"><label>Description</label><textarea name="description" rows="2" placeholder="Ingredients or short description"></textarea></div>
          <button type="submit" class="mini-btn save-btn">Add Dish</button>
        </form>
      </details>
    </div>
    <?php
}
?>
<div class="admin-topbar"><h1>Dining Menu</h1></div>

<?php if ($errors): ?>
  <div class="alert error"><?= e(implode(' ', $errors)) ?></div>
<?php endif; ?>
<?php if ($bgError): ?>
  <div class="alert error"><?= e($bgError) ?></div>
<?php endif; ?>

<div class="admin-card">
  <h3 style="font-family:'Fraunces',serif; font-size:19px; margin-bottom:16px;">Menu Page Background Photo</h3>
  <p style="color:var(--ink-soft); font-size:13.5px; margin-bottom:14px;">
    Want it to rotate through several photos instead of one? Use <a href="dining_slides.php" style="color:var(--canopy); font-weight:600;">Dining Page Slideshow</a> — when that has photos in it, it takes over from the single photo below.
  </p>
  <p style="color:var(--ink-soft); font-size:14.5px; margin-bottom:18px;">
    Shown behind the "Our Menu" title at the top of the dining page.
  </p>
  <form method="post" enctype="multipart/form-data" class="admin-form">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="set_bg_image">
    <img class="preview" src="../<?= img_src($settings['dining_hero_image']) ?>" style="width:280px; height:160px; object-fit:cover;">
    <div class="field" style="margin-top:12px;">
      <input type="file" name="dining_hero_image" accept="image/jpeg,image/png,image/webp">
    </div>
    <button type="submit" class="abtn primary" style="padding:11px 20px;">Update Background Photo</button>
  </form>
</div>

<div class="admin-card">
  <h3 style="font-family:'Fraunces',serif; font-size:19px; margin-bottom:16px;">Add a new category</h3>
  <form method="post" class="admin-form">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="add_category">
    <div class="form-row" style="align-items:flex-end;">
      <div class="field" style="flex:0 0 90px;"><label>Icon</label><input type="text" name="icon" placeholder="🍽️"></div>
      <div class="field" style="flex:2;"><label>Category Name</label><input type="text" name="name" placeholder="e.g. Grilled Specialties" required></div>
      <div class="field" style="flex:1;"><label>Menu</label>
        <select name="menu_type">
          <option value="lunch_dinner">Lunch & Dinner</option>
          <option value="breakfast">Breakfast</option>
        </select>
      </div>
      <div class="field" style="flex:0 0 auto;"><button type="submit" class="abtn primary" style="padding:12px 20px;">Add Category</button></div>
    </div>
  </form>
</div>

<div class="admin-card">
  <h3 style="font-family:'Fraunces',serif; font-size:20px; margin-bottom:18px;">🍽️ Lunch &amp; Dinner</h3>
  <?php if (!$lunchDinner): ?>
    <div class="empty-state">No categories yet — add one above.</div>
  <?php endif; ?>
  <?php foreach ($lunchDinner as $cat) render_category_block($cat); ?>
</div>

<div class="admin-card">
  <h3 style="font-family:'Fraunces',serif; font-size:20px; margin-bottom:18px;">☀️ Breakfast</h3>
  <?php if (!$breakfast): ?>
    <div class="empty-state">No categories yet — add one above.</div>
  <?php endif; ?>
  <?php foreach ($breakfast as $cat) render_category_block($cat); ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
