<?php
$title = 'Login - MyFinances';
ob_start();
?>

<div class="auth-page">

    <!-- Lado esquerdo: apresentação do sistema -->
    <div class="auth-hero">
        <div class="auth-hero-inner">

            <!-- Logo -->
            <div class="auth-logo">
                <div class="auth-logo-icon">
                    <i class="bi bi-wallet2"></i>
                </div>
                <span class="auth-logo-text">MyFinances</span>
            </div>

            <!-- Badge gratuito -->
            <div class="auth-badge">
                <i class="bi bi-gift-fill"></i> 100% Gratuito para sempre
            </div>

            <!-- Headline -->
            <h1 class="auth-headline">
                Controle financeiro pessoal <span class="auth-headline-highlight">simples e gratuito</span>
            </h1>
            <p class="auth-subheadline">
                Gerencie despesas, dívidas e ganhos em um só lugar. Acompanhe seus gastos e organize sua vida financeira de forma inteligente.
            </p>

            <!-- Cards de funcionalidades -->
            <div class="auth-features">
                <div class="auth-feature-card">
                    <div class="auth-feature-icon bg-success-soft">
                        <i class="bi bi-arrow-up-circle-fill text-success"></i>
                    </div>
                    <div>
                        <strong>Receitas</strong>
                        <p>Registre todos os seus ganhos mensais</p>
                    </div>
                </div>
                <div class="auth-feature-card">
                    <div class="auth-feature-icon bg-danger-soft">
                        <i class="bi bi-arrow-down-circle-fill text-danger"></i>
                    </div>
                    <div>
                        <strong>Despesas</strong>
                        <p>Controle gastos fixos, variáveis e parcelados</p>
                    </div>
                </div>
                <div class="auth-feature-card">
                    <div class="auth-feature-icon bg-warning-soft">
                        <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                    </div>
                    <div>
                        <strong>Dívidas</strong>
                        <p>Acompanhe e quite suas dívidas com facilidade</p>
                    </div>
                </div>
                <div class="auth-feature-card">
                    <div class="auth-feature-icon bg-info-soft">
                        <i class="bi bi-bar-chart-fill text-info"></i>
                    </div>
                    <div>
                        <strong>Dashboard</strong>
                        <p>Visão completa da sua saúde financeira</p>
                    </div>
                </div>
            </div>

            <!-- Rodapé do hero -->
            <div class="auth-hero-footer">
                <div class="auth-trust-badge">
                    <i class="bi bi-shield-lock-fill"></i>
                    <span>Seus dados são privados e seguros</span>
                </div>
                <div class="auth-trust-badge">
                    <i class="bi bi-device-ssd-fill"></i>
                    <span>Acesse de qualquer dispositivo</span>
                </div>
            </div>

        </div>
    </div>

    <!-- Lado direito: formulário de login -->
    <div class="auth-form-side">
        <div class="auth-form-wrapper">

            <!-- Logo mobile (visível apenas em telas pequenas) -->
            <div class="auth-logo-mobile d-flex d-lg-none align-items-center gap-2 mb-4">
                <div class="auth-logo-icon-sm">
                    <i class="bi bi-wallet2"></i>
                </div>
                <span class="fw-bold fs-5" style="color: #667eea;">MyFinances</span>
            </div>

            <h2 class="auth-form-title">Bem-vindo de volta!</h2>
            <p class="auth-form-subtitle">Acesse sua conta para continuar</p>

            <form method="POST" action="/login" novalidate>

                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">
                        <i class="bi bi-envelope me-1 text-muted"></i> E-mail
                    </label>
                    <input
                        type="email"
                        class="form-control auth-input"
                        id="email"
                        name="email"
                        placeholder="seu@email.com"
                        required
                        autofocus
                    >
                </div>

                <div class="mb-4">
                    <label for="senha" class="form-label fw-semibold">
                        <i class="bi bi-lock me-1 text-muted"></i> Senha
                    </label>
                    <div class="input-group">
                        <input
                            type="password"
                            class="form-control auth-input"
                            id="senha"
                            name="senha"
                            placeholder="••••••••"
                            required
                        >
                        <button
                            class="btn btn-outline-secondary auth-toggle-password"
                            type="button"
                            id="toggleSenha"
                            tabindex="-1"
                            title="Mostrar/ocultar senha"
                        >
                            <i class="bi bi-eye" id="toggleSenhaIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary auth-btn-submit w-100 mb-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Entrar na minha conta
                </button>

                <div class="auth-divider">
                    <span>ou</span>
                </div>

                <a href="/register" class="btn auth-btn-register w-100">
                    <i class="bi bi-person-plus me-2"></i> Criar conta gratuita
                </a>

            </form>

            <!-- Mensagem de confiança -->
            <div class="auth-security-note">
                <i class="bi bi-lock-fill"></i>
                Conexão segura &bull; Dados criptografados &bull; Sem cartão de crédito
            </div>

        </div>
    </div>

</div>

<style>
/* ================================================
   Auth Page - Layout Split Screen
   ================================================ */

.auth-page {
    min-height: 100vh;
    display: flex;
    flex-direction: row;
}

/* ---- Hero (lado esquerdo) ---- */
.auth-hero {
    flex: 1 1 55%;
    background: linear-gradient(145deg, #667eea 0%, #764ba2 60%, #4e3bbd 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 40px;
    position: relative;
    overflow: hidden;
}

.auth-hero::before {
    content: '';
    position: absolute;
    top: -100px;
    right: -100px;
    width: 350px;
    height: 350px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
    pointer-events: none;
}

.auth-hero::after {
    content: '';
    position: absolute;
    bottom: -120px;
    left: -80px;
    width: 400px;
    height: 400px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
    pointer-events: none;
}

.auth-hero-inner {
    max-width: 480px;
    width: 100%;
    position: relative;
    z-index: 1;
}

/* Logo */
.auth-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 32px;
}

.auth-logo-icon {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.2);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    color: white;
    backdrop-filter: blur(4px);
}

.auth-logo-text {
    font-size: 1.6rem;
    font-weight: 700;
    color: white;
    letter-spacing: -0.5px;
}

/* Badge gratuito */
.auth-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.18);
    color: white;
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.82rem;
    font-weight: 600;
    letter-spacing: 0.3px;
    margin-bottom: 24px;
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,0.25);
}

/* Headline */
.auth-headline {
    font-size: 2rem;
    font-weight: 800;
    color: white;
    line-height: 1.25;
    margin-bottom: 16px;
    letter-spacing: -0.5px;
}

.auth-headline-highlight {
    position: relative;
    color: #ffd86b;
}

.auth-subheadline {
    font-size: 1rem;
    color: rgba(255,255,255,0.82);
    line-height: 1.65;
    margin-bottom: 36px;
}

/* Feature Cards */
.auth-features {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 36px;
}

.auth-feature-card {
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: 14px;
    padding: 14px 16px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    backdrop-filter: blur(6px);
    transition: background 0.2s;
}

.auth-feature-card:hover {
    background: rgba(255,255,255,0.18);
}

.auth-feature-card strong {
    display: block;
    color: white;
    font-size: 0.875rem;
    margin-bottom: 2px;
}

.auth-feature-card p {
    color: rgba(255,255,255,0.72);
    font-size: 0.78rem;
    margin: 0;
    line-height: 1.4;
}

.auth-feature-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.bg-success-soft { background: rgba(40,167,69,0.2); }
.bg-danger-soft  { background: rgba(220,53,69,0.2); }
.bg-warning-soft { background: rgba(255,193,7,0.2); }
.bg-info-soft    { background: rgba(23,162,184,0.2); }

/* Hero Footer */
.auth-hero-footer {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.auth-trust-badge {
    display: flex;
    align-items: center;
    gap: 7px;
    color: rgba(255,255,255,0.75);
    font-size: 0.82rem;
}

.auth-trust-badge i {
    font-size: 1rem;
    color: rgba(255,255,255,0.9);
}

/* ---- Form Side (lado direito) ---- */
.auth-form-side {
    flex: 0 0 420px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 40px;
}

.auth-form-wrapper {
    width: 100%;
    max-width: 360px;
}

.auth-logo-icon-sm {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: white;
}

.auth-form-title {
    font-size: 1.7rem;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 6px;
    letter-spacing: -0.3px;
}

.auth-form-subtitle {
    color: #6c757d;
    font-size: 0.95rem;
    margin-bottom: 32px;
}

.auth-input {
    border-radius: 10px !important;
    border: 1.5px solid #e0e3ec;
    padding: 13px 16px;
    font-size: 0.95rem;
    transition: border-color 0.25s, box-shadow 0.25s;
}

.auth-input:focus {
    border-color: #667eea !important;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.18) !important;
}

.auth-toggle-password {
    border: 1.5px solid #e0e3ec;
    border-left: none;
    border-radius: 0 10px 10px 0 !important;
    background: #f8f9fa;
    color: #6c757d;
    padding: 0 14px;
}

.auth-toggle-password:hover {
    background: #e9ecef;
    color: #495057;
}

.auth-btn-submit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 10px;
    padding: 13px;
    font-size: 1rem;
    font-weight: 600;
    letter-spacing: 0.2px;
    transition: all 0.25s;
    box-shadow: 0 4px 14px rgba(102,126,234,0.35);
}

.auth-btn-submit:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(102,126,234,0.45);
}

.auth-divider {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #adb5bd;
    font-size: 0.85rem;
    margin: 18px 0;
}

.auth-divider::before,
.auth-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e9ecef;
}

.auth-btn-register {
    background: transparent;
    border: 1.5px solid #667eea;
    color: #667eea;
    border-radius: 10px;
    padding: 12px;
    font-size: 0.95rem;
    font-weight: 600;
    transition: all 0.25s;
}

.auth-btn-register:hover {
    background: #667eea;
    color: white;
    transform: translateY(-1px);
}

.auth-security-note {
    margin-top: 28px;
    text-align: center;
    font-size: 0.78rem;
    color: #adb5bd;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.auth-security-note i {
    font-size: 0.85rem;
    color: #28a745;
}

/* ================================================
   Responsivo
   ================================================ */

/* Tablet: colapsa o hero para mostrar apenas cabeçalho */
@media (max-width: 991.98px) {
    .auth-page {
        flex-direction: column;
    }

    .auth-hero {
        flex: none;
        padding: 32px 24px 28px;
    }

    .auth-hero-inner {
        max-width: 100%;
    }

    .auth-headline {
        font-size: 1.5rem;
    }

    .auth-features {
        grid-template-columns: 1fr 1fr;
    }

    .auth-form-side {
        flex: none;
        padding: 32px 24px;
    }
}

/* Mobile: esconde features cards para não pesar a tela */
@media (max-width: 575.98px) {
    .auth-features {
        display: none;
    }

    .auth-hero-footer {
        display: none;
    }

    .auth-subheadline {
        display: none;
    }

    .auth-hero {
        padding: 24px 20px;
    }

    .auth-badge {
        margin-bottom: 14px;
    }

    .auth-headline {
        font-size: 1.35rem;
        margin-bottom: 0;
    }

    .auth-form-side {
        padding: 28px 20px;
    }

    .auth-form-title {
        font-size: 1.4rem;
    }
}
</style>

<script>
// Toggle mostrar/ocultar senha
document.getElementById('toggleSenha').addEventListener('click', function () {
    const input = document.getElementById('senha');
    const icon  = document.getElementById('toggleSenhaIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
});
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>
