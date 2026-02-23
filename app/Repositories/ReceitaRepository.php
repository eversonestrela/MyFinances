<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Receita;
use PDO;

/**
 * Repository para gerenciar operações de banco de dados relacionadas a receitas
 */
class ReceitaRepository
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
     * Busca receita por ID
     * 
     * @param int $id
     * @return Receita|null
     */
    public function findById(int $id): ?Receita
    {
        $stmt = $this->db->prepare("SELECT * FROM receitas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        $data = $stmt->fetch();
        
        return $data ? new Receita($data) : null;
    }

    /**
     * Lista todas as receitas de um usuário
     * 
     * @param int $usuarioId
     * @return array
     */
    public function findByUsuario(int $usuarioId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM receitas 
             WHERE usuario_id = :usuario_id 
             ORDER BY data_recebimento DESC"
        );
        $stmt->execute(['usuario_id' => $usuarioId]);
        
        $receitas = [];
        while ($row = $stmt->fetch()) {
            $receitas[] = new Receita($row);
        }
        
        return $receitas;
    }

    /**
     * Busca receitas por período
     * 
     * @param int $usuarioId
     * @param int $mes
     * @param int $ano
     * @return array
     */
    public function findByPeriodo(int $usuarioId, int $mes, int $ano): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM receitas 
             WHERE usuario_id = :usuario_id 
             AND MONTH(data_recebimento) = :mes 
             AND YEAR(data_recebimento) = :ano
             ORDER BY data_recebimento DESC"
        );
        $stmt->execute([
            'usuario_id' => $usuarioId,
            'mes' => $mes,
            'ano' => $ano
        ]);
        
        $receitas = [];
        while ($row = $stmt->fetch()) {
            $receitas[] = new Receita($row);
        }
        
        return $receitas;
    }

    /**
     * Calcula total de receitas por período
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
             FROM receitas 
             WHERE usuario_id = :usuario_id 
             AND MONTH(data_recebimento) = :mes 
             AND YEAR(data_recebimento) = :ano"
        );
        $stmt->execute([
            'usuario_id' => $usuarioId,
            'mes' => $mes,
            'ano' => $ano
        ]);
        
        return (float) $stmt->fetchColumn();
    }

    /**
     * Cria uma nova receita
     * 
     * @param Receita $receita
     * @return bool
     */
    public function create(Receita $receita): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO receitas (usuario_id, descricao, valor, tipo_receita, data_recebimento, data_fim, receita_grupo_id) 
             VALUES (:usuario_id, :descricao, :valor, :tipo_receita, :data_recebimento, :data_fim, :receita_grupo_id)"
        );

        $result = $stmt->execute([
            'usuario_id' => $receita->usuario_id,
            'descricao' => $receita->descricao,
            'valor' => $receita->valor,
            'tipo_receita' => $receita->tipo_receita ?? 'unica',
            'data_recebimento' => $receita->data_recebimento,
            'data_fim' => $receita->data_fim ?? null,
            'receita_grupo_id' => $receita->receita_grupo_id ?? null
        ]);

        if ($result) {
            $receita->id = (int) $this->db->lastInsertId();
        }

        return $result;
    }

    /**
     * Atualiza uma receita
     * 
     * @param Receita $receita
     * @return bool
     */
    public function update(Receita $receita): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE receitas 
             SET descricao = :descricao, valor = :valor, data_recebimento = :data_recebimento
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $receita->id,
            'descricao' => $receita->descricao,
            'valor' => $receita->valor,
            'data_recebimento' => $receita->data_recebimento
        ]);
    }

    /**
     * Deleta uma receita
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM receitas WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Deleta todas as receitas de um grupo (receitas recorrentes)
     * 
     * @param string $grupoId
     * @return bool
     */
    public function deleteByGrupoId(string $grupoId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM receitas WHERE receita_grupo_id = :grupo_id");
        return $stmt->execute(['grupo_id' => $grupoId]);
    }
}
