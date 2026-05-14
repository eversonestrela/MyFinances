<?php

namespace App\Services;

use App\Models\Despesa;
use App\Models\DespesaParcela;
use App\Repositories\DespesaRepository;
use App\Repositories\DespesaParcelaRepository;
use App\Repositories\CategoriaRepository;
use DateTime;

/**
 * Service para lógica de despesas parceladas
 */
class DespesaService
{
    private DespesaRepository $despesaRepository;
    private DespesaParcelaRepository $parcelaRepository;
    private CategoriaRepository $categoriaRepository;

    public function __construct()
    {
        $this->despesaRepository   = new DespesaRepository();
        $this->parcelaRepository   = new DespesaParcelaRepository();
        $this->categoriaRepository = new CategoriaRepository();
    }

    /**
     * Cria uma despesa parcelada e gera automaticamente as parcelas
     * 
     * @param array $data
     * @return array ['success' => bool, 'message' => string, 'despesa' => Despesa|null]
     */
    public function criarDespesa(array $data): array
    {
        // Validar dados
        if (empty($data['descricao']) || empty($data['data_inicio']) || empty($data['data_fim'])) {
            return ['success' => false, 'message' => 'Todos os campos são obrigatórios', 'despesa' => null];
        }

        if (empty($data['categoria_id'])) {
            return ['success' => false, 'message' => 'Selecione uma categoria', 'despesa' => null];
        }

        $tipoParcelamento = $data['tipo_parcelamento'] ?? 'dividir';
        
        // Validar campos conforme tipo
        if ($tipoParcelamento === 'dividir' && empty($data['valor_total'])) {
            return ['success' => false, 'message' => 'Valor total é obrigatório', 'despesa' => null];
        }
        
        if ($tipoParcelamento === 'fixa' && empty($data['valor_parcela_fixa'])) {
            return ['success' => false, 'message' => 'Valor da parcela é obrigatório', 'despesa' => null];
        }

        // Calcular quantidade de parcelas
        $dataInicio = new DateTime($data['data_inicio']);
        $dataFim = new DateTime($data['data_fim']);
        
        $quantidadeParcelas = $this->calcularMesesEntreDatas($dataInicio, $dataFim);
        
        if ($quantidadeParcelas <= 0) {
            return ['success' => false, 'message' => 'Data de início deve ser anterior à data de fim', 'despesa' => null];
        }

        // Definir valores conforme tipo de parcelamento
        if ($tipoParcelamento === 'fixa') {
            $valorParcela = (float) $data['valor_parcela_fixa'];
            $valorTotal = $valorParcela * $quantidadeParcelas;
        } else {
            $valorTotal = (float) $data['valor_total'];
            $valorParcela = round($valorTotal / $quantidadeParcelas, 2);
        }

        // Criar despesa
        $despesa = new Despesa();
        $despesa->usuario_id          = $data['usuario_id'];
        $despesa->categoria_id        = (int) $data['categoria_id'];
        $despesa->descricao           = $data['descricao'];
        $despesa->valor_total = $valorTotal;
        $despesa->data_inicio = $data['data_inicio'];
        $despesa->data_fim = $data['data_fim'];
        $despesa->quantidade_parcelas = $quantidadeParcelas;
        $despesa->valor_parcela = $valorParcela;

        if (!$this->despesaRepository->create($despesa)) {
            return ['success' => false, 'message' => 'Erro ao criar despesa', 'despesa' => null];
        }

        // Gerar parcelas automaticamente
        if ($tipoParcelamento === 'fixa') {
            $this->gerarParcelasFixas($despesa, $dataInicio, $quantidadeParcelas, $valorParcela);
        } else {
            $this->gerarParcelas($despesa, $dataInicio, $quantidadeParcelas);
        }

        return ['success' => true, 'message' => 'Despesa criada com sucesso', 'despesa' => $despesa];
    }

    /**
     * Calcula quantidade de meses entre duas datas
     * 
     * @param DateTime $dataInicio
     * @param DateTime $dataFim
     * @return int
     */
    private function calcularMesesEntreDatas(DateTime $dataInicio, DateTime $dataFim): int
    {
        $diff = $dataInicio->diff($dataFim);
        return ($diff->y * 12) + $diff->m + 1; // +1 para incluir o mês inicial
    }

    /**
     * Gera parcelas automaticamente
     * 
     * @param Despesa $despesa
     * @param DateTime $dataInicio
     * @param int $quantidadeParcelas
     * @return void
     */
    private function gerarParcelas(Despesa $despesa, DateTime $dataInicio, int $quantidadeParcelas): void
    {
        $valorParcela = $despesa->valor_parcela;
        $valorTotal = 0;

        for ($i = 0; $i < $quantidadeParcelas; $i++) {
            $parcela = new DespesaParcela();
            $parcela->despesa_id = $despesa->id;
            $parcela->usuario_id = $despesa->usuario_id;
            $parcela->mes = (int) $dataInicio->format('m');
            $parcela->ano = (int) $dataInicio->format('Y');
            
            // Ajustar última parcela para compensar arredondamento
            if ($i === $quantidadeParcelas - 1) {
                $parcela->valor = round($despesa->valor_total - $valorTotal, 2);
            } else {
                $parcela->valor = $valorParcela;
                $valorTotal += $valorParcela;
            }
            
            $parcela->status_pago = false;

            $this->parcelaRepository->create($parcela);

            // Avançar para o próximo mês
            $dataInicio->modify('+1 month');
        }
    }

    /**
     * Gera parcelas fixas (mesmo valor todos os meses)
     * 
     * @param Despesa $despesa
     * @param DateTime $dataInicio
     * @param int $quantidadeParcelas
     * @param float $valorParcela
     * @return void
     */
    private function gerarParcelasFixas(Despesa $despesa, DateTime $dataInicio, int $quantidadeParcelas, float $valorParcela): void
    {
        for ($i = 0; $i < $quantidadeParcelas; $i++) {
            $parcela = new DespesaParcela();
            $parcela->despesa_id = $despesa->id;
            $parcela->usuario_id = $despesa->usuario_id;
            $parcela->mes = (int) $dataInicio->format('m');
            $parcela->ano = (int) $dataInicio->format('Y');
            $parcela->valor = $valorParcela;
            $parcela->status_pago = false;

            $this->parcelaRepository->create($parcela);

            // Avançar para o próximo mês
            $dataInicio->modify('+1 month');
        }
    }

    /**
     * Lista todas as despesas de um usuário
     * 
     * @param int $usuarioId
     * @return array
     */
    public function listarDespesas(int $usuarioId): array
    {
        return $this->despesaRepository->findByUsuario($usuarioId);
    }

    /**
     * Lista parcelas por período
     * 
     * @param int $usuarioId
     * @param int $mes
     * @param int $ano
     * @return array
     */
    public function listarParcelasPorPeriodo(int $usuarioId, int $mes, int $ano): array
    {
        return $this->parcelaRepository->findByPeriodo($usuarioId, $mes, $ano);
    }

    /**
     * Alterna status de pagamento de uma parcela
     * 
     * @param int $parcelaId
     * @return bool
     */
    public function toggleStatusPago(int $parcelaId): bool
    {
        $parcela = $this->parcelaRepository->findById($parcelaId);
        
        if (!$parcela) {
            return false;
        }

        return $this->parcelaRepository->updateStatusPago($parcelaId, !$parcela->status_pago);
    }

    /**
     * Exclui uma despesa e todas as suas parcelas
     * 
     * @param int $despesaId
     * @return bool
     */
    public function excluirDespesa(int $despesaId): bool
    {
        // Parcelas serão excluídas automaticamente por CASCADE
        return $this->despesaRepository->delete($despesaId);
    }

    /**
     * Obtém total de despesas por período
     * 
     * @param int $usuarioId
     * @param int $mes
     * @param int $ano
     * @return float
     */
    public function getTotalPorPeriodo(int $usuarioId, int $mes, int $ano): float
    {
        return $this->parcelaRepository->getTotalByPeriodo($usuarioId, $mes, $ano);
    }
}
