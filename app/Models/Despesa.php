<?php

namespace App\Models;

/**
 * Model Despesa
 * Representa uma despesa parcelada
 */
class Despesa
{
    public ?int $id = null;
    public int $usuario_id;
    public ?int $categoria_id = null;
    public ?string $categoria_nome = null;
    public string $descricao;
    public float $valor_total;
    public string $data_inicio;
    public string $data_fim;
    public int $quantidade_parcelas;
    public float $valor_parcela;
    public ?string $created_at = null;

    /**
     * Construtor
     * 
     * @param array $data Dados da despesa
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
        $this->categoria_id = isset($data['categoria_id']) ? (int) $data['categoria_id'] : null;
        $this->categoria_nome = $data['categoria_nome'] ?? null;
        $this->descricao = $data['descricao'] ?? '';
        $this->valor_total = (float) ($data['valor_total'] ?? 0);
        $this->data_inicio = $data['data_inicio'] ?? '';
        $this->data_fim = $data['data_fim'] ?? '';
        $this->quantidade_parcelas = (int) ($data['quantidade_parcelas'] ?? 0);
        $this->valor_parcela = (float) ($data['valor_parcela'] ?? 0);
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
            'categoria_id' => $this->categoria_id,
            'descricao' => $this->descricao,
            'valor_total' => $this->valor_total,
            'data_inicio' => $this->data_inicio,
            'data_fim' => $this->data_fim,
            'quantidade_parcelas' => $this->quantidade_parcelas,
            'valor_parcela' => $this->valor_parcela,
            'created_at' => $this->created_at,
        ];
    }
}
