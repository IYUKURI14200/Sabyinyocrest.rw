<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'status') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? 'new';
    if (in_array($status, ['new','confirmed','cancelled'], true) && $id) {
        db()->prepare('UPDATE bookings SET status=? WHERE id=?')->execute([$status, $id]);
        flash_set('Booking updated.');
    }
    redirect('bookings.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
        db()->prepare('DELETE FROM bookings WHERE id=?')->execute([$id]);
        flash_set('Booking deleted.');
    }
    redirect('bookings.php');
}

$filter = $_GET['status'] ?? 'all';
$sql = 'SELECT * FROM bookings';
$params = [];
if (in_array($filter, ['new','confirmed','cancelled'], true)) {
    $sql .= ' WHERE status = ?';
    $params[] = $filter;
}
$sql .= ' ORDER BY created_at DESC';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$bookings = $stmt->fetchAll();

$counts = db()->query("SELECT status, COUNT(*) c FROM bookings GROUP BY status")->fetchAll(PDO::FETCH_KEY_PAIR);

$activeTab = 'bookings';
require __DIR__ . '/includes/header.php';
?>
<div class="admin-topbar"><h1>Bookings</h1></div>

<div class="stat-grid">
  <div class="stat-card"><b><?= (int)($counts['new'] ?? 0) ?></b><span>New Requests</span></div>
  <div class="stat-card"><b><?= (int)($counts['confirmed'] ?? 0) ?></b><span>Confirmed</span></div>
  <div class="stat-card"><b><?= (int)($counts['cancelled'] ?? 0) ?></b><span>Cancelled</span></div>
</div>

<div class="admin-card">
  <div class="top-actions">
    <div class="admin-actions">
      <a href="bookings.php?status=all" class="abtn <?= $filter==='all'?'primary':'' ?>">All</a>
      <a href="bookings.php?status=new" class="abtn <?= $filter==='new'?'primary':'' ?>">New</a>
      <a href="bookings.php?status=confirmed" class="abtn <?= $filter==='confirmed'?'primary':'' ?>">Confirmed</a>
      <a href="bookings.php?status=cancelled" class="abtn <?= $filter==='cancelled'?'primary':'' ?>">Cancelled</a>
    </div>
  </div>

  <?php if (!$bookings): ?>
    <div class="empty-state">No booking requests here yet.</div>
  <?php else: ?>
  <table class="admin-table">
    <thead><tr><th>Guest</th><th>Dates</th><th>Room</th><th>Received</th><th>Status</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($bookings as $b): ?>
      <tr>
        <td>
          <b><?= e($b['name']) ?></b><br>
          <span style="color:var(--ink-soft); font-size:13px;"><?= e($b['email']) ?><br><?= e($b['phone']) ?></span>
        </td>
        <td><?= e($b['check_in']) ?> → <?= e($b['check_out']) ?></td>
        <td><?= e($b['room_name'] ?: 'No preference') ?></td>
        <td style="color:var(--ink-soft); font-size:13px;">
          <?= e(date('d M Y, H:i', strtotime($b['created_at']))) ?><br>
          <?= $b['email_sent'] ? '✅ emailed' : '⚠️ email failed' ?>
        </td>
        <td>
          <form method="post" style="display:inline;">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="status">
            <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
            <select name="status" onchange="this.form.submit()" style="padding:6px 8px; border-radius:6px; border:1px solid var(--line);">
              <option value="new" <?= $b['status']==='new'?'selected':'' ?>>New</option>
              <option value="confirmed" <?= $b['status']==='confirmed'?'selected':'' ?>>Confirmed</option>
              <option value="cancelled" <?= $b['status']==='cancelled'?'selected':'' ?>>Cancelled</option>
            </select>
          </form>
        </td>
        <td>
          <div class="admin-actions">
            <?php if ($b['message']): ?>
              <button type="button" class="abtn" onclick="alert(<?= json_encode($b['message']) ?>)">Note</button>
            <?php endif; ?>
            <form method="post">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
              <button type="submit" class="abtn danger" data-confirm="Delete this booking request?">Delete</button>
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
