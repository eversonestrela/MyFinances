<?php
/**
 * Landing Page Pública — MyFinances
 * Otimizada para SEO | Palavras-chave: controle financeiro pessoal gratuito
 */

use App\Core\Env;
use App\Core\Session;

Session::start();

$appUrl = Env::get('APP_URL', 'http://localhost:8000');

// Se já está logado, vai pro dashboard
if (Session::has('usuario_id')) {
    header('Location: /dashboard');
    exit;
}

$seoTitle       = 'MyFinances — Sistema de Controle Financeiro Pessoal Gratuito';
$seoDescription = 'Organize suas finanças pessoais de graça. Controle receitas, despesas, dívidas e visualize relatórios completos. O melhor sistema de controle financeiro pessoal online.';
$seoKeywords    = 'controle financeiro pessoal gratuito, sistema de controle financeiro, gestão financeira pessoal grátis, controle de gastos online, controle de despesas, organizar finanças pessoais, planejamento financeiro, controle de dívidas, finanças pessoais app, aplicativo financeiro gratuito';
$seoImage       = $appUrl . '/assets/img/og-image.svg';
$seoUrl         = $appUrl . '/';
$seoNoIndex     = false;

// Schema.org: SoftwareApplication
$schemaJsonLd = json_encode([
    '@context'            => 'https://schema.org',
    '@type'               => 'SoftwareApplication',
    'name'                => 'MyFinances',
    'url'                 => $appUrl,
    'description'         => $seoDescription,
    'applicationCategory' => 'FinanceApplication',
    'operatingSystem'     => 'Web',
    'offers'              => [
        '@type'    => 'Offer',
        'price'    => '0',
        'priceCurrency' => 'BRL',
    ],
    'featureList' => [
        'Controle de receitas e despesas',
        'Gestão de dívidas e parcelas',
        'Relatórios financeiros em PDF e Excel',
        'Categorias personalizáveis',
        'Dashboard com gráficos',
        '100% gratuito e seguro',
    ],
    'inLanguage' => 'pt-BR',
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// FAQ Schema
$faqSchema = json_encode([
    '@context'  => 'https://schema.org',
    '@type'     => 'FAQPage',
    'mainEntity' => [
        [
            '@type'          => 'Question',
            'name'           => 'O MyFinances é realmente gratuito?',
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Sim, o MyFinances é 100% gratuito. Não há planos pagos nem cobranças ocultas.'],
        ],
        [
            '@type'          => 'Question',
            'name'           => 'Como organizar minhas finanças pessoais?',
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Com o MyFinances você cadastra suas receitas e despesas mensais, define categorias de gastos e acompanha tudo em um dashboard visual. Em minutos você tem total controle financeiro.'],
        ],
        [
            '@type'          => 'Question',
            'name'           => 'Posso exportar meus dados financeiros?',
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Sim! O MyFinances permite exportar relatórios completos em PDF e Excel com gráficos e tabelas detalhadas por período.'],
        ],
        [
            '@type'          => 'Question',
            'name'           => 'O sistema é seguro?',
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Sim. Seus dados são protegidos por autenticação segura com senha criptografada. Cada usuário acessa apenas suas próprias informações financeiras.'],
        ],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
<!DOCTYPE html>
<html lang="pt-BR" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($seoTitle) ?></title>
    <meta name="description"        content="<?= htmlspecialchars($seoDescription) ?>">
    <meta name="keywords"           content="<?= htmlspecialchars($seoKeywords) ?>">
    <meta name="author"             content="MyFinances">
    <meta name="robots"             content="index, follow">
    <link rel="canonical"           href="<?= htmlspecialchars($appUrl . '/') ?>">

    <!-- Open Graph -->
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="<?= htmlspecialchars($appUrl . '/') ?>">
    <meta property="og:title"       content="<?= htmlspecialchars($seoTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($seoDescription) ?>">
    <meta property="og:image"       content="<?= htmlspecialchars($seoImage) ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height"content="630">
    <meta property="og:site_name"   content="MyFinances">
    <meta property="og:locale"      content="pt_BR">

    <!-- Twitter Cards -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?= htmlspecialchars($seoTitle) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($seoDescription) ?>">
    <meta name="twitter:image"       content="<?= htmlspecialchars($seoImage) ?>">

    <!-- Favicon -->
    <link rel="icon"             type="image/svg+xml" href="<?= $appUrl ?>/assets/img/favicon.svg">
    <link rel="apple-touch-icon"                      href="<?= $appUrl ?>/assets/img/apple-touch-icon.png" sizes="180x180">
    <link rel="manifest"                              href="<?= $appUrl ?>/manifest.json">
    <meta name="theme-color"     content="#667eea">

    <!-- Schema.org: SoftwareApplication -->
    <script type="application/ld+json"><?= $schemaJsonLd ?></script>
    <!-- Schema.org: FAQ -->
    <script type="application/ld+json"><?= $faqSchema ?></script>

    <!-- Performance -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= $appUrl ?>/assets/css/style.css" rel="stylesheet">

    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
        }
        html { scroll-behavior: smooth; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; }

        /* ── HERO ── */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='10'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .hero-badge {
            background: rgba(255,255,255,.2);
            border: 1px solid rgba(255,255,255,.3);
            border-radius: 50px;
            padding: .4rem 1.2rem;
            font-size: .85rem;
            color: #fff;
            display: inline-block;
            margin-bottom: 1.5rem;
        }
        .hero h1 {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
        }
        .hero p.lead { color: rgba(255,255,255,.85); font-size: 1.2rem; }
        .btn-hero-primary {
            background: #fff;
            color: var(--primary);
            font-weight: 700;
            border-radius: 12px;
            padding: .85rem 2.2rem;
            font-size: 1.05rem;
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,.2);
            transition: all .25s;
        }
        .btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,.25); color: var(--secondary); }
        .btn-hero-outline {
            background: transparent;
            color: #fff;
            border: 2px solid rgba(255,255,255,.6);
            font-weight: 600;
            border-radius: 12px;
            padding: .85rem 2.2rem;
            font-size: 1.05rem;
            transition: all .25s;
        }
        .btn-hero-outline:hover { background: rgba(255,255,255,.15); border-color: #fff; color: #fff; }

        /* ── STATS ── */
        .stats-bar { background: #fff; border-radius: 20px; box-shadow: 0 4px 30px rgba(0,0,0,.1); margin-top: -40px; position: relative; z-index: 10; padding: 2rem; }
        .stat-item { text-align: center; }
        .stat-item .stat-num { font-size: 2rem; font-weight: 800; background: linear-gradient(135deg,var(--primary),var(--secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .stat-item .stat-label { color: #6c757d; font-size: .9rem; margin-top: .2rem; }

        /* ── FEATURES ── */
        .feature-card {
            background: #fff;
            border-radius: 20px;
            padding: 2rem;
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,.07);
            transition: transform .25s, box-shadow .25s;
            height: 100%;
        }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(102,126,234,.2); }
        .feature-icon {
            width: 60px; height: 60px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; margin-bottom: 1.2rem;
        }
        .feature-card h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: .5rem; color: #1a1a2e; }
        .feature-card p { color: #6c757d; font-size: .95rem; line-height: 1.6; }

        /* ── HOW IT WORKS ── */
        .step-num {
            width: 48px; height: 48px; border-radius: 50%;
            background: linear-gradient(135deg,var(--primary),var(--secondary));
            color: #fff; font-weight: 800; font-size: 1.2rem;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
        }

        /* ── FAQ ── */
        .faq-item { border-bottom: 1px solid #f0f0f0; padding: 1.25rem 0; }
        .faq-item:last-child { border-bottom: none; }
        .faq-q { font-weight: 700; color: #1a1a2e; cursor: pointer; display: flex; justify-content: space-between; align-items: center; }
        .faq-a { color: #6c757d; margin-top: .75rem; line-height: 1.7; display: none; }
        .faq-a.open { display: block; }

        /* ── CTA FINAL ── */
        .cta-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 24px;
            padding: 4rem 2rem;
            text-align: center;
            color: #fff;
        }

        /* ── NAVBAR ── */
        .lp-nav { background: transparent; position: absolute; top: 0; left: 0; right: 0; z-index: 100; padding: 1.5rem 2rem; }
        .lp-nav .navbar-brand { font-weight: 800; font-size: 1.4rem; color: #fff; }
        .lp-nav .nav-link { color: rgba(255,255,255,.85); font-weight: 500; }
        .lp-nav .nav-link:hover { color: #fff; }
        .lp-nav .btn-nav { background: rgba(255,255,255,.2); color: #fff; border: 1px solid rgba(255,255,255,.4); border-radius: 8px; padding: .45rem 1.3rem; font-weight: 600; }
        .lp-nav .btn-nav:hover { background: rgba(255,255,255,.35); color: #fff; }

        /* ── SECTIONS ── */
        section { padding: 5rem 0; }
        .section-label { text-transform: uppercase; letter-spacing: 2px; font-size: .75rem; font-weight: 700; color: var(--primary); margin-bottom: .5rem; }
        .section-title { font-size: clamp(1.6rem, 3vw, 2.4rem); font-weight: 800; color: #1a1a2e; margin-bottom: 1rem; }
        .section-sub { color: #6c757d; font-size: 1.05rem; max-width: 560px; margin: 0 auto 3rem; }
    </style>
</head>
<body>

<!-- ══════════════════════════════════════════
     NAVBAR
══════════════════════════════════════════ -->
<nav class="lp-nav d-flex align-items-center justify-content-between">
    <a href="/" class="navbar-brand">
        <i class="bi bi-wallet2 me-2"></i>MyFinances
    </a>
    <div class="d-flex gap-2 align-items-center">
        <a href="/login"    class="btn-nav btn me-1 d-none d-md-inline">Entrar</a>
        <a href="/register" class="btn btn-light fw-bold" style="border-radius:8px;padding:.45rem 1.3rem;color:var(--primary)">Criar conta grátis</a>
    </div>
</nav>

<!-- ══════════════════════════════════════════
     HERO — H1 principal da landing page
══════════════════════════════════════════ -->
<section class="hero" aria-label="Apresentação do MyFinances">
    <div class="container position-relative" style="z-index:2">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="hero-badge">
                    <i class="bi bi-shield-check me-2"></i>100% Gratuito · Sem cartão de crédito
                </div>
                <h1>O melhor sistema de <span style="text-decoration:underline;text-decoration-color:rgba(255,255,255,.5)">controle financeiro pessoal</span> gratuito</h1>
                <p class="lead mt-3 mb-4">
                    Gerencie receitas, despesas e dívidas em um só lugar. Visualize relatórios completos, exporte em PDF/Excel e tenha total controle das suas finanças pessoais — de graça, para sempre.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="/register" class="btn-hero-primary btn">
                        <i class="bi bi-rocket-takeoff me-2"></i>Começar gratuitamente
                    </a>
                    <a href="#funcionalidades" class="btn-hero-outline btn">
                        <i class="bi bi-play-circle me-2"></i>Ver funcionalidades
                    </a>
                </div>
                <div class="mt-4 d-flex flex-wrap gap-3">
                    <small style="color:rgba(255,255,255,.75)"><i class="bi bi-check-circle-fill me-1"></i>Sem anúncios</small>
                    <small style="color:rgba(255,255,255,.75)"><i class="bi bi-check-circle-fill me-1"></i>Dados protegidos</small>
                    <small style="color:rgba(255,255,255,.75)"><i class="bi bi-check-circle-fill me-1"></i>Acesso pelo celular</small>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block text-center">
                <!-- Mockup visual das funcionalidades -->
                <div style="background:rgba(255,255,255,.12);border-radius:24px;padding:2rem;backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.2)">
                    <div class="row g-3">
                        <?php
                        $features_hero = [
                            ['bi-arrow-up-circle-fill', '#27ae60', 'Receitas', 'R$ 5.200,00'],
                            ['bi-arrow-down-circle-fill', '#e74c3c', 'Despesas', 'R$ 2.850,00'],
                            ['bi-credit-card-fill', '#e67e22', 'Dívidas', 'R$ 890,00'],
                            ['bi-wallet2', '#667eea', 'Saldo', 'R$ 1.460,00'],
                        ];
                        foreach ($features_hero as $f): ?>
                        <div class="col-6">
                            <div style="background:rgba(255,255,255,.1);border-radius:16px;padding:1.2rem;text-align:left">
                                <i class="bi <?= $f[0] ?>" style="font-size:1.5rem;color:<?= $f[1] ?>"></i>
                                <p style="color:rgba(255,255,255,.7);font-size:.75rem;margin:.5rem 0 .2rem"><?= $f[2] ?></p>
                                <p style="color:#fff;font-weight:700;font-size:1rem;margin:0"><?= $f[3] ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div style="margin-top:1rem;background:rgba(255,255,255,.1);border-radius:12px;padding:1rem">
                        <p style="color:rgba(255,255,255,.7);font-size:.8rem;margin-bottom:.5rem">Saldo do mês</p>
                        <div style="background:rgba(255,255,255,.15);border-radius:8px;height:10px;overflow:hidden">
                            <div style="background:#27ae60;width:62%;height:100%;border-radius:8px"></div>
                        </div>
                        <p style="color:rgba(255,255,255,.6);font-size:.75rem;margin-top:.4rem">62% do orçamento disponível</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     STATS
══════════════════════════════════════════ -->
<div class="container">
    <div class="stats-bar">
        <div class="row g-3 justify-content-center">
            <div class="col-6 col-md-3 stat-item">
                <div class="stat-num">100%</div>
                <div class="stat-label">Gratuito para sempre</div>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <div class="stat-num">5+</div>
                <div class="stat-label">Módulos financeiros</div>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <div class="stat-num">PDF</div>
                <div class="stat-label">Relatórios exportáveis</div>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <div class="stat-num">24/7</div>
                <div class="stat-label">Acesso online</div>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════
     FUNCIONALIDADES — H2 principal
══════════════════════════════════════════ -->
<section id="funcionalidades" aria-labelledby="features-heading">
    <div class="container">
        <div class="text-center">
            <p class="section-label">Funcionalidades</p>
            <h2 class="section-title" id="features-heading">Tudo que você precisa para organizar suas finanças pessoais</h2>
            <p class="section-sub">Uma plataforma completa de gestão financeira pessoal, simples de usar e sem custo algum.</p>
        </div>
        <div class="row g-4">
            <?php
            $funcionalidades = [
                ['bi-arrow-up-circle-fill', '#e8f8f0', '#27ae60', 'Controle de Receitas',
                    'Registre todas as suas fontes de renda — salário, freelances, aluguéis. Acompanhe receitas recorrentes e pontuais com histórico completo.'],
                ['bi-arrow-down-circle-fill', '#fde8e8', '#e74c3c', 'Controle de Despesas',
                    'Lance despesas com parcelas automáticas. Controle quais parcelas já foram pagas e quais ainda estão pendentes. Nunca mais perca uma conta.'],
                ['bi-credit-card-fill', '#fef3e2', '#e67e22', 'Gestão de Dívidas',
                    'Registre e acompanhe todas as suas dívidas. Organize por categoria e período para ter visibilidade total dos seus compromissos financeiros.'],
                ['bi-bar-chart-line-fill', '#ede9fe', '#667eea', 'Relatórios Financeiros',
                    'Gere relatórios detalhados do mês com gráficos interativos. Exporte em PDF ou Excel para análise aprofundada das suas finanças.'],
                ['bi-tags-fill', '#fdf2f8', '#e91e63', 'Categorias Personalizadas',
                    'Crie categorias de gastos com ícones e cores. Visualize exatamente onde está gastando mais com gráficos de pizza por categoria.'],
                ['bi-house-door-fill', '#e8f0fe', '#3b82f6', 'Dashboard Completo',
                    'Painel visual com resumo de receitas, despesas e dívidas do mês. Gráficos de evolução anual para acompanhar sua saúde financeira.'],
            ];
            foreach ($funcionalidades as $f): ?>
            <div class="col-md-6 col-lg-4">
                <article class="feature-card">
                    <div class="feature-icon" style="background:<?= $f[1] ?>">
                        <i class="bi <?= $f[0] ?>" style="color:<?= $f[2] ?>"></i>
                    </div>
                    <h3><?= $f[3] ?></h3>
                    <p><?= $f[4] ?></p>
                </article>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     COMO FUNCIONA — H2
══════════════════════════════════════════ -->
<section id="como-funciona" style="background:#f8f9fa" aria-labelledby="how-heading">
    <div class="container">
        <div class="text-center">
            <p class="section-label">Como funciona</p>
            <h2 class="section-title" id="how-heading">Comece a organizar suas finanças em 3 passos simples</h2>
            <p class="section-sub">Não precisa de conhecimento financeiro avançado. O MyFinances é intuitivo para qualquer pessoa.</p>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="step-num">1</div>
                <h3 style="font-weight:700;color:#1a1a2e">Crie sua conta grátis</h3>
                <p style="color:#6c757d;line-height:1.7">Cadastre-se em menos de 1 minuto. Basta nome, e-mail e senha. Sem cartão de crédito, sem burocracia.</p>
            </div>
            <div class="col-md-4">
                <div class="step-num">2</div>
                <h3 style="font-weight:700;color:#1a1a2e">Registre seus lançamentos</h3>
                <p style="color:#6c757d;line-height:1.7">Lance suas receitas, despesas e dívidas. O sistema cria as parcelas automaticamente e organiza por categoria.</p>
            </div>
            <div class="col-md-4">
                <div class="step-num">3</div>
                <h3 style="font-weight:700;color:#1a1a2e">Acompanhe seus relatórios</h3>
                <p style="color:#6c757d;line-height:1.7">Visualize seu dashboard financeiro, gere relatórios em PDF/Excel e tome decisões mais inteligentes com seu dinheiro.</p>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     CONTEÚDO SEO — Sobre controle financeiro
══════════════════════════════════════════ -->
<section id="sobre-controle-financeiro" aria-labelledby="about-finance-heading">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <p class="section-label">Educação Financeira</p>
                <h2 class="section-title" id="about-finance-heading">Por que controlar suas finanças pessoais é essencial?</h2>
                <p style="color:#6c757d;line-height:1.8">
                    O controle financeiro pessoal é a base para uma vida financeira saudável. Sem saber para onde vai o seu dinheiro, é impossível economizar, investir ou se preparar para imprevistos.
                </p>
                <p style="color:#6c757d;line-height:1.8">
                    Estudos mostram que pessoas que registram seus gastos mensalmente conseguem reduzir despesas desnecessárias em até 20% já no primeiro mês. A <strong>organização financeira</strong> não é apenas para quem ganha muito — é para quem quer viver melhor com o que tem.
                </p>
                <p style="color:#6c757d;line-height:1.8">
                    O <strong>MyFinances</strong> foi criado para tornar esse processo simples, visual e acessível a todos. Com ele, você tem um verdadeiro <strong>sistema de gestão financeira pessoal</strong> na palma da mão — e de graça.
                </p>
                <a href="/register" class="btn btn-primary mt-3" style="border-radius:12px;padding:.75rem 2rem;font-weight:700;background:linear-gradient(135deg,#667eea,#764ba2);border:none">
                    <i class="bi bi-rocket-takeoff me-2"></i>Quero organizar minhas finanças
                </a>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <?php
                    $benefits = [
                        ['bi-graph-down-arrow', '#e74c3c', 'Reduza gastos desnecessários', 'Identifique onde está gastando mais e corte despesas que não agregam valor à sua vida.'],
                        ['bi-piggy-bank-fill',  '#27ae60', 'Construa uma reserva de emergência', 'Com o controle das despesas, fica fácil separar uma parte da renda para uma poupança.'],
                        ['bi-calendar-check',   '#667eea', 'Nunca mais perca pagamentos', 'Acompanhe parcelas e vencimentos com alertas visuais de pago/pendente.'],
                        ['bi-trophy-fill',      '#f39c12', 'Alcance seus objetivos financeiros', 'Visualize sua evolução mês a mês e mantenha-se motivado a melhorar.'],
                    ];
                    foreach ($benefits as $b): ?>
                    <div class="col-6">
                        <div style="background:#f8f9fa;border-radius:16px;padding:1.25rem">
                            <i class="bi <?= $b[0] ?>" style="font-size:1.4rem;color:<?= $b[1] ?>"></i>
                            <h4 style="font-size:.95rem;font-weight:700;margin:.6rem 0 .3rem;color:#1a1a2e"><?= $b[2] ?></h4>
                            <p style="font-size:.85rem;color:#6c757d;margin:0;line-height:1.5"><?= $b[3] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     FAQ — H2
══════════════════════════════════════════ -->
<section id="faq" style="background:#f8f9fa" aria-labelledby="faq-heading">
    <div class="container">
        <div class="text-center mb-5">
            <p class="section-label">Dúvidas</p>
            <h2 class="section-title" id="faq-heading">Perguntas frequentes sobre o MyFinances</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php
                $faqs = [
                    ['O MyFinances é realmente gratuito?',
                     'Sim, o MyFinances é 100% gratuito. Não há planos pagos, assinaturas ou cobranças ocultas. Todas as funcionalidades — controle de receitas, despesas, dívidas, relatórios PDF/Excel e categorias — estão disponíveis sem custo.'],
                    ['Como organizar minhas finanças pessoais com o MyFinances?',
                     'É simples: crie sua conta, cadastre suas fontes de receita e comece a registrar seus gastos por categoria. O dashboard mostrará automaticamente seu saldo, evolução mensal e quais despesas estão pagas ou pendentes.'],
                    ['Posso exportar meus dados financeiros?',
                     'Sim! O MyFinances permite exportar relatórios completos em PDF e Excel (xlsx) com gráficos, tabelas detalhadas por período, agrupamento por categoria e resumo financeiro do mês.'],
                    ['O sistema funciona no celular?',
                     'Sim. O MyFinances é totalmente responsivo e funciona perfeitamente em smartphones e tablets. O layout se adapta automaticamente à tela, incluindo um menu inferior para facilitar a navegação mobile.'],
                    ['Meus dados financeiros estão seguros?',
                     'Sim. Cada usuário acessa exclusivamente seus próprios dados, protegidos por senha criptografada (bcrypt). O sistema segue boas práticas de segurança (OWASP) para proteger suas informações financeiras.'],
                    ['Preciso instalar algum aplicativo?',
                     'Não. O MyFinances é um sistema web — basta abrir o navegador em qualquer dispositivo e acessar. Não precisa instalar nada no celular ou no computador.'],
                ];
                foreach ($faqs as $i => $faq): ?>
                <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <div class="faq-q" onclick="toggleFaq(<?= $i ?>)" aria-expanded="false">
                        <h3 itemprop="name" style="font-size:1rem;margin:0"><?= htmlspecialchars($faq[0]) ?></h3>
                        <i class="bi bi-plus-circle" id="faq-icon-<?= $i ?>" style="font-size:1.2rem;color:var(--primary);flex-shrink:0"></i>
                    </div>
                    <div class="faq-a" id="faq-a-<?= $i ?>" itemprop="acceptedAnswer" itemscope itemtype="https://schema.org/Answer">
                        <p itemprop="text" style="margin:0"><?= htmlspecialchars($faq[1]) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     CTA FINAL
══════════════════════════════════════════ -->
<section>
    <div class="container">
        <div class="cta-section">
            <i class="bi bi-wallet2" style="font-size:3rem;opacity:.85"></i>
            <h2 style="font-size:clamp(1.6rem,4vw,2.4rem);font-weight:800;margin:1rem 0 .75rem">Pronto para assumir o controle das suas finanças?</h2>
            <p style="font-size:1.1rem;opacity:.85;max-width:520px;margin:0 auto 2rem;line-height:1.7">
                Crie sua conta gratuita agora e comece hoje mesmo a organizar suas receitas, despesas e dívidas em um só lugar.
            </p>
            <div class="d-flex justify-content-center flex-wrap gap-3">
                <a href="/register" class="btn btn-light fw-bold" style="border-radius:12px;padding:.9rem 2.5rem;font-size:1.05rem;color:var(--primary)">
                    <i class="bi bi-rocket-takeoff me-2"></i>Criar conta grátis agora
                </a>
                <a href="/login" class="btn-hero-outline btn">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Já tenho uma conta
                </a>
            </div>
            <p style="opacity:.6;font-size:.85rem;margin-top:1.5rem">
                <i class="bi bi-shield-check me-1"></i>Sem cartão · Sem limite · 100% gratuito
            </p>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     FOOTER
══════════════════════════════════════════ -->
<footer style="background:#1a1a2e;color:rgba(255,255,255,.6);padding:2.5rem 0;text-align:center">
    <div class="container">
        <p style="font-weight:700;color:#fff;font-size:1.1rem;margin-bottom:.5rem">
            <i class="bi bi-wallet2 me-2" style="color:#667eea"></i>MyFinances
        </p>
        <p style="font-size:.9rem;margin-bottom:1rem">Sistema de controle financeiro pessoal gratuito</p>
        <div class="d-flex justify-content-center gap-4 flex-wrap" style="font-size:.85rem">
            <a href="/login"    style="color:rgba(255,255,255,.5);text-decoration:none">Entrar</a>
            <a href="/register" style="color:rgba(255,255,255,.5);text-decoration:none">Cadastrar</a>
            <a href="/sitemap.xml" style="color:rgba(255,255,255,.5);text-decoration:none">Sitemap</a>
        </div>
        <p style="font-size:.78rem;margin-top:1.5rem;opacity:.4">
            &copy; <?= date('Y') ?> MyFinances. Controle financeiro pessoal gratuito.
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleFaq(i) {
    const a    = document.getElementById('faq-a-' + i);
    const icon = document.getElementById('faq-icon-' + i);
    const q    = a.previousElementSibling;
    const isOpen = a.classList.contains('open');
    // Fecha todos
    document.querySelectorAll('.faq-a').forEach(el => el.classList.remove('open'));
    document.querySelectorAll('[id^="faq-icon-"]').forEach(el => {
        el.className = 'bi bi-plus-circle';
        el.style.color = 'var(--primary)';
    });
    if (!isOpen) {
        a.classList.add('open');
        icon.className = 'bi bi-dash-circle';
        icon.style.color = '#764ba2';
        q.setAttribute('aria-expanded', 'true');
    }
}
</script>
</body>
</html>
