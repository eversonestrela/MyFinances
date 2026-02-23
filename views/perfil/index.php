<?php $title = 'Perfil - MyFinances'; ob_start(); $appUrl = \App\Core\Env::get('APP_URL', 'http://localhost:8000'); ?>
<div class="container py-4">
    <h3 class="mb-4">Meu Perfil</h3>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <?php if ($usuario->foto_perfil): ?>
                        <img src="<?= $appUrl ?>/storage/uploads/profile/<?= $usuario->foto_perfil ?>" 
                             class="rounded-circle mb-3" width="150" height="150" alt="Foto">
                    <?php else: ?>
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 150px; height: 150px; font-size: 60px;">
                            <i class="bi bi-person"></i>
                        </div>
                    <?php endif; ?>
                    <h5><?= htmlspecialchars($usuario->nome) ?></h5>
                    <p class="text-muted"><?= htmlspecialchars($usuario->email) ?></p>
                    <form method="POST" action="/perfil/upload-foto" enctype="multipart/form-data" class="mt-3">
                        <input type="file" class="form-control mb-2" name="foto" accept="image/*" required>
                        <button type="submit" class="btn btn-sm btn-primary">Alterar Foto</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Dados Pessoais</h5></div>
                <div class="card-body">
                    <form method="POST" action="/perfil/update">
                        <div class="mb-3"><label class="form-label">Nome</label><input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($usuario->nome) ?>" required></div>
                        <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="<?= htmlspecialchars($usuario->email) ?>" required></div>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Alterar Senha</h5></div>
                <div class="card-body">
                    <form method="POST" action="/perfil/update-password">
                        <div class="mb-3"><label class="form-label">Senha Atual</label><input type="password" class="form-control" name="senha_atual" required></div>
                        <div class="mb-3"><label class="form-label">Nova Senha</label><input type="password" class="form-control" name="nova_senha" required minlength="6"></div>
                        <div class="mb-3"><label class="form-label">Confirmar Nova Senha</label><input type="password" class="form-control" name="confirmar_senha" required minlength="6"></div>
                        <button type="submit" class="btn btn-warning">Alterar Senha</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/app.php'; ?>
