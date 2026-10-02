<?php
/**
 * includes/auth.php — admin session guard + login helpers
 */

function current_admin(): ?array {
    return $_SESSION['admin'] ?? null;
}

function require_admin(): void {
    if (empty($_SESSION['admin'])) {
        redirect('login.php');
    }
    // simple idle timeout: 2 hours
    if (!empty($_SESSION['admin_last_active']) && (time() - $_SESSION['admin_last_active']) > 7200) {
        session_unset();
        session_destroy();
        redirect('login.php?timeout=1');
    }
    $_SESSION['admin_last_active'] = time();
}

function attempt_login(string $username, string $password): bool {
    $stmt = db()->prepare('SELECT * FROM admins WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin'] = ['id' => $admin['id'], 'username' => $admin['username']];
        $_SESSION['admin_last_active'] = time();
        return true;
    }
    return false;
}

function do_logout(): void {
    session_unset();
    session_destroy();
}
