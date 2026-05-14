<?php

namespace App\Models;

/**
 * Model Categoria
 * Representa uma categoria de lançamento financeiro
 */
class Categoria
{
    public ?int    $id         = null;
    public int     $usuario_id = 0;
    public string  $nome       = '';
    public string  $icone      = 'bi-tag';
    public string  $cor        = '#667eea';
    public bool    $ativo      = true;
    public ?string $created_at = null;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->fill($data);
        }
    }

    public function fill(array $data): void
    {
        $this->id         = isset($data['id']) ? (int) $data['id'] : null;
        $this->usuario_id = (int) ($data['usuario_id'] ?? 0);
        $this->nome       = $data['nome']   ?? '';
        $this->icone      = $data['icone']  ?? 'bi-tag';
        $this->cor        = $data['cor']    ?? '#667eea';
        $this->ativo      = (bool) ($data['ativo'] ?? true);
        $this->created_at = $data['created_at'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'usuario_id' => $this->usuario_id,
            'nome'       => $this->nome,
            'icone'      => $this->icone,
            'cor'        => $this->cor,
            'ativo'      => $this->ativo ? 1 : 0,
            'created_at' => $this->created_at,
        ];
    }
}
