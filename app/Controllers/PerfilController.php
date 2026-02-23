<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\PerfilService;
use App\Core\Session;

/**
 * Controller para perfil do usuário
 */
class PerfilController extends Controller
{
    private AuthService $authService;
    private PerfilService $perfilService;

    /**
     * Construtor
     */
    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->authService = new AuthService();
        $this->perfilService = new PerfilService();

        // Proteger rota
        $this->authService->requireAuth();
    }

    /**
     * Exibe página de perfil
     */
    public function index(): void
    {
        $this->view('perfil/index', [
            'usuario' => $this->authService->getUsuario()
        ]);
    }

    /**
     * Atualiza dados do perfil
     */
    public function update(): void
    {
        $usuarioId = $this->authService->getUsuarioId();
        $data = $this->request->only(['nome', 'email']);

        $result = $this->perfilService->atualizarPerfil($usuarioId, $data);

        if ($result['success']) {
            Session::flash('success', $result['message']);
            
            // Atualizar sessão
            Session::set('usuario_nome', $data['nome']);
            Session::set('usuario_email', $data['email']);
        } else {
            Session::flash('error', $result['message']);
        }

        $this->redirect('/perfil');
    }

    /**
     * Atualiza senha
     */
    public function updatePassword(): void
    {
        $usuarioId = $this->authService->getUsuarioId();
        
        $senhaAtual = $this->request->post('senha_atual');
        $novaSenha = $this->request->post('nova_senha');
        $confirmarSenha = $this->request->post('confirmar_senha');

        $result = $this->perfilService->atualizarSenha($usuarioId, $senhaAtual, $novaSenha, $confirmarSenha);

        if ($result['success']) {
            Session::flash('success', $result['message']);
        } else {
            Session::flash('error', $result['message']);
        }

        $this->redirect('/perfil');
    }

    /**
     * Upload de foto de perfil
     */
    public function uploadFoto(): void
    {
        $usuarioId = $this->authService->getUsuarioId();
        $file = $this->request->file('foto');

        if (!$file) {
            Session::flash('error', 'Nenhum arquivo enviado');
            $this->redirect('/perfil');
            return;
        }

        $result = $this->perfilService->uploadFotoPerfil($usuarioId, $file);

        if ($result['success']) {
            Session::flash('success', $result['message']);
            
            // Atualizar sessão
            Session::set('usuario_foto', $result['foto']);
        } else {
            Session::flash('error', $result['message']);
        }

        $this->redirect('/perfil');
    }
}
