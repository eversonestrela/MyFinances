<?php
$title      = 'Nova Dívida — MyFinances';
$seoNoIndex = true;
ob_start();
?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Nova Dívida Variável</h5></div>
                <div class="card-body">
                    <form method="POST" action="/dividas/store">
                        <div class="mb-3"><label for="descricao" class="form-label">Descrição</label><input type="text" class="form-control" id="descricao" name="descricao" required></div>

                        <!-- Categoria -->
                        <div class="mb-3">
                            <label for="categoria_id" class="form-label">
                                Categoria <span class="text-danger">*</span>
                            </label>
                            <?php if (empty($categorias)): ?>
                                <div class="alert alert-warning py-2 mb-0">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Nenhuma categoria cadastrada.
                                    <a href="/categorias" target="_blank">Criar categorias</a>
                                </div>
                                <input type="hidden" name="categoria_id" value="">
                            <?php else: ?>
                                <select class="form-select" id="categoria_id" name="categoria_id" required>
                                    <option value="">Selecione uma categoria...</option>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= $cat->id ?>"><?= htmlspecialchars($cat->nome) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3"><label for="valor" class="form-label">Valor</label><input type="number" class="form-control" id="valor" name="valor" step="0.01" min="0" required></div>
                        <div class="row">
                            <div class="col-6 mb-3"><label for="mes" class="form-label">Mês</label><select class="form-select" id="mes" name="mes" required>
                                <?php for($i=1; $i<=12; $i++): ?><option value="<?=$i?>" <?=$i==(int)date('m')?'selected':''?>><?=str_pad($i,2,'0',STR_PAD_LEFT)?></option><?php endfor; ?>
                            </select></div>
                            <div class="col-6 mb-3"><label for="ano" class="form-label">Ano</label><input type="number" class="form-control" id="ano" name="ano" value="<?=date('Y')?>" required></div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning"><i class="bi bi-check-circle"></i> Salvar</button>
                            <a href="/dividas" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/app.php'; ?>
