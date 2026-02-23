<?php
$title = 'Receitas - MyFinances';
ob_start();
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Receitas</h3>
        <a href="/receitas/create" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nova Receita
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (empty($receitas)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size: 4rem;"></i>
                    <p class="mt-3">Nenhuma receita cadastrada</p>
                    <a href="/receitas/create" class="btn btn-success">Cadastrar primeira receita</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Descrição</th>
                                <th>Valor</th>
                                <th>Tipo</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $gruposExibidos = [];
                            foreach ($receitas as $receita): 
                                // Se for recorrente e já exibimos o grupo, pular
                                if ($receita->tipo_receita === 'recorrente' && 
                                    $receita->receita_grupo_id && 
                                    in_array($receita->receita_grupo_id, $gruposExibidos)) {
                                    continue;
                                }
                                
                                // Marcar grupo como exibido
                                if ($receita->tipo_receita === 'recorrente' && $receita->receita_grupo_id) {
                                    $gruposExibidos[] = $receita->receita_grupo_id;
                                }
                            ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($receita->descricao) ?>
                                        <?php if ($receita->tipo_receita === 'recorrente'): ?>
                                            <span class="badge bg-info ms-2">
                                                <i class="bi bi-arrow-repeat"></i> Recorrente
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-success fw-bold">R$ <?= number_format($receita->valor, 2, ',', '.') ?></td>
                                    <td>
                                        <?php if ($receita->tipo_receita === 'recorrente'): ?>
                                            <small class="text-muted">
                                                <?= date('m/Y', strtotime($receita->data_recebimento)) ?> até 
                                                <?= date('m/Y', strtotime($receita->data_fim)) ?>
                                            </small>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Única</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($receita->data_recebimento)) ?></td>
                                    <td>
                                        <?php if ($receita->tipo_receita === 'recorrente'): ?>
                                            <a href="/receitas/<?= $receita->id ?>/delete?excluir_grupo=1" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Deseja excluir TODAS as receitas recorrentes deste grupo?')">
                                                <i class="bi bi-trash"></i> Excluir Todas
                                            </a>
                                        <?php else: ?>
                                            <a href="/receitas/<?= $receita->id ?>/edit" class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="/receitas/<?= $receita->id ?>/delete" class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Deseja excluir esta receita?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php endif; ?>
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

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>
