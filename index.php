<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page = $_GET['page'] ?? 'home';
$allowed = ['home', 'about', 'blog', 'blog-detail', 'contact'];

if (!in_array($page, $allowed)) {
    $page = '404';
}

$pageFile = __DIR__ . "/pages/{$page}.php";

if (!file_exists($pageFile)) {
    http_response_code(404);
    $pageFile = __DIR__ . '/pages/404.php';
}

include $pageFile;
