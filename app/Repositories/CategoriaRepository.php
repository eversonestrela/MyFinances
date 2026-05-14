<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Categoria;
use PDO;

/**
 * Repository para gerenciar operações de banco de dados de categorias
 */
class CategoriaRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Busca categoria por ID
     */
    public function findById(int $id): ?Categoria
    {
        $stmt = $this->db->prepare("SELECT * FROM categorias WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        return $data ? new Categoria($data) : null;
    }

    /**
     * Lista categorias ativas de um usuário (para uso em formulários)
     */
    public function findByUsuario(int $usuarioId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM categorias
             WHERE usuario_id = :usuario_id AND ativo = 1
             ORDER BY nome ASC"
        );
        $stmt->execute(['usuario_id' => $usuarioId]);

        $categorias = [];
        while ($row = $stmt->fetch()) {
            $categorias[] = new Categoria($row);
        }
        return $categorias;
    }

    /**
     * Lista todas as categorias de um usuário (incluindo inativas)
     */
    public function findAllByUsuario(int $usuarioId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM categorias
             WHERE usuario_id = :usuario_id
             ORDER BY nome ASC"
        );
        $stmt->execute(['usuario_id' => $usuarioId]);

        $categorias = [];
        while ($row = $stmt->fetch()) {
            $categorias[] = new Categoria($row);
        }
        return $categorias;
    }

    /**
     * Cria uma nova categoria
     */
    public function create(Categoria $categoria): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO categorias (usuario_id, nome, icone, cor, ativo)
             VALUES (:usuario_id, :nome, :icone, :cor, :ativo)"
        );

        $result = $stmt->execute([
            'usuario_id' => $categoria->usuario_id,
            'nome'       => $categoria->nome,
            'icone'      => $categoria->icone,
            'cor'        => $categoria->cor,
            'ativo'      => $categoria->ativo ? 1 : 0,
        ]);

        if ($result) {
            $categoria->id = (int) $this->db->lastInsertId();
        }

        return $result;
    }

    /**
     * Atualiza uma categoria
     */
    public function update(Categoria $categoria): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE categorias
             SET nome = :nome, icone = :icone, cor = :cor
             WHERE id = :id AND usuario_id = :usuario_id"
        );

        return $stmt->execute([
            'id'         => $categoria->id,
            'usuario_id' => $categoria->usuario_id,
            'nome'       => $categoria->nome,
            'icone'      => $categoria->icone,
            'cor'        => $categoria->cor,
        ]);
    }

    /**
     * Alterna o status ativo/inativo de uma categoria
     */
    public function toggleAtivo(int $id, int $usuarioId): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE categorias SET ativo = NOT ativo
             WHERE id = :id AND usuario_id = :usuario_id"
        );
        return $stmt->execute(['id' => $id, 'usuario_id' => $usuarioId]);
    }

    /**
     * Exclui uma categoria
     */
    public function delete(int $id, int $usuarioId): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM categorias WHERE id = :id AND usuario_id = :usuario_id"
        );
        return $stmt->execute(['id' => $id, 'usuario_id' => $usuarioId]);
    }
}
