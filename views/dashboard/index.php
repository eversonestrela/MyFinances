<?php
$title = 'Dashboard - MyFinances';
ob_start();

$meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
?>

<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>Dashboard</h3>
            <p class="text-muted mb-0"><?= $meses[$dados['mes'] - 1] ?>/<?= $dados['ano'] ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="?mes=<?= $dados['mes'] - 1 ?>&ano=<?= $dados['ano'] ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-chevron-left"></i>
            </a>
            <a href="?mes=<?= $dados['mes'] + 1 ?>&ano=<?= $dados['ano'] ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Saldo</p>
                            <h4 class="mb-0 <?= $dados['saldo'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                R$ <?= number_format($dados['saldo'], 2, ',', '.') ?>
                            </h4>
                        </div>
                        <div class="stat-icon bg-primary">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Receitas</p>
                            <h4 class="mb-0 text-success">
                                R$ <?= number_format($dados['total_receitas'], 2, ',', '.') ?>
                            </h4>
                        </div>
                        <div class="stat-icon bg-success">
                            <i class="bi bi-arrow-up-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Despesas</p>
                            <h4 class="mb-0 text-danger">
                                R$ <?= number_format($dados['total_despesas'], 2, ',', '.') ?>
                            </h4>
                        </div>
                        <div class="stat-icon bg-danger">
                            <i class="bi bi-arrow-down-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Dívidas</p>
                            <h4 class="mb-0 text-warning">
                                R$ <?= number_format($dados['total_dividas'], 2, ',', '.') ?>
                            </h4>
                        </div>
                        <div class="stat-icon bg-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Visão Geral - Últimos 6 Meses</h5>
        </div>
        <div class="card-body">
            <canvas id="financeChart" height="80"></canvas>
        </div>
    </div>

    <!-- Últimas Movimentações -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Últimas Movimentações</h5>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                <?php if (empty($movimentacoes)): ?>
                    <div class="p-4 text-center text-muted">
                        Nenhuma movimentação encontrada
                    </div>
                <?php else: ?>
                    <?php foreach ($movimentacoes as $mov): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?= htmlspecialchars($mov['descricao']) ?></h6>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i> 
                                        <?= date('d/m/Y', strtotime($mov['data'])) ?>
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-<?= $mov['class'] ?>">
                                        <?= strtoupper($mov['tipo']) ?>
                                    </span>
                                    <h6 class="mb-0 text-<?= $mov['class'] ?>">
                                        <?= $mov['tipo'] === 'receita' ? '+' : '-' ?>
                                        R$ <?= number_format($mov['valor'], 2, ',', '.') ?>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Dados do gráfico
const ctx = document.getElementById('financeChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($grafico['labels']) ?>,
        datasets: [
            {
                label: 'Receitas',
                data: <?= json_encode($grafico['receitas']) ?>,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4
            },
            {
                label: 'Despesas',
                data: <?= json_encode($grafico['despesas']) ?>,
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                tension: 0.4
            },
            {
                label: 'Dívidas',
                data: <?= json_encode($grafico['dividas']) ?>,
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'R$ ' + value.toLocaleString('pt-BR');
                    }
                }
            }
        }
    }
});
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>
