<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getPDO();

// Handle delete
if (isPost() && isset($_POST['delete_id'])) {
    verifyCsrf();
    $stmt = $pdo->prepare("SELECT image FROM posts WHERE id = ?");
    $stmt->execute([(int)$_POST['delete_id']]);
    $post = $stmt->fetch();
    if ($post && $post['image'] && file_exists(UPLOAD_DIR . $post['image'])) {
        unlink(UPLOAD_DIR . $post['image']);
    }
    $pdo->prepare("DELETE FROM posts WHERE id = ?")->execute([(int)$_POST['delete_id']]);
    redirect(BASE_URL . '/admin/posts.php');
}

$posts = $pdo->query("SELECT id, title, status, created_at FROM posts ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel — Admin Humas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/output.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <main class="ml-64 p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Artikel</h1>
            <a href="<?= BASE_URL ?>/admin/posts-add.php"
               class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                + Tambah Artikel
            </a>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-left text-gray-500">
                        <th class="px-4 py-3">Judul</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                    <tr class="border-b last:border-0 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800"><?= sanitize($post['title']) ?></td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs <?= $post['status'] === 'published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                                <?= $post['status'] ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500"><?= formatDate($post['created_at']) ?></td>
                        <td class="px-4 py-3 space-x-3">
                            <a href="<?= BASE_URL ?>/admin/posts-edit.php?id=<?= $post['id'] ?>"
                               class="text-blue-600 hover:underline">Edit</a>
                            <form method="POST" class="inline"
                                  onsubmit="return confirm('Hapus artikel ini?')">
                                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                <input type="hidden" name="delete_id" value="<?= $post['id'] ?>">
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($posts)): ?>
                    <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">Belum ada artikel.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
