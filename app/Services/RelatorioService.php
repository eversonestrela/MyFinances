<?php

namespace App\Services;

use App\Core\Database;
use App\Core\Env;
use PDO;
use DateTime;

/**
 * Service para geração de dados de relatórios financeiros
 */
class RelatorioService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // =========================================================
    // RESUMO MENSAL
    // =========================================================

    /**
     * Retorna resumo financeiro completo de um mês
     */
    public function getResumoMensal(int $usuarioId, int $mes, int $ano): array
    {
        $totalReceitas  = $this->getTotalReceitas($usuarioId, $mes, $ano);
        $totalDespesas  = $this->getTotalParcelas($usuarioId, $mes, $ano);
        $totalDividas   = $this->getTotalDividas($usuarioId, $mes, $ano);
        $parcelasPagas  = $this->getTotalParcelasPagas($usuarioId, $mes, $ano);
        $parcelasPend   = $this->getTotalParcelasPendentes($usuarioId, $mes, $ano);
        $saldo          = $totalReceitas - $totalDespesas - $totalDividas;

        return [
            'mes'              => $mes,
            'ano'              => $ano,
            'total_receitas'   => $totalReceitas,
            'total_despesas'   => $totalDespesas,
            'total_dividas'    => $totalDividas,
            'parcelas_pagas'   => $parcelasPagas,
            'parcelas_pend'    => $parcelasPend,
            'saldo'            => $saldo,
        ];
    }

    // =========================================================
    // RESUMO ANUAL (12 meses)
    // =========================================================

    /**
     * Resumo mês a mês de um ano completo
     */
    public function getResumoAnual(int $usuarioId, int $ano): array
    {
        $meses = [];
        $nomeMeses = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];

        for ($m = 1; $m <= 12; $m++) {
            $meses[] = [
                'mes'            => $m,
                'label'          => $nomeMeses[$m - 1],
                'total_receitas' => $this->getTotalReceitas($usuarioId, $m, $ano),
                'total_despesas' => $this->getTotalParcelas($usuarioId, $m, $ano),
                'total_dividas'  => $this->getTotalDividas($usuarioId, $m, $ano),
            ];
        }

        return $meses;
    }

    // =========================================================
    // GASTOS POR CATEGORIA
    // =========================================================

    /**
     * Agrupamento de parcelas de despesas por categoria no período
     */
    public function getDespesasPorCategoria(int $usuarioId, int $mes, int $ano): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                COALESCE(c.nome,  'Sem categoria') AS categoria,
                COALESCE(c.icone, 'bi-tag')        AS icone,
                COALESCE(c.cor,   '#6c757d')       AS cor,
                SUM(dp.valor)                      AS total,
                COUNT(dp.id)                       AS qtd
             FROM despesa_parcelas dp
             INNER JOIN despesas d ON d.id = dp.despesa_id
             LEFT  JOIN categorias c ON c.id = d.categoria_id
             WHERE dp.usuario_id = :uid AND dp.mes = :mes AND dp.ano = :ano
             GROUP BY c.id, c.nome, c.icone, c.cor
             ORDER BY total DESC"
        );
        $stmt->execute(['uid' => $usuarioId, 'mes' => $mes, 'ano' => $ano]);
        return $stmt->fetchAll();
    }

    /**
     * Agrupamento de dívidas por categoria no período
     */
    public function getDividasPorCategoria(int $usuarioId, int $mes, int $ano): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                COALESCE(c.nome,  'Sem categoria') AS categoria,
                COALESCE(c.icone, 'bi-tag')        AS icone,
                COALESCE(c.cor,   '#6c757d')       AS cor,
                SUM(dv.valor)                      AS total,
                COUNT(dv.id)                       AS qtd
             FROM dividas_variaveis dv
             LEFT JOIN categorias c ON c.id = dv.categoria_id
             WHERE dv.usuario_id = :uid AND dv.mes = :mes AND dv.ano = :ano
             GROUP BY c.id, c.nome, c.icone, c.cor
             ORDER BY total DESC"
        );
        $stmt->execute(['uid' => $usuarioId, 'mes' => $mes, 'ano' => $ano]);
        return $stmt->fetchAll();
    }

    // =========================================================
    // DETALHAMENTOS
    // =========================================================

    /**
     * Lista detalhada de receitas do período
     */
    public function getReceitasDetalhadas(int $usuarioId, int $mes, int $ano): array
    {
        $stmt = $this->db->prepare(
            "SELECT descricao, valor, tipo_receita, data_recebimento
             FROM receitas
             WHERE usuario_id = :uid
               AND MONTH(data_recebimento) = :mes
               AND YEAR(data_recebimento)  = :ano
             ORDER BY data_recebimento DESC"
        );
        $stmt->execute(['uid' => $usuarioId, 'mes' => $mes, 'ano' => $ano]);
        return $stmt->fetchAll();
    }

    /**
     * Lista detalhada de parcelas de despesas do período
     */
    public function getDespesasDetalhadas(int $usuarioId, int $mes, int $ano): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                d.descricao,
                COALESCE(c.nome, 'Sem categoria') AS categoria,
                dp.valor,
                dp.status_pago,
                dp.mes,
                dp.ano
             FROM despesa_parcelas dp
             INNER JOIN despesas d ON d.id = dp.despesa_id
             LEFT  JOIN categorias c ON c.id = d.categoria_id
             WHERE dp.usuario_id = :uid AND dp.mes = :mes AND dp.ano = :ano
             ORDER BY d.descricao"
        );
        $stmt->execute(['uid' => $usuarioId, 'mes' => $mes, 'ano' => $ano]);
        return $stmt->fetchAll();
    }

    /**
     * Lista detalhada de dívidas do período
     */
    public function getDividasDetalhadas(int $usuarioId, int $mes, int $ano): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                dv.descricao,
                COALESCE(c.nome, 'Sem categoria') AS categoria,
                dv.valor,
                dv.mes,
                dv.ano
             FROM dividas_variaveis dv
             LEFT JOIN categorias c ON c.id = dv.categoria_id
             WHERE dv.usuario_id = :uid AND dv.mes = :mes AND dv.ano = :ano
             ORDER BY dv.descricao"
        );
        $stmt->execute(['uid' => $usuarioId, 'mes' => $mes, 'ano' => $ano]);
        return $stmt->fetchAll();
    }

    // =========================================================
    // EVOLUÇÃO FINANCEIRA (últimos N meses)
    // =========================================================

    /**
     * Evolução mês a mês dos últimos $meses meses
     */
    public function getEvolucaoMensal(int $usuarioId, int $meses = 12): array
    {
        $resultado = [];
        $data = new DateTime();

        for ($i = $meses - 1; $i >= 0; $i--) {
            $d   = clone $data;
            $d->modify("-{$i} months");
            $m   = (int) $d->format('m');
            $a   = (int) $d->format('Y');

            $resultado[] = [
                'label'          => $d->format('M/Y'),
                'mes'            => $m,
                'ano'            => $a,
                'total_receitas' => $this->getTotalReceitas($usuarioId, $m, $a),
                'total_despesas' => $this->getTotalParcelas($usuarioId, $m, $a),
                'total_dividas'  => $this->getTotalDividas($usuarioId, $m, $a),
            ];
        }

        return $resultado;
    }

    // =========================================================
    // STATUS PAGO / PENDENTE do mês
    // =========================================================

    /**
     * Dados do gráfico de pizza: pago vs pendente
     */
    public function getStatusParcelas(int $usuarioId, int $mes, int $ano): array
    {
        $stmt = $this->db->prepare(
            "SELECT status_pago, SUM(valor) AS total, COUNT(*) AS qtd
             FROM despesa_parcelas
             WHERE usuario_id = :uid AND mes = :mes AND ano = :ano
             GROUP BY status_pago"
        );
        $stmt->execute(['uid' => $usuarioId, 'mes' => $mes, 'ano' => $ano]);
        $rows = $stmt->fetchAll();

        $result = ['pago' => 0, 'pendente' => 0, 'qtd_pago' => 0, 'qtd_pendente' => 0];
        foreach ($rows as $r) {
            if ($r['status_pago']) {
                $result['pago']      = (float) $r['total'];
                $result['qtd_pago']  = (int)   $r['qtd'];
            } else {
                $result['pendente']     = (float) $r['total'];
                $result['qtd_pendente'] = (int)   $r['qtd'];
            }
        }
        return $result;
    }

    // =========================================================
    // HELPERS PRIVADOS
    // =========================================================

    private function getTotalReceitas(int $uid, int $mes, int $ano): float
    {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(valor), 0) FROM receitas
             WHERE usuario_id = :uid
               AND MONTH(data_recebimento) = :mes
               AND YEAR(data_recebimento)  = :ano"
        );
        $stmt->execute(['uid' => $uid, 'mes' => $mes, 'ano' => $ano]);
        return (float) $stmt->fetchColumn();
    }

    private function getTotalParcelas(int $uid, int $mes, int $ano): float
    {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(valor), 0) FROM despesa_parcelas
             WHERE usuario_id = :uid AND mes = :mes AND ano = :ano"
        );
        $stmt->execute(['uid' => $uid, 'mes' => $mes, 'ano' => $ano]);
        return (float) $stmt->fetchColumn();
    }

    private function getTotalDividas(int $uid, int $mes, int $ano): float
    {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(valor), 0) FROM dividas_variaveis
             WHERE usuario_id = :uid AND mes = :mes AND ano = :ano"
        );
        $stmt->execute(['uid' => $uid, 'mes' => $mes, 'ano' => $ano]);
        return (float) $stmt->fetchColumn();
    }

    private function getTotalParcelasPagas(int $uid, int $mes, int $ano): float
    {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(valor), 0) FROM despesa_parcelas
             WHERE usuario_id = :uid AND mes = :mes AND ano = :ano AND status_pago = 1"
        );
        $stmt->execute(['uid' => $uid, 'mes' => $mes, 'ano' => $ano]);
        return (float) $stmt->fetchColumn();
    }

    private function getTotalParcelasPendentes(int $uid, int $mes, int $ano): float
    {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(valor), 0) FROM despesa_parcelas
             WHERE usuario_id = :uid AND mes = :mes AND ano = :ano AND status_pago = 0"
        );
        $stmt->execute(['uid' => $uid, 'mes' => $mes, 'ano' => $ano]);
        return (float) $stmt->fetchColumn();
    }
}
