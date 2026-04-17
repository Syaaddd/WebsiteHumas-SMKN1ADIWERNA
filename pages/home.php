<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo         = getPDO();
$latestPosts = $pdo->query("SELECT id, title, slug, image, created_at FROM posts WHERE status='published' ORDER BY created_at DESC LIMIT 6")->fetchAll();
$totalNews   = $pdo->query("SELECT COUNT(*) FROM posts WHERE status='published'")->fetchColumn();

$pageTitle = 'Beranda';
include __DIR__ . '/../includes/header.php';
?>

<!-- ═══ HERO ═══ -->
<section style="background:linear-gradient(135deg,#fff7ed 0%,#fef9f0 60%,#f0fdf4 100%); padding:5rem 1.5rem;">
    <div style="max-width:1200px; margin:0 auto; display:grid; grid-template-columns:1fr 1fr; gap:4rem; align-items:center;">
        <div>
            <div class="section-tag">🎓 Portal Informasi Resmi</div>
            <h1 style="font-size:2.75rem; font-weight:900; color:#1f2937; line-height:1.15; margin-bottom:1.25rem;">
                Informasi Terkini<br>
                <span style="color:#f97316;">Seputar Sekolah</span><br>
                <span style="color:#16a34a;">& Pendidikan</span>
            </h1>
            <p style="font-size:1rem; color:#6b7280; line-height:1.7; margin-bottom:2rem; max-width:480px;">
                <?= htmlspecialchars(getSetting('site_description', 'Kami menyediakan informasi terpercaya seputar kegiatan, pengumuman, dan berita terbaru dari institusi kami.')) ?>
            </p>
            <div style="display:flex; gap:0.875rem; flex-wrap:wrap;">
                <a href="<?= BASE_URL ?>/?page=blog" class="btn-orange">📰 Lihat Berita</a>
                <a href="<?= BASE_URL ?>/?page=about" class="btn-outline">Tentang Kami →</a>
            </div>
        </div>

        <!-- Visual Card -->
        <div style="position:relative;">
            <div style="background:#fff; border-radius:1.5rem; padding:2rem; box-shadow:0 20px 60px rgba(249,115,22,0.12); border:1px solid #fed7aa;">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                    <div style="background:#fff7ed; border-radius:1rem; padding:1.25rem; text-align:center; border:1px solid #fed7aa;">
                        <div style="font-size:2rem; margin-bottom:0.25rem;">📰</div>
                        <div style="font-size:1.5rem; font-weight:900; color:#f97316;"><?= $totalNews ?></div>
                        <div style="font-size:0.72rem; color:#9ca3af; font-weight:600;">Berita Tayang</div>
                    </div>
                    <div style="background:#f0fdf4; border-radius:1rem; padding:1.25rem; text-align:center; border:1px solid #bbf7d0;">
                        <div style="font-size:2rem; margin-bottom:0.25rem;">✅</div>
                        <div style="font-size:1.5rem; font-weight:900; color:#16a34a;">Resmi</div>
                        <div style="font-size:0.72rem; color:#9ca3af; font-weight:600;">Terverifikasi</div>
                    </div>
                </div>
                <div style="background:linear-gradient(135deg,#f97316,#ea580c); border-radius:1rem; padding:1.25rem; color:#fff; display:flex; align-items:center; gap:1rem;">
                    <div style="font-size:2.5rem;">🏫</div>
                    <div>
                        <div style="font-weight:800; font-size:0.95rem;"><?= htmlspecialchars(getSetting('site_name', 'Humas Sekolah')) ?></div>
                        <div style="font-size:0.75rem; opacity:0.85;">Terdepan dalam informasi</div>
                    </div>
                </div>
                <!-- Floating badge -->
                <div style="position:absolute; top:-12px; right:24px; background:#16a34a; color:#fff; font-size:0.7rem; font-weight:700; padding:0.35rem 0.85rem; border-radius:999px; box-shadow:0 4px 12px rgba(22,163,74,0.4);">
                    🔴 Live Update
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ FEATURE STRIPS ═══ -->
<section style="background:#fff; border-top:1px solid #f3f4f6; border-bottom:1px solid #f3f4f6;">
    <div style="max-width:1200px; margin:0 auto; padding:1.5rem; display:grid; grid-template-columns:repeat(4,1fr); gap:0.5rem; text-align:center;">
        <?php
        $features = [
            ['🎯','Informasi Akurat','Data terverifikasi resmi'],
            ['⚡','Update Terkini','Berita setiap hari'],
            ['📱','Mudah Diakses','Dari perangkat apapun'],
            ['🔒','Terpercaya','Sumber informasi resmi'],
        ];
        foreach ($features as $f): ?>
        <div style="padding:1rem; border-radius:0.75rem;">
            <div style="font-size:1.5rem; margin-bottom:0.35rem;"><?= $f[0] ?></div>
            <div style="font-size:0.8rem; font-weight:700; color:#1f2937; margin-bottom:0.15rem;"><?= $f[1] ?></div>
            <div style="font-size:0.72rem; color:#9ca3af;"><?= $f[2] ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ═══ BERITA TERBARU ═══ -->
<section style="max-width:1200px; margin:0 auto; padding:5rem 1.5rem;">
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:2.5rem;">
        <div>
            <div class="section-tag">📰 Terkini</div>
            <h2 style="font-size:1.875rem; font-weight:900; color:#1f2937; margin:0;">Berita & Informasi</h2>
        </div>
        <a href="<?= BASE_URL ?>/?page=blog" style="font-size:0.875rem; font-weight:600; color:#f97316; text-decoration:none;"
           onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#f97316'">
            Lihat semua →
        </a>
    </div>

    <?php if ($latestPosts): ?>
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1.5rem;">
        <?php foreach ($latestPosts as $i => $post): ?>
        <article class="card-lift" style="background:#fff; border-radius:1.25rem; overflow:hidden; border:1px solid #f3f4f6; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
            <?php if ($post['image']): ?>
                <img src="<?= UPLOAD_URL . sanitize($post['image']) ?>"
                     alt="<?= sanitize($post['title']) ?>"
                     style="width:100%; height:200px; object-fit:cover; display:block;">
            <?php else: ?>
                <div style="width:100%; height:200px; background:linear-gradient(135deg,<?= $i%2===0 ? '#fff7ed,#fed7aa' : '#f0fdf4,#bbf7d0' ?>); display:flex; align-items:center; justify-content:center; font-size:3.5rem;">
                    📰
                </div>
            <?php endif; ?>
            <div style="padding:1.25rem;">
                <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.75rem;">
                    <span class="badge-orange">Berita</span>
                    <span style="font-size:0.72rem; color:#9ca3af;"><?= formatDate($post['created_at']) ?></span>
                </div>
                <h3 style="font-size:0.9rem; font-weight:700; color:#1f2937; line-height:1.45; margin-bottom:0.875rem;">
                    <?= sanitize($post['title']) ?>
                </h3>
                <a href="<?= BASE_URL ?>/?page=blog-detail&slug=<?= urlencode($post['slug']) ?>"
                   style="font-size:0.82rem; font-weight:600; color:#f97316; text-decoration:none; display:inline-flex; align-items:center; gap:0.25rem;"
                   onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#f97316'">
                    Baca selengkapnya →
                </a>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div style="text-align:center; padding:5rem 0; color:#9ca3af;">
        <div style="font-size:4rem; margin-bottom:0.75rem;">📭</div>
        <p style="font-size:1rem; font-weight:500;">Belum ada berita yang dipublikasikan.</p>
    </div>
    <?php endif; ?>
</section>

<!-- ═══ CTA BANNER ═══ -->
<section style="background:linear-gradient(135deg,#1f2937 0%,#374151 100%); padding:4rem 1.5rem; text-align:center;">
    <div style="max-width:640px; margin:0 auto;">
        <div style="display:inline-flex; align-items:center; gap:0.5rem; background:rgba(249,115,22,0.15); color:#fb923c; font-size:0.75rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; padding:0.3rem 1rem; border-radius:999px; border:1px solid rgba(249,115,22,0.3); margin-bottom:1.25rem;">
            💬 Ada Pertanyaan?
        </div>
        <h2 style="font-size:2rem; font-weight:900; color:#fff; margin-bottom:1rem; line-height:1.3;">
            Hubungi Tim Humas Kami
        </h2>
        <p style="color:#9ca3af; margin-bottom:2rem; line-height:1.7; font-size:0.95rem;">
            Kami siap membantu menjawab pertanyaan seputar informasi dan kegiatan institusi.
        </p>
        <a href="<?= BASE_URL ?>/?page=contact" class="btn-orange" style="font-size:1rem; padding:0.875rem 2.5rem;">
            📬 Kirim Pesan Sekarang
        </a>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
