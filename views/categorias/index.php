<?php $title = 'Categorias - MyFinances'; ob_start(); ?>

<?php
// Ícones disponíveis para seleção
$icones = [
    'bi-tag'                => 'Tag',
    'bi-credit-card-fill'   => 'Cartão',
    'bi-basket-fill'        => 'Cesta',
    'bi-car-front-fill'     => 'Carro',
    'bi-heart-pulse-fill'   => 'Saúde',
    'bi-house-fill'         => 'Casa',
    'bi-wifi'               => 'Internet',
    'bi-controller'         => 'Lazer',
    'bi-graph-up-arrow'     => 'Investimento',
    'bi-bank'               => 'Banco',
    'bi-three-dots'         => 'Outros',
    'bi-bag-fill'           => 'Compras',
    'bi-book-fill'          => 'Educação',
    'bi-briefcase-fill'     => 'Trabalho',
    'bi-cup-hot-fill'       => 'Café/Rest.',
    'bi-fuel-pump-fill'     => 'Combustível',
    'bi-lightning-charge-fill' => 'Energia',
    'bi-phone-fill'         => 'Celular',
    'bi-scissors'           => 'Beleza/Higiene',
    'bi-gift-fill'          => 'Presentes',
    'bi-airplane-fill'      => 'Viagem',
    'bi-hospital-fill'      => 'Hospital',
    'bi-receipt'            => 'Contas',
    'bi-piggy-bank-fill'    => 'Poupança',
    'bi-tools'              => 'Manutenção',
];
?>

<div class="container py-4">

    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-0"><i class="bi bi-tags-fill me-2 text-primary"></i>Categorias</h3>
            <small class="text-muted">Organize seus lançamentos financeiros por categoria</small>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCategoria" onclick="abrirModalNova()">
            <i class="bi bi-plus-circle me-1"></i> Nova Categoria
        </button>
    </div>

    <!-- Tabela de categorias -->
    <div class="card">
        <div class="card-body p-0">
            <?php if (empty($categorias)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-tags" style="font-size: 3.5rem; opacity:.3;"></i>
                    <p class="mt-3 mb-1 fw-semibold">Nenhuma categoria cadastrada</p>
                    <p class="small">Crie categorias para organizar suas despesas e dívidas.</p>
                    <button class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#modalCategoria" onclick="abrirModalNova()">
                        <i class="bi bi-plus-circle me-1"></i> Criar primeira categoria
                    </button>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:52px;"></th>
                                <th>Nome</th>
                                <th>Ícone / Cor</th>
                                <th>Status</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categorias as $cat): ?>
                                <tr class="<?= $cat->ativo ? '' : 'table-secondary opacity-75' ?>">
                                    <!-- Prévia -->
                                    <td class="text-center">
                                        <span class="cat-badge-preview" style="background:<?= htmlspecialchars($cat->cor) ?>;">
                                            <i class="bi <?= htmlspecialchars($cat->icone) ?>"></i>
                                        </span>
                                    </td>
                                    <!-- Nome -->
                                    <td class="fw-semibold"><?= htmlspecialchars($cat->nome) ?></td>
                                    <!-- Ícone / Cor -->
                                    <td>
                                        <code class="small text-muted"><?= htmlspecialchars($cat->icone) ?></code>
                                        <span class="ms-2 d-inline-block rounded" style="width:18px;height:18px;background:<?= htmlspecialchars($cat->cor) ?>;vertical-align:middle;border:1px solid rgba(0,0,0,.1);"></span>
                                        <code class="small text-muted ms-1"><?= htmlspecialchars($cat->cor) ?></code>
                                    </td>
                                    <!-- Status -->
                                    <td>
                                        <?php if ($cat->ativo): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">Ativa</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Inativa</span>
                                        <?php endif; ?>
                                    </td>
                                    <!-- Ações -->
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <button
                                                class="btn btn-outline-primary"
                                                title="Editar"
                                                onclick="abrirModalEditar(<?= $cat->id ?>, '<?= htmlspecialchars(addslashes($cat->nome)) ?>', '<?= htmlspecialchars($cat->icone) ?>', '<?= htmlspecialchars($cat->cor) ?>')"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalCategoria"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <a
                                                href="/categorias/<?= $cat->id ?>/toggle"
                                                class="btn <?= $cat->ativo ? 'btn-outline-warning' : 'btn-outline-success' ?>"
                                                title="<?= $cat->ativo ? 'Desativar' : 'Ativar' ?>"
                                            >
                                                <i class="bi <?= $cat->ativo ? 'bi-pause-circle' : 'bi-play-circle' ?>"></i>
                                            </a>
                                            <a
                                                href="/categorias/<?= $cat->id ?>/delete"
                                                class="btn btn-outline-danger"
                                                title="Excluir"
                                                onclick="return confirm('Deseja excluir a categoria \'<?= htmlspecialchars(addslashes($cat->nome)) ?>\'?\nLançamentos vinculados perderão a categoria.')"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
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

<!-- =====================================================
     Modal: Criar / Editar Categoria
     ===================================================== -->
<div class="modal fade" id="modalCategoria" tabindex="-1" aria-labelledby="modalCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalCategoriaLabel">
                    <i class="bi bi-tag-fill me-2 text-primary"></i>
                    <span id="modalTitulo">Nova Categoria</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formCategoria" method="POST" action="/categorias/store">
                <input type="hidden" id="categoriaId" name="_categoria_id" value="">

                <div class="modal-body">

                    <!-- Nome -->
                    <div class="mb-3">
                        <label for="cat_nome" class="form-label fw-semibold">Nome <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control"
                            id="cat_nome"
                            name="nome"
                            placeholder="Ex: Alimentação"
                            maxlength="100"
                            required
                        >
                    </div>

                    <!-- Cor -->
                    <div class="mb-3">
                        <label for="cat_cor" class="form-label fw-semibold">Cor do Badge</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="color" class="form-control form-control-color" id="cat_cor" name="cor" value="#667eea" title="Escolha uma cor">
                            <div class="cat-preview-box">
                                <span id="previewBadge" class="cat-badge-preview" style="background:#667eea;">
                                    <i class="bi bi-tag" id="previewIcon"></i>
                                </span>
                                <span id="previewNome" class="ms-2 fw-semibold small text-muted">Prévia</span>
                            </div>
                        </div>
                    </div>

                    <!-- Ícone -->
                    <div class="mb-1">
                        <label class="form-label fw-semibold">Ícone</label>
                        <div class="icon-grid" id="iconGrid">
                            <?php foreach ($icones as $classe => $label): ?>
                                <button
                                    type="button"
                                    class="icon-btn"
                                    data-icon="<?= $classe ?>"
                                    title="<?= $label ?>"
                                    onclick="selecionarIcone(this)"
                                >
                                    <i class="bi <?= $classe ?>"></i>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" id="cat_icone" name="icone" value="bi-tag">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Salvar
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
/* Badge de prévia */
.cat-badge-preview {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    font-size: 18px;
    color: #fff;
    flex-shrink: 0;
}

/* Grid de ícones */
.icon-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 10px;
    border: 1px solid #dee2e6;
    max-height: 180px;
    overflow-y: auto;
}

.icon-btn {
    width: 40px;
    height: 40px;
    border: 2px solid transparent;
    border-radius: 8px;
    background: #fff;
    cursor: pointer;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .15s;
    color: #495057;
}

.icon-btn:hover {
    border-color: #667eea;
    color: #667eea;
    background: #f0f2ff;
}

.icon-btn.active {
    border-color: #667eea;
    background: #667eea;
    color: #fff;
}

.cat-preview-box {
    display: flex;
    align-items: center;
}
</style>

<script>
const form       = document.getElementById('formCategoria');
const inputId    = document.getElementById('categoriaId');
const inputNome  = document.getElementById('cat_nome');
const inputCor   = document.getElementById('cat_cor');
const inputIcone = document.getElementById('cat_icone');

// Atualiza prévia em tempo real
function atualizarPrevia() {
    const cor   = inputCor.value;
    const icone = inputIcone.value;
    const nome  = inputNome.value || 'Prévia';

    document.getElementById('previewBadge').style.background = cor;
    document.getElementById('previewIcon').className = 'bi ' + icone;
    document.getElementById('previewNome').textContent = nome;
}

inputCor.addEventListener('input',   atualizarPrevia);
inputNome.addEventListener('input',  atualizarPrevia);

function selecionarIcone(btn) {
    document.querySelectorAll('.icon-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    inputIcone.value = btn.dataset.icon;
    atualizarPrevia();
}

function abrirModalNova() {
    document.getElementById('modalTitulo').textContent = 'Nova Categoria';
    form.action   = '/categorias/store';
    inputId.value = '';
    inputNome.value  = '';
    inputCor.value   = '#667eea';
    inputIcone.value = 'bi-tag';
    document.querySelectorAll('.icon-btn').forEach(b => b.classList.remove('active'));
    const first = document.querySelector('.icon-btn[data-icon="bi-tag"]');
    if (first) first.classList.add('active');
    atualizarPrevia();
}

function abrirModalEditar(id, nome, icone, cor) {
    document.getElementById('modalTitulo').textContent = 'Editar Categoria';
    form.action      = '/categorias/' + id + '/update';
    inputId.value    = id;
    inputNome.value  = nome;
    inputCor.value   = cor;
    inputIcone.value = icone;

    document.querySelectorAll('.icon-btn').forEach(b => {
        b.classList.toggle('active', b.dataset.icon === icone);
    });
    atualizarPrevia();
}
</script>

<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/app.php'; ?>
