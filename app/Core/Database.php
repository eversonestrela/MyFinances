<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Classe para gerenciar conexão com banco de dados usando PDO
 */
class Database
{
    /**
     * Instância singleton da conexão PDO
     */
    private static ?PDO $connection = null;

    /**
     * Obtém a conexão com o banco de dados (Singleton Pattern)
     * 
     * @return PDO
     * @throws PDOException
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            try {
                $host = Env::get('DB_HOST', 'localhost');
                $dbname = Env::get('DB_NAME', 'myfinances');
                $user = Env::get('DB_USER', 'root');
                $pass = Env::get('DB_PASS', '');

                $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
                
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];

                self::$connection = new PDO($dsn, $user, $pass, $options);
                
            } catch (PDOException $e) {
                throw new PDOException("Erro ao conectar com o banco de dados: " . $e->getMessage());
            }
        }

        return self::$connection;
    }

    /**
     * Fecha a conexão com o banco de dados
     * 
     * @return void
     */
    public static function closeConnection(): void
    {
        self::$connection = null;
    }
}
