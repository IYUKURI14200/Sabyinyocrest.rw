<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('hero_slides.php');
}
csrf_check();
$id = (int)($_POST['id'] ?? 0);
if ($id) {
    $stmt = db()->prepare('DELETE FROM hero_slides WHERE id = ?');
    $stmt->execute([$id]);
    flash_set('Slide removed.');
}
redirect('hero_slides.php');
