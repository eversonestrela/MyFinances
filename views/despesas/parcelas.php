<?php $title = 'Parcelas do Mês - MyFinances'; ob_start(); $meses = ['', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro']; ?>
<div class="container py-4">
    <h3 class="mb-4">Parcelas - <?= $meses[$mes] ?>/<?= $ano ?></h3>
    <div class="card">
        <div class="card-body">
            <?php if (empty($parcelas)): ?>
                <div class="text-center py-5 text-muted"><p>Nenhuma parcela neste mês</p></div>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($parcelas as $parcela): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?= htmlspecialchars($parcela['despesa_descricao']) ?></h6>
                                    <small class="text-muted"><?= $meses[$parcela['mes']] ?>/<?= $parcela['ano'] ?></small>
                                </div>
                                <div class="text-end">
                                    <h6 class="mb-0 text-danger">R$ <?= number_format($parcela['valor'], 2, ',', '.') ?></h6>
                                    <button class="btn btn-sm btn-<?= $parcela['status_pago'] ? 'success' : 'outline-secondary' ?> toggle-pago" 
                                            data-id="<?= $parcela['id'] ?>">
                                        <i class="bi bi-check-circle"></i> <?= $parcela['status_pago'] ? 'Pago' : 'Pendente' ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
document.querySelectorAll('.toggle-pago').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        fetch(`/despesas/parcelas/${id}/toggle`, { method: 'POST' })
            .then(r => r.json())
            .then(data => { if(data.success) location.reload(); });
    });
});
</script>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/app.php'; ?>
