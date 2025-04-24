<?php
namespace Config;

class Database {
    private static $connection;

    public static function connect() {
        if (self::$connection) {
            return self::$connection;
        }

        // Cargar variables de entorno manualmente
        $env = self::loadEnv();
        
        $host = $env['DB_HOST'] ?? 'localhost';
        $db = $env['DB_NAME'] ?? '';
        $user = $env['DB_USER'] ?? '';
        $pass = $env['DB_PASS'] ?? '';
        $port = $env['DB_PORT'] ?? 3306;

        try {
            self::$connection = new \PDO(
                "mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4", 
                $user, 
                $pass,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ]
            );
        } catch (\PDOException $e) {
            throw new \RuntimeException(
                "Error de conexión a la base de datos: " . $e->getMessage() . 
                "\nConfiguración usada: host=$host, db=$db, user=$user"
            );
        }

        return self::$connection;
    }

    private static function loadEnv() {
        $envPath = __DIR__ . '/../.env';
        if (!file_exists($envPath)) {
            throw new \RuntimeException("El archivo .env no existe en: " . $envPath);
        }

        $env = [];
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Ignorar comentarios
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            // Separar nombre y valor
            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) {
                continue;
            }
            
            $name = trim($parts[0]);
            $value = trim($parts[1]);
            
            // Eliminar comillas si existen
            if (preg_match('/^"(.*)"$/', $value, $matches)) {
                $value = $matches[1];
            } elseif (preg_match('/^\'(.*)\'$/', $value, $matches)) {
                $value = $matches[1];
            }
            
            $env[$name] = $value;
        }

        return $env;
    }
}