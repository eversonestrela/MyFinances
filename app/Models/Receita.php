<?php

namespace App\Models;

/**
 * Model Receita
 * Representa uma receita (provento) do usuário
 */
class Receita
{
    public ?int $id = null;
    public int $usuario_id;
    public string $descricao;
    public float $valor;
    public string $tipo_receita = 'unica';
    public string $data_recebimento;
    public ?string $data_fim = null;
    public ?string $receita_grupo_id = null;
    public ?string $created_at = null;

    /**
     * Construtor
     * 
     * @param array $data Dados da receita
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
        $this->usuario_id = $data['usuario_id'] ?? 0;
        $this->descricao = $data['descricao'] ?? '';
        $this->valor = (float) ($data['valor'] ?? 0);
        $this->tipo_receita = $data['tipo_receita'] ?? 'unica';
        $this->data_recebimento = $data['data_recebimento'] ?? '';
        $this->data_fim = $data['data_fim'] ?? null;
        $this->receita_grupo_id = $data['receita_grupo_id'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
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
            'usuario_id' => $this->usuario_id,
            'descricao' => $this->descricao,
            'valor' => $this->valor,
            'tipo_receita' => $this->tipo_receita,
            'data_recebimento' => $this->data_recebimento,
            'data_fim' => $this->data_fim,
            'receita_grupo_id' => $this->receita_grupo_id,
            'created_at' => $this->created_at,
        ];
    }
}
