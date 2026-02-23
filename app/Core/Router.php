<?php

namespace App\Core;

/**
 * Classe Router para gerenciar rotas da aplicação
 */
class Router
{
    /**
     * Array de rotas registradas
     */
    private array $routes = [];

    /**
     * Instância de Request
     */
    private Request $request;

    /**
     * Instância de Response
     */
    private Response $response;

    /**
     * Construtor
     */
    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
    }

    /**
     * Adiciona uma rota GET
     * 
     * @param string $path Caminho da rota
     * @param callable|array $handler Controller ou função callback
     * @return void
     */
    public function get(string $path, $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * Adiciona uma rota POST
     * 
     * @param string $path Caminho da rota
     * @param callable|array $handler Controller ou função callback
     * @return void
     */
    public function post(string $path, $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    /**
     * Adiciona uma rota
     * 
     * @param string $method Método HTTP
     * @param string $path Caminho da rota
     * @param callable|array $handler Controller ou função callback
     * @return void
     */
    private function addRoute(string $method, string $path, $handler): void
    {
        // Converter parâmetros de rota {param} para regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }

    /**
     * Executa o roteamento
     * 
     * @return void
     */
    public function dispatch(): void
    {
        $method = $this->request->getMethod();
        $uri = $this->request->getUri();

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                // Extrair parâmetros da rota
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->request->setParams($params);

                // Executar handler
                $this->executeHandler($route['handler']);
                return;
            }
        }

        // Rota não encontrada
        $this->response->error(404);
    }

    /**
     * Executa o handler da rota
     * 
     * @param callable|array $handler
     * @return void
     */
    private function executeHandler($handler): void
    {
        if (is_callable($handler)) {
            // Handler é uma função callback
            call_user_func($handler, $this->request, $this->response);
        } elseif (is_array($handler) && count($handler) === 2) {
            // Handler é um array [ControllerClass, 'method']
            [$controllerClass, $method] = $handler;

            if (!class_exists($controllerClass)) {
                throw new \Exception("Controller não encontrado: {$controllerClass}");
            }

            $controller = new $controllerClass($this->request, $this->response);

            if (!method_exists($controller, $method)) {
                throw new \Exception("Método não encontrado: {$controllerClass}::{$method}");
            }

            $controller->$method();
        } else {
            throw new \Exception("Handler inválido");
        }
    }

    /**
     * Carrega rotas de um arquivo
     * 
     * @param string $file Caminho do arquivo de rotas
     * @return void
     */
    public function loadRoutes(string $file): void
    {
        if (!file_exists($file)) {
            throw new \Exception("Arquivo de rotas não encontrado: {$file}");
        }

        $router = $this;
        require $file;
    }
}
