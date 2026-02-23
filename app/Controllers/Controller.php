<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;

/**
 * Classe base para controllers
 */
abstract class Controller
{
    protected Request $request;
    protected Response $response;

    /**
     * Construtor
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Renderiza uma view
     * 
     * @param string $view
     * @param array $data
     * @return void
     */
    protected function view(string $view, array $data = []): void
    {
        $this->response->view($view, $data);
    }

    /**
     * Retorna JSON
     * 
     * @param mixed $data
     * @param int $status
     * @return void
     */
    protected function json($data, int $status = 200): void
    {
        $this->response->json($data, $status);
    }

    /**
     * Redireciona
     * 
     * @param string $url
     * @return void
     */
    protected function redirect(string $url): void
    {
        $this->response->redirect($url);
    }
}
