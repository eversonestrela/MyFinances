<?php $title = 'Despesas - MyFinances'; ob_start(); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Despesas Parceladas</h3>
        <a href="/despesas/create" class="btn btn-danger"><i class="bi bi-plus-circle"></i> Nova Despesa</a>
    </div>
    <div class="card">
        <div class="card-body">
            <?php if (empty($despesas)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size: 4rem;"></i>
                    <p class="mt-3">Nenhuma despesa cadastrada</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th>Descrição</th><th>Valor Total</th><th>Parcelas</th><th>Período</th><th>Ações</th></tr></thead>
                        <tbody>
                            <?php foreach ($despesas as $despesa): ?>
                                <tr>
                                    <td><?= htmlspecialchars($despesa->descricao) ?></td>
                                    <td class="text-danger fw-bold">R$ <?= number_format($despesa->valor_total, 2, ',', '.') ?></td>
                                    <td><?= $despesa->quantidade_parcelas ?>x de R$ <?= number_format($despesa->valor_parcela, 2, ',', '.') ?></td>
                                    <td><?= date('d/m/Y', strtotime($despesa->data_inicio)) ?> - <?= date('d/m/Y', strtotime($despesa->data_fim)) ?></td>
                                    <td>
                                        <a href="/despesas/<?= $despesa->id ?>/delete" class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Deseja excluir esta despesa?')"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="mt-3 text-center">
        <a href="/despesas/parcelas" class="btn btn-outline-primary">Ver Parcelas do Mês</a>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/app.php'; ?>
