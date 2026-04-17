<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['image'])) {
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}

$file = $_FILES['image'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'Upload error: ' . $file['error']]);
    exit;
}

if ($file['size'] > MAX_FILE_SIZE) {
    echo json_encode(['error' => 'File terlalu besar (maks 2MB)']);
    exit;
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime  = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mime, ALLOWED_MIME)) {
    echo json_encode(['error' => 'Tipe file tidak diizinkan']);
    exit;
}

$ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid('img_', true) . '.' . strtolower($ext);
$dest     = UPLOAD_DIR . $filename;

if (!move_uploaded_file($file['tmp_name'], $dest)) {
    echo json_encode(['error' => 'Gagal menyimpan file']);
    exit;
}

echo json_encode(['success' => true, 'filename' => $filename, 'url' => UPLOAD_URL . $filename]);
