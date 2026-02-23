<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\DespesaParcela;
use PDO;

/**
 * Repository para gerenciar operações de banco de dados relacionadas a parcelas de despesas
 */
class DespesaParcelaRepository
{
    private PDO $db;

    /**
     * Construtor
     */
    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Busca parcela por ID
     * 
     * @param int $id
     * @return DespesaParcela|null
     */
    public function findById(int $id): ?DespesaParcela
    {
        $stmt = $this->db->prepare("SELECT * FROM despesa_parcelas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        $data = $stmt->fetch();
        
        return $data ? new DespesaParcela($data) : null;
    }

    /**
     * Lista todas as parcelas de uma despesa
     * 
     * @param int $despesaId
     * @return array
     */
    public function findByDespesa(int $despesaId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM despesa_parcelas 
             WHERE despesa_id = :despesa_id 
             ORDER BY ano, mes"
        );
        $stmt->execute(['despesa_id' => $despesaId]);
        
        $parcelas = [];
        while ($row = $stmt->fetch()) {
            $parcelas[] = new DespesaParcela($row);
        }
        
        return $parcelas;
    }

    /**
     * Busca parcelas por período
     * 
     * @param int $usuarioId
     * @param int $mes
     * @param int $ano
     * @return array
     */
    public function findByPeriodo(int $usuarioId, int $mes, int $ano): array
    {
        $stmt = $this->db->prepare(
            "SELECT dp.*, d.descricao as despesa_descricao 
             FROM despesa_parcelas dp
             INNER JOIN despesas d ON d.id = dp.despesa_id
             WHERE dp.usuario_id = :usuario_id 
             AND dp.mes = :mes 
             AND dp.ano = :ano
             ORDER BY dp.status_pago, d.descricao"
        );
        $stmt->execute([
            'usuario_id' => $usuarioId,
            'mes' => $mes,
            'ano' => $ano
        ]);
        
        return $stmt->fetchAll();
    }

    /**
     * Calcula total de despesas por período
     * 
     * @param int $usuarioId
     * @param int $mes
     * @param int $ano
     * @return float
     */
    public function getTotalByPeriodo(int $usuarioId, int $mes, int $ano): float
    {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(valor), 0) as total 
             FROM despesa_parcelas 
             WHERE usuario_id = :usuario_id 
             AND mes = :mes 
             AND ano = :ano"
        );
        $stmt->execute([
            'usuario_id' => $usuarioId,
            'mes' => $mes,
            'ano' => $ano
        ]);
        
        return (float) $stmt->fetchColumn();
    }

    /**
     * Cria uma nova parcela
     * 
     * @param DespesaParcela $parcela
     * @return bool
     */
    public function create(DespesaParcela $parcela): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO despesa_parcelas 
             (despesa_id, usuario_id, mes, ano, valor, status_pago) 
             VALUES (:despesa_id, :usuario_id, :mes, :ano, :valor, :status_pago)"
        );

        $result = $stmt->execute([
            'despesa_id' => $parcela->despesa_id,
            'usuario_id' => $parcela->usuario_id,
            'mes' => $parcela->mes,
            'ano' => $parcela->ano,
            'valor' => $parcela->valor,
            'status_pago' => $parcela->status_pago ? 1 : 0
        ]);

        if ($result) {
            $parcela->id = (int) $this->db->lastInsertId();
        }

        return $result;
    }

    /**
     * Atualiza status de pagamento de uma parcela
     * 
     * @param int $id
     * @param bool $statusPago
     * @return bool
     */
    public function updateStatusPago(int $id, bool $statusPago): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE despesa_parcelas SET status_pago = :status_pago WHERE id = :id"
        );
        return $stmt->execute(['id' => $id, 'status_pago' => $statusPago ? 1 : 0]);
    }

    /**
     * Deleta todas as parcelas de uma despesa
     * 
     * @param int $despesaId
     * @return bool
     */
    public function deleteByDespesa(int $despesaId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM despesa_parcelas WHERE despesa_id = :despesa_id");
        return $stmt->execute(['despesa_id' => $despesaId]);
    }

    /**
     * Deleta uma parcela específica
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM despesa_parcelas WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
