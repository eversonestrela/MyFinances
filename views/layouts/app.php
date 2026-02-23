<?php
use App\Core\Session;
Session::start();

$appUrl = \App\Core\Env::get('APP_URL', 'http://localhost:8000');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'MyFinances' ?></title>
    
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
