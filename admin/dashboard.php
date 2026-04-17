<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getPDO();
$totalPosts     = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$publishedPosts = $pdo->query("SELECT COUNT(*) FROM posts WHERE status = 'published'")->fetchColumn();
$draftPosts     = $pdo->query("SELECT COUNT(*) FROM posts WHERE status = 'draft'")->fetchColumn();
$totalPages     = $pdo->query("SELECT COUNT(*) FROM pages")->fetchColumn();

$recentPosts = $pdo->query("SELECT id, title, status, created_at FROM posts ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Admin Humas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/output.css">
</head>
<body class="bg-gray-100 min-h-screen" x-data>
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <main class="ml-64 p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

        <div class="grid grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-sm text-gray-500">Total Artikel</p>
                <p class="text-3xl font-bold text-blue-600"><?= $totalPosts ?></p>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-sm text-gray-500">Published</p>
                <p class="text-3xl font-bold text-green-600"><?= $publishedPosts ?></p>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-sm text-gray-500">Draft</p>
                <p class="text-3xl font-bold text-yellow-600"><?= $draftPosts ?></p>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-sm text-gray-500">Halaman</p>
                <p class="text-3xl font-bold text-purple-600"><?= $totalPages ?></p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Artikel Terbaru</h2>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-2">Judul</th>
                        <th class="pb-2">Status</th>
                        <th class="pb-2">Tanggal</th>
                        <th class="pb-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentPosts as $post): ?>
                    <tr class="border-b last:border-0">
                        <td class="py-2"><?= sanitize($post['title']) ?></td>
                        <td class="py-2">
                            <span class="px-2 py-0.5 rounded-full text-xs <?= $post['status'] === 'published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                                <?= $post['status'] ?>
                            </span>
                        </td>
                        <td class="py-2 text-gray-500"><?= formatDate($post['created_at']) ?></td>
                        <td class="py-2">
                            <a href="<?= BASE_URL ?>/admin/posts-edit.php?id=<?= $post['id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
