<?php

namespace App\Models;

/**
 * Model DividaVariavel
 * Representa uma dívida com valor variável por mês
 */
class DividaVariavel
{
    public ?int $id = null;
    public int $usuario_id;
    public ?int $categoria_id = null;
    public ?string $categoria_nome = null;
    public string $descricao;
    public int $mes;
    public int $ano;
    public float $valor;
    public ?string $created_at = null;

    /**
     * Construtor
     * 
     * @param array $data Dados da dívida
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
        $this->mes = (int) ($data['mes'] ?? 0);
        $this->ano = (int) ($data['ano'] ?? 0);
        $this->valor = (float) ($data['valor'] ?? 0);
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
            'mes' => $this->mes,
            'ano' => $this->ano,
            'valor' => $this->valor,
            'created_at' => $this->created_at,
        ];
    }
}
