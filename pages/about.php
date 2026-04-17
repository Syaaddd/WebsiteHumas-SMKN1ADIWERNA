<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo  = getPDO();
$stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = 'about'");
$stmt->execute();
$page = $stmt->fetch();

$pageTitle       = $page['title'] ?? 'Tentang Kami';
$metaDescription = $page['meta_description'] ?? '';
include __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<div style="background:linear-gradient(135deg,#fff7ed,#fef9f0); border-bottom:1px solid #fed7aa; padding:3rem 1.5rem;">
    <div style="max-width:1200px; margin:0 auto;">
        <nav style="font-size:0.8rem; color:#9ca3af; margin-bottom:0.75rem; display:flex; align-items:center; gap:0.5rem;">
            <a href="<?= BASE_URL ?>/" style="color:#f97316; text-decoration:none; font-weight:600;">Beranda</a>
            <span>›</span>
            <span style="color:#6b7280;"><?= htmlspecialchars($page['title'] ?? 'Tentang Kami') ?></span>
        </nav>
        <h1 style="font-size:2.25rem; font-weight:900; color:#1f2937; margin:0;"><?= htmlspecialchars($page['title'] ?? 'Tentang Kami') ?></h1>
    </div>
</div>

<div style="max-width:900px; margin:0 auto; padding:3.5rem 1.5rem;">
    <div style="background:#fff; border-radius:1.5rem; padding:3rem; box-shadow:0 4px 20px rgba(0,0,0,0.06); border:1px solid #f3f4f6;">
        <?php if ($page && $page['content']): ?>
            <div style="font-size:0.95rem; color:#374151; line-height:1.8;">
                <?= nl2br(htmlspecialchars($page['content'])) ?>
            </div>
        <?php else: ?>
            <div style="text-align:center; padding:4rem 0; color:#9ca3af;">
                <div style="font-size:5rem; margin-bottom:1rem;">🏫</div>
                <p style="font-size:1rem; font-weight:500; color:#6b7280;">Konten halaman ini belum tersedia.</p>
                <p style="font-size:0.85rem;">Silakan edit melalui panel admin.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
