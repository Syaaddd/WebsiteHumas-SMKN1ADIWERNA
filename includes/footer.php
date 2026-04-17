<?php $settings = getSettings(); ?>

<footer style="background:#1f2937; color:#d1d5db; margin-top:5rem;">
    <div style="max-width:1200px; margin:0 auto; padding:3.5rem 1.5rem 2rem; display:grid; grid-template-columns:repeat(3,1fr); gap:3rem;">

        <!-- Brand -->
        <div>
            <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
                <?php if (!empty($settings['logo'])): ?>
                    <img src="<?= UPLOAD_URL . htmlspecialchars($settings['logo']) ?>"
                         style="height:42px;width:42px;border-radius:10px;object-fit:cover;">
                <?php else: ?>
                    <div style="height:42px;width:42px;border-radius:10px;background:linear-gradient(135deg,#f97316,#ea580c);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:1.1rem;">
                        <?= strtoupper(substr($settings['site_name'] ?? 'H', 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div>
                    <div style="font-weight:800;color:#fff;font-size:0.95rem;"><?= htmlspecialchars($settings['site_name'] ?? 'Humas') ?></div>
                    <div style="font-size:0.68rem;color:#9ca3af;">Portal Informasi Resmi</div>
                </div>
            </div>
            <p style="font-size:0.85rem;line-height:1.7;color:#9ca3af;">
                <?= htmlspecialchars($settings['site_description'] ?? '') ?>
            </p>
        </div>

        <!-- Links -->
        <div>
            <h4 style="color:#fff;font-weight:700;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:1rem;">Navigasi</h4>
            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:0.6rem;">
                <?php
                $links = ['/' => '🏠 Beranda', '/?page=about' => '🏫 Tentang Kami', '/?page=blog' => '📰 Berita & Informasi', '/?page=contact' => '📬 Hubungi Kami'];
                foreach ($links as $href => $label): ?>
                <li><a href="<?= BASE_URL . $href ?>" style="color:#9ca3af;text-decoration:none;font-size:0.875rem;transition:color 0.15s;"
                       onmouseover="this.style.color='#f97316'" onmouseout="this.style.color='#9ca3af'"><?= $label ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Kontak -->
        <div>
            <h4 style="color:#fff;font-weight:700;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:1rem;">Kontak</h4>
            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:0.75rem;font-size:0.875rem;color:#9ca3af;">
                <?php if (!empty($settings['site_address'])): ?>
                <li style="display:flex;gap:0.5rem;"><span>📍</span><span><?= nl2br(htmlspecialchars($settings['site_address'])) ?></span></li>
                <?php endif; ?>
                <?php if (!empty($settings['site_phone'])): ?>
                <li>📞 <?= htmlspecialchars($settings['site_phone']) ?></li>
                <?php endif; ?>
                <?php if (!empty($settings['site_email'])): ?>
                <li>✉️ <?= htmlspecialchars($settings['site_email']) ?></li>
                <?php endif; ?>
                <li>🕐 Senin–Jumat: 07.00–15.00 WIB</li>
            </ul>
        </div>
    </div>

    <div style="border-top:1px solid #374151;padding:1.25rem 1.5rem;text-align:center;font-size:0.75rem;color:#6b7280;">
        &copy; <?= date('Y') ?> <strong style="color:#9ca3af;"><?= htmlspecialchars($settings['site_name'] ?? 'Humas') ?></strong>.
        Hak cipta dilindungi undang-undang.
    </div>
</footer>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
