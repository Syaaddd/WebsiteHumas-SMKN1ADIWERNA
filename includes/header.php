<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$settings    = getSettings();
$siteName    = $settings['site_name'] ?? 'Humas Sekolah';
$metaDesc    = $metaDescription ?? ($settings['site_description'] ?? '');
$pageTitle   = isset($pageTitle) ? "$pageTitle — $siteName" : $siteName;
$currentPage = $_GET['page'] ?? 'home';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDesc) ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/output.css">
    <style>
        body { font-family: 'Segoe UI', ui-sans-serif, system-ui, sans-serif; }

        .btn-orange {
            background: #f97316;
            color: #fff;
            font-weight: 600;
            padding: 0.625rem 1.5rem;
            border-radius: 0.625rem;
            transition: background 0.15s;
            display: inline-block;
        }
        .btn-orange:hover { background: #ea580c; }

        .btn-green {
            background: #16a34a;
            color: #fff;
            font-weight: 600;
            padding: 0.625rem 1.5rem;
            border-radius: 0.625rem;
            transition: background 0.15s;
            display: inline-block;
        }
        .btn-green:hover { background: #15803d; }

        .btn-outline {
            border: 2px solid #f97316;
            color: #f97316;
            font-weight: 600;
            padding: 0.5625rem 1.5rem;
            border-radius: 0.625rem;
            transition: all 0.15s;
            display: inline-block;
        }
        .btn-outline:hover { background: #f97316; color: #fff; }

        .card-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-lift:hover { transform: translateY(-5px); box-shadow: 0 16px 40px rgba(0,0,0,0.1); }

        .hero-bg {
            background: linear-gradient(135deg, #fff7ed 0%, #fef3c7 50%, #ecfdf5 100%);
        }

        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            background: #fff7ed;
            color: #f97316;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            border: 1px solid #fed7aa;
            margin-bottom: 0.75rem;
        }

        .badge-green {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
        }

        .badge-orange {
            background: #fff7ed;
            color: #f97316;
            border: 1px solid #fed7aa;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            ring: none;
            border-color: #f97316;
            box-shadow: 0 0 0 3px rgba(249,115,22,0.12);
        }
    </style>
</head>
<body style="background:#f9fafb; color:#1f2937;">

<!-- Top Info Bar -->
<div style="background:#1f2937; color:#9ca3af; font-size:0.75rem; padding:0.375rem 0;">
    <div style="max-width:1200px; margin:0 auto; padding:0 1.5rem; display:flex; justify-content:space-between; align-items:center;">
        <span>
            <?php if (!empty($settings['site_address'])): ?>
                📍 <?= htmlspecialchars($settings['site_address']) ?>
            <?php else: ?>
                Portal Informasi & Komunikasi Resmi
            <?php endif; ?>
        </span>
        <span style="display:flex; gap:1.25rem;">
            <?php if (!empty($settings['site_phone'])): ?>
                <span>📞 <?= htmlspecialchars($settings['site_phone']) ?></span>
            <?php endif; ?>
            <?php if (!empty($settings['site_email'])): ?>
                <span>✉️ <?= htmlspecialchars($settings['site_email']) ?></span>
            <?php endif; ?>
        </span>
    </div>
</div>

<!-- Main Navbar -->
<nav style="background:#fff; border-bottom:1px solid #e5e7eb; position:sticky; top:0; z-index:50; box-shadow:0 1px 4px rgba(0,0,0,0.06);"
     x-data="{ open: false }">
    <div style="max-width:1200px; margin:0 auto; padding:0 1.5rem; display:flex; align-items:center; justify-content:space-between; height:68px;">

        <!-- Brand -->
        <a href="<?= BASE_URL ?>/" style="display:flex; align-items:center; gap:0.75rem; text-decoration:none;">
            <?php if (!empty($settings['logo'])): ?>
                <img src="<?= UPLOAD_URL . htmlspecialchars($settings['logo']) ?>"
                     style="height:44px; width:44px; border-radius:50%; object-fit:cover; border:2px solid #fed7aa;">
            <?php else: ?>
                <div style="height:44px; width:44px; border-radius:12px; background:linear-gradient(135deg,#f97316,#ea580c); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:900; font-size:1.1rem;">
                    <?= strtoupper(substr($siteName, 0, 1)) ?>
                </div>
            <?php endif; ?>
            <div>
                <div style="font-weight:800; color:#1f2937; font-size:0.95rem; line-height:1.2;"><?= htmlspecialchars($siteName) ?></div>
                <div style="font-size:0.68rem; color:#9ca3af; line-height:1;">Portal Informasi Resmi</div>
            </div>
        </a>

        <!-- Desktop Nav -->
        <?php
        $navItems = ['home' => 'Beranda', 'about' => 'Tentang', 'blog' => 'Berita', 'contact' => 'Kontak'];
        ?>
        <ul style="display:flex; align-items:center; gap:0.25rem; list-style:none; margin:0; padding:0;" class="hidden md:flex">
            <?php foreach ($navItems as $key => $label):
                $isActive = $currentPage === $key;
                $href = $key === 'home' ? BASE_URL . '/' : BASE_URL . '/?page=' . $key;
            ?>
            <li>
                <a href="<?= $href ?>" style="
                    padding:0.45rem 1rem;
                    border-radius:0.5rem;
                    font-size:0.875rem;
                    font-weight:600;
                    text-decoration:none;
                    transition:all 0.15s;
                    <?= $isActive
                        ? 'background:#f97316; color:#fff;'
                        : 'color:#374151;' ?>
                "
                onmouseover="if(!this.classList.contains('active')) { this.style.background='#fff7ed'; this.style.color='#f97316'; }"
                onmouseout="if(!<?= $isActive ? 'false' : 'true' ?>) { this.style.background=''; this.style.color='#374151'; }"
                <?= $isActive ? 'class="active"' : '' ?>>
                    <?= $label ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>

        <!-- Mobile Toggle -->
        <button @click="open = !open"
                style="display:none; padding:0.5rem; border-radius:0.5rem; background:none; border:none; cursor:pointer; color:#374151;"
                class="md:hidden-none" id="mob-toggle">
            <svg style="width:22px;height:22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                <path x-show="open"  stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <button @click="open = !open"
                class="md:hidden"
                style="padding:0.5rem; border-radius:0.5rem; background:none; border:1px solid #e5e7eb; cursor:pointer; color:#374151;">
            <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                <path x-show="open"  stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Mobile Dropdown -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         style="border-top:1px solid #f3f4f6; background:#fff; padding:0.75rem 1.5rem 1rem;"
         class="md:hidden">
        <?php foreach ($navItems as $key => $label):
            $href = $key === 'home' ? BASE_URL . '/' : BASE_URL . '/?page=' . $key;
        ?>
        <a href="<?= $href ?>" style="display:block; padding:0.6rem 0.75rem; border-radius:0.5rem; font-size:0.875rem; font-weight:600; color:#374151; text-decoration:none; margin-bottom:0.25rem;">
            <?= $label ?>
        </a>
        <?php endforeach; ?>
    </div>
</nav>
