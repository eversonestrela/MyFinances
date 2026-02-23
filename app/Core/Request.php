<?php

namespace App\Core;

/**
 * Classe para gerenciar requisições HTTP
 */
class Request
{
    /**
     * Método HTTP da requisição (GET, POST, etc)
     */
    private string $method;

    /**
     * URI da requisição
     */
    private string $uri;

    /**
     * Parâmetros da requisição
     */
    private array $params = [];

    /**
     * Construtor
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        
        // Remover a pasta public da URI se existir
        $this->uri = str_replace('/public', '', $this->uri);
        
        // Garantir que URI comece com /
        if (strpos($this->uri, '/') !== 0) {
            $this->uri = '/' . $this->uri;
        }
    }

    /**
     * Obtém o método HTTP
     * 
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Obtém a URI
     * 
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Obtém todos os parâmetros da requisição (GET e POST)
     * 
     * @return array
     */
    public function all(): array
    {
        return array_merge($_GET, $_POST);
    }

    /**
     * Obtém um parâmetro específico
     * 
     * @param string $key Nome do parâmetro
     * @param mixed $default Valor padrão
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->all()[$key] ?? $default;
    }

    /**
     * Obtém um parâmetro do POST
     * 
     * @param string $key Nome do parâmetro
     * @param mixed $default Valor padrão
     * @return mixed
     */
    public function post(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Obtém um arquivo enviado
     * 
     * @param string $key Nome do campo de arquivo
     * @return array|null
     */
    public function file(string $key): ?array
    {
        return $_FILES[$key] ?? null;
    }

    /**
     * Verifica se a requisição é POST
     * 
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->method === 'POST';
    }

    /**
     * Verifica se a requisição é GET
     * 
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->method === 'GET';
    }

    /**
     * Define parâmetros de rota
     * 
     * @param array $params
     * @return void
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    /**
     * Obtém um parâmetro de rota
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function param(string $key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    /**
     * Sanitiza uma string contra XSS
     * 
     * @param string $value
     * @return string
     */
    public function sanitize(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Valida e sanitiza todos os inputs
     * 
     * @param array $keys
     * @return array
     */
    public function only(array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            $value = $this->get($key);
            $result[$key] = is_string($value) ? $this->sanitize($value) : $value;
        }
        return $result;
    }
}
