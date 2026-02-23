<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Repositories\DividaVariavelRepository;
use App\Models\DividaVariavel;
use App\Core\Session;

/**
 * Controller para dívidas variáveis
 */
class DividaController extends Controller
{
    private AuthService $authService;
    private DividaVariavelRepository $dividaRepository;

    /**
     * Construtor
     */
    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->authService = new AuthService();
        $this->dividaRepository = new DividaVariavelRepository();

        // Proteger rota
        $this->authService->requireAuth();
    }

    /**
     * Lista todas as dívidas
     */
    public function index(): void
    {
        $usuarioId = $this->authService->getUsuarioId();
        $dividas = $this->dividaRepository->findByUsuario($usuarioId);

        $this->view('dividas/index', [
            'dividas' => $dividas,
            'usuario' => $this->authService->getUsuario()
        ]);
    }

    /**
     * Exibe formulário de criação
     */
    public function create(): void
    {
        $this->view('dividas/create', [
            'usuario' => $this->authService->getUsuario()
        ]);
    }

    /**
     * Salva nova dívida
     */
    public function store(): void
    {
        $data = $this->request->only(['descricao', 'valor', 'mes', 'ano']);
        
        $divida = new DividaVariavel();
        $divida->usuario_id = $this->authService->getUsuarioId();
        $divida->descricao = $data['descricao'];
        $divida->valor = (float) $data['valor'];
        $divida->mes = (int) $data['mes'];
        $divida->ano = (int) $data['ano'];

        if ($this->dividaRepository->create($divida)) {
            Session::flash('success', 'Dívida cadastrada com sucesso!');
        } else {
            Session::flash('error', 'Erro ao cadastrar dívida');
        }

        $this->redirect('/dividas');
    }

    /**
     * Exibe formulário de edição
     */
    public function edit(): void
    {
        $id = (int) $this->request->param('id');
        $divida = $this->dividaRepository->findById($id);

        if (!$divida || $divida->usuario_id !== $this->authService->getUsuarioId()) {
            Session::flash('error', 'Dívida não encontrada');
            $this->redirect('/dividas');
            return;
        }

        $this->view('dividas/edit', [
            'divida' => $divida,
            'usuario' => $this->authService->getUsuario()
        ]);
    }

    /**
     * Atualiza dívida
     */
    public function update(): void
    {
        $id = (int) $this->request->param('id');
        $divida = $this->dividaRepository->findById($id);

        if (!$divida || $divida->usuario_id !== $this->authService->getUsuarioId()) {
            Session::flash('error', 'Dívida não encontrada');
            $this->redirect('/dividas');
            return;
        }

        $data = $this->request->only(['descricao', 'valor', 'mes', 'ano']);
        
        $divida->descricao = $data['descricao'];
        $divida->valor = (float) $data['valor'];
        $divida->mes = (int) $data['mes'];
        $divida->ano = (int) $data['ano'];

        if ($this->dividaRepository->update($divida)) {
            Session::flash('success', 'Dívida atualizada com sucesso!');
        } else {
            Session::flash('error', 'Erro ao atualizar dívida');
        }

        $this->redirect('/dividas');
    }

    /**
     * Exclui dívida
     */
    public function delete(): void
    {
        $id = (int) $this->request->param('id');
        $divida = $this->dividaRepository->findById($id);

        if (!$divida || $divida->usuario_id !== $this->authService->getUsuarioId()) {
            Session::flash('error', 'Dívida não encontrada');
            $this->redirect('/dividas');
            return;
        }

        if ($this->dividaRepository->delete($id)) {
            Session::flash('success', 'Dívida excluída com sucesso!');
        } else {
            Session::flash('error', 'Erro ao excluir dívida');
        }

        $this->redirect('/dividas');
    }
}
