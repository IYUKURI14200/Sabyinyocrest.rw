<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$rooms = get_rooms();
$activeTab = 'rooms';
require __DIR__ . '/includes/header.php';
?>
<div class="admin-topbar">
  <h1>Rooms</h1>
  <a href="room_form.php" class="abtn primary">+ Add New Room</a>
</div>

<div class="admin-card">
  <?php if (!$rooms): ?>
    <div class="empty-state">No rooms yet. <a href="room_form.php">Add your first room</a>.</div>
  <?php else: ?>
  <table class="admin-table">
    <thead><tr><th>Photo</th><th>Name</th><th>Price / Night</th><th>Description</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($rooms as $r): ?>
      <tr>
        <td><img src="../<?= img_src($r['image']) ?>" alt=""></td>
        <td><b><?= e($r['name']) ?></b></td>
        <td>$<?= number_format((float)$r['price'], 2) ?></td>
        <td style="max-width:320px; color:var(--ink-soft);"><?= e(str_trunc($r['description'] ?? '', 90)) ?></td>
        <td>
          <div class="admin-actions">
            <a href="room_form.php?id=<?= (int)$r['id'] ?>" class="abtn">Edit</a>
            <form method="post" action="room_delete.php" style="display:inline;">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
              <button type="submit" class="abtn danger" data-confirm="Delete “<?= e($r['name']) ?>”? This can't be undone.">Delete</button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
