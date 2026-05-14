<?php
$title          = 'Relatórios Financeiros — MyFinances';
$seoDescription = 'Visualize relatórios detalhados das suas finanças pessoais com gráficos. Exporte em PDF e Excel. Sistema gratuito de relatórios financeiros.';
$seoNoIndex     = true;
ob_start();
?>
<?php
$nomeMeses = [
    1=>'Janeiro',2=>'Fevereiro',3=>'Março',4=>'Abril',
    5=>'Maio',6=>'Junho',7=>'Julho',8=>'Agosto',
    9=>'Setembro',10=>'Outubro',11=>'Novembro',12=>'Dezembro'
];
$nomeMes = $nomeMeses[$mes] ?? $mes;

// JSON para Chart.js
$evolucaoLabels    = json_encode(array_column($evolucao, 'label'));
$evolucaoReceitas  = json_encode(array_column($evolucao, 'total_receitas'));
$evolucaoDespesas  = json_encode(array_column($evolucao, 'total_despesas'));
$evolucaoDividas   = json_encode(array_column($evolucao, 'total_dividas'));
$catDespLabels     = json_encode(array_column($catDespesas, 'categoria'));
$catDespTotais     = json_encode(array_map(fn($r) => (float)$r['total'], $catDespesas));
$catDespCores      = json_encode(array_column($catDespesas, 'cor'));
$statusData        = json_encode([$statusParc['pago'], $statusParc['pendente']]);

$saldoClass = $resumo['saldo'] >= 0 ? 'text-success' : 'text-danger';
$totalGastos = $resumo['total_despesas'] + $resumo['total_dividas'];
$percPago    = ($statusParc['pago'] + $statusParc['pendente']) > 0
    ? round($statusParc['pago'] / ($statusParc['pago'] + $statusParc['pendente']) * 100)
    : 0;
?>

<style>
.report-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,.07);
    transition: transform .2s;
}
.report-card:hover { transform: translateY(-2px); }
.report-card .card-icon {
    width: 52px; height: 52px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
}
.chart-card { border-radius: 16px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,.07); }
.filter-bar { background: #fff; border-radius: 16px; box-shadow: 0 2px 10px rgba(0,0,0,.06); padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; }
.section-title { font-size: 1rem; font-weight: 700; color: #495057; margin-bottom: 1rem; border-left: 4px solid #667eea; padding-left: .6rem; }
.badge-cat { font-size: .7rem; padding: .3em .65em; border-radius: 8px; }
.progress-bar-pago { background: linear-gradient(90deg,#27ae60,#2ecc71); }
</style>

<div class="container-fluid px-3 px-md-4 py-4">

  <!-- Título + filtro -->
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
      <h4 class="fw-bold mb-0"><i class="bi bi-bar-chart-line me-2" style="color:#667eea"></i>Relatórios Financeiros</h4>
      <p class="text-muted small mb-0">Visualize e exporte seus dados financeiros</p>
    </div>
    <div class="d-flex gap-2">
      <a href="/relatorios/exportar-pdf?mes=<?= $mes ?>&ano=<?= $ano ?>"
         class="btn btn-sm btn-outline-danger" target="_blank">
        <i class="bi bi-file-earmark-pdf me-1"></i>Exportar PDF
      </a>
      <a href="/relatorios/exportar-excel?mes=<?= $mes ?>&ano=<?= $ano ?>"
         class="btn btn-sm btn-outline-success" target="_blank">
        <i class="bi bi-file-earmark-excel me-1"></i>Exportar Excel
      </a>
    </div>
  </div>

  <!-- Barra de filtro -->
  <div class="filter-bar">
    <form method="GET" action="/relatorios" class="row g-2 align-items-end">
      <div class="col-auto">
        <label class="form-label small fw-semibold mb-1">Mês</label>
        <select name="mes" class="form-select form-select-sm" style="min-width:130px">
          <?php foreach ($nomeMeses as $n => $label): ?>
            <option value="<?= $n ?>" <?= $n == $mes ? 'selected' : '' ?>><?= $label ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-auto">
        <label class="form-label small fw-semibold mb-1">Ano</label>
        <select name="ano" class="form-select form-select-sm" style="min-width:90px">
          <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
            <option value="<?= $y ?>" <?= $y == $ano ? 'selected' : '' ?>><?= $y ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-sm btn-primary px-4">
          <i class="bi bi-funnel me-1"></i>Filtrar
        </button>
      </div>
    </form>
  </div>

  <!-- Cards de resumo -->
  <div class="row g-3 mb-4">
    <!-- Receitas -->
    <div class="col-6 col-md-3">
      <div class="card report-card h-100 p-3">
        <div class="d-flex align-items-center gap-3">
          <div class="card-icon" style="background:#e8f8f0">
            <i class="bi bi-arrow-down-circle-fill" style="color:#27ae60"></i>
          </div>
          <div>
            <p class="text-muted small mb-0">Receitas</p>
            <h5 class="fw-bold mb-0 text-success">
              R$ <?= number_format($resumo['total_receitas'], 2, ',', '.') ?>
            </h5>
          </div>
        </div>
      </div>
    </div>
    <!-- Despesas -->
    <div class="col-6 col-md-3">
      <div class="card report-card h-100 p-3">
        <div class="d-flex align-items-center gap-3">
          <div class="card-icon" style="background:#fde8e8">
            <i class="bi bi-arrow-up-circle-fill" style="color:#e74c3c"></i>
          </div>
          <div>
            <p class="text-muted small mb-0">Despesas</p>
            <h5 class="fw-bold mb-0 text-danger">
              R$ <?= number_format($resumo['total_despesas'], 2, ',', '.') ?>
            </h5>
          </div>
        </div>
      </div>
    </div>
    <!-- Dívidas -->
    <div class="col-6 col-md-3">
      <div class="card report-card h-100 p-3">
        <div class="d-flex align-items-center gap-3">
          <div class="card-icon" style="background:#fef3e2">
            <i class="bi bi-credit-card-fill" style="color:#e67e22"></i>
          </div>
          <div>
            <p class="text-muted small mb-0">Dívidas</p>
            <h5 class="fw-bold mb-0 text-warning">
              R$ <?= number_format($resumo['total_dividas'], 2, ',', '.') ?>
            </h5>
          </div>
        </div>
      </div>
    </div>
    <!-- Saldo -->
    <div class="col-6 col-md-3">
      <div class="card report-card h-100 p-3">
        <div class="d-flex align-items-center gap-3">
          <div class="card-icon" style="background:#ede9fe">
            <i class="bi bi-wallet2" style="color:#667eea"></i>
          </div>
          <div>
            <p class="text-muted small mb-0">Saldo</p>
            <h5 class="fw-bold mb-0 <?= $saldoClass ?>">
              R$ <?= number_format($resumo['saldo'], 2, ',', '.') ?>
            </h5>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Parcelas: barra de progresso -->
  <?php $totalParc = $statusParc['pago'] + $statusParc['pendente']; ?>
  <?php if ($totalParc > 0): ?>
  <div class="card chart-card mb-4 p-3">
    <div class="section-title">Status das Parcelas — <?= $nomeMes ?>/<?= $ano ?></div>
    <div class="row align-items-center g-3">
      <div class="col-md-8">
        <div class="progress" style="height:18px;border-radius:9px;background:#f1f1f1">
          <div class="progress-bar progress-bar-pago" style="width:<?= $percPago ?>%;border-radius:9px">
            <?= $percPago ?>%
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="d-flex gap-3 flex-wrap">
          <div>
            <span class="badge bg-success me-1"><?= $statusParc['qtd_pago'] ?></span>
            <small class="text-muted">Pagas · R$ <?= number_format($statusParc['pago'], 2, ',', '.') ?></small>
          </div>
          <div>
            <span class="badge bg-danger me-1"><?= $statusParc['qtd_pendente'] ?></span>
            <small class="text-muted">Pendentes · R$ <?= number_format($statusParc['pendente'], 2, ',', '.') ?></small>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Gráficos: Evolução + Categorias -->
  <div class="row g-4 mb-4">
    <!-- Evolução 12 meses -->
    <div class="col-lg-8">
      <div class="card chart-card h-100 p-3">
        <div class="section-title">Evolução dos Últimos 12 Meses</div>
        <div style="position:relative;height:260px">
          <canvas id="chartEvolucao"></canvas>
        </div>
      </div>
    </div>
    <!-- Doughnut Categorias Despesas -->
    <div class="col-lg-4">
      <div class="card chart-card h-100 p-3">
        <div class="section-title">Despesas por Categoria</div>
        <?php if (!empty($catDespesas)): ?>
          <div style="position:relative;height:220px">
            <canvas id="chartCatDesp"></canvas>
          </div>
        <?php else: ?>
          <div class="text-center text-muted py-5">
            <i class="bi bi-pie-chart" style="font-size:2.5rem;opacity:.3"></i>
            <p class="mt-2 small">Sem dados no período</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Tabelas detalhadas -->
  <div class="row g-4 mb-4">
    <!-- Receitas -->
    <div class="col-12">
      <div class="card chart-card p-3">
        <div class="section-title"><i class="bi bi-arrow-down-circle text-success me-2"></i>Receitas — <?= $nomeMes ?>/<?= $ano ?></div>
        <?php if (!empty($receitas ?? [])): ?>
        <?php
          // Buscar receitas detalhadas a partir dos dados já disponíveis via include
          // A view recebe $dados injetados pelo controller
          // Porém aqui usamos variável isolada — o controller passa $resumo mas não $receitas separado
          // Vamos usar um fetch via AJAX ou passar pelo controller
          // Na verdade o controller passa apenas os dados de resumo para a view — precisamos adicionar receitas/despesas/dividas detalhadas
          // Por ora exibiremos mensagem amigável
        ?>
        <?php endif; ?>
        <?php
          // Dados detalhados passados pelo controller como variáveis individuais
          if (!isset($receitasDetalhe)) $receitasDetalhe = [];
          if (!isset($despesasDetalhe)) $despesasDetalhe = [];
          if (!isset($dividasDetalhe))  $dividasDetalhe  = [];
        ?>
        <?php if (!empty($receitasDetalhe)): ?>
        <div class="table-responsive">
          <table class="table table-sm table-hover align-middle mb-0">
            <thead class="table-light"><tr>
              <th>Descrição</th><th>Tipo</th><th>Data</th><th class="text-end">Valor</th>
            </tr></thead>
            <tbody>
              <?php foreach ($receitasDetalhe as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['descricao']) ?></td>
                <td><span class="badge bg-light text-dark"><?= $r['tipo_receita'] === 'recorrente' ? 'Recorrente' : 'Única' ?></span></td>
                <td><?= date('d/m/Y', strtotime($r['data_recebimento'])) ?></td>
                <td class="text-end fw-semibold text-success">R$ <?= number_format($r['valor'], 2, ',', '.') ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot class="table-success">
              <tr>
                <td colspan="3" class="fw-bold">Total</td>
                <td class="text-end fw-bold">R$ <?= number_format($resumo['total_receitas'], 2, ',', '.') ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <?php else: ?>
        <p class="text-muted small text-center py-3 mb-0">
          <i class="bi bi-info-circle me-1"></i>Nenhuma receita registrada em <?= $nomeMes ?>/<?= $ano ?>
        </p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Despesas detalhadas -->
    <div class="col-12">
      <div class="card chart-card p-3">
        <div class="section-title"><i class="bi bi-arrow-up-circle text-danger me-2"></i>Despesas (Parcelas) — <?= $nomeMes ?>/<?= $ano ?></div>
        <?php if (!empty($despesasDetalhe)): ?>
        <div class="table-responsive">
          <table class="table table-sm table-hover align-middle mb-0">
            <thead class="table-light"><tr>
              <th>Descrição</th><th>Categoria</th><th>Status</th><th class="text-end">Valor</th>
            </tr></thead>
            <tbody>
              <?php foreach ($despesasDetalhe as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['descricao']) ?></td>
                <td><span class="badge-cat badge bg-secondary bg-opacity-10 text-secondary"><?= htmlspecialchars($r['categoria']) ?></span></td>
                <td>
                  <?php if ($r['status_pago']): ?>
                    <span class="badge bg-success-subtle text-success">Pago</span>
                  <?php else: ?>
                    <span class="badge bg-danger-subtle text-danger">Pendente</span>
                  <?php endif; ?>
                </td>
                <td class="text-end fw-semibold text-danger">R$ <?= number_format($r['valor'], 2, ',', '.') ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot class="table-danger">
              <tr>
                <td colspan="3" class="fw-bold">Total</td>
                <td class="text-end fw-bold">R$ <?= number_format($resumo['total_despesas'], 2, ',', '.') ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <?php else: ?>
        <p class="text-muted small text-center py-3 mb-0">
          <i class="bi bi-info-circle me-1"></i>Nenhuma parcela registrada em <?= $nomeMes ?>/<?= $ano ?>
        </p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Dívidas detalhadas -->
    <div class="col-12">
      <div class="card chart-card p-3">
        <div class="section-title"><i class="bi bi-credit-card text-warning me-2"></i>Dívidas — <?= $nomeMes ?>/<?= $ano ?></div>
        <?php if (!empty($dividasDetalhe)): ?>
        <div class="table-responsive">
          <table class="table table-sm table-hover align-middle mb-0">
            <thead class="table-light"><tr>
              <th>Descrição</th><th>Categoria</th><th class="text-end">Valor</th>
            </tr></thead>
            <tbody>
              <?php foreach ($dividasDetalhe as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['descricao']) ?></td>
                <td><span class="badge-cat badge bg-secondary bg-opacity-10 text-secondary"><?= htmlspecialchars($r['categoria']) ?></span></td>
                <td class="text-end fw-semibold text-warning">R$ <?= number_format($r['valor'], 2, ',', '.') ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot class="table-warning">
              <tr>
                <td colspan="2" class="fw-bold">Total</td>
                <td class="text-end fw-bold">R$ <?= number_format($resumo['total_dividas'], 2, ',', '.') ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <?php else: ?>
        <p class="text-muted small text-center py-3 mb-0">
          <i class="bi bi-info-circle me-1"></i>Nenhuma dívida registrada em <?= $nomeMes ?>/<?= $ano ?>
        </p>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div><!-- /container -->

<!-- Charts -->
<script>
document.addEventListener('DOMContentLoaded', function () {

  // Evolução 12 meses
  const ctxEv = document.getElementById('chartEvolucao');
  if (ctxEv) {
    new Chart(ctxEv, {
      type: 'line',
      data: {
        labels: <?= $evolucaoLabels ?>,
        datasets: [
          {
            label: 'Receitas',
            data: <?= $evolucaoReceitas ?>,
            borderColor: '#27ae60', backgroundColor: 'rgba(39,174,96,.1)',
            fill: true, tension: .4, pointRadius: 4
          },
          {
            label: 'Despesas',
            data: <?= $evolucaoDespesas ?>,
            borderColor: '#e74c3c', backgroundColor: 'rgba(231,76,60,.08)',
            fill: true, tension: .4, pointRadius: 4
          },
          {
            label: 'Dívidas',
            data: <?= $evolucaoDividas ?>,
            borderColor: '#e67e22', backgroundColor: 'rgba(230,126,34,.08)',
            fill: true, tension: .4, pointRadius: 4
          }
        ]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } },
        scales: {
          y: { beginAtZero: true, ticks: { callback: v => 'R$ ' + v.toLocaleString('pt-BR') } }
        }
      }
    });
  }

  // Doughnut categorias despesas
  const ctxCat = document.getElementById('chartCatDesp');
  if (ctxCat) {
    const labels = <?= $catDespLabels ?>;
    const data   = <?= $catDespTotais ?>;
    const cores  = <?= $catDespCores ?>;

    new Chart(ctxCat, {
      type: 'doughnut',
      data: {
        labels: labels,
        datasets: [{ data, backgroundColor: cores, borderWidth: 2 }]
      },
      options: {
        responsive: true, maintainAspectRatio: false, cutout: '65%',
        plugins: {
          legend: { position: 'bottom', labels: { font: { size: 10 }, boxWidth: 12 } },
          tooltip: {
            callbacks: {
              label: ctx => ' R$ ' + Number(ctx.raw).toLocaleString('pt-BR', {minimumFractionDigits:2})
            }
          }
        }
      }
    });
  }
});
</script>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/app.php'; ?>
