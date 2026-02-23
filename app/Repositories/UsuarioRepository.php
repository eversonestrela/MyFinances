<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Usuario;
use PDO;

/**
 * Repository para gerenciar operações de banco de dados relacionadas a usuários
 */
class UsuarioRepository
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
     * Busca usuário por email
     * 
     * @param string $email
     * @return Usuario|null
     */
    public function findByEmail(string $email): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        
        $data = $stmt->fetch();
        
        return $data ? new Usuario($data) : null;
    }

    /**
     * Busca usuário por ID
     * 
     * @param int $id
     * @return Usuario|null
     */
    public function findById(int $id): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        $data = $stmt->fetch();
        
        return $data ? new Usuario($data) : null;
    }

    /**
     * Cria um novo usuário
     * 
     * @param Usuario $usuario
     * @return bool
     */
    public function create(Usuario $usuario): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO usuarios (nome, email, senha, foto_perfil) 
             VALUES (:nome, :email, :senha, :foto_perfil)"
        );

        $result = $stmt->execute([
            'nome' => $usuario->nome,
            'email' => $usuario->email,
            'senha' => $usuario->senha,
            'foto_perfil' => $usuario->foto_perfil
        ]);

        if ($result) {
            $usuario->id = (int) $this->db->lastInsertId();
        }

        return $result;
    }

    /**
     * Atualiza dados do usuário
     * 
     * @param Usuario $usuario
     * @return bool
     */
    public function update(Usuario $usuario): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE usuarios 
             SET nome = :nome, email = :email, foto_perfil = :foto_perfil
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $usuario->id,
            'nome' => $usuario->nome,
            'email' => $usuario->email,
            'foto_perfil' => $usuario->foto_perfil
        ]);
    }

    /**
     * Atualiza senha do usuário
     * 
     * @param int $id
     * @param string $senhaHash
     * @return bool
     */
    public function updatePassword(int $id, string $senhaHash): bool
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET senha = :senha WHERE id = :id");
        return $stmt->execute(['id' => $id, 'senha' => $senhaHash]);
    }

    /**
     * Atualiza foto de perfil
     * 
     * @param int $id
     * @param string $foto
     * @return bool
     */
    public function updateFotoPerfil(int $id, string $foto): bool
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET foto_perfil = :foto WHERE id = :id");
        return $stmt->execute(['id' => $id, 'foto' => $foto]);
    }

    /**
     * Deleta usuário
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Verifica se email já existe
     * 
     * @param string $email
     * @param int|null $excludeId ID para excluir da verificação (para updates)
     * @return bool
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        $params = ['email' => $email];

        if ($excludeId !== null) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchColumn() > 0;
    }
}
