<?php
$title = 'Login - MyFinances';
ob_start();
?>

<div class="login-container">
    <div class="login-card">
        <div class="text-center mb-4">
            <div class="login-icon">
                <i class="bi bi-wallet2"></i>
            </div>
            <h2 class="mb-3">MyFinances</h2>
            <p class="text-muted">Faça login para continuar</p>
        </div>

        <form method="POST" action="/login">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required autofocus>
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="bi bi-box-arrow-in-right"></i> Entrar
            </button>
        </form>

        <div class="text-center">
            <p class="text-muted">Não tem uma conta? <a href="/register">Cadastre-se</a></p>
        </div>
    </div>
</div>

<style>
.login-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
}

.login-card {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    max-width: 400px;
    width: 100%;
}

.login-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 40px;
    color: white;
}

.login-card h2 {
    color: #333;
    font-weight: 600;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 12px;
    font-weight: 500;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}
</style>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>
