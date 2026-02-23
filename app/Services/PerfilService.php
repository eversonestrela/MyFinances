<?php

namespace App\Services;

use App\Repositories\UsuarioRepository;

/**
 * Service para gerenciar perfil do usuário
 */
class PerfilService
{
    private UsuarioRepository $usuarioRepository;

    /**
     * Construtor
     */
    public function __construct()
    {
        $this->usuarioRepository = new UsuarioRepository();
    }

    /**
     * Atualiza dados do perfil
     * 
     * @param int $usuarioId
     * @param array $data
     * @return array
     */
    public function atualizarPerfil(int $usuarioId, array $data): array
    {
        $usuario = $this->usuarioRepository->findById($usuarioId);

        if (!$usuario) {
            return ['success' => false, 'message' => 'Usuário não encontrado'];
        }

        // Validar dados
        if (empty($data['nome']) || empty($data['email'])) {
            return ['success' => false, 'message' => 'Nome e email são obrigatórios'];
        }

        // Validar email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email inválido'];
        }

        // Verificar se email já existe (exceto para o próprio usuário)
        if ($this->usuarioRepository->emailExists($data['email'], $usuarioId)) {
            return ['success' => false, 'message' => 'Email já cadastrado'];
        }

        // Atualizar dados
        $usuario->nome = $data['nome'];
        $usuario->email = $data['email'];

        if ($this->usuarioRepository->update($usuario)) {
            return ['success' => true, 'message' => 'Perfil atualizado com sucesso'];
        }

        return ['success' => false, 'message' => 'Erro ao atualizar perfil'];
    }

    /**
     * Atualiza senha do usuário
     * 
     * @param int $usuarioId
     * @param string $senhaAtual
     * @param string $novaSenha
     * @param string $confirmarSenha
     * @return array
     */
    public function atualizarSenha(int $usuarioId, string $senhaAtual, string $novaSenha, string $confirmarSenha): array
    {
        $usuario = $this->usuarioRepository->findById($usuarioId);

        if (!$usuario) {
            return ['success' => false, 'message' => 'Usuário não encontrado'];
        }

        // Verificar senha atual
        if (!password_verify($senhaAtual, $usuario->senha)) {
            return ['success' => false, 'message' => 'Senha atual incorreta'];
        }

        // Validar nova senha
        if (strlen($novaSenha) < 6) {
            return ['success' => false, 'message' => 'Nova senha deve ter no mínimo 6 caracteres'];
        }

        // Verificar confirmação
        if ($novaSenha !== $confirmarSenha) {
            return ['success' => false, 'message' => 'Senhas não conferem'];
        }

        // Atualizar senha
        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
        
        if ($this->usuarioRepository->updatePassword($usuarioId, $senhaHash)) {
            return ['success' => true, 'message' => 'Senha atualizada com sucesso'];
        }

        return ['success' => false, 'message' => 'Erro ao atualizar senha'];
    }

    /**
     * Faz upload da foto de perfil
     * 
     * @param int $usuarioId
     * @param array $file Arquivo $_FILES['foto']
     * @return array
     */
    public function uploadFotoPerfil(int $usuarioId, array $file): array
    {
        // Validar arquivo
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            return ['success' => false, 'message' => 'Nenhum arquivo enviado'];
        }

        // Validar tipo de arquivo
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Tipo de arquivo inválido. Use JPG, PNG ou GIF'];
        }

        // Validar tamanho (máximo 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            return ['success' => false, 'message' => 'Arquivo muito grande. Máximo 2MB'];
        }

        // Gerar nome único
        $extensao = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nomeArquivo = 'perfil_' . $usuarioId . '_' . time() . '.' . $extensao;
        
        // Caminho de destino
        $destino = __DIR__ . '/../../storage/uploads/profile/' . $nomeArquivo;

        // Mover arquivo
        if (move_uploaded_file($file['tmp_name'], $destino)) {
            // Atualizar no banco
            if ($this->usuarioRepository->updateFotoPerfil($usuarioId, $nomeArquivo)) {
                return ['success' => true, 'message' => 'Foto atualizada com sucesso', 'foto' => $nomeArquivo];
            }
        }

        return ['success' => false, 'message' => 'Erro ao fazer upload da foto'];
    }
}
