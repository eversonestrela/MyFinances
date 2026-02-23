<?php

namespace App\Core;

/**
 * Classe para gerenciar sessões de usuário
 */
class Session
{
    /**
     * Inicia a sessão
     * 
     * @return void
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $sessionName = Env::get('SESSION_NAME', 'myfinances_session');
            $sessionLifetime = (int) Env::get('SESSION_LIFETIME', 7200);
            
            session_name($sessionName);
            
            session_set_cookie_params([
                'lifetime' => $sessionLifetime,
                'path' => '/',
                'secure' => Env::get('APP_ENV') === 'production',
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            
            session_start();
        }
    }

    /**
     * Define um valor na sessão
     * 
     * @param string $key Chave
     * @param mixed $value Valor
     * @return void
     */
    public static function set(string $key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Obtém um valor da sessão
     * 
     * @param string $key Chave
     * @param mixed $default Valor padrão se não existir
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Verifica se uma chave existe na sessão
     * 
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Remove um item da sessão
     * 
     * @param string $key
     * @return void
     */
    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Destrói toda a sessão
     * 
     * @return void
     */
    public static function destroy(): void
    {
        self::start();
        session_unset();
        session_destroy();
    }

    /**
     * Define uma mensagem flash (disponível apenas na próxima requisição)
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function flash(string $key, $value): void
    {
        self::set("flash_{$key}", $value);
    }

    /**
     * Obtém e remove uma mensagem flash
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getFlash(string $key, $default = null)
    {
        $value = self::get("flash_{$key}", $default);
        self::remove("flash_{$key}");
        return $value;
    }

    /**
     * Verifica se há mensagem flash
     * 
     * @param string $key
     * @return bool
     */
    public static function hasFlash(string $key): bool
    {
        return self::has("flash_{$key}");
    }

    /**
     * Regenera o ID da sessão (segurança contra session fixation)
     * 
     * @return void
     */
    public static function regenerate(): void
    {
        self::start();
        session_regenerate_id(true);
    }
}
