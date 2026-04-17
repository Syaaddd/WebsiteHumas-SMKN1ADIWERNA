<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo    = getPDO();
$id     = (int)($_GET['id'] ?? 0);
$errors = [];

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    redirect(BASE_URL . '/admin/posts.php');
}

$data = $post;

if (isPost()) {
    verifyCsrf();

    $data['title']   = trim($_POST['title'] ?? '');
    $data['content'] = trim($_POST['content'] ?? '');
    $data['status']  = in_array($_POST['status'] ?? '', ['draft', 'published']) ? $_POST['status'] : 'draft';

    if (!$data['title']) $errors[] = 'Judul wajib diisi.';

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $file = $_FILES['image'];
        if ($file['error'] === UPLOAD_ERR_OK && $file['size'] <= MAX_FILE_SIZE) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            if (in_array($mime, ALLOWED_MIME)) {
                // Hapus gambar lama
                if ($post['image'] && file_exists(UPLOAD_DIR . $post['image'])) {
                    unlink(UPLOAD_DIR . $post['image']);
                }
                $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
                $name = uniqid('img_', true) . '.' . strtolower($ext);
                if (move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $name)) {
                    $data['image'] = $name;
                }
            } else {
                $errors[] = 'Tipe file gambar tidak diizinkan.';
            }
        } else {
            $errors[] = 'File gambar terlalu besar atau error upload.';
        }
    }

    if (empty($errors)) {
        $slug = makeSlug($data['title']);
        $check = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE slug = ? AND id != ?");
        $check->execute([$slug, $id]);
        if ($check->fetchColumn() > 0) {
            $slug .= '-' . time();
        }

        $stmt = $pdo->prepare("UPDATE posts SET title=?, slug=?, content=?, image=?, status=? WHERE id=?");
        $stmt->execute([$data['title'], $slug, $data['content'], $data['image'], $data['status'], $id]);
        redirect(BASE_URL . '/admin/posts.php');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel — Admin Humas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/output.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <main class="ml-64 p-8">
        <div class="flex items-center gap-3 mb-6">
            <a href="<?= BASE_URL ?>/admin/posts.php" class="text-gray-400 hover:text-gray-600">← Kembali</a>
            <h1 class="text-2xl font-bold text-gray-800">Edit Artikel</h1>
        </div>

        <?php if ($errors): ?>
            <div class="bg-red-100 text-red-700 text-sm px-4 py-3 rounded-lg mb-4">
                <?php foreach ($errors as $e): ?><p><?= sanitize($e) ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="grid grid-cols-3 gap-6">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="col-span-2 space-y-4">
                <div class="bg-white rounded-xl shadow p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul *</label>
                    <input type="text" name="title" required
                        value="<?= sanitize($data['title']) ?>"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="bg-white rounded-xl shadow p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konten</label>
                    <textarea name="content" rows="12"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"><?= sanitize($data['content']) ?></textarea>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-white rounded-xl shadow p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="draft" <?= $data['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= $data['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                    </select>
                </div>
                <div class="bg-white rounded-xl shadow p-6">
                    <?php if ($data['image']): ?>
                        <p class="text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</p>
                        <img src="<?= UPLOAD_URL . sanitize($data['image']) ?>" class="w-full rounded mb-3 object-cover h-32">
                    <?php endif; ?>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ganti Gambar</label>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
                        class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-blue-50 file:text-blue-700">
                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengganti.</p>
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
                    Update Artikel
                </button>
            </div>
        </form>
    </main>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
