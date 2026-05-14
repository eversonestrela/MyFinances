<?php

/**
 * Arquivo de rotas da aplicação
 * Define todas as rotas e seus respectivos controllers
 */

use App\Controllers\AuthController;
use App\Controllers\CategoriaController;
use App\Controllers\DashboardController;
use App\Controllers\ReceitaController;
use App\Controllers\DespesaController;
use App\Controllers\DividaController;
use App\Controllers\PerfilController;
use App\Controllers\RelatorioController;

// Rota raiz - redireciona para dashboard
$router->get('/', function($request, $response) {
    $response->redirect('/dashboard');
});

// ============================================
// Rotas de Autenticação
// ============================================
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);

// ============================================
// Rotas de Dashboard
// ============================================
$router->get('/dashboard', [DashboardController::class, 'index']);

// ============================================
// Rotas de Receitas
// ============================================
$router->get('/receitas', [ReceitaController::class, 'index']);
$router->get('/receitas/create', [ReceitaController::class, 'create']);
$router->post('/receitas/store', [ReceitaController::class, 'store']);
$router->get('/receitas/{id}/edit', [ReceitaController::class, 'edit']);
$router->post('/receitas/{id}/update', [ReceitaController::class, 'update']);
$router->get('/receitas/{id}/delete', [ReceitaController::class, 'delete']);

// ============================================
// Rotas de Despesas
// ============================================
$router->get('/despesas', [DespesaController::class, 'index']);
$router->get('/despesas/create', [DespesaController::class, 'create']);
$router->post('/despesas/store', [DespesaController::class, 'store']);
$router->get('/despesas/{id}/delete', [DespesaController::class, 'delete']);
$router->get('/despesas/parcelas', [DespesaController::class, 'parcelas']);
$router->post('/despesas/parcelas/{id}/toggle', [DespesaController::class, 'toggleParcela']);

// ============================================
// Rotas de Dívidas Variáveis
// ============================================
$router->get('/dividas', [DividaController::class, 'index']);
$router->get('/dividas/create', [DividaController::class, 'create']);
$router->post('/dividas/store', [DividaController::class, 'store']);
$router->get('/dividas/{id}/edit', [DividaController::class, 'edit']);
$router->post('/dividas/{id}/update', [DividaController::class, 'update']);
$router->get('/dividas/{id}/delete', [DividaController::class, 'delete']);

// ============================================
// Rotas de Relatórios
// ============================================
$router->get('/relatorios', [RelatorioController::class, 'index']);
$router->get('/relatorios/exportar-pdf', [RelatorioController::class, 'exportarPdf']);
$router->get('/relatorios/exportar-excel', [RelatorioController::class, 'exportarExcel']);

// ============================================
// Rotas de Categorias
// ============================================
$router->get('/categorias', [CategoriaController::class, 'index']);
$router->post('/categorias/store', [CategoriaController::class, 'store']);
$router->post('/categorias/{id}/update', [CategoriaController::class, 'update']);
$router->get('/categorias/{id}/delete', [CategoriaController::class, 'delete']);
$router->get('/categorias/{id}/toggle', [CategoriaController::class, 'toggle']);

// ============================================
// Rotas de Perfil
// ============================================
$router->get('/perfil', [PerfilController::class, 'index']);
$router->post('/perfil/update', [PerfilController::class, 'update']);
$router->post('/perfil/update-password', [PerfilController::class, 'updatePassword']);
$router->post('/perfil/upload-foto', [PerfilController::class, 'uploadFoto']);
