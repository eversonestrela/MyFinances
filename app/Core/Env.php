<?php

namespace App\Core;

/**
 * Classe para carregar e gerenciar variáveis de ambiente do arquivo .env
 */
class Env
{
    /**
     * Array que armazena as variáveis de ambiente
     */
    private static array $variables = [];

    /**
     * Carrega as variáveis do arquivo .env
     * 
     * @param string $path Caminho para o arquivo .env
     * @return void
     */
    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            throw new \Exception("Arquivo .env não encontrado em: {$path}");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Ignorar comentários
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Separar chave e valor
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Remover aspas do valor, se houver
                if (preg_match('/^["\'](.*)["\']/s', $value, $matches)) {
                    $value = $matches[1];
                }

                self::$variables[$key] = $value;
                
                // Definir também como variável de ambiente do sistema
                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
            }
        }
    }

    /**
     * Obtém o valor de uma variável de ambiente
     * 
     * @param string $key Nome da variável
     * @param mixed $default Valor padrão se a variável não existir
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return self::$variables[$key] ?? $default;
    }

    /**
     * Verifica se uma variável existe
     * 
     * @param string $key Nome da variável
     * @return bool
     */
    public static function has(string $key): bool
    {
        return isset(self::$variables[$key]);
    }
}
