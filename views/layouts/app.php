<?php
use App\Core\Session;
Session::start();

$appUrl  = \App\Core\Env::get('APP_URL', 'http://localhost:8000');
$appName = \App\Core\Env::get('APP_NAME', 'MyFinances');

// ── SEO defaults (views podem sobrescrever antes de incluir este layout) ──
$seoTitle       = isset($seoTitle)       ? $seoTitle       : ($title ?? 'MyFinances — Controle Financeiro Pessoal Gratuito');
$seoDescription = isset($seoDescription) ? $seoDescription : 'MyFinances é um sistema gratuito de controle financeiro pessoal. Gerencie receitas, despesas, dívidas e relatórios financeiros de forma simples e intuitiva.';
$seoKeywords    = isset($seoKeywords)    ? $seoKeywords    : 'controle financeiro pessoal, gestão financeira, controle de gastos, controle de despesas, organização financeira, planejamento financeiro, sistema financeiro gratuito, gestão de dívidas, controle de contas pessoais, finanças pessoais';
$seoImage       = isset($seoImage)       ? $seoImage       : $appUrl . '/assets/img/og-image.png';
$seoUrl         = isset($seoUrl)         ? $seoUrl         : $appUrl . ($_SERVER['REQUEST_URI'] ?? '/');
$seoType        = isset($seoType)        ? $seoType        : 'website';
$seoNoIndex     = isset($seoNoIndex)     ? $seoNoIndex     : false; // true em páginas privadas

// Sanitizar para uso em atributos HTML
$seoTitle       = htmlspecialchars($seoTitle,       ENT_QUOTES, 'UTF-8');
$seoDescription = htmlspecialchars($seoDescription, ENT_QUOTES, 'UTF-8');
$seoKeywords    = htmlspecialchars($seoKeywords,    ENT_QUOTES, 'UTF-8');
$seoUrl         = htmlspecialchars($seoUrl,         ENT_QUOTES, 'UTF-8');
$seoImage       = htmlspecialchars($seoImage,       ENT_QUOTES, 'UTF-8');

// Schema.org JSON-LD (pode ser sobrescrito por cada view)
$schemaJsonLd   = isset($schemaJsonLd) ? $schemaJsonLd : null;

// GA4 measurement ID (configure no .env)
$gaId = \App\Core\Env::get('GA_MEASUREMENT_ID', '');
?>
<!DOCTYPE html>
<html lang="pt-BR" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ═══════════════════════════════════════════
         SEO PRIMÁRIO
    ═══════════════════════════════════════════ -->
    <title><?= $seoTitle ?></title>
    <meta name="description"        content="<?= $seoDescription ?>">
    <meta name="keywords"           content="<?= $seoKeywords ?>">
    <meta name="author"             content="MyFinances">
    <meta name="robots"             content="<?= $seoNoIndex ? 'noindex, nofollow' : 'index, follow' ?>">
    <meta name="googlebot"          content="<?= $seoNoIndex ? 'noindex, nofollow' : 'index, follow' ?>">
    <link rel="canonical"           href="<?= $seoUrl ?>">

    <!-- ═══════════════════════════════════════════
         OPEN GRAPH (Facebook / WhatsApp / LinkedIn)
    ═══════════════════════════════════════════ -->
    <meta property="og:type"        content="<?= htmlspecialchars($seoType, ENT_QUOTES) ?>">
    <meta property="og:url"         content="<?= $seoUrl ?>">
    <meta property="og:title"       content="<?= $seoTitle ?>">
    <meta property="og:description" content="<?= $seoDescription ?>">
    <meta property="og:image"       content="<?= $seoImage ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height"content="630">
    <meta property="og:site_name"   content="MyFinances">
    <meta property="og:locale"      content="pt_BR">

    <!-- ═══════════════════════════════════════════
         TWITTER CARDS
    ═══════════════════════════════════════════ -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?= $seoTitle ?>">
    <meta name="twitter:description" content="<?= $seoDescription ?>">
    <meta name="twitter:image"       content="<?= $seoImage ?>">

    <!-- ═══════════════════════════════════════════
         FAVICON + APP ICONS
    ═══════════════════════════════════════════ -->
    <link rel="icon"             type="image/svg+xml"  href="<?= $appUrl ?>/assets/img/favicon.svg">
    <link rel="icon"             type="image/png"      href="<?= $appUrl ?>/assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon"                       href="<?= $appUrl ?>/assets/img/apple-touch-icon.png" sizes="180x180">
    <link rel="manifest"                               href="<?= $appUrl ?>/manifest.json">
    <meta name="theme-color"     content="#667eea">
    <meta name="msapplication-TileColor" content="#667eea">

    <!-- ═══════════════════════════════════════════
         PERFORMANCE / PRECONNECT
    ═══════════════════════════════════════════ -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <!-- ═══════════════════════════════════════════
         SCHEMA.ORG JSON-LD
    ═══════════════════════════════════════════ -->
    <?php if ($schemaJsonLd): ?>
    <script type="application/ld+json"><?= $schemaJsonLd ?></script>
    <?php endif; ?>

    <!-- ═══════════════════════════════════════════
         GOOGLE ANALYTICS 4
    ═══════════════════════════════════════════ -->
    <?php if ($gaId): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($gaId, ENT_QUOTES) ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?= htmlspecialchars($gaId, ENT_QUOTES) ?>', { anonymize_ip: true });
    </script>
    <?php endif; ?>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= $appUrl ?>/assets/css/style.css" rel="stylesheet">

    <?= $extraHead ?? '' ?>
</head>
<body>
    <?php if (Session::has('usuario_id')): ?>
        <!-- Navbar Desktop -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary d-none d-lg-block">
            <div class="container-fluid">
                <a class="navbar-brand" href="/dashboard">
                    <i class="bi bi-wallet2"></i> MyFinances
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard">
                                <i class="bi bi-house-door"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/receitas">
                                <i class="bi bi-arrow-up-circle"></i> Receitas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/despesas">
                                <i class="bi bi-arrow-down-circle"></i> Despesas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/dividas">
                                <i class="bi bi-exclamation-triangle"></i> Dívidas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/categorias">
                                <i class="bi bi-tags"></i> Categorias
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/relatorios">
                                <i class="bi bi-bar-chart-line"></i> Relatórios
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <?php if (Session::get('usuario_foto')): ?>
                                    <img src="<?= $appUrl ?>/storage/uploads/profile/<?= Session::get('usuario_foto') ?>" 
                                         class="rounded-circle" width="30" height="30" alt="Foto">
                                <?php else: ?>
                                    <i class="bi bi-person-circle"></i>
                                <?php endif; ?>
                                <?= Session::get('usuario_nome') ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/perfil"><i class="bi bi-person"></i> Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/logout"><i class="bi bi-box-arrow-right"></i> Sair</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <?php if (Session::hasFlash('success')): ?>
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= Session::getFlash('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if (Session::hasFlash('error')): ?>
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= Session::getFlash('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </main>

    <?php if (Session::has('usuario_id')): ?>
        <!-- Menu Inferior Mobile -->
        <nav class="mobile-bottom-nav d-lg-none">
            <a href="/dashboard" class="mobile-nav-item">
                <i class="bi bi-house-door"></i>
                <span>Home</span>
            </a>
            <a href="/receitas" class="mobile-nav-item">
                <i class="bi bi-arrow-up-circle"></i>
                <span>Receitas</span>
            </a>
            <a href="/despesas" class="mobile-nav-item">
                <i class="bi bi-arrow-down-circle"></i>
                <span>Despesas</span>
            </a>
            <a href="/dividas" class="mobile-nav-item">
                <i class="bi bi-exclamation-triangle"></i>
                <span>Dívidas</span>
            </a>
            <a href="/categorias" class="mobile-nav-item">
                <i class="bi bi-tags"></i>
                <span>Categorias</span>
            </a>
            <a href="/relatorios" class="mobile-nav-item">
                <i class="bi bi-bar-chart-line"></i>
                <span>Relatórios</span>
            </a>
            <a href="/perfil" class="mobile-nav-item">
                <i class="bi bi-person"></i>
                <span>Perfil</span>
            </a>
        </nav>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= $appUrl ?>/assets/js/app.js"></script>
    
    <?= $extraScripts ?? '' ?>
</body>
</html>
