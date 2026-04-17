<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo      = getPDO();
$perPage  = 9;
$currentP = max(1, (int)($_GET['p'] ?? 1));
$offset   = ($currentP - 1) * $perPage;

$total      = $pdo->query("SELECT COUNT(*) FROM posts WHERE status='published'")->fetchColumn();
$totalPages = (int)ceil($total / $perPage);

$stmt = $pdo->prepare("SELECT id, title, slug, image, created_at FROM posts WHERE status='published' ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->execute([$perPage, $offset]);
$posts = $stmt->fetchAll();

$pageTitle = 'Berita & Informasi';
include __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<div style="background:linear-gradient(135deg,#fff7ed,#fef9f0); border-bottom:1px solid #fed7aa; padding:3rem 1.5rem;">
    <div style="max-width:1200px; margin:0 auto;">
        <nav style="font-size:0.8rem; color:#9ca3af; margin-bottom:0.75rem; display:flex; align-items:center; gap:0.5rem;">
            <a href="<?= BASE_URL ?>/" style="color:#f97316; text-decoration:none; font-weight:600;">Beranda</a>
            <span>›</span>
            <span style="color:#6b7280;">Berita</span>
        </nav>
        <h1 style="font-size:2.25rem; font-weight:900; color:#1f2937; margin:0 0 0.5rem;">Berita & Informasi</h1>
        <p style="color:#6b7280; font-size:0.9rem; margin:0;">Informasi terkini seputar kegiatan dan pengumuman resmi</p>
    </div>
</div>

<div style="max-width:1200px; margin:0 auto; padding:3.5rem 1.5rem;">
    <?php if ($posts): ?>
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1.5rem; margin-bottom:3rem;">
        <?php foreach ($posts as $i => $post): ?>
        <article class="card-lift" style="background:#fff; border-radius:1.25rem; overflow:hidden; border:1px solid #f3f4f6; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
            <?php if ($post['image']): ?>
                <img src="<?= UPLOAD_URL . sanitize($post['image']) ?>"
                     alt="<?= sanitize($post['title']) ?>"
                     style="width:100%; height:200px; object-fit:cover; display:block;">
            <?php else: ?>
                <div style="width:100%; height:200px; background:linear-gradient(135deg,<?= $i%3===0 ? '#fff7ed,#fed7aa' : ($i%3===1 ? '#f0fdf4,#bbf7d0' : '#f8fafc,#e2e8f0') ?>); display:flex; align-items:center; justify-content:center; font-size:3.5rem;">
                    📰
                </div>
            <?php endif; ?>
            <div style="padding:1.25rem;">
                <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.75rem;">
                    <span class="badge-orange">Berita</span>
                    <span style="font-size:0.72rem; color:#9ca3af;"><?= formatDate($post['created_at']) ?></span>
                </div>
                <h2 style="font-size:0.9rem; font-weight:700; color:#1f2937; line-height:1.45; margin-bottom:0.875rem; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                    <?= sanitize($post['title']) ?>
                </h2>
                <a href="<?= BASE_URL ?>/?page=blog-detail&slug=<?= urlencode($post['slug']) ?>"
                   style="font-size:0.82rem; font-weight:600; color:#f97316; text-decoration:none;"
                   onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#f97316'">
                    Baca selengkapnya →
                </a>
            </div>
        </article>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div style="display:flex; justify-content:center; gap:0.5rem; flex-wrap:wrap;">
        <?php if ($currentP > 1): ?>
        <a href="?page=blog&p=<?= $currentP-1 ?>"
           style="padding:0.5rem 1rem; border-radius:0.625rem; font-size:0.875rem; font-weight:600; border:1px solid #e5e7eb; background:#fff; color:#374151; text-decoration:none;"
           onmouseover="this.style.borderColor='#f97316';this.style.color='#f97316'" onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#374151'">
            ← Prev
        </a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=blog&p=<?= $i ?>"
           style="padding:0.5rem 0.9rem; border-radius:0.625rem; font-size:0.875rem; font-weight:600; text-decoration:none; <?= $i === $currentP ? 'background:#f97316; color:#fff; border:1px solid #f97316;' : 'border:1px solid #e5e7eb; background:#fff; color:#374151;' ?>">
            <?= $i ?>
        </a>
        <?php endfor; ?>
        <?php if ($currentP < $totalPages): ?>
        <a href="?page=blog&p=<?= $currentP+1 ?>"
           style="padding:0.5rem 1rem; border-radius:0.625rem; font-size:0.875rem; font-weight:600; border:1px solid #e5e7eb; background:#fff; color:#374151; text-decoration:none;"
           onmouseover="this.style.borderColor='#f97316';this.style.color='#f97316'" onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#374151'">
            Next →
        </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <div style="text-align:center; padding:6rem 0; color:#9ca3af;">
        <div style="font-size:5rem; margin-bottom:1rem;">📭</div>
        <p style="font-size:1.1rem; font-weight:600; color:#6b7280; margin-bottom:0.5rem;">Belum ada berita.</p>
        <p style="font-size:0.875rem;">Konten sedang disiapkan.</p>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
