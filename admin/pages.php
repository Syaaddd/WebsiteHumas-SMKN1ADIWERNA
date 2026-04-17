<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo    = getPDO();
$errors = [];
$msg    = '';

// Load page for editing
$editSlug = $_GET['slug'] ?? '';
$editPage = null;
if ($editSlug) {
    $stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = ?");
    $stmt->execute([$editSlug]);
    $editPage = $stmt->fetch();
}

if (isPost()) {
    verifyCsrf();

    $slug            = trim($_POST['slug'] ?? '');
    $title           = trim($_POST['title'] ?? '');
    $content         = trim($_POST['content'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');

    if (!$slug || !$title) {
        $errors[] = 'Slug dan judul wajib diisi.';
    }

    if (empty($errors)) {
        $check = $pdo->prepare("SELECT COUNT(*) FROM pages WHERE slug = ?");
        $check->execute([$slug]);

        if ($check->fetchColumn() > 0) {
            $stmt = $pdo->prepare("UPDATE pages SET title=?, content=?, meta_description=? WHERE slug=?");
            $stmt->execute([$title, $content, $meta_description, $slug]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO pages (slug, title, content, meta_description) VALUES (?,?,?,?)");
            $stmt->execute([$slug, $title, $content, $meta_description]);
        }
        $msg = 'Halaman berhasil disimpan.';
        $editPage = $pdo->prepare("SELECT * FROM pages WHERE slug = ?") ?: null;
        if ($editPage) { $editPage->execute([$slug]); $editPage = $editPage->fetch(); }
    }
}

$pages = $pdo->query("SELECT slug, title, updated_at FROM pages ORDER BY slug")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman — Admin Humas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/output.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <main class="ml-64 p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Kelola Halaman</h1>

        <?php if ($msg): ?>
            <div class="bg-green-100 text-green-700 text-sm px-4 py-3 rounded-lg mb-4"><?= sanitize($msg) ?></div>
        <?php endif; ?>
        <?php if ($errors): ?>
            <div class="bg-red-100 text-red-700 text-sm px-4 py-3 rounded-lg mb-4">
                <?php foreach ($errors as $e): ?><p><?= sanitize($e) ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-3 gap-6">
            <!-- List halaman -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="font-semibold text-gray-700 mb-4">Daftar Halaman</h2>
                <ul class="space-y-2 text-sm">
                    <?php foreach ($pages as $p): ?>
                    <li>
                        <a href="?slug=<?= urlencode($p['slug']) ?>"
                           class="flex justify-between items-center hover:text-blue-600 <?= $editSlug === $p['slug'] ? 'text-blue-600 font-medium' : 'text-gray-700' ?>">
                            <span><?= sanitize($p['title']) ?></span>
                            <span class="text-xs text-gray-400">/<?= sanitize($p['slug']) ?></span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <hr class="my-4">
                <a href="?slug=" class="text-sm text-blue-600 hover:underline">+ Buat halaman baru</a>
            </div>

            <!-- Form edit -->
            <div class="col-span-2">
                <form method="POST" class="bg-white rounded-xl shadow p-6 space-y-4">
                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug (URL)</label>
                        <input type="text" name="slug"
                            value="<?= sanitize($editPage['slug'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="about, contact, dll">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                        <input type="text" name="title"
                            value="<?= sanitize($editPage['title'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konten</label>
                        <textarea name="content" rows="8"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"><?= sanitize($editPage['content'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description (SEO)</label>
                        <input type="text" name="meta_description"
                            value="<?= sanitize($editPage['meta_description'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </main>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
