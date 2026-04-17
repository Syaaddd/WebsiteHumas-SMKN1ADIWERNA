<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getPDO();
$msg = '';

if (isPost()) {
    verifyCsrf();

    $fields = ['site_name', 'site_description', 'site_email', 'site_phone', 'site_address'];
    foreach ($fields as $key) {
        $value = trim($_POST[$key] ?? '');
        $check = $pdo->prepare("SELECT COUNT(*) FROM settings WHERE `key` = ?");
        $check->execute([$key]);
        if ($check->fetchColumn() > 0) {
            $pdo->prepare("UPDATE settings SET value = ? WHERE `key` = ?")->execute([$value, $key]);
        } else {
            $pdo->prepare("INSERT INTO settings (`key`, value) VALUES (?, ?)")->execute([$key, $value]);
        }
    }

    // Handle logo upload
    if (!empty($_FILES['logo']['name'])) {
        $file = $_FILES['logo'];
        if ($file['error'] === UPLOAD_ERR_OK && $file['size'] <= MAX_FILE_SIZE) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            if (in_array($mime, ALLOWED_MIME)) {
                $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
                $name = 'logo.' . strtolower($ext);
                if (move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $name)) {
                    $pdo->prepare("UPDATE settings SET value=? WHERE `key`='logo'")->execute([$name]);
                }
            }
        }
    }

    $msg = 'Pengaturan berhasil disimpan.';
    // Reset static cache
}

$s = getSettings();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan — Admin Humas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/output.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <main class="ml-64 p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Pengaturan Situs</h1>

        <?php if ($msg): ?>
            <div class="bg-green-100 text-green-700 text-sm px-4 py-3 rounded-lg mb-4"><?= sanitize($msg) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow p-6 max-w-xl space-y-4">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Situs</label>
                <input type="text" name="site_name" value="<?= sanitize($s['site_name'] ?? '') ?>"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Situs</label>
                <textarea name="site_description" rows="2"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"><?= sanitize($s['site_description'] ?? '') ?></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="site_email" value="<?= sanitize($s['site_email'] ?? '') ?>"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                <input type="text" name="site_phone" value="<?= sanitize($s['site_phone'] ?? '') ?>"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea name="site_address" rows="2"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"><?= sanitize($s['site_address'] ?? '') ?></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                <?php if (!empty($s['logo'])): ?>
                    <img src="<?= UPLOAD_URL . sanitize($s['logo']) ?>" class="h-12 mb-2">
                <?php endif; ?>
                <input type="file" name="logo" accept="image/jpeg,image/png,image/webp"
                    class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-blue-50 file:text-blue-700">
            </div>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                Simpan Pengaturan
            </button>
        </form>
    </main>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
