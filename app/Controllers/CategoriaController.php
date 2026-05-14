<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\CategoriaService;
use App\Core\Session;

/**
 * Controller para gerenciamento de categorias
 */
class CategoriaController extends Controller
{
    private AuthService     $authService;
    private CategoriaService $categoriaService;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->authService      = new AuthService();
        $this->categoriaService = new CategoriaService();
        $this->authService->requireAuth();
    }

    /**
     * Lista todas as categorias do usuário
     */
    public function index(): void
    {
        $usuarioId = $this->authService->getUsuarioId();
        $categorias = $this->categoriaService->listarTodas($usuarioId);

        $this->view('categorias/index', [
            'categorias' => $categorias,
            'usuario'    => $this->authService->getUsuario(),
        ]);
    }

    /**
     * Cria nova categoria
     */
    public function store(): void
    {
        $data = $this->request->only(['nome', 'icone', 'cor']);
        $data['usuario_id'] = $this->authService->getUsuarioId();

        $result = $this->categoriaService->criar($data);

        if ($result['success']) {
            Session::flash('success', $result['message']);
        } else {
            Session::flash('error', $result['message']);
        }

        $this->redirect('/categorias');
    }

    /**
     * Atualiza categoria existente
     */
    public function update(): void
    {
        $id   = (int) $this->request->param('id');
        $data = $this->request->only(['nome', 'icone', 'cor']);
        $data['usuario_id'] = $this->authService->getUsuarioId();

        $result = $this->categoriaService->editar($id, $data);

        if ($result['success']) {
            Session::flash('success', $result['message']);
        } else {
            Session::flash('error', $result['message']);
        }

        $this->redirect('/categorias');
    }

    /**
     * Exclui categoria
     */
    public function delete(): void
    {
        $id     = (int) $this->request->param('id');
        $result = $this->categoriaService->excluir($id, $this->authService->getUsuarioId());

        if ($result['success']) {
            Session::flash('success', $result['message']);
        } else {
            Session::flash('error', $result['message']);
        }

        $this->redirect('/categorias');
    }

    /**
     * Ativa/inativa categoria
     */
    public function toggle(): void
    {
        $id     = (int) $this->request->param('id');
        $result = $this->categoriaService->toggleAtivo($id, $this->authService->getUsuarioId());

        if ($result['success']) {
            Session::flash('success', $result['message']);
        } else {
            Session::flash('error', $result['message']);
        }

        $this->redirect('/categorias');
    }
}
