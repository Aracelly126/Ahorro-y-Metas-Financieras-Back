<?php
require_once './config/database.php';

class DatabaseSeeder {
    private $pdo;

    public function __construct() {
        // Usamos la clase Database para obtener la conexión
        $this->pdo = \Config\Database::connect();
    }

    public function run() {
        // Lista de seeders a ejecutar
        $seeders = [
            'CategoriasSeeder',
            'ClientesSeeder',
            'MetasAhorroSeeder',
            'AportesSeeder'
        ];

        foreach ($seeders as $seeder) {
            // Incluir el archivo del seeder
            require_once __DIR__ . "/{$seeder}.php";
            // Instanciamos el seeder y lo ejecutamos
            $seederInstance = new $seeder($this->pdo);
            $seederInstance->run();
            echo "Seeder ejecutado: {$seeder}\n";
        }
    }
}
