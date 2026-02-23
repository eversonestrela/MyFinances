<?php

namespace App\Services;

use App\Models\Receita;
use App\Repositories\ReceitaRepository;
use DateTime;

/**
 * Service para lógica de receitas
 */
class ReceitaService
{
    private ReceitaRepository $receitaRepository;

    /**
     * Construtor
     */
    public function __construct()
    {
        $this->receitaRepository = new ReceitaRepository();
    }

    /**
     * Cria receita única ou recorrente
     * 
     * @param array $data
     * @return array ['success' => bool, 'message' => string]
     */
    public function criarReceita(array $data): array
    {
        // Validar dados básicos
        if (empty($data['descricao']) || empty($data['valor']) || empty($data['data_recebimento'])) {
            return ['success' => false, 'message' => 'Todos os campos são obrigatórios'];
        }

        $tipoReceita = $data['tipo_receita'] ?? 'unica';

        // Se for recorrente, validar período
        if ($tipoReceita === 'recorrente') {
            if (empty($data['data_fim'])) {
                return ['success' => false, 'message' => 'Data fim é obrigatória para receitas recorrentes'];
            }

            $dataInicio = new DateTime($data['data_recebimento']);
            $dataFim = new DateTime($data['data_fim']);

            if ($dataInicio >= $dataFim) {
                return ['success' => false, 'message' => 'Data de início deve ser anterior à data fim'];
            }

            // Gerar receitas recorrentes
            return $this->criarReceitasRecorrentes($data, $dataInicio, $dataFim);
        }

        // Criar receita única
        $receita = new Receita();
        $receita->usuario_id = $data['usuario_id'];
        $receita->descricao = $data['descricao'];
        $receita->valor = (float) $data['valor'];
        $receita->tipo_receita = 'unica';
        $receita->data_recebimento = $data['data_recebimento'];
        $receita->data_fim = null;
        $receita->receita_grupo_id = null;

        if (!$this->receitaRepository->create($receita)) {
            return ['success' => false, 'message' => 'Erro ao criar receita'];
        }

        return ['success' => true, 'message' => 'Receita criada com sucesso'];
    }

    /**
     * Cria receitas recorrentes para um período
     * 
     * @param array $data
     * @param DateTime $dataInicio
     * @param DateTime $dataFim
     * @return array
     */
    private function criarReceitasRecorrentes(array $data, DateTime $dataInicio, DateTime $dataFim): array
    {
        $grupoId = $this->gerarUUID();
        $valor = (float) $data['valor'];
        $quantidadeMeses = $this->calcularMesesEntreDatas($dataInicio, $dataFim);
        
        if ($quantidadeMeses <= 0) {
            return ['success' => false, 'message' => 'Período inválido'];
        }

        $dataAtual = clone $dataInicio;

        for ($i = 0; $i < $quantidadeMeses; $i++) {
            $receita = new Receita();
            $receita->usuario_id = $data['usuario_id'];
            $receita->descricao = $data['descricao'];
            $receita->valor = $valor;
            $receita->tipo_receita = 'recorrente';
            $receita->data_recebimento = $dataAtual->format('Y-m-d');
            $receita->data_fim = $data['data_fim'];
            $receita->receita_grupo_id = $grupoId;

            if (!$this->receitaRepository->create($receita)) {
                return ['success' => false, 'message' => 'Erro ao criar receitas recorrentes'];
            }

            // Avançar para o próximo mês
            $dataAtual->modify('+1 month');
        }

        return [
            'success' => true, 
            'message' => "Receitas recorrentes criadas com sucesso ({$quantidadeMeses} meses)"
        ];
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
     * Gera UUID para agrupar receitas recorrentes
     * 
     * @return string
     */
    private function gerarUUID(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * Lista receitas do usuário
     * 
     * @param int $usuarioId
     * @return array
     */
    public function listarReceitas(int $usuarioId): array
    {
        return $this->receitaRepository->findByUsuario($usuarioId);
    }

    /**
     * Exclui receita ou grupo de receitas recorrentes
     * 
     * @param int $receitaId
     * @param bool $excluirGrupo Se true, exclui todas as receitas do grupo
     * @return bool
     */
    public function excluirReceita(int $receitaId, bool $excluirGrupo = false): bool
    {
        $receita = $this->receitaRepository->findById($receitaId);
        
        if (!$receita) {
            return false;
        }

        // Se for recorrente e pediu para excluir grupo
        if ($excluirGrupo && $receita->tipo_receita === 'recorrente' && $receita->receita_grupo_id) {
            return $this->receitaRepository->deleteByGrupoId($receita->receita_grupo_id);
        }

        // Excluir apenas esta receita
        return $this->receitaRepository->delete($receitaId);
    }
}
