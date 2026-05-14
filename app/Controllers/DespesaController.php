<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\DespesaService;
use App\Services\CategoriaService;
use App\Core\Session;

/**
 * Controller para despesas
 */
class DespesaController extends Controller
{
    private AuthService $authService;
    private DespesaService $despesaService;
    private CategoriaService $categoriaService;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->authService      = new AuthService();
        $this->despesaService   = new DespesaService();
        $this->categoriaService = new CategoriaService();
        $this->authService->requireAuth();
    }

    /**
     * Lista todas as despesas
     */
    public function index(): void
    {
        $usuarioId = $this->authService->getUsuarioId();
        $despesas = $this->despesaService->listarDespesas($usuarioId);

        $this->view('despesas/index', [
            'despesas' => $despesas,
            'usuario' => $this->authService->getUsuario()
        ]);
    }

    /**
     * Exibe formulário de criação
     */
    public function create(): void
    {
        $usuarioId  = $this->authService->getUsuarioId();
        $categorias = $this->categoriaService->listar($usuarioId);

        $this->view('despesas/create', [
            'usuario'    => $this->authService->getUsuario(),
            'categorias' => $categorias,
        ]);
    }

    /**
     * Salva nova despesa
     */
    public function store(): void
    {
        $data = $this->request->only(['descricao', 'categoria_id', 'valor_total', 'valor_parcela_fixa', 'tipo_parcelamento', 'data_inicio', 'data_fim']);
        $data['usuario_id'] = $this->authService->getUsuarioId();

        $result = $this->despesaService->criarDespesa($data);

        if ($result['success']) {
            Session::flash('success', $result['message']);
        } else {
            Session::flash('error', $result['message']);
        }

        $this->redirect('/despesas');
    }

    /**
     * Exclui despesa
     */
    public function delete(): void
    {
        $id = (int) $this->request->param('id');

        if ($this->despesaService->excluirDespesa($id)) {
            Session::flash('success', 'Despesa excluída com sucesso!');
        } else {
            Session::flash('error', 'Erro ao excluir despesa');
        }

        $this->redirect('/despesas');
    }

    /**
     * Lista parcelas do mês
     */
    public function parcelas(): void
    {
        $usuarioId = $this->authService->getUsuarioId();
        
        $mes = (int) $this->request->get('mes', date('m'));
        $ano = (int) $this->request->get('ano', date('Y'));

        $parcelas = $this->despesaService->listarParcelasPorPeriodo($usuarioId, $mes, $ano);

        $this->view('despesas/parcelas', [
            'parcelas' => $parcelas,
            'mes' => $mes,
            'ano' => $ano,
            'usuario' => $this->authService->getUsuario()
        ]);
    }

    /**
     * Alterna status de pagamento de parcela
     */
    public function toggleParcela(): void
    {
        $id = (int) $this->request->param('id');

        if ($this->despesaService->toggleStatusPago($id)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false], 400);
        }
    }
}
