<?php
require_once __DIR__ . '/../config/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!empty($_SESSION[ADMIN_SESSION_KEY])) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
} else {
    header('Location: ' . BASE_URL . '/admin/login.php');
}
exit;
