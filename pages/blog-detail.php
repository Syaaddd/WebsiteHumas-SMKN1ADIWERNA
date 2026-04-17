<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo  = getPDO();
$slug = $_GET['slug'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM posts WHERE slug = ? AND status = 'published'");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    http_response_code(404);
    include __DIR__ . '/404.php';
    exit;
}

$related = $pdo->prepare("SELECT id, title, slug, image, created_at FROM posts WHERE status='published' AND id != ? ORDER BY created_at DESC LIMIT 3");
$related->execute([$post['id']]);
$relatedPosts = $related->fetchAll();

$pageTitle       = $post['title'];
$metaDescription = mb_strimwidth(strip_tags($post['content']), 0, 160, '...');
include __DIR__ . '/../includes/header.php';
?>

<!-- Breadcrumb -->
<div style="background:#fff; border-bottom:1px solid #f3f4f6; padding:0.875rem 1.5rem;">
    <div style="max-width:900px; margin:0 auto; font-size:0.8rem; display:flex; align-items:center; gap:0.5rem; color:#9ca3af;">
        <a href="<?= BASE_URL ?>/" style="color:#f97316; text-decoration:none; font-weight:600;">Beranda</a>
        <span>›</span>
        <a href="<?= BASE_URL ?>/?page=blog" style="color:#f97316; text-decoration:none; font-weight:600;">Berita</a>
        <span>›</span>
        <span style="color:#6b7280; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:300px;">
            <?= sanitize($post['title']) ?>
        </span>
    </div>
</div>

<div style="max-width:900px; margin:0 auto; padding:2.5rem 1.5rem;">
    <article style="background:#fff; border-radius:1.5rem; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.07); border:1px solid #f3f4f6;">
        <?php if ($post['image']): ?>
        <img src="<?= UPLOAD_URL . sanitize($post['image']) ?>"
             alt="<?= sanitize($post['title']) ?>"
             style="width:100%; height:380px; object-fit:cover; display:block;">
        <?php endif; ?>

        <div style="padding:2.5rem 3rem;">
            <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.25rem;">
                <span class="badge-orange">Berita</span>
                <span class="badge-green">Resmi</span>
                <span style="font-size:0.78rem; color:#9ca3af;">📅 <?= formatDate($post['created_at'], 'd F Y') ?></span>
            </div>

            <h1 style="font-size:1.875rem; font-weight:900; color:#1f2937; margin-bottom:2rem; line-height:1.3;">
                <?= sanitize($post['title']) ?>
            </h1>

            <div style="font-size:0.95rem; color:#374151; line-height:1.85; border-top:2px solid #f3f4f6; padding-top:2rem;">
                <?= nl2br(htmlspecialchars($post['content'])) ?>
            </div>

            <div style="margin-top:2.5rem; padding-top:1.5rem; border-top:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center;">
                <a href="<?= BASE_URL ?>/?page=blog"
                   style="font-size:0.875rem; font-weight:600; color:#f97316; text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem;"
                   onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#f97316'">
                    ← Kembali ke Berita
                </a>
                <span style="font-size:0.75rem; color:#9ca3af;">Diposting: <?= formatDate($post['created_at'], 'd M Y') ?></span>
            </div>
        </div>
    </article>
</div>

<!-- Berita Terkait -->
<?php if ($relatedPosts): ?>
<div style="max-width:900px; margin:0 auto; padding:0 1.5rem 4rem;">
    <h2 style="font-size:1.125rem; font-weight:800; color:#1f2937; margin-bottom:1.25rem;">Berita Lainnya</h2>
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem;">
        <?php foreach ($relatedPosts as $i => $r): ?>
        <a href="<?= BASE_URL ?>/?page=blog-detail&slug=<?= urlencode($r['slug']) ?>"
           class="card-lift"
           style="background:#fff; border-radius:1rem; overflow:hidden; border:1px solid #f3f4f6; text-decoration:none; display:block;">
            <?php if ($r['image']): ?>
            <img src="<?= UPLOAD_URL . sanitize($r['image']) ?>" style="width:100%; height:120px; object-fit:cover; display:block;">
            <?php else: ?>
            <div style="width:100%; height:120px; background:linear-gradient(135deg,<?= $i%2===0?'#fff7ed,#fed7aa':'#f0fdf4,#bbf7d0' ?>); display:flex; align-items:center; justify-content:center; font-size:2rem;">📰</div>
            <?php endif; ?>
            <div style="padding:0.875rem;">
                <p style="font-size:0.72rem; color:#9ca3af; margin-bottom:0.3rem;"><?= formatDate($r['created_at']) ?></p>
                <p style="font-size:0.82rem; font-weight:700; color:#1f2937; line-height:1.4; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;"><?= sanitize($r['title']) ?></p>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
