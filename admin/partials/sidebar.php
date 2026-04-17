<?php
$currentPage = basename($_SERVER['PHP_SELF']);
function navLink(string $href, string $label, string $current): string {
    $active = basename($href) === $current ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700';
    return "<a href=\"$href\" class=\"flex items-center px-4 py-2 rounded-lg text-sm $active transition\">$label</a>";
}
?>
<aside class="fixed top-0 left-0 h-full w-64 bg-blue-800 text-white flex flex-col">
    <div class="px-6 py-5 text-lg font-bold border-b border-blue-700">
        <?= htmlspecialchars(getSetting('site_name', 'Humas')) ?> <span class="text-blue-300 text-sm font-normal">Admin</span>
    </div>
    <nav class="flex-1 px-4 py-4 space-y-1">
        <?= navLink(BASE_URL . '/admin/dashboard.php', 'Dashboard', $currentPage) ?>
        <?= navLink(BASE_URL . '/admin/posts.php', 'Artikel', $currentPage) ?>
        <?= navLink(BASE_URL . '/admin/pages.php', 'Halaman', $currentPage) ?>
        <?= navLink(BASE_URL . '/admin/settings.php', 'Pengaturan', $currentPage) ?>
    </nav>
    <div class="px-4 py-4 border-t border-blue-700 text-sm">
        <span class="text-blue-300"><?= htmlspecialchars($_SESSION[ADMIN_SESSION_KEY]['username'] ?? '') ?></span>
        <a href="<?= BASE_URL ?>/admin/logout.php" class="block mt-2 text-blue-200 hover:text-white">Keluar</a>
    </div>
</aside>
