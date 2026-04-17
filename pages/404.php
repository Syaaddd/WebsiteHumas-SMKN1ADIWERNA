<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

http_response_code(404);
$pageTitle = 'Halaman Tidak Ditemukan';
include __DIR__ . '/../includes/header.php';
?>

<div style="min-height:70vh; display:flex; align-items:center; justify-content:center; padding:4rem 1.5rem; text-align:center;">
    <div>
        <div style="font-size:7rem; font-weight:900; color:#fed7aa; line-height:1; margin-bottom:0.5rem;">404</div>
        <h1 style="font-size:1.5rem; font-weight:800; color:#1f2937; margin-bottom:0.75rem;">Halaman Tidak Ditemukan</h1>
        <p style="color:#9ca3af; font-size:0.9rem; max-width:380px; margin:0 auto 2rem; line-height:1.6;">
            Halaman yang Anda cari tidak tersedia atau telah dipindahkan ke alamat lain.
        </p>
        <div style="display:flex; gap:0.875rem; justify-content:center; flex-wrap:wrap;">
            <a href="<?= BASE_URL ?>/" class="btn-orange">🏠 Kembali ke Beranda</a>
            <a href="<?= BASE_URL ?>/?page=blog" class="btn-outline">📰 Lihat Berita</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
