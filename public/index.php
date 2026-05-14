<?php

/**
 * MyFinances - Sistema de Controle Financeiro Pessoal
 * Ponto de entrada da aplicação
 */

// Definir caminho raiz do projeto
define('BASE_PATH', dirname(__DIR__));

// Carregar autoloader do Composer (bibliotecas de terceiros)
if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    require BASE_PATH . '/vendor/autoload.php';
}

// Carregador de classes (PSR-4 simplificado)
spl_autoload_register(function ($class) {
    // Converter namespace para caminho de arquivo
    $prefix = 'App\\';
    $base_dir = BASE_PATH . '/app/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Importar classes necessárias
use App\Core\Env;
use App\Core\Router;
use App\Core\Database;

// Carregar variáveis de ambiente
try {
    Env::load(BASE_PATH . '/.env');
} catch (Exception $e) {
    die("Erro ao carregar configurações: " . $e->getMessage());
}

// Configurações de erro baseadas no ambiente
if (Env::get('APP_ENV') === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Configurações de timezone
date_default_timezone_set('America/Sao_Paulo');

// Testar conexão com banco de dados
try {
    Database::getConnection();
} catch (PDOException $e) {
    die("Erro ao conectar com banco de dados. Verifique as configurações no arquivo .env");
}

// Inicializar roteador
$router = new Router();

// Carregar rotas
$router->loadRoutes(BASE_PATH . '/routes/web.php');

// Executar roteamento
try {
    $router->dispatch();
} catch (Exception $e) {
    if (Env::get('APP_ENV') === 'development') {
        echo "<h1>Erro</h1>";
        echo "<p>" . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    } else {
        http_response_code(500);
        echo "Erro interno do servidor";
    }
}
