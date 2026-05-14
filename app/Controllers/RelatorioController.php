<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\CategoriaService;
use App\Services\RelatorioService;
use App\Services\ExportService;
use App\Core\Session;

/**
 * Controller para relatórios financeiros
 */
class RelatorioController extends Controller
{
    private AuthService    $authService;
    private RelatorioService $relatorioService;
    private CategoriaService $categoriaService;
    private ExportService  $exportService;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->authService      = new AuthService();
        $this->relatorioService = new RelatorioService();
        $this->categoriaService = new CategoriaService();
        $this->exportService    = new ExportService();
        $this->authService->requireAuth();
    }

    /**
     * Tela principal de relatórios
     */
    public function index(): void
    {
        $usuarioId = $this->authService->getUsuarioId();
        $mes = (int) $this->request->get('mes', date('m'));
        $ano = (int) $this->request->get('ano', date('Y'));

        // Validar limites
        $mes = max(1, min(12, $mes));
        $ano = max(2000, min(2100, $ano));

        $resumo      = $this->relatorioService->getResumoMensal($usuarioId, $mes, $ano);
        $statusParc  = $this->relatorioService->getStatusParcelas($usuarioId, $mes, $ano);
        $catDespesas = $this->relatorioService->getDespesasPorCategoria($usuarioId, $mes, $ano);
        $catDividas  = $this->relatorioService->getDividasPorCategoria($usuarioId, $mes, $ano);
        $evolucao    = $this->relatorioService->getEvolucaoMensal($usuarioId, 12);
        $resumoAnual = $this->relatorioService->getResumoAnual($usuarioId, $ano);
        $categorias  = $this->categoriaService->listar($usuarioId);

        $this->view('relatorios/index', [
            'usuario'         => $this->authService->getUsuario(),
            'mes'             => $mes,
            'ano'             => $ano,
            'resumo'          => array_merge($resumo, $statusParc),
            'statusParc'      => $statusParc,
            'catDespesas'     => $catDespesas,
            'catDividas'      => $catDividas,
            'evolucao'        => $evolucao,
            'resumoAnual'     => $resumoAnual,
            'categorias'      => $categorias,
            'receitasDetalhe' => $this->relatorioService->getReceitasDetalhadas($usuarioId, $mes, $ano),
            'despesasDetalhe' => $this->relatorioService->getDespesasDetalhadas($usuarioId, $mes, $ano),
            'dividasDetalhe'  => $this->relatorioService->getDividasDetalhadas($usuarioId, $mes, $ano),
        ]);
    }

    /**
     * Exporta relatório em PDF
     */
    public function exportarPdf(): void
    {
        $usuarioId = $this->authService->getUsuarioId();
        $mes = max(1, min(12, (int) $this->request->get('mes', date('m'))));
        $ano = max(2000, min(2100, (int) $this->request->get('ano', date('Y'))));

        $usuario = $this->authService->getUsuario();
        $dados   = $this->buildDadosExport($usuarioId, $mes, $ano);

        $this->exportService->gerarPdf($dados, $mes, $ano, $usuario->nome ?? 'Usuário');
    }

    /**
     * Exporta relatório em Excel (.xlsx)
     */
    public function exportarExcel(): void
    {
        $usuarioId = $this->authService->getUsuarioId();
        $mes = max(1, min(12, (int) $this->request->get('mes', date('m'))));
        $ano = max(2000, min(2100, (int) $this->request->get('ano', date('Y'))));

        $usuario = $this->authService->getUsuario();
        $dados   = $this->buildDadosExport($usuarioId, $mes, $ano);

        $this->exportService->gerarExcel($dados, $mes, $ano, $usuario->nome ?? 'Usuário');
    }

    /**
     * Monta array de dados completo para os exportadores
     */
    private function buildDadosExport(int $usuarioId, int $mes, int $ano): array
    {
        $resumo  = $this->relatorioService->getResumoMensal($usuarioId, $mes, $ano);
        $status  = $this->relatorioService->getStatusParcelas($usuarioId, $mes, $ano);
        $resumo  = array_merge($resumo, $status);

        return [
            'resumo'       => $resumo,
            'receitas'     => $this->relatorioService->getReceitasDetalhadas($usuarioId, $mes, $ano),
            'despesas'     => $this->relatorioService->getDespesasDetalhadas($usuarioId, $mes, $ano),
            'dividas'      => $this->relatorioService->getDividasDetalhadas($usuarioId, $mes, $ano),
            'cat_despesas' => $this->relatorioService->getDespesasPorCategoria($usuarioId, $mes, $ano),
            'cat_dividas'  => $this->relatorioService->getDividasPorCategoria($usuarioId, $mes, $ano),
            'evolucao'     => $this->relatorioService->getEvolucaoMensal($usuarioId, 12),
        ];
    }
}
