<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\DividaVariavel;
use PDO;

/**
 * Repository para gerenciar operações de banco de dados relacionadas a dívidas variáveis
 */
class DividaVariavelRepository
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
     * Busca dívida por ID
     * 
     * @param int $id
     * @return DividaVariavel|null
     */
    public function findById(int $id): ?DividaVariavel
    {
        $stmt = $this->db->prepare(
            "SELECT dv.*, c.nome AS categoria_nome
             FROM dividas_variaveis dv
             LEFT JOIN categorias c ON c.id = dv.categoria_id
             WHERE dv.id = :id"
        );
        $stmt->execute(['id' => $id]);

        $data = $stmt->fetch();

        return $data ? new DividaVariavel($data) : null;
    }

    /**
     * Lista todas as dívidas de um usuário
     * 
     * @param int $usuarioId
     * @return array
     */
    public function findByUsuario(int $usuarioId): array
    {
        $stmt = $this->db->prepare(
            "SELECT dv.*, c.nome AS categoria_nome
             FROM dividas_variaveis dv
             LEFT JOIN categorias c ON c.id = dv.categoria_id
             WHERE dv.usuario_id = :usuario_id
             ORDER BY dv.ano DESC, dv.mes DESC"
        );
        $stmt->execute(['usuario_id' => $usuarioId]);

        $dividas = [];
        while ($row = $stmt->fetch()) {
            $dividas[] = new DividaVariavel($row);
        }

        return $dividas;
    }

    /**
     * Busca dívidas por período
     * 
     * @param int $usuarioId
     * @param int $mes
     * @param int $ano
     * @return array
     */
    public function findByPeriodo(int $usuarioId, int $mes, int $ano): array
    {
        $stmt = $this->db->prepare(
            "SELECT dv.*, c.nome AS categoria_nome
             FROM dividas_variaveis dv
             LEFT JOIN categorias c ON c.id = dv.categoria_id
             WHERE dv.usuario_id = :usuario_id
             AND dv.mes = :mes
             AND dv.ano = :ano
             ORDER BY dv.descricao"
        );
        $stmt->execute([
            'usuario_id' => $usuarioId,
            'mes'        => $mes,
            'ano'        => $ano,
        ]);

        $dividas = [];
        while ($row = $stmt->fetch()) {
            $dividas[] = new DividaVariavel($row);
        }

        return $dividas;
    }

    /**
     * Calcula total de dívidas por período
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
             FROM dividas_variaveis 
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
     * Cria uma nova dívida
     * 
     * @param DividaVariavel $divida
     * @return bool
     */
    public function create(DividaVariavel $divida): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO dividas_variaveis (usuario_id, categoria_id, descricao, mes, ano, valor)
             VALUES (:usuario_id, :categoria_id, :descricao, :mes, :ano, :valor)"
        );

        $result = $stmt->execute([
            'usuario_id'   => $divida->usuario_id,
            'categoria_id' => $divida->categoria_id,
            'descricao'    => $divida->descricao,
            'mes'          => $divida->mes,
            'ano'          => $divida->ano,
            'valor'        => $divida->valor,
        ]);

        if ($result) {
            $divida->id = (int) $this->db->lastInsertId();
        }

        return $result;
    }

    /**
     * Atualiza uma dívida
     * 
     * @param DividaVariavel $divida
     * @return bool
     */
    public function update(DividaVariavel $divida): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE dividas_variaveis
             SET categoria_id = :categoria_id, descricao = :descricao, mes = :mes, ano = :ano, valor = :valor
             WHERE id = :id"
        );

        return $stmt->execute([
            'id'           => $divida->id,
            'categoria_id' => $divida->categoria_id,
            'descricao'    => $divida->descricao,
            'mes'          => $divida->mes,
            'ano'          => $divida->ano,
            'valor'        => $divida->valor,
        ]);
    }

    /**
     * Deleta uma dívida
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM dividas_variaveis WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
