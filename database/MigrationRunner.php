<?php
require_once './config/database.php';

class MigrationRunner {
    private $pdo;

    public function __construct() {
        // Usamos la clase Config\Database para obtener la conexión
        $this->pdo = \Config\Database::connect();

        // Crear tabla de control de migraciones si no existe
        $this->createMigrationsTable();
    }

    private function createMigrationsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            batch INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        $this->pdo->exec($sql);
    }

    public function runMigrations() {
        $this->pdo->beginTransaction();
        try {
            $migrations = glob(__DIR__ . '/migrations/*.php');
            sort($migrations);
            
            $batch = $this->getNextBatchNumber();
            
            foreach ($migrations as $migration) {
                $migrationName = basename($migration, '.php');
                
                if (!$this->isMigrationExecuted($migrationName)) {
                    require_once $migration;
                    $className = $this->getClassNameFromFileName($migrationName);
                    $instance = new $className();
                    
                    $instance->up($this->pdo);
                    $this->recordMigration($migrationName, $batch);
                    
                    echo "Migración ejecutada: $migrationName\n";
                }
            }
            
            $this->pdo->commit();
            echo "Todas las migraciones se han ejecutado correctamente.\n";
        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo "Error en la migración: " . $e->getMessage() . "\n";
        }
    }

    private function getClassNameFromFileName($fileName) {
        $parts = explode('_', $fileName);
        $parts = array_slice($parts, 2); // Elimina el número y "create"
        $className = implode('', array_map('ucfirst', $parts));
        return $className;
    }

    private function isMigrationExecuted($migrationName) {
        $stmt = $this->pdo->prepare("SELECT id FROM migrations WHERE migration = ?");
        $stmt->execute([$migrationName]);
        return $stmt->fetch() !== false;
    }

    private function recordMigration($migrationName, $batch) {
        $stmt = $this->pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
        $stmt->execute([$migrationName, $batch]);
    }

    private function getNextBatchNumber() {
        $stmt = $this->pdo->query("SELECT MAX(batch) as max_batch FROM migrations");
        $result = $stmt->fetch();
        return $result['max_batch'] + 1;
    }
}
