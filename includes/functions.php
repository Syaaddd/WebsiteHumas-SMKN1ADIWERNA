<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';

function sanitize(string $str): string {
    return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
}

function makeSlug(string $str): string {
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9\s-]/', '', $str);
    $str = preg_replace('/[\s-]+/', '-', $str);
    return trim($str, '-');
}

function formatDate(string $date, string $format = 'd M Y'): string {
    return date($format, strtotime($date));
}

function getSettings(): array {
    static $settings = null;
    if ($settings === null) {
        $pdo = getPDO();
        $rows = $pdo->query("SELECT `key`, value FROM settings")->fetchAll();
        $settings = array_column($rows, 'value', 'key');
    }
    return $settings;
}

function getSetting(string $key, string $default = ''): string {
    $settings = getSettings();
    return $settings[$key] ?? $default;
}

function redirect(string $url): void {
    header("Location: $url");
    exit;
}

function isPost(): bool {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die('Invalid CSRF token.');
    }
}
