<?php $title = 'Dívidas - MyFinances'; ob_start(); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Dívidas Variáveis</h3>
        <a href="/dividas/create" class="btn btn-warning"><i class="bi bi-plus-circle"></i> Nova Dívida</a>
    </div>
    <div class="card">
        <div class="card-body">
            <?php if (empty($dividas)): ?>
                <div class="text-center py-5 text-muted"><i class="bi bi-inbox" style="font-size: 4rem;"></i><p class="mt-3">Nenhuma dívida cadastrada</p></div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th>Descrição</th><th>Categoria</th><th>Valor</th><th>Período</th><th>Ações</th></tr></thead>
                        <tbody>
                            <?php foreach ($dividas as $divida): ?>
                                <tr>
                                    <td><?= htmlspecialchars($divida->descricao) ?></td>
                                    <td>
                                        <?php if ($divida->categoria_nome): ?>
                                            <span class="badge rounded-pill" style="background:#6c757d20;color:#495057;border:1px solid #dee2e6;">
                                                <?= htmlspecialchars($divida->categoria_nome) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-warning fw-bold">R$ <?= number_format($divida->valor, 2, ',', '.') ?></td>
                                    <td><?= str_pad($divida->mes, 2, '0', STR_PAD_LEFT) ?>/<?= $divida->ano ?></td>
                                    <td>
                                        <a href="/dividas/<?= $divida->id ?>/edit" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                        <a href="/dividas/<?= $divida->id ?>/delete" class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Deseja excluir?')"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/app.php'; ?>
