<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\ReceitaService;
use App\Core\Session;

/**
 * Controller para receitas
 */
class ReceitaController extends Controller
{
    private AuthService $authService;
    private ReceitaService $receitaService;

    /**
     * Construtor
     */
    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->authService = new AuthService();
        $this->receitaService = new ReceitaService();

        // Proteger rota
        $this->authService->requireAuth();
    }

    /**
     * Lista todas as receitas
     */
    public function index(): void
    {
        $usuarioId = $this->authService->getUsuarioId();
        $receitas = $this->receitaService->listarReceitas($usuarioId);

        $this->view('receitas/index', [
            'receitas' => $receitas,
            'usuario' => $this->authService->getUsuario()
        ]);
    }

    /**
     * Exibe formulário de criação
     */
    public function create(): void
    {
        $this->view('receitas/create', [
            'usuario' => $this->authService->getUsuario()
        ]);
    }

    /**
     * Salva nova receita
     */
    public function store(): void
    {
        $data = $this->request->only(['descricao', 'valor', 'data_recebimento', 'tipo_receita', 'data_fim']);
        $data['usuario_id'] = $this->authService->getUsuarioId();

        $result = $this->receitaService->criarReceita($data);

        if ($result['success']) {
            Session::flash('success', $result['message']);
        } else {
            Session::flash('error', $result['message']);
        }

        $this->redirect('/receitas');
    }

    /**
     * Exibe formulário de edição
     */
    public function edit(): void
    {
        $id = (int) $this->request->param('id');
        $receitas = $this->receitaService->listarReceitas($this->authService->getUsuarioId());
        $receita = null;
        
        foreach ($receitas as $r) {
            if ($r->id === $id) {
                $receita = $r;
                break;
            }
        }

        if (!$receita) {
            Session::flash('error', 'Receita não encontrada');
            $this->redirect('/receitas');
            return;
        }

        $this->view('receitas/edit', [
            'receita' => $receita,
            'usuario' => $this->authService->getUsuario()
        ]);
    }

    /**
     * Atualiza receita
     */
    public function update(): void
    {
        // Implementação simplificada - apenas receitas únicas por enquanto
        Session::flash('info', 'Edição de receitas em desenvolvimento');
        $this->redirect('/receitas');
    }

    /**
     * Exclui receita ou grupo de receitas recorrentes
     */
    public function delete(): void
    {
        $id = (int) $this->request->param('id');
        $excluirGrupo = $this->request->get('excluir_grupo') === '1';

        if ($this->receitaService->excluirReceita($id, $excluirGrupo)) {
            Session::flash('success', 'Receita(s) excluída(s) com sucesso!');
        } else {
            Session::flash('error', 'Erro ao excluir receita');
        }

        $this->redirect('/receitas');
    }
}
