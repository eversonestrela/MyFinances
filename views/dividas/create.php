<?php $title = 'Nova Dívida - MyFinances'; ob_start(); ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Nova Dívida Variável</h5></div>
                <div class="card-body">
                    <form method="POST" action="/dividas/store">
                        <div class="mb-3"><label for="descricao" class="form-label">Descrição</label><input type="text" class="form-control" id="descricao" name="descricao" required></div>
                        <div class="mb-3"><label for="valor" class="form-label">Valor</label><input type="number" class="form-control" id="valor" name="valor" step="0.01" min="0" required></div>
                        <div class="row">
                            <div class="col-6 mb-3"><label for="mes" class="form-label">Mês</label><select class="form-select" id="mes" name="mes" required>
                                <?php for($i=1; $i<=12; $i++): ?><option value="<?=$i?>"><?=str_pad($i,2,'0',STR_PAD_LEFT)?></option><?php endfor; ?>
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
