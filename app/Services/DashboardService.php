<?php

namespace App\Services;

use App\Repositories\ReceitaRepository;
use App\Repositories\DespesaParcelaRepository;
use App\Repositories\DividaVariavelRepository;

/**
 * Service para cálculos do dashboard financeiro
 */
class DashboardService
{
    private ReceitaRepository $receitaRepository;
    private DespesaParcelaRepository $despesaRepository;
    private DividaVariavelRepository $dividaRepository;

    /**
     * Construtor
     */
    public function __construct()
    {
        $this->receitaRepository = new ReceitaRepository();
        $this->despesaRepository = new DespesaParcelaRepository();
        $this->dividaRepository = new DividaVariavelRepository();
    }

    /**
     * Obtém dados do dashboard para um período específico
     * 
     * @param int $usuarioId
     * @param int $mes
     * @param int $ano
     * @return array
     */
    public function getDashboardData(int $usuarioId, int $mes, int $ano): array
    {
        // Totais do período
        $totalReceitas = $this->receitaRepository->getTotalByPeriodo($usuarioId, $mes, $ano);
        $totalDespesas = $this->despesaRepository->getTotalByPeriodo($usuarioId, $mes, $ano);
        $totalDividas = $this->dividaRepository->getTotalByPeriodo($usuarioId, $mes, $ano);

        // Saldo do mês
        $saldo = $totalReceitas - $totalDespesas - $totalDividas;

        return [
            'saldo' => $saldo,
            'total_receitas' => $totalReceitas,
            'total_despesas' => $totalDespesas,
            'total_dividas' => $totalDividas,
            'mes' => $mes,
            'ano' => $ano
        ];
    }

    /**
     * Obtém dados para gráfico dos últimos 6 meses
     * 
     * @param int $usuarioId
     * @return array
     */
    public function getGraficoUltimosSeisMeses(int $usuarioId): array
    {
        $dados = [
            'labels' => [],
            'receitas' => [],
            'despesas' => [],
            'dividas' => []
        ];

        // Obter dados dos últimos 6 meses
        $dataAtual = new \DateTime();
        
        for ($i = 5; $i >= 0; $i--) {
            $data = clone $dataAtual;
            $data->modify("-{$i} months");
            
            $mes = (int) $data->format('m');
            $ano = (int) $data->format('Y');
            
            $dados['labels'][] = $data->format('M/Y');
            $dados['receitas'][] = $this->receitaRepository->getTotalByPeriodo($usuarioId, $mes, $ano);
            $dados['despesas'][] = $this->despesaRepository->getTotalByPeriodo($usuarioId, $mes, $ano);
            $dados['dividas'][] = $this->dividaRepository->getTotalByPeriodo($usuarioId, $mes, $ano);
        }

        return $dados;
    }

    /**
     * Obtém últimas movimentações (receitas, despesas e dívidas)
     * 
     * @param int $usuarioId
     * @param int $limite
     * @return array
     */
    public function getUltimasMovimentacoes(int $usuarioId, int $limite = 10): array
    {
        $mesAtual = (int) date('m');
        $anoAtual = (int) date('Y');

        $movimentacoes = [];

        // Receitas do mês
        $receitas = $this->receitaRepository->findByPeriodo($usuarioId, $mesAtual, $anoAtual);
        foreach ($receitas as $receita) {
            $movimentacoes[] = [
                'tipo' => 'receita',
                'descricao' => $receita->descricao,
                'valor' => $receita->valor,
                'data' => $receita->data_recebimento,
                'class' => 'success'
            ];
        }

        // Despesas do mês
        $despesas = $this->despesaRepository->findByPeriodo($usuarioId, $mesAtual, $anoAtual);
        foreach ($despesas as $despesa) {
            $movimentacoes[] = [
                'tipo' => 'despesa',
                'descricao' => $despesa['despesa_descricao'],
                'valor' => $despesa['valor'],
                'data' => "{$anoAtual}-{$mesAtual}-01",
                'class' => 'danger'
            ];
        }

        // Dívidas do mês
        $dividas = $this->dividaRepository->findByPeriodo($usuarioId, $mesAtual, $anoAtual);
        foreach ($dividas as $divida) {
            $movimentacoes[] = [
                'tipo' => 'divida',
                'descricao' => $divida->descricao,
                'valor' => $divida->valor,
                'data' => "{$anoAtual}-{$mesAtual}-01",
                'class' => 'warning'
            ];
        }

        // Ordenar por data decrescente
        usort($movimentacoes, function($a, $b) {
            return strtotime($b['data']) - strtotime($a['data']);
        });

        // Limitar resultado
        return array_slice($movimentacoes, 0, $limite);
    }
}
