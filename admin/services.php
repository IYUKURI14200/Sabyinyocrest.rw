<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$services = get_services();
$activeTab = 'services';
require __DIR__ . '/includes/header.php';
?>
<div class="admin-topbar">
  <h1>Services</h1>
  <a href="service_form.php" class="abtn primary">+ Add New Service</a>
</div>

<div class="admin-card">
  <?php if (!$services): ?>
    <div class="empty-state">No services yet. <a href="service_form.php">Add your first service</a>.</div>
  <?php else: ?>
  <table class="admin-table">
    <thead><tr><th>Icon</th><th>Title</th><th>Description</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($services as $s): ?>
      <tr>
        <td style="text-transform:capitalize; color:var(--ink-soft);"><?= e($s['icon']) ?></td>
        <td><b><?= e($s['title']) ?></b></td>
        <td style="max-width:360px; color:var(--ink-soft);"><?= e(str_trunc($s['description'] ?? '', 100)) ?></td>
        <td>
          <div class="admin-actions">
            <a href="service_form.php?id=<?= (int)$s['id'] ?>" class="abtn">Edit</a>
            <form method="post" action="service_delete.php" style="display:inline;">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
              <button type="submit" class="abtn danger" data-confirm="Delete “<?= e($s['title']) ?>”?">Delete</button>
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
