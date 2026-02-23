<?php

namespace App\Models;

/**
 * Model Usuario
 * Representa um usuário do sistema
 */
class Usuario
{
    public ?int $id = null;
    public string $nome;
    public string $email;
    public string $senha;
    public ?string $foto_perfil = null;
    public ?string $data_criacao = null;

    /**
     * Construtor
     * 
     * @param array $data Dados do usuário
     */
    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->fill($data);
        }
    }

    /**
     * Preenche o objeto com dados
     * 
     * @param array $data
     * @return void
     */
    public function fill(array $data): void
    {
        $this->id = $data['id'] ?? null;
        $this->nome = $data['nome'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->senha = $data['senha'] ?? '';
        $this->foto_perfil = $data['foto_perfil'] ?? null;
        $this->data_criacao = $data['data_criacao'] ?? null;
    }

    /**
     * Converte o objeto para array
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email,
            'foto_perfil' => $this->foto_perfil,
            'data_criacao' => $this->data_criacao,
        ];
    }
}
