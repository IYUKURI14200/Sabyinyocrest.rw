<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('rooms.php');
}
csrf_check();
$id = (int)($_POST['id'] ?? 0);
if ($id) {
    $stmt = db()->prepare('DELETE FROM rooms WHERE id = ?');
    $stmt->execute([$id]);
    flash_set('Room deleted.');
}
redirect('rooms.php');
