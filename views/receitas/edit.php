<?php
$title = 'Editar Receita - MyFinances';
ob_start();
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Editar Receita</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/receitas/<?= $receita->id ?>/update">
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" 
                                   value="<?= htmlspecialchars($receita->descricao) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor</label>
                            <input type="number" class="form-control" id="valor" name="valor" step="0.01" min="0" 
                                   value="<?= $receita->valor ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="data_recebimento" class="form-label">Data de Recebimento</label>
                            <input type="date" class="form-control" id="data_recebimento" name="data_recebimento" 
                                   value="<?= $receita->data_recebimento ?>" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Salvar
                            </button>
                            <a href="/receitas" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>
