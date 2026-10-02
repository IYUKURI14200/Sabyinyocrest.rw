<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : null);
$room = ['id'=>null,'name'=>'','price'=>0,'description'=>'','image'=>''];
if ($id) {
    $stmt = db()->prepare('SELECT * FROM rooms WHERE id = ?');
    $stmt->execute([$id]);
    $found = $stmt->fetch();
    if ($found) $room = $found;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name = trim($_POST['name'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');

    if ($name === '') $errors[] = 'Room name is required.';
    if ($price < 0) $errors[] = 'Price cannot be negative.';

    $imagePath = $room['image'];
    try {
        $uploaded = handle_upload('image', 'rooms');
        if ($uploaded) $imagePath = $uploaded;
    } catch (RuntimeException $ex) {
        $errors[] = $ex->getMessage();
    }
    if (!$imagePath) $imagePath = 'assets/img/room_wardrobe_hall.jpg';

    if (!$errors) {
        if ($id) {
            $stmt = db()->prepare('UPDATE rooms SET name=?, price=?, description=?, image=? WHERE id=?');
            $stmt->execute([$name, $price, $description, $imagePath, $id]);
            flash_set('Room updated.');
        } else {
            $maxOrder = db()->query('SELECT COALESCE(MAX(sort_order),0)+1 n FROM rooms')->fetch()['n'];
            $stmt = db()->prepare('INSERT INTO rooms (name, price, description, image, sort_order) VALUES (?,?,?,?,?)');
            $stmt->execute([$name, $price, $description, $imagePath, $maxOrder]);
            flash_set('Room added.');
        }
        redirect('rooms.php');
    }
    $room = ['id'=>$id,'name'=>$name,'price'=>$price,'description'=>$description,'image'=>$imagePath];
}

$activeTab = 'rooms';
require __DIR__ . '/includes/header.php';
?>
<div class="admin-topbar">
  <h1><?= $id ? 'Edit Room' : 'Add New Room' ?></h1>
  <a href="rooms.php" class="abtn">← Back to Rooms</a>
</div>

<?php if ($errors): ?>
  <div class="alert error"><?= e(implode(' ', $errors)) ?></div>
<?php endif; ?>

<div class="admin-card">
  <form method="post" enctype="multipart/form-data" class="admin-form">
    <?= csrf_field() ?>
    <?php if ($id): ?><input type="hidden" name="id" value="<?= (int)$id ?>"><?php endif; ?>

    <div class="field">
      <label>Room Photo</label>
      <img id="roomImgPreview" class="preview" src="../<?= img_src($room['image'] ?: 'assets/img/room_wardrobe_hall.jpg') ?>" alt="">
      <input type="file" name="image" accept="image/jpeg,image/png,image/webp" data-preview="roomImgPreview">
    </div>

    <div class="form-row">
      <div class="field">
        <label>Room Name</label>
        <input type="text" name="name" value="<?= e($room['name']) ?>" required>
      </div>
      <div class="field">
        <label>Price / Night (USD)</label>
        <input type="number" name="price" step="0.01" min="0" value="<?= e((string)$room['price']) ?>" required>
      </div>
    </div>

    <div class="field">
      <label>Description</label>
      <textarea name="description" rows="4"><?= e($room['description']) ?></textarea>
    </div>

    <button type="submit" class="abtn primary" style="padding:12px 22px;"><?= $id ? 'Save Changes' : 'Add Room' ?></button>
  </form>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
