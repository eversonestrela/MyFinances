<?php

namespace App\Core;

/**
 * Classe para gerenciar respostas HTTP
 */
class Response
{
    /**
     * Renderiza uma view
     * 
     * @param string $view Nome da view (sem extensão .php)
     * @param array $data Dados para passar para a view
     * @return void
     */
    public function view(string $view, array $data = []): void
    {
        // Extrair variáveis para o escopo da view
        extract($data);

        // Caminho completo da view
        $viewPath = __DIR__ . '/../../views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            $this->error(404, "View não encontrada: {$view}");
            return;
        }

        require $viewPath;
    }

    /**
     * Retorna resposta JSON
     * 
     * @param mixed $data Dados para converter em JSON
     * @param int $statusCode Código de status HTTP
     * @return void
     */
    public function json($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Redireciona para outra URL
     * 
     * @param string $url URL de destino
     * @return void
     */
    public function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Redireciona de volta
     * 
     * @return void
     */
    public function back(): void
    {
        $url = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($url);
    }

    /**
     * Exibe página de erro
     * 
     * @param int $code Código do erro (404, 500, etc)
     * @param string $message Mensagem de erro
     * @return void
     */
    public function error(int $code, string $message = ''): void
    {
        http_response_code($code);
        
        $errorMessages = [
            404 => 'Página não encontrada',
            403 => 'Acesso negado',
            500 => 'Erro interno do servidor',
        ];

        $title = $errorMessages[$code] ?? 'Erro';
        $message = $message ?: $title;

        echo "<!DOCTYPE html>
        <html lang='pt-BR'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Erro {$code}</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                h1 { font-size: 72px; margin: 0; color: #e74c3c; }
                p { font-size: 24px; color: #555; }
                a { color: #3498db; text-decoration: none; }
            </style>
        </head>
        <body>
            <h1>{$code}</h1>
            <p>{$message}</p>
            <a href='/'>← Voltar para página inicial</a>
        </body>
        </html>";
        exit;
    }

    /**
     * Define código de status HTTP
     * 
     * @param int $code
     * @return self
     */
    public function status(int $code): self
    {
        http_response_code($code);
        return $this;
    }
}
