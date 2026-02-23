<?php

namespace App\Models;

/**
 * Model DespesaParcela
 * Representa uma parcela individual de uma despesa
 */
class DespesaParcela
{
    public ?int $id = null;
    public int $despesa_id;
    public int $usuario_id;
    public int $mes;
    public int $ano;
    public float $valor;
    public bool $status_pago;
    public ?string $created_at = null;

    /**
     * Construtor
     * 
     * @param array $data Dados da parcela
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
        $this->despesa_id = $data['despesa_id'] ?? 0;
        $this->usuario_id = $data['usuario_id'] ?? 0;
        $this->mes = (int) ($data['mes'] ?? 0);
        $this->ano = (int) ($data['ano'] ?? 0);
        $this->valor = (float) ($data['valor'] ?? 0);
        $this->status_pago = (bool) ($data['status_pago'] ?? false);
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
            'despesa_id' => $this->despesa_id,
            'usuario_id' => $this->usuario_id,
            'mes' => $this->mes,
            'ano' => $this->ano,
            'valor' => $this->valor,
            'status_pago' => $this->status_pago,
            'created_at' => $this->created_at,
        ];
    }
}
