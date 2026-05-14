<?php

namespace App\Services;

use App\Models\Categoria;
use App\Repositories\CategoriaRepository;

/**
 * Service para lógica de negócio de categorias
 */
class CategoriaService
{
    private CategoriaRepository $repository;

    public function __construct()
    {
        $this->repository = new CategoriaRepository();
    }

    /**
     * Lista categorias ativas do usuário
     */
    public function listar(int $usuarioId): array
    {
        return $this->repository->findByUsuario($usuarioId);
    }

    /**
     * Lista todas as categorias do usuário (incluindo inativas)
     */
    public function listarTodas(int $usuarioId): array
    {
        return $this->repository->findAllByUsuario($usuarioId);
    }

    /**
     * Cria uma nova categoria
     *
     * @param array $data ['usuario_id', 'nome', 'icone', 'cor']
     */
    public function criar(array $data): array
    {
        $nome = trim($data['nome'] ?? '');

        if (empty($nome)) {
            return ['success' => false, 'message' => 'O nome da categoria é obrigatório'];
        }

        if (strlen($nome) > 100) {
            return ['success' => false, 'message' => 'O nome deve ter no máximo 100 caracteres'];
        }

        $categoria              = new Categoria();
        $categoria->usuario_id  = (int) $data['usuario_id'];
        $categoria->nome        = $nome;
        $categoria->icone       = $data['icone'] ?? 'bi-tag';
        $categoria->cor         = $data['cor']   ?? '#667eea';
        $categoria->ativo       = true;

        if (!$this->repository->create($categoria)) {
            return ['success' => false, 'message' => 'Erro ao criar categoria'];
        }

        return ['success' => true, 'message' => 'Categoria criada com sucesso', 'categoria' => $categoria];
    }

    /**
     * Edita uma categoria existente
     *
     * @param int   $id
     * @param array $data ['usuario_id', 'nome', 'icone', 'cor']
     */
    public function editar(int $id, array $data): array
    {
        $categoria = $this->repository->findById($id);

        if (!$categoria || $categoria->usuario_id !== (int) $data['usuario_id']) {
            return ['success' => false, 'message' => 'Categoria não encontrada'];
        }

        $nome = trim($data['nome'] ?? '');

        if (empty($nome)) {
            return ['success' => false, 'message' => 'O nome da categoria é obrigatório'];
        }

        $categoria->nome  = $nome;
        $categoria->icone = $data['icone'] ?? $categoria->icone;
        $categoria->cor   = $data['cor']   ?? $categoria->cor;

        if (!$this->repository->update($categoria)) {
            return ['success' => false, 'message' => 'Erro ao atualizar categoria'];
        }

        return ['success' => true, 'message' => 'Categoria atualizada com sucesso'];
    }

    /**
     * Exclui uma categoria
     */
    public function excluir(int $id, int $usuarioId): array
    {
        $categoria = $this->repository->findById($id);

        if (!$categoria || $categoria->usuario_id !== $usuarioId) {
            return ['success' => false, 'message' => 'Categoria não encontrada'];
        }

        if (!$this->repository->delete($id, $usuarioId)) {
            return ['success' => false, 'message' => 'Não foi possível excluir. A categoria pode estar vinculada a lançamentos.'];
        }

        return ['success' => true, 'message' => 'Categoria excluída com sucesso'];
    }

    /**
     * Ativa ou inativa uma categoria
     */
    public function toggleAtivo(int $id, int $usuarioId): array
    {
        $categoria = $this->repository->findById($id);

        if (!$categoria || $categoria->usuario_id !== $usuarioId) {
            return ['success' => false, 'message' => 'Categoria não encontrada'];
        }

        if (!$this->repository->toggleAtivo($id, $usuarioId)) {
            return ['success' => false, 'message' => 'Erro ao atualizar status'];
        }

        $novoStatus = $categoria->ativo ? 'desativada' : 'ativada';
        return ['success' => true, 'message' => "Categoria {$novoStatus} com sucesso"];
    }
}
