<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('gallery.php');
}
csrf_check();
$id = (int)($_POST['id'] ?? 0);
if ($id) {
    $stmt = db()->prepare('DELETE FROM gallery WHERE id = ?');
    $stmt->execute([$id]);
    flash_set('Photo removed.');
}
redirect('gallery.php');
