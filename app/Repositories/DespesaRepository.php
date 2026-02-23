<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Despesa;
use PDO;

/**
 * Repository para gerenciar operações de banco de dados relacionadas a despesas
 */
class DespesaRepository
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
     * Busca despesa por ID
     * 
     * @param int $id
     * @return Despesa|null
     */
    public function findById(int $id): ?Despesa
    {
        $stmt = $this->db->prepare("SELECT * FROM despesas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        $data = $stmt->fetch();
        
        return $data ? new Despesa($data) : null;
    }

    /**
     * Lista todas as despesas de um usuário
     * 
     * @param int $usuarioId
     * @return array
     */
    public function findByUsuario(int $usuarioId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM despesas 
             WHERE usuario_id = :usuario_id 
             ORDER BY data_inicio DESC"
        );
        $stmt->execute(['usuario_id' => $usuarioId]);
        
        $despesas = [];
        while ($row = $stmt->fetch()) {
            $despesas[] = new Despesa($row);
        }
        
        return $despesas;
    }

    /**
     * Cria uma nova despesa
     * 
     * @param Despesa $despesa
     * @return bool
     */
    public function create(Despesa $despesa): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO despesas 
             (usuario_id, descricao, valor_total, data_inicio, data_fim, quantidade_parcelas, valor_parcela) 
             VALUES (:usuario_id, :descricao, :valor_total, :data_inicio, :data_fim, :quantidade_parcelas, :valor_parcela)"
        );

        $result = $stmt->execute([
            'usuario_id' => $despesa->usuario_id,
            'descricao' => $despesa->descricao,
            'valor_total' => $despesa->valor_total,
            'data_inicio' => $despesa->data_inicio,
            'data_fim' => $despesa->data_fim,
            'quantidade_parcelas' => $despesa->quantidade_parcelas,
            'valor_parcela' => $despesa->valor_parcela
        ]);

        if ($result) {
            $despesa->id = (int) $this->db->lastInsertId();
        }

        return $result;
    }

    /**
     * Atualiza uma despesa
     * 
     * @param Despesa $despesa
     * @return bool
     */
    public function update(Despesa $despesa): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE despesas 
             SET descricao = :descricao, valor_total = :valor_total, 
                 data_inicio = :data_inicio, data_fim = :data_fim,
                 quantidade_parcelas = :quantidade_parcelas, valor_parcela = :valor_parcela
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $despesa->id,
            'descricao' => $despesa->descricao,
            'valor_total' => $despesa->valor_total,
            'data_inicio' => $despesa->data_inicio,
            'data_fim' => $despesa->data_fim,
            'quantidade_parcelas' => $despesa->quantidade_parcelas,
            'valor_parcela' => $despesa->valor_parcela
        ]);
    }

    /**
     * Deleta uma despesa
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM despesas WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
