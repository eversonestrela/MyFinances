<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\DashboardService;

/**
 * Controller para dashboard
 */
class DashboardController extends Controller
{
    private AuthService $authService;
    private DashboardService $dashboardService;

    /**
     * Construtor
     */
    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->authService = new AuthService();
        $this->dashboardService = new DashboardService();

        // Proteger rota
        $this->authService->requireAuth();
    }

    /**
     * Exibe dashboard principal
     */
    public function index(): void
    {
        $usuarioId = $this->authService->getUsuarioId();
        
        // Período atual (mês e ano)
        $mes = (int) $this->request->get('mes', date('m'));
        $ano = (int) $this->request->get('ano', date('Y'));

        // Dados do dashboard
        $dados = $this->dashboardService->getDashboardData($usuarioId, $mes, $ano);
        
        // Dados do gráfico
        $grafico = $this->dashboardService->getGraficoUltimosSeisMeses($usuarioId);
        
        // Últimas movimentações
        $movimentacoes = $this->dashboardService->getUltimasMovimentacoes($usuarioId, 10);

        $this->view('dashboard/index', [
            'dados' => $dados,
            'grafico' => $grafico,
            'movimentacoes' => $movimentacoes,
            'usuario' => $this->authService->getUsuario()
        ]);
    }
}
