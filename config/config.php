<?php
define('BASE_URL', 'http://localhost/humas');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', BASE_URL . '/uploads/');
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB
define('ALLOWED_MIME', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
define('ADMIN_SESSION_KEY', 'humas_admin');
