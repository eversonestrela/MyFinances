<?php
$title = 'Cadastro - MyFinances';
ob_start();
?>

<div class="login-container">
    <div class="login-card">
        <div class="text-center mb-4">
            <div class="login-icon">
                <i class="bi bi-person-plus"></i>
            </div>
            <h2 class="mb-3">Criar Conta</h2>
            <p class="text-muted">Preencha os dados abaixo</p>
        </div>

        <form method="POST" action="/register">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome Completo</label>
                <input type="text" class="form-control" id="nome" name="nome" required autofocus>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required minlength="6">
                <small class="text-muted">Mínimo 6 caracteres</small>
            </div>

            <div class="mb-3">
                <label for="confirmar_senha" class="form-label">Confirmar Senha</label>
                <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" required minlength="6">
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="bi bi-check-circle"></i> Cadastrar
            </button>
        </form>

        <div class="text-center">
            <p class="text-muted">Já tem uma conta? <a href="/login">Faça login</a></p>
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
</style>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>
