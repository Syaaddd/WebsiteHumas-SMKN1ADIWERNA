<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo  = getPDO();
$stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = 'contact'");
$stmt->execute();
$page = $stmt->fetch();

$msg    = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize(trim($_POST['name'] ?? ''));
    $email   = trim($_POST['email'] ?? '');
    $subject = sanitize(trim($_POST['subject'] ?? ''));
    $body    = sanitize(trim($_POST['message'] ?? ''));

    if (!$name) $errors[] = 'Nama wajib diisi.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid.';
    if (!$body) $errors[] = 'Pesan wajib diisi.';

    if (empty($errors)) {
        $to      = getSetting('site_email', '');
        $headers = "From: $name <$email>\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8";
        if ($to) mail($to, $subject ?: 'Pesan dari Website', $body, $headers);
        $msg = 'Pesan berhasil dikirim. Terima kasih telah menghubungi kami!';
    }
}

$pageTitle       = $page['title'] ?? 'Hubungi Kami';
$metaDescription = $page['meta_description'] ?? '';
include __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<div style="background:linear-gradient(135deg,#fff7ed,#fef9f0); border-bottom:1px solid #fed7aa; padding:3rem 1.5rem;">
    <div style="max-width:1200px; margin:0 auto;">
        <nav style="font-size:0.8rem; color:#9ca3af; margin-bottom:0.75rem; display:flex; align-items:center; gap:0.5rem;">
            <a href="<?= BASE_URL ?>/" style="color:#f97316; text-decoration:none; font-weight:600;">Beranda</a>
            <span>›</span>
            <span style="color:#6b7280;">Kontak</span>
        </nav>
        <h1 style="font-size:2.25rem; font-weight:900; color:#1f2937; margin:0 0 0.5rem;"><?= htmlspecialchars($page['title'] ?? 'Hubungi Kami') ?></h1>
        <?php if ($page && $page['content']): ?>
        <p style="color:#6b7280; font-size:0.9rem; margin:0;"><?= htmlspecialchars($page['content']) ?></p>
        <?php endif; ?>
    </div>
</div>

<div style="max-width:1100px; margin:0 auto; padding:3.5rem 1.5rem;">
    <div style="display:grid; grid-template-columns:3fr 2fr; gap:2.5rem; align-items:start;">

        <!-- Form -->
        <div style="background:#fff; border-radius:1.5rem; padding:2.5rem; box-shadow:0 4px 20px rgba(0,0,0,0.06); border:1px solid #f3f4f6;">
            <h2 style="font-size:1.1rem; font-weight:800; color:#1f2937; margin:0 0 1.5rem;">Kirim Pesan</h2>

            <?php if ($msg): ?>
            <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; font-size:0.875rem; padding:0.875rem 1rem; border-radius:0.75rem; margin-bottom:1.25rem; display:flex; align-items:center; gap:0.5rem;">
                ✅ <?= $msg ?>
            </div>
            <?php endif; ?>
            <?php if ($errors): ?>
            <div style="background:#fff7ed; border:1px solid #fed7aa; color:#c2410c; font-size:0.875rem; padding:0.875rem 1rem; border-radius:0.75rem; margin-bottom:1.25rem;">
                <?php foreach ($errors as $e): ?><p style="margin:0.2rem 0;">⚠️ <?= $e ?></p><?php endforeach; ?>
            </div>
            <?php endif; ?>

            <form method="POST">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                    <div>
                        <label style="display:block; font-size:0.8rem; font-weight:700; color:#374151; margin-bottom:0.4rem;">Nama *</label>
                        <input type="text" name="name" required value="<?= sanitize($_POST['name'] ?? '') ?>"
                               style="width:100%; border:1.5px solid #e5e7eb; border-radius:0.625rem; padding:0.625rem 0.875rem; font-size:0.875rem; background:#f9fafb; color:#1f2937; transition:border-color 0.15s; box-sizing:border-box;"
                               onfocus="this.style.borderColor='#f97316';this.style.boxShadow='0 0 0 3px rgba(249,115,22,0.1)'"
                               onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                    </div>
                    <div>
                        <label style="display:block; font-size:0.8rem; font-weight:700; color:#374151; margin-bottom:0.4rem;">Email *</label>
                        <input type="email" name="email" required value="<?= sanitize($_POST['email'] ?? '') ?>"
                               style="width:100%; border:1.5px solid #e5e7eb; border-radius:0.625rem; padding:0.625rem 0.875rem; font-size:0.875rem; background:#f9fafb; color:#1f2937; transition:border-color 0.15s; box-sizing:border-box;"
                               onfocus="this.style.borderColor='#f97316';this.style.boxShadow='0 0 0 3px rgba(249,115,22,0.1)'"
                               onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                    </div>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-size:0.8rem; font-weight:700; color:#374151; margin-bottom:0.4rem;">Subjek</label>
                    <input type="text" name="subject" value="<?= sanitize($_POST['subject'] ?? '') ?>"
                           style="width:100%; border:1.5px solid #e5e7eb; border-radius:0.625rem; padding:0.625rem 0.875rem; font-size:0.875rem; background:#f9fafb; color:#1f2937; box-sizing:border-box;"
                           onfocus="this.style.borderColor='#f97316';this.style.boxShadow='0 0 0 3px rgba(249,115,22,0.1)'"
                           onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                </div>
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; font-size:0.8rem; font-weight:700; color:#374151; margin-bottom:0.4rem;">Pesan *</label>
                    <textarea name="message" rows="5" required
                              style="width:100%; border:1.5px solid #e5e7eb; border-radius:0.625rem; padding:0.625rem 0.875rem; font-size:0.875rem; background:#f9fafb; color:#1f2937; resize:vertical; box-sizing:border-box;"
                              onfocus="this.style.borderColor='#f97316';this.style.boxShadow='0 0 0 3px rgba(249,115,22,0.1)'"
                              onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'"><?= sanitize($_POST['message'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="btn-orange" style="width:100%; text-align:center; padding:0.8rem; font-size:0.95rem;">
                    📬 Kirim Pesan
                </button>
            </form>
        </div>

        <!-- Info Cards -->
        <div style="display:flex; flex-direction:column; gap:1rem;">
            <?php $s = getSettings();
            $cards = [
                ['icon'=>'📍','label'=>'Alamat','val'=>nl2br(htmlspecialchars($s['site_address']??'')),'show'=>!empty($s['site_address']),'color'=>'#fff7ed','border'=>'#fed7aa','icon_bg'=>'#f97316'],
                ['icon'=>'📞','label'=>'Telepon','val'=>htmlspecialchars($s['site_phone']??''),'show'=>!empty($s['site_phone']),'color'=>'#f0fdf4','border'=>'#bbf7d0','icon_bg'=>'#16a34a'],
                ['icon'=>'✉️','label'=>'Email','val'=>htmlspecialchars($s['site_email']??''),'show'=>!empty($s['site_email']),'color'=>'#fff7ed','border'=>'#fed7aa','icon_bg'=>'#f97316'],
            ];
            foreach ($cards as $c):
                if (!$c['show']) continue; ?>
            <div style="background:<?= $c['color'] ?>; border:1px solid <?= $c['border'] ?>; border-radius:1rem; padding:1.25rem; display:flex; align-items:flex-start; gap:1rem;">
                <div style="width:40px; height:40px; border-radius:0.625rem; background:<?= $c['icon_bg'] ?>; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0;">
                    <?= $c['icon'] ?>
                </div>
                <div>
                    <p style="font-size:0.72rem; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:0.06em; margin:0 0 0.2rem;"><?= $c['label'] ?></p>
                    <p style="font-size:0.875rem; color:#374151; margin:0; font-weight:500;"><?= $c['val'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Jam Operasional -->
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:1rem; padding:1.25rem;">
                <div style="display:flex; align-items:center; gap:0.625rem; margin-bottom:0.75rem;">
                    <div style="width:36px; height:36px; border-radius:0.5rem; background:#1f2937; display:flex; align-items:center; justify-content:center; font-size:1rem;">🕐</div>
                    <p style="font-size:0.8rem; font-weight:700; color:#1f2937; margin:0;">Jam Operasional</p>
                </div>
                <div style="font-size:0.82rem; color:#6b7280; display:flex; flex-direction:column; gap:0.3rem;">
                    <div style="display:flex; justify-content:space-between;">
                        <span>Senin – Jumat</span><span style="font-weight:600; color:#374151;">07.00 – 15.00</span>
                    </div>
                    <div style="display:flex; justify-content:space-between;">
                        <span>Sabtu</span><span style="font-weight:600; color:#374151;">07.00 – 12.00</span>
                    </div>
                    <div style="display:flex; justify-content:space-between;">
                        <span>Minggu</span><span style="font-weight:600; color:#ef4444;">Tutup</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
