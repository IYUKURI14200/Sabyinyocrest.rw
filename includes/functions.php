<?php
/**
 * includes/functions.php — small shared helpers used across the site
 */

function e(?string $str): string {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/** Truncate a string to $len chars, adding an ellipsis if cut. Works even without the mbstring extension. */
function str_trunc(?string $str, int $len = 90, string $end = '…'): string {
    $str = $str ?? '';
    if (function_exists('mb_strlen')) {
        if (mb_strlen($str) <= $len) return $str;
        return mb_substr($str, 0, $len) . $end;
    }
    if (strlen($str) <= $len) return $str;
    return substr($str, 0, $len) . $end;
}

/** Fetch the single settings row as an associative array. */
function get_settings(): array {
    static $settings = null;
    if ($settings === null) {
        $stmt = db()->query('SELECT * FROM settings WHERE id = 1 LIMIT 1');
        $settings = $stmt->fetch() ?: [];
    }
    return $settings;
}

function get_rooms(): array {
    return db()->query('SELECT * FROM rooms ORDER BY sort_order ASC, id ASC')->fetchAll();
}
function get_services(): array {
    return db()->query('SELECT * FROM services ORDER BY sort_order ASC, id ASC')->fetchAll();
}
function get_gallery(): array {
    return db()->query('SELECT * FROM gallery ORDER BY sort_order ASC, id ASC')->fetchAll();
}
function get_hero_slides(): array {
    return db()->query('SELECT * FROM hero_slides ORDER BY sort_order ASC, id ASC')->fetchAll();
}
function get_menu(string $type): array {
    $stmt = db()->prepare('SELECT * FROM menu_categories WHERE menu_type = ? ORDER BY sort_order ASC, id ASC');
    $stmt->execute([$type]);
    $categories = $stmt->fetchAll();
    foreach ($categories as &$cat) {
        $istmt = db()->prepare('SELECT * FROM menu_items WHERE category_id = ? ORDER BY sort_order ASC, id ASC');
        $istmt->execute([$cat['id']]);
        $cat['items'] = $istmt->fetchAll();
    }
    return $categories;
}
function get_dining_slides(): array {
    return db()->query('SELECT * FROM dining_slides ORDER BY sort_order ASC, id ASC')->fetchAll();
}
function get_facilities(): array {
    return db()->query('SELECT * FROM facilities ORDER BY sort_order ASC, id ASC')->fetchAll();
}
function get_amenities(): array {
    return db()->query('SELECT * FROM amenities ORDER BY sort_order ASC, id ASC')->fetchAll();
}

/** Resolve a stored image path (relative) into a usable <img src>. */
function img_src(?string $path): string {
    if (!$path) return 'assets/img/room_wardrobe_hall.jpg';
    return e($path);
}

/* ---------------- CSRF ---------------- */
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}
function csrf_field(): string {
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}
function csrf_check(): void {
    $token = $_POST['csrf'] ?? '';
    if (!$token || !hash_equals($_SESSION['csrf'] ?? '', $token)) {
        http_response_code(400);
        die('Security check failed (invalid or expired form). Please go back and try again.');
    }
}

/* ---------------- Flash messages ---------------- */
function flash_set(string $msg, string $type = 'success'): void {
    $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
}
function flash_get(): ?array {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

/* ---------------- Uploads ---------------- */
/**
 * Handle an <input type="file"> upload and move it into a subfolder of /uploads.
 * Returns the relative path (e.g. "uploads/rooms/xyz.jpg") or null if no file was sent.
 * Throws RuntimeException on validation failure.
 */
function handle_upload(string $fieldName, string $subfolder): ?string {
    if (empty($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $file = $_FILES[$fieldName];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload failed (error code ' . $file['error'] . ').');
    }
    if ($file['size'] > MAX_UPLOAD_BYTES) {
        throw new RuntimeException('Image is too large. Please use a file under 5MB.');
    }
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Please upload a JPG, PNG, or WEBP image.');
    }
    $ext = $allowed[$mime];
    $filename = bin2hex(random_bytes(8)) . '.' . $ext;
    $destDir = UPLOADS_DIR . '/' . $subfolder;
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }
    $destPath = $destDir . '/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        throw new RuntimeException('Could not save the uploaded file. Check folder permissions on ' . UPLOADS_URL . '/' . $subfolder);
    }
    return UPLOADS_URL . '/' . $subfolder . '/' . $filename;
}

/**
 * Handle a video file upload (larger size limit, video mime types only).
 * Returns the relative path or null if no file was sent.
 */
function handle_video_upload(string $fieldName, string $subfolder): ?string {
    if (empty($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $file = $_FILES[$fieldName];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $phpLimitHint = '';
        if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
            $phpLimitHint = ' Your server\'s php.ini upload_max_filesize / post_max_size is likely too small — increase both and restart Apache.';
        }
        throw new RuntimeException('Video upload failed (error code ' . $file['error'] . ').' . $phpLimitHint);
    }
    if ($file['size'] > VIDEO_MAX_UPLOAD_BYTES) {
        throw new RuntimeException('Video is too large. Please use a file under ' . round(VIDEO_MAX_UPLOAD_BYTES / 1024 / 1024) . 'MB.');
    }
    $allowed = ['video/mp4' => 'mp4', 'video/webm' => 'webm', 'video/ogg' => 'ogv', 'video/quicktime' => 'mov'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Please upload an MP4, WebM, or MOV video file.');
    }
    $ext = $allowed[$mime];
    $filename = bin2hex(random_bytes(8)) . '.' . $ext;
    $destDir = UPLOADS_DIR . '/' . $subfolder;
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }
    $destPath = $destDir . '/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        throw new RuntimeException('Could not save the uploaded video. Check folder permissions on ' . UPLOADS_URL . '/' . $subfolder);
    }
    return UPLOADS_URL . '/' . $subfolder . '/' . $filename;
}

/**
 * Extract a YouTube video ID from any common YouTube URL format.
 * Returns null if the URL doesn't look like a YouTube link.
 */
function parse_youtube_id(string $url): ?string {
    $url = trim($url);
    if ($url === '') return null;
    $patterns = [
        '~youtu\.be/([A-Za-z0-9_-]{6,})~',
        '~youtube\.com/watch\?v=([A-Za-z0-9_-]{6,})~',
        '~youtube\.com/shorts/([A-Za-z0-9_-]{6,})~',
        '~youtube\.com/embed/([A-Za-z0-9_-]{6,})~',
    ];
    foreach ($patterns as $p) {
        if (preg_match($p, $url, $m)) return $m[1];
    }
    // if they just pasted the bare ID
    if (preg_match('~^[A-Za-z0-9_-]{6,}$~', $url)) return $url;
    return null;
}

function redirect(string $to): void {
    header('Location: ' . $to);
    exit;
}
